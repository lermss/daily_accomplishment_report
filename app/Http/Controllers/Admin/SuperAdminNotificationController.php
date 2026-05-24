<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuperAdminNotification;
use App\Models\User;
use App\Services\AuthFlowService;
use App\Services\SuperAdminNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuperAdminNotificationController extends Controller
{
    public function __construct(
        private readonly AuthFlowService $authFlowService,
        private readonly SuperAdminNotificationService $notificationService,
    ) {
    }

    public function index(Request $request): View|RedirectResponse
    {
        $user = $this->superAdminUser($request);

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        $this->notificationService->refreshSummaryNotifications();

        return view('super_admin.notifications.index', [
            'title' => 'Super Admin Notifications',
            'user' => $user,
            'notifications' => $this->notificationService->paginate(),
            'unreadCount' => $this->notificationService->unreadCount(),
            'canAccessAudit' => $this->authFlowService->canAccessAudit($user->role),
        ]);
    }

    public function markRead(Request $request, SuperAdminNotification $notification): RedirectResponse
    {
        $user = $this->superAdminUser($request);

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        $this->notificationService->markAsRead($notification);

        return back()->with('status', 'Notification marked as read.');
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        $user = $this->superAdminUser($request);

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        $this->notificationService->markAllAsRead();

        return back()->with('status', 'All notifications marked as read.');
    }

    private function superAdminUser(Request $request): User|RedirectResponse
    {
        return $this->authFlowService->requireAuthenticated(
            $request,
            fn (User $user) => $this->authFlowService->isSuperAdminRole($user->role)
        );
    }
}
