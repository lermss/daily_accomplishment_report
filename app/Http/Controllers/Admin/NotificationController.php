<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            'success' => true,
            'unread_count' => 0,
        ]);
    }
}
