<?php

namespace App\View\Components;

use App\Models\OfficeReminder;
use App\Models\Report;
use App\Models\User;
use App\Services\AuthFlowService;
use App\Services\SuperAdminNotificationService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\Component;

class Topbar extends Component
{
    public bool $isAdminNavigation;
    public bool $isSuperAdminNavigation;
    public bool $isStaffNavigation;
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
    public Collection $staffNotifications;
    public int $staffUnreadCount;

    public function __construct(
        public string $active = 'home',
        public bool $canAccessAudit = false,
        public ?User $user = null,
    ) {
        $authFlowService = app(AuthFlowService::class);

        $this->isAdminNavigation = $this->user !== null && $authFlowService->isAdminRole($this->user->role);
        $this->isSuperAdminNavigation = $this->user !== null && $authFlowService->isSuperAdminRole($this->user->role);
        $this->isStaffNavigation = $this->user !== null && in_array((string) $this->user->role, ['staff', 'interns'], true);
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
        $this->staffNotifications = collect();
        $this->staffUnreadCount = 0;

        // ADD THIS CODE: custom super admin notifications are kept separate from the
        // existing admin/ph-admin pending submission preview.
        if ($this->isSuperAdminNavigation) {
            $notificationService = app(SuperAdminNotificationService::class);
            $notificationService->refreshSummaryNotifications();
            $this->superAdminNotifications = $notificationService->latestPreview(5);
            $this->superAdminUnreadCount = $notificationService->unreadCount();
        } elseif ($this->canViewNotifications) {
            // ph-admin: pending reports page; other admins: generic admin dashboard
            $baseNotificationRoute = $this->user?->role === 'ph-admin'
                ? route('admin.dashboard.pending')
                : route('dashboard.admin');

            $officeScope = function ($query) {
                $query->where(function ($sq) {
                    $sq->where('reports.assigned_provincial_head_id', $this->user?->id)
                       ->orWhere('users.office', $this->user?->office);
                });
            };

            // ── 1. Pending (new submissions) ────────────────────────────
            $pendingItems = DB::table('reports')
                ->leftJoin('users', 'users.id', '=', 'reports.user_id')
                ->where('reports.status', 'pending')
                ->when($this->user?->role === 'ph-admin', $officeScope)
                ->orderByDesc(DB::raw('COALESCE(reports.submitted_at, reports.created_at)'))
                ->get([
                    'reports.id',
                    'reports.file_name',
                    'reports.status',
                    'reports.submitted_at',
                    'reports.created_at',
                    'reports.last_edited_at',
                    'users.name as user_name',
                    'users.office as user_office',
                ])
                ->map(function ($n) use ($baseNotificationRoute) {
                    $n->route        = $baseNotificationRoute . '?open_report=' . $n->id;
                    $n->notif_type   = 'submission';
                    $n->sort_time    = $n->submitted_at ?? $n->created_at;
                    return $n;
                });

            // ── 2. Edited reports (staff saved changes) ─────────────────
            $editedItems = DB::table('reports')
                ->leftJoin('users', 'users.id', '=', 'reports.user_id')
                ->whereNotNull('reports.last_edited_at')
                ->where('reports.status', '!=', 'draft') // don't notify on draft edits
                ->when($this->user?->role === 'ph-admin', $officeScope)
                ->orderByDesc('reports.last_edited_at')
                ->get([
                    'reports.id',
                    'reports.file_name',
                    'reports.status',
                    'reports.submitted_at',
                    'reports.created_at',
                    'reports.last_edited_at',
                    'users.name as user_name',
                    'users.office as user_office',
                ])
                ->map(function ($n) use ($baseNotificationRoute) {
                    $n->route        = $baseNotificationRoute . '?open_report=' . $n->id;
                    $n->notif_type   = 'edit';
                    $n->sort_time    = $n->last_edited_at;
                    return $n;
                });

            // ── Merge, deduplicate (prefer edited entry), sort, cap at 10 ─
            $this->submissionNotifications = $pendingItems
                ->concat($editedItems)
                ->groupBy('id')             // deduplicate by report id
                ->map(fn ($group) => $group->sortByDesc(fn ($n) => $n->notif_type === 'edit')->first())
                ->values()
                ->sortByDesc('sort_time')
                ->take(10)
                ->values();

            // ── Unread badge count ────────────────────────────────────────
            $readAt = $this->user?->notifications_read_at;

            $this->pendingNotificationsCount = $this->submissionNotifications->filter(function ($n) use ($readAt) {
                if (! $readAt) {
                    return true; // never read → all are unread
                }
                $sortTime = $n->sort_time ? \Illuminate\Support\Carbon::parse($n->sort_time) : null;
                return $sortTime && $sortTime->greaterThan($readAt);
            })->count();
        } elseif ($this->isStaffNavigation) {
            // ── Fetch report reviews for this staff member ──────────────
            $reportItems = Report::query()
                ->where('user_id', $this->user?->id)
                ->whereIn('status', ['approved', 'for_revision'])
                ->whereNotNull('reviewed_at')
                ->orderByDesc('reviewed_at')
                ->limit(10)
                ->get(['id', 'file_name', 'status', 'reviewed_at', 'review_comment'])
                ->map(fn ($r) => (object) [
                    'type'       => 'report_review',
                    'title'      => $r->status === 'approved' ? 'Report Approved' : 'Needs Revision',
                    'message'    => $r->file_name ?: 'Untitled report',
                    'comment'    => $r->review_comment,
                    'timestamp'  => $r->reviewed_at,
                    'time_label' => $r->reviewed_at?->diffForHumans() ?? 'Just now',
                    'status'     => $r->status,
                ]);

            // ── Fetch office reminders for this staff member ────────────
            $reminderItems = collect();
            if ($this->user?->office) {
                $reminderItems = OfficeReminder::query()
                    ->where('office', $this->user->office)
                    ->orderByDesc('triggered_at')
                    ->limit(10)
                    ->get()
                    ->map(fn ($r) => (object) [
                        'type'       => 'office_reminder',
                        'title'      => 'Office Reminder',
                        'message'    => $r->message,
                        'comment'    => null,
                        'timestamp'  => $r->triggered_at,
                        'time_label' => $r->triggered_at?->diffForHumans() ?? 'Just now',
                        'status'     => 'reminder',
                    ]);
            }

            $this->staffNotifications = $reportItems
                ->concat($reminderItems)
                ->sortByDesc(fn ($n) => $n->timestamp?->getTimestamp() ?? 0)
                ->take(15)
                ->values();

            // ── Unread count ────────────────────────────────────────────
            $readAt = null;
            try {
                if (Schema::hasColumn('users', 'notifications_read_at')) {
                    $readAt = $this->user?->notifications_read_at;
                }
            } catch (\Throwable) {
            }

            $this->staffUnreadCount = $this->staffNotifications->filter(
                fn ($n) => ! $readAt || ($n->timestamp && $n->timestamp->greaterThan($readAt))
            )->count();
        }
    }

    public function render(): View
    {
        return view('components.topbar');
    }
}
