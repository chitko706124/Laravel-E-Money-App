<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Notification;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Wallet::with('user');
            return DataTables::of($data)

                ->addColumn('account_person', function ($each) {
                    $user = $each->user;
                    if ($user) {
                        return '<p>Name : ' . $user->name . '</p> <p>Email : ' . $user->email . '</p> <p>Phone : ' . $user->phone . '</p>';
                    }

                    return '--';
                })

                ->rawColumns(['account_person'])
                ->make(true);
        }
        return view('backend.wallet.index');
    }

    public function addAmount()
    {
        $users = User::orderBy('name')->get();
        return view('backend.wallet.add-amount', compact('users'));
    }

    public function addAmountStore(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'amount' => 'gte:1000',
        ]);
        DB::beginTransaction();

        try {
            $toUser = User::with('wallet')
                ->where('id', $request->user_id)
                ->first();
            $toUser_wallet = $toUser->wallet;
            $toUser_wallet->increment('amount', $request->amount);
            $toUser_wallet->update();

            $refNumber = UUIDGenerate::refNumber();

            $to_account_transaction = new Transaction();
            $to_account_transaction->ref_no = $refNumber;
            $to_account_transaction->trx_no = UUIDGenerate::trxNumber();
            $to_account_transaction->user_id = $toUser->id;
            $to_account_transaction->type = 1;
            $to_account_transaction->amount = $request->amount;
            $to_account_transaction->source_id = 0;
            $to_account_transaction->description = $request->description;
            $to_account_transaction->save();
            DB::commit();
            return redirect()
                ->route('wallet.index')
                ->with('create', 'Successfully Added');
        } catch (\Exception $error) {
            DB::rollback();

            return back()
                ->withErrors(['fail' => 'Something wrong. ' . $error->getMessage()])
                ->withInput();
        }
    }

    public function reduceAmount()
    {
        $users = User::orderBy('name')->get();
        return view('backend.wallet.reduce-amount', compact('users'));
    }

    public function reduceAmountStore(Request $request)
    {
        $toUser = User::with('wallet')
            ->where('id', $request->user_id)
            ->first();

        $request->validate([
            'user_id' => 'required',
            'amount' => ['gte:100', 'lte:' . $toUser->wallet->amount],
        ]);
        DB::beginTransaction();

        try {
            $toUser_wallet = $toUser->wallet;
            $toUser_wallet->decrement('amount', $request->amount);
            $toUser_wallet->update();

            $refNumber = UUIDGenerate::refNumber();

            $to_account_transaction = new Transaction();
            $to_account_transaction->ref_no = $refNumber;
            $to_account_transaction->trx_no = UUIDGenerate::trxNumber();
            $to_account_transaction->user_id = $toUser->id;
            $to_account_transaction->type = 1;
            $to_account_transaction->amount = $request->amount;
            $to_account_transaction->source_id = 0;
            $to_account_transaction->description = $request->description;
            $to_account_transaction->save();
            DB::commit();
            return redirect()
                ->route('wallet.index')
                ->with('create', 'Successfully Reduced');
        } catch (\Exception $error) {
            DB::rollback();

            return back()
                ->withErrors(['fail' => 'Something wrong. ' . $error->getMessage()])
                ->withInput();
        }
    }
}
