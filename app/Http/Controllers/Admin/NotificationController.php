<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use App\Services\AuthFlowService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class NotificationController extends Controller
{
    public function __construct(
        private readonly AuthFlowService $authFlowService,
    ) {
    }

    /** PH Admin notification inbox — shows all report submissions from their office. */
    public function index(Request $request): \Illuminate\View\View|RedirectResponse
    {
        $user = $this->authFlowService->requireAuthenticated(
            $request,
            fn (User $u) => $u->role === 'ph-admin'
        );

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        $office = (string) $user->office;

        $submissions = Report::query()
            ->with(['user:id,name,avatar_path,office'])
            ->whereHas('user', fn ($q) => $q->where('office', $office))
            ->whereIn('status', ['pending', 'approved', 'for_revision'])
            ->orderByRaw('COALESCE(submitted_at, created_at) DESC')
            ->paginate(15);

        // Mark all as read by updating user's read timestamp
        $user->forceFill(['notifications_read_at' => now()])->save();

        return view('admin.notifications', [
            'title'          => 'Notifications',
            'user'           => $user,
            'submissions'    => $submissions,
            'office'         => $office,
            'canAccessAudit' => $this->authFlowService->canAccessAudit($user->role),
        ]);
    }

    public function markAsRead(Request $request): JsonResponse
    {
        $user = $this->authFlowService->requireAuthenticated(
            $request,
            fn (User $user) => in_array((string) $user->role, ['super_admin', 'hr-super-admin', 'admin', 'ph-admin'], true)
        );

        if ($user instanceof RedirectResponse) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        if ($user->notifications_read_at === null || $user->notifications_read_at->isBefore(now())) {
            $user->forceFill([
                'notifications_read_at' => Carbon::now(),
            ])->save();
        }

        return response()->json([
            'success'      => true,
            'unread_count' => 0,
        ]);
    }
}
