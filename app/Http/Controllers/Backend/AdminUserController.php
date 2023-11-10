<?php

namespace App\Http\Controllers\Backend;

use App\Models\AdminUser;
use Jenssegers\Agent\Agent;

use Illuminate\Http\Request;
use function Laravel\Prompts\alert;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreAdminUser;

use App\Http\Requests\UpdateAdminUser;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = AdminUser::query();
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
                    $edit_icon = '<a href="' . route('admin-user.edit', $each->id) . '" class="text-info"><i class="bi bi-pencil"></i></a>';
                    // $edit_icon = '<a href="{{ route('admin-user.edit', $each->id) }}" class="text-info"><i class="bi bi-pencil"></i></a>';
                    $delete_icon = '<a href="#" class="text-danger delete" data-id="' . $each->id . '"><i class="bi bi-trash3-fill"></i></a>';
                    return '<div class="action-icon">' . $edit_icon . $delete_icon . '</div>';
                    // return "<a href=''>hello</a>";
                })
                ->rawColumns(['user_agent', 'action'])
                ->make(true);
        }
        return view('backend.admin-user.index');
    }

    // public function ssr()
    // {
    //     $data = AdminUser::all();
    //     return DataTables::of($data)->toJson();

    //     // return DataTables::of($data)->make(true);
    // }

    public function create()
    {
        return view('backend.admin-user.create');
    }

    public function store(StoreAdminUser $request)
    {
        $adminUser = new AdminUser();
        $adminUser->name = $request->name;
        $adminUser->email = $request->email;
        $adminUser->phone = $request->phone;
        $adminUser->password = Hash::make($request->password);
        $adminUser->save();

        return redirect()
            ->route('admin-user.index')
            ->with('create', 'Successfully created');
    }

    public function edit($id)
    {
        $admin = AdminUser::findOrFail($id);
        return view('backend.admin-user.edit', compact('admin'));
    }

    public function update(UpdateAdminUser $request, $id)
    {
        $adminUser = AdminUser::findOrFail($id);
        $adminUser->name = $request->name;
        $adminUser->email = $request->email;
        $adminUser->phone = $request->phone;
        $adminUser->password = $request->password ? Hash::make($request->password) : $adminUser->password;
        $adminUser->update();

        return redirect()
            ->route('admin-user.index')
            ->with('update', 'Successfully Updated');
    }

    public function destroy($id)
    {
        $admin = AdminUser::find($id);
        $admin->delete();
        return 'success';
    }
}
