<?php

namespace App\View\Components;

use App\Models\User;
use App\Services\AuthFlowService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Topbar extends Component
{
    public bool $isAdminNavigation;
    public bool $isSuperAdminNavigation;
    public bool $canViewNotifications;
    public string $reportsRoute;
    public string $notificationRoute;
    public Collection $submissionNotifications;
    public int $pendingNotificationsCount;

    public function __construct(
        public string $active = 'home',
        public bool $canAccessAudit = false,
        public ?User $user = null,
    ) {
        $authFlowService = app(AuthFlowService::class);

        $this->isAdminNavigation = $this->user !== null && $authFlowService->isAdminRole($this->user->role);
        $this->isSuperAdminNavigation = $this->user !== null && $authFlowService->isSuperAdminRole($this->user->role);
        $this->canViewNotifications = $this->isAdminNavigation || $this->isSuperAdminNavigation;
        $this->reportsRoute = $this->isSuperAdminNavigation ? route('reports.index') : route('admin.dashboard.employees');
        $this->notificationRoute = $this->isSuperAdminNavigation ? route('reports.pending') : route('admin.dashboard.pending');
        $this->submissionNotifications = collect();
        $this->pendingNotificationsCount = 0;

        if ($this->canViewNotifications) {
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
                ->limit(6)
                ->get([
                    'reports.id',
                    'reports.file_name',
                    'reports.submitted_at',
                    'reports.created_at',
                    'users.name as user_name',
                    'users.office as user_office',
                ]);

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
