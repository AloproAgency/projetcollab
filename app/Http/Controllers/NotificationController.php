<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {

        $user = auth()->user();
        $notifications = $user->notifications;
        $notifications_unread = Notification::where('user_id', $user->id)
                                    ->where('is_read', false)
                                    ->get();

        foreach ($notifications_unread as $notification) {
            $notification->update(['is_read' => true]);
        }
        return view('notifications', compact('notifications'));
    }
}
