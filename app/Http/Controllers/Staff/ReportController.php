<?php

namespace App\Http\Controllers\Staff;
use App\Http\Controllers\Controller;


use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Models\Report;
use App\Models\ReportEntry;
use App\Models\User;
use App\Services\ReportWorkflowService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    private const DEFAULT_ACTIVITY = 'N/A';

    public function __construct(
        private readonly ReportWorkflowService $reportWorkflowService,
    ) {
    }

    public function index(Request $request): View
    {
        $searchTerm   = trim((string) $request->query('search', ''));
        $statusFilter = trim((string) $request->query('status_filter', ''));
        $perPage      = max(5, min(100, (int) $request->query('per_page', 10)));
        $staffUser    = $this->resolveStaffUser($request);

        // The controller stays thin and delegates the report listing query to the workflow service.
        $reports = $this->reportWorkflowService->staffReportsFor($staffUser, $searchTerm, $perPage, $statusFilter);

        return view('staff.reports.index', compact('reports', 'perPage', 'searchTerm', 'statusFilter'));
    }

    public function update(UpdateReportRequest $request, int $id): RedirectResponse
    {
        $staffUser = $this->resolveStaffUser($request);
        $report    = $this->findOwnedReport($request, $id, ['entries']);

        $this->reportWorkflowService->updateReport($report, $request->validated());

        // Stamp the edit time — used by PH Admin topbar unread count
        $report->update(['last_edited_at' => now()]);

        // Unhide reports with pending or for_revision status when they are updated
        if (in_array($report->status, ['pending', 'for_revision'])) {
            $report->update(['is_hidden_from_staff_index' => false]);
        }

        // Notify Super Admin
        if ($staffUser) {
            app(\App\Services\SuperAdminNotificationService::class)
                ->recordReportEdit($report, $staffUser);
        }

        return back()->with('success', 'Report updated.');
    }

    public function updateFile(Request $request, int $id): RedirectResponse
    {
        $report = $this->findOwnedReport($request, $id);
        $validated = $request->validate([
            'file_name' => 'required|string|max:255',
        ]);

        $report->update([
            'file_name' => $validated['file_name'],
        ]);

        return back()->with('success', 'Report file name updated.');
    }

    public function createReport(): View
    {
        return view('staff.reports.createReport');
    }

    public function storeReport(StoreReportRequest $request): RedirectResponse
    {
        $staffUser = $this->resolveStaffUser($request);
        $this->reportWorkflowService->createDraftReport($staffUser, $request->validated());

        return redirect()
            // ADD THIS CODE
            ->route(app(\App\Services\AuthFlowService::class)->staffPortalRoute($staffUser?->role, 'reports'))
            ->with('success', 'Draft report created.')
            ->with('clear_report_draft', true);
    }

    public function show(Request $request, int $id): View
    {
        $report = $this->findOwnedReport($request, $id, ['entries']);

        return view('staff.reports.show', compact('report'));
    }

    public function exportPDF(Request $request, int $id)
    {
        $report = $this->findOwnedReport($request, $id, ['entries']);

        // Allow export for approved AND draft
        if (!in_array($report->status, ['approved', 'draft'])) {
            abort(403, 'Export is only available for approved or draft reports.');
        }

        // Find a writable temp dir within open_basedir (shared hosting fix)
        $tempDir = null;
        foreach ([storage_path('app/dompdf-tmp'), sys_get_temp_dir(), public_path('dompdf-tmp')] as $candidate) {
            if (!is_dir($candidate)) { @mkdir($candidate, 0755, true); }
            if (is_dir($candidate) && is_writable($candidate)) { $tempDir = $candidate; break; }
        }

        $pdf = Pdf::loadView('staff.reports.pdf', compact('report'))
            ->setPaper('a4', 'portrait')
            ->setOption('dpi', 96)
            ->setOption('defaultFont', 'Times-Roman')
            ->setOption('isRemoteEnabled', false)
            ->setOption('isPhpEnabled', true);

        // Directly configure the underlying dompdf Options — this is the only
        // reliable way to override tempDir on shared hosting with open_basedir.
        if ($tempDir) {
            $options = $pdf->getDomPDF()->getOptions();
            $options->setTempDir($tempDir);
            $options->setFontCache($tempDir);
        }

        return $pdf->download('report_' . $report->id . '.pdf');
    }

    public function pdf(Request $request, int $id)
    {
        return $this->exportPDF($request, $id);
    }

    /**
     * Lightweight JSON endpoint polled by the staff dashboard / reports page
     * to detect status changes without a full page reload.
     */
    public function statusPoll(Request $request): \Illuminate\Http\JsonResponse
    {
        $staffUser = $this->resolveStaffUser($request);

        if (! $staffUser) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $reports = Report::query()
            ->where('user_id', $staffUser->id)
            ->where('is_hidden_from_staff_dashboard', false)
            ->whereIn('status', [Report::STATUS_DRAFT, Report::STATUS_PENDING, Report::STATUS_APPROVED, Report::STATUS_FOR_REVISION])
            ->get(['id', 'status', 'reviewed_at', 'review_comment'])
            ->keyBy('id')
            ->map(fn ($r) => [
                'status'         => $r->status,
                'reviewed_at'    => $r->reviewed_at?->toIso8601String(),
                'review_comment' => $r->review_comment,
            ]);

        return response()->json(['reports' => $reports]);
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        $report = $this->findOwnedReport($request, $id);

        // Soft delete: hide from staff index instead of actually deleting
        $report->update(['is_hidden_from_staff_index' => true]);

        return redirect()
            // ADD THIS CODE
            ->route(app(\App\Services\AuthFlowService::class)->staffPortalRoute($this->resolveStaffUser($request)?->role, 'reports.index'))
            ->with('success', 'Report hidden from your reports list.');
    }

    public function submit(Request $request, int $id): RedirectResponse
    {
        $staffUser = $this->resolveStaffUser($request);

        if (!$staffUser) {
            return redirect()->route('login');
        }

        $report = $this->findOwnedReport($request, $id);
        $this->reportWorkflowService->submitReport($report, $staffUser);

        // Unhide reports when submitted so they become visible in the index
        $report->update(['is_hidden_from_staff_dashboard' => false]);

        return redirect()
            // ADD THIS CODE
            ->route(app(\App\Services\AuthFlowService::class)->staffPortalRoute($staffUser?->role, 'dashboard'))
            ->with('success', 'Report submitted to your assigned Provincial Head for review.');
    }

    public function storeEntry(Request $request)
    {
        $validated = $request->validate([
            'report_id' => ['required', 'integer', 'exists:reports,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date'],
            'activity' => ['nullable', 'string'],
            'details' => ['nullable', 'string'],
            'remarks' => ['nullable', 'string'],
        ]);

        $entry = ReportEntry::create([
            'report_id' => $validated['report_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'activity' => $this->normalizeOptionalText($validated['activity'] ?? null, self::DEFAULT_ACTIVITY),
            'details' => $this->normalizeOptionalText($validated['details'] ?? null),
            'remarks' => $this->normalizeOptionalText($validated['remarks'] ?? null),
        ]);

        return response()->json(['success' => true, 'entry' => $entry]);
    }

    private function resolveStaffUser(Request $request): ?User
    {
        $userId = $request->session()->get('authenticated_user_id');

        if (!$userId) {
            return null;
        }

        // The project currently stores the authenticated user id in session during the OTP flow.
        return User::find($userId);
    }

    private function normalizeOptionalText(?string $value, ?string $default = null): ?string
    {
        $value = $value !== null ? trim($value) : null;

        if ($value === null || $value === '') {
            return $default;
        }

        return $value;
    }

    private function findOwnedReport(Request $request, int $id, array $with = []): Report
    {
        $staffUser = $this->resolveStaffUser($request);

        if (!$staffUser) {
            throw new ModelNotFoundException();
        }

        return Report::query()
            ->with($with)
            ->where('user_id', $staffUser->id)
            ->findOrFail($id);
    }
}
