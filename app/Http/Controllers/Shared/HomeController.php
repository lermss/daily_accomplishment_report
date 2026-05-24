<?php

namespace App\Http\Controllers\Shared;
use App\Http\Controllers\Controller;


use App\Services\AuthFlowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly AuthFlowService $authFlowService,
    ) {
    }

    public function dashboard(Request $request): RedirectResponse
    {
        $user = $this->authFlowService->requireAuthenticated($request);

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        return redirect()->route($this->authFlowService->dashboardRoute($user->role));
    }

    public function homepage(Request $request): View|RedirectResponse
    {
        $user = $this->authFlowService->requireAuthenticated($request);

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        return view('home_page', [
            'title' => 'Home Page',
            'user' => $user,
            'canAccessAudit' => $this->authFlowService->canAccessAudit($user->role),
        ]);
    }

    public function staffHome(Request $request): View|RedirectResponse
    {
        $user = $this->authFlowService->requireAuthenticated($request);

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        return view('home_page', [
            'title' => 'Home Page',
            'user' => $user,
            'canAccessAudit' => $this->authFlowService->canAccessAudit($user->role),
        ]);
    }
}



