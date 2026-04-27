<?php

namespace App\View\Components;

use App\Models\User;
use App\Services\AuthFlowService;
use App\Services\SuperAdminNotificationService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Topbar extends Component
{
    public bool $isAdminNavigation;
    public bool $isSuperAdminNavigation;
    public bool $canViewNotifications;
    public bool $canManageAuthenticatorAccess;
    public bool $canManageReminders;
    public bool $canViewOfficeUsers;
    public bool $canViewSuperAdminUsers;
    public string $reportsRoute;
    public string $notificationRoute;
    public Collection $submissionNotifications;
    public int $pendingNotificationsCount;
    public Collection $superAdminNotifications;
    public int $superAdminUnreadCount;

    public function __construct(
        public string $active = 'home',
        public bool $canAccessAudit = false,
        public ?User $user = null,
    ) {
        $authFlowService = app(AuthFlowService::class);

        $this->isAdminNavigation = $this->user !== null && $authFlowService->isAdminRole($this->user->role);
        $this->isSuperAdminNavigation = $this->user !== null && $authFlowService->isSuperAdminRole($this->user->role);
        $this->canViewNotifications = $this->isAdminNavigation || $this->isSuperAdminNavigation;
        $this->canManageAuthenticatorAccess = $this->isSuperAdminNavigation;
        $this->canManageReminders = $this->user?->role === 'ph-admin';
        $this->canViewOfficeUsers = $this->user?->role === 'ph-admin';
        $this->canViewSuperAdminUsers = $this->isSuperAdminNavigation;
        $this->reportsRoute = $this->isSuperAdminNavigation ? route('reports.index') : route('admin.dashboard.employees');
        $this->notificationRoute = $this->isSuperAdminNavigation
            ? route('super-admin.notifications.index')
            : ($this->user?->role === 'ph-admin'
                ? route('admin.dashboard.notifications.index')
                : route('dashboard.admin'));
        $this->submissionNotifications = collect();
        $this->pendingNotificationsCount = 0;
        $this->superAdminNotifications = collect();
        $this->superAdminUnreadCount = 0;

        // ADD THIS CODE: custom super admin notifications are kept separate from the
        // existing admin/ph-admin pending submission preview.
        if ($this->isSuperAdminNavigation) {
            $notificationService = app(SuperAdminNotificationService::class);
            $notificationService->refreshSummaryNotifications();
            $this->superAdminNotifications = $notificationService->latestPreview(5);
            $this->superAdminUnreadCount = $notificationService->unreadCount();
        } elseif ($this->canViewNotifications) {
            $baseNotificationRoute = route('dashboard.admin');

            $this->submissionNotifications = DB::table('reports')
                ->leftJoin('users', 'users.id', '=', 'reports.user_id')
                ->where('reports.status', 'pending')
                ->when($this->user?->role === 'ph-admin', function ($query) {
                    $query->where(function ($scopedQuery) {
                        $scopedQuery
                            ->where('reports.assigned_provincial_head_id', $this->user?->id)
                            ->orWhere(function ($fallbackQuery) {
                                $fallbackQuery
                                    ->whereNull('reports.assigned_provincial_head_id')
                                    ->where('users.office', $this->user?->office);
                            });
                    });
                })
                ->orderByDesc(DB::raw('COALESCE(reports.submitted_at, reports.created_at)'))
                ->get([
                    'reports.id',
                    'reports.file_name',
                    'reports.submitted_at',
                    'reports.created_at',
                    'users.name as user_name',
                    'users.office as user_office',
                ])
                ->map(function ($notification) use ($baseNotificationRoute) {
                    $notification->route = $baseNotificationRoute . '?open_report=' . $notification->id;

                    return $notification;
                });

            $this->pendingNotificationsCount = DB::table('reports')
                ->leftJoin('users', 'users.id', '=', 'reports.user_id')
                ->where('reports.status', 'pending')
                ->when($this->user?->role === 'ph-admin', function ($query) {
                    $query->where(function ($scopedQuery) {
                        $scopedQuery
                            ->where('reports.assigned_provincial_head_id', $this->user?->id)
                            ->orWhere(function ($fallbackQuery) {
                                $fallbackQuery
                                    ->whereNull('reports.assigned_provincial_head_id')
                                    ->where('users.office', $this->user?->office);
                            });
                    });
                })
                ->count();
        }
    }

    public function render(): View
    {
        return view('components.topbar');
    }
}
