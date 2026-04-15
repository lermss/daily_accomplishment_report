<?php

namespace App\Services;

use App\Models\Report;
use App\Models\User;
use App\Support\ProvincialOffice;
use Illuminate\Validation\ValidationException;

class ProvincialHeadAssignmentService
{
    public function officeOptions(): array
    {
        return ProvincialOffice::all();
    }

    public function resolveProvincialHeadForStaff(User $staffUser): User
    {
        $office = (string) $staffUser->office;

        if (!ProvincialOffice::isValid($office)) {
            throw ValidationException::withMessages([
                'office' => 'Your account must be assigned to one of the supported provincial offices before submitting a report.',
            ]);
        }

        $provincialHead = User::query()
            ->where('role', 'ph-admin')
            ->where('status', 'active')
            ->where('office', $office)
            ->orderBy('id')
            ->first();

        if (!$provincialHead) {
            throw ValidationException::withMessages([
                'office' => 'No active Provincial Head is assigned to your office yet. Please contact an administrator.',
            ]);
        }

        return $provincialHead;
    }

    public function ensureValidManagedUserAssignment(array $validated, array $details, ?User $targetUser = null): void
    {
        $role = (string) ($validated['role'] ?? '');
        $office = $details['office'] ?? null;

        if (!in_array($role, ['ph-admin', 'staff', 'interns'], true)) {
            return;
        }

        if (!ProvincialOffice::isValid($office)) {
            throw ValidationException::withMessages([
                'office' => 'Office must be one of the four provincial offices: La Union, Ilocos Norte, Ilocos Sur, or Pangasinan.',
            ]);
        }

        if ($role !== 'ph-admin') {
            return;
        }

        $duplicateExists = User::query()
            ->where('role', 'ph-admin')
            ->where('status', 'active')
            ->where('office', $office)
            ->when($targetUser, fn ($query) => $query->where('id', '!=', $targetUser->id))
            ->exists();

        if ($duplicateExists) {
            throw ValidationException::withMessages([
                'office' => 'This office already has an active Provincial Head assigned.',
            ]);
        }
    }

    public function canReviewReport(User $reviewer, Report $report): bool
    {
        if ($reviewer->role === 'admin') {
            return true;
        }

        if ($reviewer->role !== 'ph-admin') {
            return false;
        }

        if ($report->assigned_provincial_head_id !== null) {
            return (int) $report->assigned_provincial_head_id === (int) $reviewer->id;
        }

        $reportOffice = (string) ($report->user?->office ?? '');

        return $reportOffice !== '' && $reportOffice === (string) $reviewer->office;
    }

    public function scopeReportsForReviewer($query, User $reviewer)
    {
        if ($reviewer->role !== 'ph-admin') {
            return $query;
        }

        return $query->where(function ($officeQuery) use ($reviewer) {
            $officeQuery
                ->where('assigned_provincial_head_id', $reviewer->id)
                ->orWhere(function ($fallbackQuery) use ($reviewer) {
                    $fallbackQuery
                        ->whereNull('assigned_provincial_head_id')
                        ->whereHas('user', fn ($userQuery) => $userQuery->where('office', $reviewer->office));
                });
        });
    }
}
