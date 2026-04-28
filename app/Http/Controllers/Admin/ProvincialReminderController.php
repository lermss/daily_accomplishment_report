<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthFlowService;
use App\Services\ProvincialReminderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProvincialReminderController extends Controller
{
    public function __construct(
        private readonly AuthFlowService $authFlowService,
        private readonly ProvincialReminderService $provincialReminderService,
    ) {
    }

    public function index(Request $request): View|RedirectResponse
    {
        $user = $this->provincialHeadUser($request);

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        $this->provincialReminderService->dispatchDueReminders($user->office);
        $schedule = $this->provincialReminderService->scheduleForUser($user);
        $recentReminders = $this->provincialReminderService->recentRemindersForOfficePaginated($user->office, 5, $request);

        return view('admin.reminders', [
            'title'               => 'Office Reminders',
            'user'                => $user,
            'canAccessAudit'      => $this->authFlowService->canAccessAudit($user->role),
            'schedule'            => $schedule,
            'recentReminders'     => $recentReminders,
            'recentReminderCount' => $recentReminders->total(),
        ]);
    }

    public function saveSchedule(Request $request): RedirectResponse
    {
        $user = $this->provincialHeadUser($request);

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        $validated = $request->validate([
            'message' => ['nullable', 'string', 'max:500'],
            'send_time' => ['required', 'date_format:H:i'],
            'is_enabled' => ['nullable', 'boolean'],
        ]);

        $this->provincialReminderService->saveDailySchedule($user, $validated);

        return back()->with('status', 'Daily reminder schedule saved.');
    }

    public function sendNow(Request $request): RedirectResponse
    {
        $user = $this->provincialHeadUser($request);

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        $validated = $request->validate([
            'message' => ['nullable', 'string', 'max:500'],
        ]);

        $this->provincialReminderService->sendReminderNow($user, $validated['message'] ?? null);

        return back()->with('status', 'Reminder sent to all staff in your office.');
    }

    private function provincialHeadUser(Request $request): User|RedirectResponse
    {
        return $this->authFlowService->requireAuthenticated(
            $request,
            fn (User $user) => $user->role === 'ph-admin'
        );
    }
}
