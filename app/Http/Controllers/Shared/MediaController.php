<?php

namespace App\Http\Controllers\Shared;
use App\Http\Controllers\Controller;


use App\Services\AuthFlowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MediaController extends Controller
{
    public function __construct(
        private readonly AuthFlowService $authFlowService,
    ) {
    }

    public function showPublic(Request $request, string $path): BinaryFileResponse|RedirectResponse
    {
        $user = $this->authFlowService->requireAuthenticated($request);

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        $normalizedPath = ltrim(str_replace('\\', '/', $path), '/');

        if (
            $normalizedPath === ''
            || str_contains($normalizedPath, '../')
            || str_starts_with($normalizedPath, '/')
            || !Storage::disk('public')->exists($normalizedPath)
        ) {
            abort(404);
        }

        return response()->file(Storage::disk('public')->path($normalizedPath));
    }
}



