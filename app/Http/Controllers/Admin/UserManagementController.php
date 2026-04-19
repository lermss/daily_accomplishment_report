<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;


use App\Models\User;
use App\Services\AdminPortalService;
use App\Services\AuthFlowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function __construct(
        private readonly AuthFlowService $authFlowService,
        private readonly AdminPortalService $adminPortalService,
    ) {
    }

    public function users(Request $request): View|RedirectResponse
    {
        return $this->renderDashboard($request, 'users');
    }

    public function archive(Request $request): View|RedirectResponse
    {
        return $this->renderDashboard($request, 'archive');
        
    }

    public function active(Request $request): View|RedirectResponse
    {
        return $this->renderDashboard($request, 'active');
    }

    public function store(Request $request): RedirectResponse
    {
        // Route middleware already limits these actions to account-management roles.
        $actor = $this->authenticatedUser($request);

        if ($actor instanceof RedirectResponse) {
            return $actor;
        }

        $formOptions = $this->adminPortalService->userFormOptions();
        $validated = $request->validate([
            'role' => ['required', 'in:' . implode(',', array_keys($formOptions))],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        ]);
        $validated['name'] = $this->buildDisplayName($validated);

        $details = $request->validate($this->detailRules($formOptions[$validated['role']]));

        $this->adminPortalService->createManagedUser($actor, $validated, $details);

        return back()->with('user_status', 'New user created successfully.');
    }

    public function update(Request $request, User $targetUser): RedirectResponse
    {
        $actor = $this->authenticatedUser($request);

        if ($actor instanceof RedirectResponse) {
            return $actor;
        }

        $formOptions = $this->adminPortalService->userFormOptions();
        $validated = $request->validate([
            'role' => ['required', 'in:' . implode(',', array_keys($formOptions))],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $targetUser->id],
        ]);
        $validated['name'] = $this->buildDisplayName($validated);

        $details = $request->validate($this->detailRules($formOptions[$validated['role']]));

        $this->adminPortalService->updateManagedUser($actor, $targetUser, $validated, $details);

        return back()->with('user_status', 'User account updated successfully.');
    }

    public function archiveUser(Request $request, User $targetUser): RedirectResponse
    {
        $actor = $this->authenticatedUser($request);

        if ($actor instanceof RedirectResponse) {
            return $actor;
        }

        if ($targetUser->id === $actor->id) {
            return back()->with('user_error', 'You cannot archive your own account.');
        }

        $this->adminPortalService->archiveManagedUser($actor, $targetUser);

        return back()->with('user_status', 'User archived successfully.');
    }

    public function restoreUser(Request $request, User $targetUser): RedirectResponse
    {
        $actor = $this->authenticatedUser($request);

        if ($actor instanceof RedirectResponse) {
            return $actor;
        }

        $this->adminPortalService->restoreManagedUser($actor, $targetUser);

        return back()->with('user_status', 'User restored successfully.');
    }

    private function renderDashboard(Request $request, string $mode): View|RedirectResponse
    {
        $user = $this->authenticatedUser($request);

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        // Managed user screens are rendered from the admin namespace.
        return view('admin.dashboard', $this->adminPortalService->buildDashboardData($request, $user, $mode));
    }

    private function detailRules(array $selectedConfig): array
    {
        $rules = [];

        foreach ($selectedConfig['fields'] as $field) {
            $rules[$field] = match ($field) {
                'position', 'institution' => ['required', 'string', 'max:255'],
                'project' => ['required', 'in:' . implode(',', $selectedConfig['projectOptions'])],
                'bureau' => ['required', 'in:' . implode(',', $selectedConfig['bureauOptions'])],
                'division' => ['required', 'in:' . implode(',', $selectedConfig['divisionOptions'])],
                'office' => ['required', 'in:' . implode(',', $selectedConfig['officeOptions'])],
                default => ['nullable'],
            };
        }

        return $rules;
    }

    private function buildDisplayName(array $validated): string
    {
        return trim(collect([
            $validated['first_name'] ?? null,
            $validated['middle_name'] ?? null,
            $validated['last_name'] ?? null,
        ])->filter(fn ($part) => filled($part))->implode(' '));
    }

    private function authenticatedUser(Request $request, ?callable $guard = null): User|RedirectResponse
    {
        return $this->authFlowService->requireAuthenticated($request, $guard);
    }
}



