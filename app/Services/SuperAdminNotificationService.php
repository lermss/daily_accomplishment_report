<?php

namespace App\Services;

use App\Models\Report;
use App\Models\SuperAdminNotification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SuperAdminNotificationService
{
    public function latestPreview(int $limit = 5): Collection
    {
        if (! $this->notificationsTableExists()) {
            return collect();
        }

        return SuperAdminNotification::query()
            ->latestFirst()
            ->limit($limit)
            ->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        if (! $this->notificationsTableExists()) {
            return new Paginator([], 0, $perPage);
        }

        return SuperAdminNotification::query()
            ->latestFirst()
            ->paginate($perPage);
    }

    public function unreadCount(): int
    {
        if (! $this->notificationsTableExists()) {
            return 0;
        }

        return SuperAdminNotification::query()->unread()->count();
    }

    public function refreshSummaryNotifications(): void
    {
        if (! $this->notificationsTableExists()) {
            return;
        }

        $this->syncPendingReportsSummary();
        $this->syncDailySummary();
    }

    public function markAsRead(SuperAdminNotification $notification): void
    {
        if ($notification->read_status) {
            return;
        }

        $notification->forceFill([
            'read_status' => true,
            'read_at' => now(),
        ])->save();
    }

    public function markAllAsRead(): void
    {
        if (! $this->notificationsTableExists()) {
            return;
        }

        SuperAdminNotification::query()
            ->unread()
            ->update([
                'read_status' => true,
                'read_at' => now(),
                'updated_at' => now(),
            ]);
    }

    /**
     * Create a real-time notification when a staff/intern submits a report.
     * Called from ReportWorkflowService::submitReport().
     */
    public function recordReportSubmission(\App\Models\Report $report, \App\Models\User $staffUser): void
    {
        if (! $this->notificationsTableExists()) {
            return;
        }

        $fileName  = $report->file_name ?: ('Report #' . $report->id);
        $staffName = $staffUser->name ?? 'A staff member';
        $office    = $staffUser->office ? ' (' . $staffUser->office . ')' : '';

        $this->upsertNotification('report-submission:' . $report->id, [
            'title'        => $staffName . ' submitted a report',
            'message'      => $staffName . $office . ' submitted "' . $fileName . '" and it is now awaiting your review.',
            'type'         => SuperAdminNotification::TYPE_REVIEW,
            'action_label' => 'Review Now',
            'action_url'   => route('reports.pending'),
            'meta'         => [
                'report_id'  => $report->id,
                'staff_id'   => $staffUser->id,
                'staff_name' => $staffName,
                'office'     => $staffUser->office,
                'file_name'  => $fileName,
            ],
        ]);
    }

    /**
     * Create a notification when a staff/intern edits (saves) an existing report.
     * Called from ReportController::update().
     */
    public function recordReportEdit(\App\Models\Report $report, \App\Models\User $staffUser): void
    {
        if (! $this->notificationsTableExists()) {
            return;
        }

        $fileName  = $report->file_name ?: ('Report #' . $report->id);
        $staffName = $staffUser->name ?? 'A staff member';
        $office    = $staffUser->office ? ' (' . $staffUser->office . ')' : '';

        // Use a time-bucketed key (hourly) so repeated quick edits don't spam;
        // each new hour produces a fresh unread notification.
        $hourKey = now()->format('Y-m-d-H');
        $this->upsertNotification('report-edit:' . $report->id . ':' . $hourKey, [
            'title'        => $staffName . ' edited a report',
            'message'      => $staffName . $office . ' updated "' . $fileName . '". Please review the latest changes.',
            'type'         => SuperAdminNotification::TYPE_INFO,
            'action_label' => 'View Report',
            'action_url'   => route('reports.pending'),
            'meta'         => [
                'report_id'  => $report->id,
                'staff_id'   => $staffUser->id,
                'staff_name' => $staffName,
                'office'     => $staffUser->office,
                'file_name'  => $fileName,
                'edited_at'  => now()->toDateTimeString(),
            ],
        ]);
    }

    public function recordOtpAbuseAttempt(string $email, Request $request, int $remainingSeconds): void
    {
        if (! $this->notificationsTableExists()) {
            return;
        }

        $cacheKey = 'super-admin:otp-abuse:' . now()->toDateString();
        $payload = Cache::get($cacheKey, [
            'count' => 0,
            'emails' => [],
            'ips' => [],
        ]);

        $payload['count']++;
        $payload['emails'] = array_values(array_unique(array_merge($payload['emails'], [strtolower(trim($email))])));
        $payload['ips'] = array_values(array_unique(array_merge($payload['ips'], [(string) $request->ip()])));

        Cache::put($cacheKey, $payload, now()->addDay());

        if ($payload['count'] < 3) {
            return;
        }

        $emailSummary = collect($payload['emails'])->take(3)->implode(', ');
        $ipSummary = collect($payload['ips'])->take(2)->implode(', ');

        $this->upsertNotification('otp-abuse:' . now()->toDateString(), [
            'title' => 'Multiple OTP requests detected',
            'message' => 'Blocked OTP retry attempts today: ' . $payload['count']
                . '. Recent targets: ' . ($emailSummary !== '' ? $emailSummary : 'unknown')
                . '. Latest cooldown remaining: ' . $remainingSeconds . ' seconds.'
                . ($ipSummary !== '' ? ' Recent IPs: ' . $ipSummary . '.' : ''),
            'type' => SuperAdminNotification::TYPE_URGENT,
            'action_label' => 'View Details',
            'action_url' => route('audit.index', ['activity' => 'otp_requested']),
            'meta' => [
                'blocked_attempts' => $payload['count'],
                'emails' => $payload['emails'],
                'ips' => $payload['ips'],
            ],
        ]);
    }

    public function recordSystemAlert(string $title, string $message, ?string $actionLabel = 'View Details', ?string $actionUrl = null, ?string $sourceKey = null): void
    {
        if (! $this->notificationsTableExists()) {
            return;
        }

        $key = $sourceKey ?: 'system-alert:' . sha1($title . '|' . $message . '|' . now()->format('Y-m-d-H'));

        $this->upsertNotification($key, [
            'title' => $title,
            'message' => $message,
            'type' => SuperAdminNotification::TYPE_URGENT,
            'action_label' => $actionLabel,
            'action_url' => $actionUrl ?: route('health.status'),
            'meta' => [],
        ]);
    }

    private function syncPendingReportsSummary(): void
    {
        $pendingCount = Report::query()
            ->where('status', Report::STATUS_PENDING)
            ->count();

        if ($pendingCount === 0) {
            SuperAdminNotification::query()
                ->where('source_key', 'pending-reports-summary')
                ->delete();

            return;
        }

        $latestPendingAt = Report::query()
            ->where('status', Report::STATUS_PENDING)
            ->max('submitted_at');

        $latestLabel = $latestPendingAt
            ? Carbon::parse($latestPendingAt)->diffForHumans()
            : 'recently';

        $this->upsertNotification('pending-reports-summary', [
            'title' => $pendingCount . ' reports pending review',
            'message' => 'There are currently ' . $pendingCount . ' report(s) waiting for review. Latest submission was ' . $latestLabel . '.',
            'type' => SuperAdminNotification::TYPE_REVIEW,
            'action_label' => 'Review Now',
            'action_url' => route('reports.pending'),
            'meta' => [
                'pending_count' => $pendingCount,
                'latest_pending_at' => $latestPendingAt,
            ],
        ]);
    }

    private function syncDailySummary(): void
    {
        $start = now()->startOfDay();
        $end = now()->endOfDay();

        $submittedToday = Report::query()
            ->whereBetween(DB::raw('COALESCE(submitted_at, created_at)'), [$start, $end])
            ->count();

        $approvedToday = Report::query()
            ->where('status', Report::STATUS_APPROVED)
            ->whereBetween('reviewed_at', [$start, $end])
            ->count();

        $pendingToday = Report::query()
            ->where('status', Report::STATUS_PENDING)
            ->whereBetween(DB::raw('COALESCE(submitted_at, created_at)'), [$start, $end])
            ->count();

        $this->upsertNotification('daily-summary:' . now()->toDateString(), [
            'title' => 'Daily report summary',
            'message' => 'Today: ' . $submittedToday . ' submitted, ' . $approvedToday . ' approved, and ' . $pendingToday . ' still pending.',
            'type' => SuperAdminNotification::TYPE_INFO,
            'action_label' => 'View Details',
            'action_url' => route('reports.index'),
            'meta' => [
                'submitted_today' => $submittedToday,
                'approved_today' => $approvedToday,
                'pending_today' => $pendingToday,
            ],
        ]);
    }

    private function upsertNotification(string $sourceKey, array $payload): SuperAdminNotification
    {
        $notification = SuperAdminNotification::query()->firstOrNew([
            'source_key' => $sourceKey,
        ]);

        $newAttributes = [
            'title' => $payload['title'],
            'message' => $payload['message'],
            'type' => $payload['type'],
            'action_label' => $payload['action_label'] ?? null,
            'action_url' => $payload['action_url'] ?? null,
            'meta' => $payload['meta'] ?? [],
        ];

        $hasChanges = ! $notification->exists
            || $notification->title !== $newAttributes['title']
            || $notification->message !== $newAttributes['message']
            || $notification->type !== $newAttributes['type']
            || $notification->action_label !== $newAttributes['action_label']
            || $notification->action_url !== $newAttributes['action_url']
            || (array) $notification->meta !== (array) $newAttributes['meta'];

        if (! $notification->exists || $hasChanges) {
            $notification->forceFill(array_merge($newAttributes, [
                'read_status' => false,
                'read_at' => null,
                'created_at' => now(),
            ]))->save();
        }

        return $notification;
    }

    private function notificationsTableExists(): bool
    {
        static $exists;

        if ($exists !== null) {
            return $exists;
        }

        try {
            return $exists = Schema::hasTable('super_admin_notifications');
        } catch (\Throwable) {
            return $exists = false;
        }
    }
}
