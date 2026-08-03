<?php

namespace App\Controllers;

use App\Models\NotificationModel;
use CodeIgniter\HTTP\RedirectResponse;

/**
 * NotificationController
 *
 * Shared between Admin and Contractor (both role-protected route groups
 * point here). Notifications are keyed by user_id, so the same code
 * safely serves either role — each user only ever sees their own feed.
 */
class NotificationController extends BaseController
{
    protected NotificationModel $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    /**
     * GET /admin/notifications or /contractor/notifications
     */
    public function index()
    {
        $userId = authId();

        $notifications = $this->notificationModel->forUser($userId);

        // Viewing the list marks everything as read.
        $this->notificationModel->markAllRead($userId);

        return view('notifications/index', [
            'title'         => 'Notifications',
            'notifications' => $notifications,
        ]);
    }

    /**
     * GET /admin/notifications/open/{id} or /contractor/notifications/open/{id}
     * Marks a single notification as read and redirects to its target link.
     */
    public function open(int $id): RedirectResponse
    {
        $userId = authId();

        $notification = $this->notificationModel->where('id', $id)->where('user_id', $userId)->first();

        if (! $notification) {
            return redirect()->back()->with('error', 'Notification not found.');
        }

        $this->notificationModel->markRead($id, $userId);

        return redirect()->to($notification['link'] ?: (isAdmin() ? '/admin/dashboard' : '/contractor/dashboard'));
    }
}
