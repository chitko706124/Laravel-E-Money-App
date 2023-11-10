<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\TransactionsResource;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function profile()
    {
        $user = auth()->user();

        $data = new ProfileResource($user);
        return success('success', $data);
    }

    public function transactions()
    {
        $user = auth()->user();
        $transactions = Transaction::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get();
        $data = TransactionsResource::collection($transactions);
        return success('success', $data);
    }

    public function transactionDetail($trx_no)
    {
        $user = auth()->user();
        $transaction = Transaction::where('user_id', $user->id)->where('trx_no', $trx_no)->first();
        return success('success', $transaction);
    }
}
