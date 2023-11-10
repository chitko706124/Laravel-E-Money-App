<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $authUser = Auth::guard('web')->user();
        $notifications = $authUser->notifications()->paginate(5)->withQueryString();
        return view('frontend.notifications.index', compact('notifications'));
    }

    public function show($id)
    {
        $authUser = Auth::guard('web')->user();
        $notification = $authUser->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();
        return view('frontend.notifications.show', compact('notification'));
    }
}
