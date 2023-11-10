<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\UUIDGenerate;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Wallet;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUser;
use App\Http\Requests\UpdateUser;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($each) {
                    return Carbon::parse($each->created_at)->format('Y/m/d H:i:s');
                })
                ->editColumn('updated_at', function ($each) {
                    return Carbon::parse($each->updated_at)->format('Y/m/d H:i:s');
                })
                ->editColumn('user_agent', function ($each) {
                    if ($each->user_agent) {
                        $agent = new Agent();
                        $agent->setUserAgent($each->user_agent);
                        $device = $agent->device();
                        $platform = $agent->platform();
                        $browser = $agent->browser();

                        return '<table class=" table table-bordered">
                        <tr>
                            <td>Device</td>
                            <td>' .
                            $device .
                            '</td>
                        </tr>
                        <tr>
                            <td>Platform</td>
                            <td>' .
                            $platform .
                            '</td>
                        </tr>
                        <tr>
                            <td>Browser</td>
                            <td>' .
                            $browser .
                            '</td>
                        </tr>
                    </table>';
                    }
                    return '--';
                })
                ->addColumn('action', function ($each) {
                    $edit_icon = '<a href="' . route('user.edit', $each->id) . '" class="text-info"><i class="bi bi-pencil"></i></a>';
                    // $edit_icon = '<a href="{{ route('admin-user.edit', $each->id) }}" class="text-info"><i class="bi bi-pencil"></i></a>';
                    $delete_icon = '<a href="#" class="text-danger delete" data-id="' . $each->id . '"><i class="bi bi-trash3-fill"></i></a>';
                    return '<div class="action-icon">' . $edit_icon . $delete_icon . '</div>';
                    // return "<a href=''>hello</a>";
                })
                ->rawColumns(['user_agent', 'action'])
                ->make(true);
        }
        return view('backend.user.index');
    }

    public function create()
    {
        return view('backend.user.create');
    }

    public function store(StoreUser $request)
    {
        DB::beginTransaction();

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->save();

            Wallet::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'account_number' => UUIDGenerate::accountNumber(),
                    'amount' => 0,
                ],
            );

            DB::commit();

            return redirect()
                ->route('user.index')
                ->with('create', 'Successfully created');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withErrors(['fail' => 'Something was wrong' . $e])
                ->withInput();
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('backend.user.edit', compact('user'));
    }

    public function update(UpdateUser $request, $id)
    {
        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = $request->password ? Hash::make($request->password) : $user->password;
            $user->update();

            Wallet::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'account_number' => UUIDGenerate::accountNumber(),
                    'amount' => 0,
                ],
            );

            DB::commit();

            return redirect()
                ->route('user.index')
                ->with('update', 'Successfully Updated');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withErrors(['fail' => 'Something was wrong' . $e])
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return 'success';
    }
}
