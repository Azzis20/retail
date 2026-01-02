<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Services\NotificationService;


class VendorNotificationController extends Controller
{
    //
        /**
     * Display all notifications
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'all');
        
        $query = Notification::with('creator')->orderBy('created_at', 'desc');

        if ($type !== 'all') {
            $query->where('type', $type);
        }

        $notifications = $query->paginate(20);
        $unreadCount = NotificationService::getUnreadCount();

        return view('vendor.notifications.index', compact('notifications', 'type', 'unreadCount'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->markAsRead();

        // Redirect to related page if exists
        if ($notification->related_type === 'Order') {
            return redirect()->route('vendor.order.show', $notification->related_id);
        } elseif ($notification->related_type === 'Product') {
            return redirect()->route('vendor.product.index');
        }

        return redirect()->back()->with('success', 'Notification marked as read');
    }

    /**
     * Mark all as read
     */
    public function markAllAsRead()
    {
        NotificationService::markAllAsRead();
        return redirect()->back()->with('success', 'All notifications marked as read');
    }

    /**
     * Delete notification
     */
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return redirect()->back()->with('success', 'Notification deleted');
    }

    /**
     * Get unread count (for AJAX)
     */
    public function getUnreadCount()
    {
        return response()->json([
            'count' => NotificationService::getUnreadCount()
        ]);
    }
}
