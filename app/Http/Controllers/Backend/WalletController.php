<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

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

                ->rawColumns(['account_person',])
                ->make(true);
        }
        return view('backend.wallet.index');
    }
}
