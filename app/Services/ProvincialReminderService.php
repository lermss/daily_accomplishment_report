<?php

namespace App\Services;

use App\Models\OfficeReminder;
use App\Models\OfficeReminderSchedule;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProvincialReminderService
{
    public function scheduleForUser(User $user): ?OfficeReminderSchedule
    {
        return OfficeReminderSchedule::query()
            ->where('office', $user->office)
            ->where('created_by', $user->id)
            ->latest('id')
            ->first();
    }

    public function recentRemindersForOffice(?string $office, int $limit = 10): Collection
    {
        if (! $office) {
            return collect();
        }

        return OfficeReminder::query()
            ->where('office', $office)
            ->orderByDesc('triggered_at')
            ->limit($limit)
            ->get();
    }

    public function recentRemindersForOfficePaginated(?string $office, int $perPage = 5, ?Request $request = null): LengthAwarePaginator
    {
        $query = OfficeReminder::query()
            ->where('office', $office ?? '')
            ->orderByDesc('triggered_at');

        return $query->paginate($perPage)->appends($request?->except('page') ?? []);
    }

    public function saveDailySchedule(User $user, array $validated): OfficeReminderSchedule
    {
        return DB::transaction(function () use ($user, $validated): OfficeReminderSchedule {
            OfficeReminderSchedule::query()
                ->where('office', $user->office)
                ->where('created_by', '!=', $user->id)
                ->delete();

            $schedule = OfficeReminderSchedule::query()->firstOrNew([
                'office' => $user->office,
                'created_by' => $user->id,
            ]);

            $schedule->forceFill([
                'message' => $this->normalizeMessage($user->office, $validated['message'] ?? null),
                'send_time' => $validated['send_time'],
                'is_enabled' => (bool) ($validated['is_enabled'] ?? false),
            ])->save();

            return $schedule;
        });
    }

    public function sendReminderNow(User $user, ?string $message = null): OfficeReminder
    {
        return OfficeReminder::query()->create([
            'office' => $user->office,
            'message' => $this->normalizeMessage($user->office, $message),
            'type' => OfficeReminder::TYPE_MANUAL,
            'triggered_at' => now(),
            'created_by' => $user->id,
            'office_reminder_schedule_id' => null,
        ]);
    }

    public function dispatchDueReminders(?string $office = null): int
    {
        $now = now();
        $today = $now->toDateString();
        $currentTime = $now->format('H:i:s');

        $schedules = OfficeReminderSchedule::query()
            ->where('is_enabled', true)
            ->when($office, fn ($query) => $query->where('office', $office))
            ->where('send_time', '<=', $currentTime)
            ->where(function ($query) use ($today) {
                $query
                    ->whereNull('last_sent_on')
                    ->orWhereDate('last_sent_on', '<', $today);
            })
            ->get();

        $dispatched = 0;

        foreach ($schedules as $schedule) {
            DB::transaction(function () use ($schedule, $today, $now, &$dispatched): void {
                $schedule->refresh();

                if (! $schedule->is_enabled || ($schedule->last_sent_on && $schedule->last_sent_on->toDateString() >= $today)) {
                    return;
                }

                OfficeReminder::query()->create([
                    'office' => $schedule->office,
                    'message' => $this->normalizeMessage($schedule->office, $schedule->message),
                    'type' => OfficeReminder::TYPE_SCHEDULED,
                    'triggered_at' => $now->copy(),
                    'created_by' => $schedule->created_by,
                    'office_reminder_schedule_id' => $schedule->id,
                ]);

                $schedule->forceFill([
                    'last_sent_on' => $today,
                ])->save();

                $dispatched++;
            });
        }

        return $dispatched;
    }

    public function reminderNotificationsForStaff(User $user): Collection
    {
        if (! $user->office) {
            return collect();
        }

        $this->dispatchDueReminders($user->office);

        return OfficeReminder::query()
            ->where('office', $user->office)
            ->orderByDesc('triggered_at')
            ->get();
    }

    public function unreadReminderCountForStaff(User $user): int
    {
        if (! $user->office) {
            return 0;
        }

        $this->dispatchDueReminders($user->office);

        return OfficeReminder::query()
            ->where('office', $user->office)
            ->when($user->notifications_read_at, fn ($query) => $query->where('triggered_at', '>', $user->notifications_read_at))
            ->count();
    }

    private function normalizeMessage(?string $office, ?string $message): string
    {
        $message = trim((string) $message);

        if ($message !== '') {
            return $message;
        }

        return 'Reminder for ' . ($office ?: 'your office') . ': Please submit your accomplishment report.';
    }
}
