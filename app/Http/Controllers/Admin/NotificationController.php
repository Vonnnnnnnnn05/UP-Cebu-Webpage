<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Display a listing of system feedback and operational notifications.
     */
    public function index(Request $request): View
    {
        $user = Auth::guard('admin')->user();
        $filter = $request->query('filter', 'all');

        $query = $user->notifications();

        if ($filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($filter === 'read') {
            $query->whereNotNull('read_at');
        } elseif ($filter === 'event') {
            $query->where('data->item_type', 'event');
        } elseif ($filter === 'inquiry') {
            $query->where('data->item_type', 'inquiry');
        }

        $notifications = $query->paginate(12)->withQueryString();

        $stats = [
            'total'          => $user->notifications()->count(),
            'unread'         => $user->unreadNotifications()->count(),
            'event_feedback' => $user->notifications()->where('data->item_type', 'event')->count(),
            'inquiry_feedback' => $user->notifications()->where('data->item_type', 'inquiry')->count(),
        ];

        return view('admin.notifications.index', compact('notifications', 'filter', 'stats'));
    }

    /**
     * Get JSON of unread count and latest notifications for dynamic header updates.
     */
    public function unreadCount(Request $request): JsonResponse
    {
        $user = Auth::guard('admin')->user();
        if (!$user) {
            return response()->json(['count' => 0, 'notifications' => []]);
        }

        $unreadCount = $user->unreadNotifications()->count();
        $recent = $user->notifications()->take(7)->get()->map(function ($item) {
            $data = is_array($item->data) ? $item->data : (json_decode($item->data, true) ?? []);
            return [
                'id'               => $item->id,
                'title'            => $data['title'] ?? 'System Notification',
                'message'          => $data['message'] ?? '',
                'reviewer_name'    => $data['reviewer_name'] ?? 'System',
                'reviewer_role'    => $data['reviewer_role'] ?? 'admin',
                'item_type'        => $data['item_type'] ?? 'general',
                'item_title'       => $data['item_title'] ?? '',
                'action_url'       => $data['action_url'] ?? '#',
                'type_label'       => $data['type_label'] ?? 'Feedback',
                'is_read'          => $item->read_at !== null,
                'created_at_human' => $item->created_at?->diffForHumans() ?? 'Just now',
            ];
        });

        return response()->json([
            'count'         => $unreadCount,
            'notifications' => $recent,
        ]);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(Request $request, string $id): JsonResponse|RedirectResponse
    {
        $user = Auth::guard('admin')->user();
        $notification = $user->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'count'   => $user->unreadNotifications()->count(),
            ]);
        }

        return redirect()->back()->with('success_message', 'Notification marked as read.');
    }

    /**
     * Mark a specific notification as unread.
     */
    public function markAsUnread(Request $request, string $id): RedirectResponse
    {
        $user = Auth::guard('admin')->user();
        $notification = $user->notifications()->where('id', $id)->firstOrFail();
        $notification->update(['read_at' => null]);

        return redirect()->back()->with('success_message', 'Notification marked as unread.');
    }

    /**
     * Mark all notifications as read for current user.
     */
    public function markAllAsRead(Request $request): JsonResponse|RedirectResponse
    {
        $user = Auth::guard('admin')->user();
        $user->unreadNotifications->markAsRead();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'count'   => 0,
            ]);
        }

        return redirect()->back()->with('success_message', 'All notifications marked as read.');
    }

    /**
     * Delete a specific notification.
     */
    public function destroy(Request $request, string $id): RedirectResponse
    {
        $user = Auth::guard('admin')->user();
        $notification = $user->notifications()->where('id', $id)->firstOrFail();
        $notification->delete();

        return redirect()->back()->with('success_message', 'Notification removed.');
    }

    /**
     * Delete all read notifications for current user.
     */
    public function clearRead(Request $request): RedirectResponse
    {
        $user = Auth::guard('admin')->user();
        $user->readNotifications()->delete();

        return redirect()->back()->with('success_message', 'All read notifications have been cleared.');
    }
}
