<?php

namespace App\Http\Controllers\Shared;
use App\Http\Controllers\Controller;


use App\Models\User;
use App\Services\AdminPortalService;
use App\Services\AuthFlowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        private readonly AuthFlowService $authFlowService,
        private readonly AdminPortalService $adminPortalService,
    ) {
    }

    public function edit(Request $request): View|RedirectResponse
    {
        $user = $this->authenticatedUser($request);

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        return view('admin.edit-profile', $this->adminPortalService->buildProfileData($user));
    }

    public function staffProfile(Request $request): View|RedirectResponse
    {
        $user = $this->authenticatedUser($request);

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        // Reuse the same profile data so staff can manage their details from the staff area.
        return view('staff.staff_profile', $this->adminPortalService->buildProfileData($user));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $this->authenticatedUser($request);

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'project' => ['nullable', 'string', 'max:255'],
            'bureau' => ['nullable', 'string', 'max:255'],
            'office' => ['required', 'string', 'max:255'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'signature_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $this->adminPortalService->updateProfile($user, $request, $validated);

        $redirectRoute = in_array((string) $user->role, ['staff', 'interns'], true)
            // ADD THIS CODE
            ? $this->authFlowService->staffPortalRoute($user->role, 'profile')
            : 'profile.edit';

        return redirect()->route($redirectRoute)->with('profile_status', 'Profile updated successfully.');
    }

    private function authenticatedUser(Request $request, ?callable $guard = null): User|RedirectResponse
    {
        return $this->authFlowService->requireAuthenticated($request, $guard);
    }
}



