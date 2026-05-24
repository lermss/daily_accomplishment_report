<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;


use App\Services\AdminPortalService;
use App\Services\AuthFlowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditController extends Controller
{
    public function __construct(
        private readonly AuthFlowService $authFlowService,
        private readonly AdminPortalService $adminPortalService,
    ) {
    }

    public function index(Request $request): View|RedirectResponse
    {
        // Route middleware limits audit access to administrative roles.
        $user = $this->authFlowService->requireAuthenticated($request);

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        return view('admin.audit-log', $this->adminPortalService->buildAuditData($request, $user));
    }
}



