<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdatePassword;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\TransferFormConfirmValidate;
use App\Notifications\GeneralNotification;

class PageController extends Controller
{
    public function home()
    {
        $user = Auth::guard('web')->user();

        return view('frontend.home', compact('user'));
    }

    public function profile()
    {
        $user = Auth::guard('web')->user();
        return view('frontend.profile', compact('user'));
    }

    public function updatePassword()
    {
        return view('frontend.update-password');
    }

    public function updatePasswordStore(UpdatePassword $request)
    {
        $current_password = $request->current_password;
        $password = $request->password;
        $user = Auth::guard('web')->user();

        if (Hash::check($current_password, $user->password)) {
            $user->password = Hash::make($password);
            $user->update();

            // Store Notification
            $title = 'Changed Password!';
            $message = 'Your password is successfully changed.';
            $sourceable_id = $user->id;
            $sourceable_type = User::class;
            $web_link = route('profile');

            Notification::send([$user], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link));
            // Store Notification end

            return redirect()
                ->route('profile')
                ->with('update', 'Successfully Updated');
        } else {
            return redirect()
                ->back()
                ->withErrors(['current_password' => 'Incorrect password']);
        }
    }

    public function wallet()
    {
        $user = Auth::guard('web')->user();
        return view('frontend.wallet', compact('user'));
    }

    public function transfer()
    {
        $user = Auth::guard('web')->user();
        return view('frontend.transfer', compact('user'));
    }

    public function transferConfirm(TransferFormConfirmValidate $request)
    {
        $authUser = Auth::guard('web')->user();
        $to_user = User::where('phone', $request->phone)->first();

        $to_phone = $request->phone;
        $amount = $request->amount;
        $description = $request->description;
        $hashData = $request->hashData;

        $str = $to_phone . $amount . $description;
        $hashData2 = hash_hmac('sha256', $str, 'magicpay123!@#');

        if ($hashData2 !== $hashData) {
            return redirect()
                ->route('transfer')
                ->withErrors(['fail' => 'Something wrong. The given data is invalid']);
        }

        return view('frontend.transfer-confirm', compact('authUser', 'to_user', 'amount', 'description', 'hashData'));
    }

    public function transferComplete(TransferFormConfirmValidate $request)
    {
        $authUser = Auth::guard('web')->user();
        $toUser = User::where('phone', $request->phone)->first();

        $hashData = $request->hashData;
        $to_phone = $request->phone;
        $amount = $request->amount;
        $description = $request->description;

        $str = $to_phone . $amount . $description;
        $hashData2 = hash_hmac('sha256', $str, 'magicpay123!@#');

        if ($hashData2 !== $hashData) {
            return redirect()
                ->route('transfer')
                ->withErrors(['fail' => 'Something wrong. The given data is invalid']);
        }

        if (!$authUser->wallet || !$toUser->wallet) {
            return redirect()
                ->route('transfer.confirm')
                ->withErrors(['fail', 'Wallet nor found']);
        }

        DB::beginTransaction();
        try {
            $authUser_wallet = $authUser->wallet;
            $toUser_wallet = $toUser->wallet;

            $authUser_wallet->decrement('amount', $amount);
            $authUser_wallet->update();

            $toUser_wallet->increment('amount', $amount);
            $toUser_wallet->update();

            $refNumber = UUIDGenerate::refNumber();

            $from_account_transaction = new Transaction();
            $from_account_transaction->ref_no = $refNumber;
            $from_account_transaction->trx_no = UUIDGenerate::trxNumber();
            $from_account_transaction->user_id = $authUser->id;
            $from_account_transaction->type = 2;
            $from_account_transaction->amount = $amount;
            $from_account_transaction->source_id = $toUser->id;
            $from_account_transaction->description = $description;
            $from_account_transaction->save();

            $to_account_transaction = new Transaction();
            $to_account_transaction->ref_no = $refNumber;
            $to_account_transaction->trx_no = UUIDGenerate::trxNumber();
            $to_account_transaction->user_id = $toUser->id;
            $to_account_transaction->type = 1;
            $to_account_transaction->amount = $amount;
            $to_account_transaction->source_id = $authUser->id;
            $to_account_transaction->description = $description;
            $to_account_transaction->save();

            // Store Notification
            // From Noti
            $title = 'E-money Transferred!';
            $message = 'Your wallet transferred ' . number_format($amount) . ' MMk to ' . $toUser->name . '. (' . $to_phone . ')';
            $sourceable_id = $from_account_transaction->id;
            $sourceable_type = User::class;
            $web_link = route('transaction.detail', $from_account_transaction->trx_no);

            Notification::send([$authUser], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link));

            // To Noti
            $title = 'E-money Received!';
            $message = 'Your wallet received ' . number_format($amount) . ' MMk from ' . $authUser->name . '. (' . $authUser->phone . ')';
            $sourceable_id = $to_account_transaction->id;
            $sourceable_type = Transaction::class;
            $web_link = route('transaction.detail', $to_account_transaction->trx_no);

            Notification::send([$toUser], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link));
            // Store Notification end


            DB::commit();
            return redirect()
                ->route('transaction.detail', $from_account_transaction->trx_no)
                ->with('transfer_success', 'Successfully Transfer');
        } catch (\Exception $error) {
            DB::rollback();
            back()
                ->withErrors(['fail' => 'Something wrong. ' . $error->getMessage()])
                ->withInput();
        }
    }

    public function transaction(Request $request)
    {
        $type = $request->type;
        $date = $request->date;

        $currentDate = Carbon::now();
        $formattedDate = $currentDate->format('Y-m-d');

        $authUser = Auth::guard('web')->user();

        $transactions = Transaction::orderBy('created_at', 'DESC')
            ->with('user', 'source')
            ->where('user_id', $authUser->id)
            ->when(in_array($type, [1, 2]), function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->when($date !== null, function ($query) use ($date) {
                $query->whereDate('created_at', $date);
            })
            ->paginate(5)
            ->withQueryString();

        return view('frontend.transaction', compact('transactions', 'formattedDate'));
    }

    public function transactionDetail($trx_no)
    {
        $authUser = Auth::guard('web')->user();
        $transaction = Transaction::with('user', 'source')
            ->where('user_id', $authUser->id)
            ->where('trx_no', $trx_no)
            ->first();
        // return $transaction;
        return view('frontend.transaction-detail', compact('transaction'));
    }

    public function receiveQR()
    {
        $authUser = Auth::guard('web')->user();
        return view('frontend.receive-qr', compact('authUser'));
    }

    public function scanAndPay()
    {
        return view('frontend.scan-pay');
    }

    public function scanAndPayTransfer(Request $request)
    {
        $user = Auth::guard('web')->user();
        $toUser = User::where('phone', $request->phone)->first();

        $validated = $request->validate(
            [
                'phone' => "required|exists:users,phone|not_in:$user->phone",
            ],
            [
                'phone.exists' => 'QR code invalid',
                'phone.not_in' => 'Ahh, R U Fool',
            ],
        );

        $phone = $request->phone;
        return view('frontend.transfer', compact('phone', 'user', 'toUser'));
    }

    public function toAccountVerify(Request $request)
    {
        $authUser = Auth::guard('web')->user();

        if ($authUser->phone !== $request->phone) {
            $toUser = User::where('phone', $request->phone)->first();
            if ($toUser) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'success',
                    'data' => $toUser,
                ]);
            }
        }

        $stupid = $authUser->phone == $request->phone ? 'Are you stupid, This is your account' : 'No result found';
        return response()->json([
            'status' => 'fail',
            'message' => $stupid,
            // 'data' => 'not found account',
        ]);
    }

    public function passwordCheck(Request $request)
    {
        if (!$request->password) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Please fill your password',
            ]);
        }
        $authUser = Auth::guard('web')->user();
        if (Hash::check($request->password, $authUser->password)) {
            return response()->json([
                'status' => 'success',
                'message' => 'The password is correct.',
            ]);
        }
        return response()->json([
            'status' => 'fail',
            'message' => 'The password is incorrect.',
        ]);
    }

    public function transferHash(Request $request)
    {
        $str = $request->phone . $request->amount . $request->description;
        $hashData = hash_hmac('sha256', $str, 'magicpay123!@#');

        return response()->json([
            'status' => 'success',
            'data' => $hashData,
        ]);
    }
}
