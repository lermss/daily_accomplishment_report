<?php

namespace App\Http\Controllers\Staff;
use App\Http\Controllers\Controller;


use App\Models\Report;
use App\Models\User;
use App\Services\AuthFlowService;
use App\Services\ProvincialReminderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class StaffNotificationController extends Controller
{
    public function __construct(
        private readonly AuthFlowService $authFlowService,
        private readonly ProvincialReminderService $provincialReminderService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $user = $this->requireStaffUser($request);

        if ($user instanceof JsonResponse) {
            return $user;
        }

        $reportNotifications = Report::query()
            ->where('user_id', $user->id)
            ->whereIn('status', [Report::STATUS_APPROVED, Report::STATUS_FOR_REVISION])
            ->whereNotNull('reviewed_at')
            ->orderByDesc('reviewed_at')
            ->get(['id', 'file_name', 'status', 'reviewed_at', 'review_comment'])
            ->map(function (Report $report) use ($user) {
                return [
                    'id' => 'report-' . $report->id,
                    'entity_id' => $report->id,
                    'type' => 'report_review',
                    'status' => $report->status,
                    'message' => $report->status === Report::STATUS_APPROVED
                        ? 'Your report has been approved'
                        : 'Your report needs revision',
                    'comment' => $report->review_comment,
                    'file_name' => $report->file_name ?: 'Untitled report',
                    'timestamp' => $report->reviewed_at,
                    'reviewed_at' => $report->reviewed_at?->format('M d, Y h:i A'),
                    // ADD THIS CODE
                    'route' => route(app(\App\Services\AuthFlowService::class)->staffPortalRoute($user->role, 'reports.show'), ['id' => $report->id]),
                ];
            })
            ->values();

        $reminderNotifications = $this->provincialReminderService->reminderNotificationsForStaff($user)
            ->map(function ($reminder) use ($user) {
                return [
                    'id'          => 'reminder-' . $reminder->id,
                    'entity_id'   => $reminder->id,
                    'type'        => 'office_reminder',
                    'status'      => 'reminder',
                    'message'     => $reminder->message ?: 'Reminder from your Provincial Head',
                    'comment'     => null,
                    'file_name'   => $reminder->message ?: 'Reminder from your Provincial Head',
                    'timestamp'   => $reminder->triggered_at,
                    'reviewed_at' => $reminder->triggered_at?->format('M d, Y h:i A'),
                    'route'       => route(app(\App\Services\AuthFlowService::class)->staffPortalRoute($user->role, 'dashboard')),
                ];
            });

        $notifications = $reportNotifications
            ->concat($reminderNotifications)
            ->sortByDesc(fn (array $notification) => optional($notification['timestamp'])->getTimestamp() ?? 0)
            ->take(20)
            ->values()
            ->map(function (array $notification) {
                unset($notification['timestamp']);

                return $notification;
            });

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $this->unreadCount($user),
        ]);
    }

    public function viewAll(Request $request): \Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        $user = $this->authFlowService->requireAuthenticated(
            $request,
            fn (User $user) => in_array((string) $user->role, ['staff', 'interns'], true)
        );

        if ($user instanceof \Illuminate\Http\RedirectResponse) {
            return $user;
        }

        $typeFilter   = $request->query('type', '');
        $statusFilter = $request->query('status', '');
        $perPage      = 15;

        // Build report notifications
        $reportQuery = Report::query()
            ->where('user_id', $user->id)
            ->whereIn('status', [Report::STATUS_APPROVED, Report::STATUS_FOR_REVISION])
            ->whereNotNull('reviewed_at')
            ->when($statusFilter && $statusFilter !== 'all', fn ($q) => $q->where('status', $statusFilter))
            ->orderByDesc('reviewed_at')
            ->select(['id', 'file_name', 'status', 'reviewed_at', 'review_comment']);

        // Build reminder notifications
        $reminderItems = collect();
        if ($user->office) {
            $reminderItems = \App\Models\OfficeReminder::query()
                ->where('office', $user->office)
                ->orderByDesc('triggered_at')
                ->get()
                ->map(fn ($r) => (object) [
                    'type'       => 'office_reminder',
                    'title'      => 'Office Reminder',
                    'message'    => $r->message,
                    'comment'    => null,
                    'timestamp'  => $r->triggered_at,
                    'time_label' => $r->triggered_at?->diffForHumans() ?? 'Just now',
                    'status'     => 'reminder',
                    'route'      => route(app(\App\Services\AuthFlowService::class)->staffPortalRoute($user->role, 'dashboard')),
                ]);
        }

        $reportItems = $reportQuery->get()->map(fn ($r) => (object) [
            'type'       => 'report_review',
            'title'      => $r->status === 'approved' ? 'Report Approved' : 'Needs Revision',
            'message'    => $r->file_name ?: 'Untitled report',
            'comment'    => $r->review_comment,
            'timestamp'  => $r->reviewed_at,
            'time_label' => $r->reviewed_at?->diffForHumans() ?? 'Just now',
            'status'     => $r->status,
            'route'      => route(app(\App\Services\AuthFlowService::class)->staffPortalRoute($user->role, 'reports.show'), ['id' => $r->id]),
        ]);

        // Merge and filter by type
        $all = $reportItems->concat($reminderItems)
            ->when($typeFilter && $typeFilter !== 'all', fn ($c) => $c->filter(fn ($n) => $n->type === $typeFilter))
            ->sortByDesc(fn ($n) => $n->timestamp?->getTimestamp() ?? 0)
            ->values();

        // Manual pagination
        $page    = (int) $request->query('page', 1);
        $total   = $all->count();
        $items   = $all->slice(($page - 1) * $perPage, $perPage)->values();
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $items, $total, $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $staffRouteBase = app(\App\Services\AuthFlowService::class)->staffPortalPrefix($user->role);

        return view('staff.notifications.page', compact('paginator', 'user', 'typeFilter', 'statusFilter', 'staffRouteBase'));
    }

    public function markAsRead(Request $request): JsonResponse
    {
        $user = $this->requireStaffUser($request);

        if ($user instanceof JsonResponse) {
            return $user;
        }

        // Mark every reviewed report notification as seen when the modal opens.
        if ($this->hasNotificationsReadColumn()) {
            $user->forceFill([
                'notifications_read_at' => Carbon::now(),
            ])->save();
        }

        return response()->json([
            'success' => true,
            'unread_count' => 0,
        ]);
    }

    private function requireStaffUser(Request $request): User|JsonResponse
    {
        $user = $this->authFlowService->requireAuthenticated(
            $request,
            fn (User $user) => in_array((string) $user->role, ['staff', 'interns'], true)
        );

        if ($user instanceof \Illuminate\Http\RedirectResponse) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        return $user;
    }

    private function unreadCount(User $user): int
    {
        $reportUnreadCount = Report::query()
            ->where('user_id', $user->id)
            ->whereIn('status', [Report::STATUS_APPROVED, Report::STATUS_FOR_REVISION])
            ->whereNotNull('reviewed_at')
            ->when(
                $this->hasNotificationsReadColumn() && $user->notifications_read_at,
                fn ($query) => $query->where('reviewed_at', '>', $user->notifications_read_at)
            )
            ->count();

        return $reportUnreadCount + $this->provincialReminderService->unreadReminderCountForStaff($user);
    }

    private function hasNotificationsReadColumn(): bool
    {
        static $hasColumn;

        if ($hasColumn !== null) {
            return $hasColumn;
        }

        try {
            return $hasColumn = Schema::hasColumn('users', 'notifications_read_at');
        } catch (\Throwable) {
            return $hasColumn = false;
        }
    }
}


