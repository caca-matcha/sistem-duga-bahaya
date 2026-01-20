<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Fetch notifications for the logged-in employee.
     * Returns a JSON response with unread and read notifications.
     */
    public function index()
    {
        $user = Auth::user();
        
        $unreadNotifications = $user->notifications()->where('is_read', false)->latest()->get();
        $readNotifications = $user->notifications()->where('is_read', true)->latest()->limit(10)->get();

        return response()->json([
            'unread' => $unreadNotifications,
            'read' => $readNotifications,
            'unread_count' => $unreadNotifications->count(),
        ]);
    }

    /**
     * Mark a specific notification as read and redirect to the related report.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead(Notification $notification)
    {
        // Authorization check: ensure the notification belongs to the authenticated user
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $notification->update(['is_read' => true]);

        // Redirect to the hazard report if it exists
        if ($notification->report_id) {
            return redirect()->route('karyawan.hazards.show', $notification->report_id);
        }

        // Fallback redirect if there's no associated report
        return redirect()->back();
    }

    /**
     * Mark all unread notifications for the user as read.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead()
    {
        Auth::user()->notifications()->where('is_read', false)->update(['is_read' => true]);

        return response()->json(['message' => 'All notifications marked as read.']);
    }
}
