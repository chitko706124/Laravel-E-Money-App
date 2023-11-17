<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use App\Notifications\GeneralNotification;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\TransactionsResource;
use Illuminate\Support\Facades\Notification;
use App\Http\Resources\TransactionDetailResource;
use App\Http\Requests\TransferFormConfirmValidate;
use App\Http\Resources\NotificationDetailResource;
use Illuminate\Support\Facades\Hash;

class PageController extends Controller
{
    public function profile()
    {
        $user = auth()->user();

        $data = new ProfileResource($user);
        return success('success', $data);
    }

    public function transactions(Request $request)
    {
        $user = auth()->user();
        $date = $request->date;
        $type = $request->type;

        $transactions = Transaction::with('user', 'source')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'DESC')
            ->when(in_array($type, [1, 2]), function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->when($date !== null, function ($query) use ($date) {
                $query->whereDate('created_at', $date);
            })
            ->paginate(2);
        $data = TransactionsResource::collection($transactions)->additional(['result' => 1, 'message' => 'success']);
        return $data;
    }

    public function transactionDetail($trx_no)
    {
        $user = auth()->user();
        $transaction = Transaction::with('user', 'source')->where('user_id', $user->id)
            ->where('trx_no', $trx_no)
            ->first();

        $data = new TransactionDetailResource($transaction);
        return success('success', $data);
    }

    public function notifications()
    {
        $authUser = auth()->user();
        $notifications = $authUser->notifications()->paginate(5)->withQueryString();

        // return $notifications;
        return NotificationResource::collection($notifications)->additional(['result' => 1, 'message' => 'success']);
    }

    public function notificationDetail($id)
    {
        $authUser = auth()->user();
        $notification = $authUser->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();
        $data = new NotificationDetailResource($notification);

        return success('success', $data);
    }

    public function toAccountVerify(Request $request)
    {
        $phone = $request->phone;
        if ($phone) {
            $authUser = auth()->user();
            if ($authUser->phone !== $phone) {
                $toUser = User::where('phone', $phone)->first();
                return success('success', ['name' => $toUser->name, 'phone' => $toUser->phone]);
            }
        }

        return fail('Invalid Data', null);
    }

    public function transferConfirm(TransferFormConfirmValidate $request)
    {
        $authUser = auth()->user();
        $toUser = User::where('phone', $request->phone)->first();

        $to_phone = $request->phone;
        $amount = $request->amount;
        $description = $request->description;
        $hashData = $request->hashData;

        $str = $to_phone . $amount . $description;
        $hashData2 = hash_hmac('sha256', $str, 'magicpay123!@#');

        if ($hashData2 !== $hashData) {
            return fail('The given data is invalid', null);
        }

        return success('success', [
            'from_name' => $authUser->name,
            'from_phone' => $authUser->phone,

            'to_name' => $toUser->name,
            'to_phone' => $to_phone,

            'amount' => $amount,
            'description' => $description,
            'hashData' => $hashData
        ]);
    }

    public function transferComplete(TransferFormConfirmValidate $request)
    {
        $authUser = auth()->user();
        $toUser = User::where('phone', $request->phone)->first();

        $to_phone = $request->phone;
        $amount = $request->amount;
        $description = $request->description;
        $hashData = $request->hashData;

        if (!$request->password) {
            return fail('Please fill your password');
        }

        if (!Hash::check($request->password, $authUser->password)) {
            return fail('The password is incorrect');
        }

        $str = $to_phone . $amount . $description;
        $hashData2 = hash_hmac('sha256', $str, 'magicpay123!@#');

        if ($hashData2 !== $hashData) {
            return fail('The given data is invalid', null);
        }

        if (!$authUser->wallet || !$toUser->wallet) {
            return fail('Wallet not found', null);
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
            $deep_link = [
                'target' => 'transaction_detail',
                'parameter' => [
                    'trx_no' => $from_account_transaction->trx_no
                ]
            ];

            Notification::send([$authUser], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link, $deep_link));

            // To Noti
            $title = 'E-money Received!';
            $message = 'Your wallet received ' . number_format($amount) . ' MMk from ' . $authUser->name . '. (' . $authUser->phone . ')';
            $sourceable_id = $to_account_transaction->id;
            $sourceable_type = Transaction::class;
            $web_link = route('transaction.detail', $to_account_transaction->trx_no);
            $deep_link = [
                'target' => 'transaction_detail',
                'parameter' => [
                    'trx_no' => $to_account_transaction->trx_no
                ]
            ];

            Notification::send([$toUser], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link, $deep_link));
            // Store Notification end


            DB::commit();
            return success('Successfully transferred', ['trx_no' => $from_account_transaction->trx_no]);
        } catch (\Exception $error) {
            DB::rollback();
            return fail($error, null);
        }
    }
}
