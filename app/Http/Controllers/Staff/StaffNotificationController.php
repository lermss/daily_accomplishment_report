<?php

namespace App\Http\Controllers\Staff;
use App\Http\Controllers\Controller;


use App\Models\Report;
use App\Models\User;
use App\Services\AuthFlowService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class StaffNotificationController extends Controller
{
    public function __construct(
        private readonly AuthFlowService $authFlowService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $user = $this->requireStaffUser($request);

        if ($user instanceof JsonResponse) {
            return $user;
        }

        $notifications = Report::query()
            ->where('user_id', $user->id)
            ->whereIn('status', [Report::STATUS_APPROVED, Report::STATUS_FOR_REVISION])
            ->whereNotNull('reviewed_at')
            ->orderByDesc('reviewed_at')
            ->limit(10)
            ->get(['id', 'file_name', 'status', 'reviewed_at', 'review_comment'])
            ->map(function (Report $report) use ($user) {
                return [
                    'id' => $report->id,
                    'status' => $report->status,
                    'message' => $report->status === Report::STATUS_APPROVED
                        ? 'Your report has been approved'
                        : 'Your report needs revision',
                    'comment' => $report->review_comment,
                    'file_name' => $report->file_name ?: 'Untitled report',
                    'reviewed_at' => $report->reviewed_at?->format('M d, Y h:i A'),
                    // ADD THIS CODE
                    'route' => route(app(\App\Services\AuthFlowService::class)->staffPortalRoute($user->role, 'reports.show'), ['id' => $report->id]),
                ];
            })
            ->values();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $this->unreadCount($user),
        ]);
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
        return Report::query()
            ->where('user_id', $user->id)
            ->whereIn('status', [Report::STATUS_APPROVED, Report::STATUS_FOR_REVISION])
            ->whereNotNull('reviewed_at')
            ->when(
                $this->hasNotificationsReadColumn() && $user->notifications_read_at,
                fn ($query) => $query->where('reviewed_at', '>', $user->notifications_read_at)
            )
            ->count();
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



