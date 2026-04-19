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
        $searchTerm = trim((string) $request->query('search', ''));
        $staffUser = $this->resolveStaffUser($request);

        // The controller stays thin and delegates the report listing query to the workflow service.
        $reports = $this->reportWorkflowService->staffReportsFor($staffUser, $searchTerm);

        return view('staff.reports.index', compact('reports'));
    }

    public function update(UpdateReportRequest $request, int $id): RedirectResponse
    {
        $report = $this->findOwnedReport($request, $id, ['entries']);
        $this->reportWorkflowService->updateReport($report, $request->validated());

        // Unhide reports with pending or for_revision status when they are updated
        if (in_array($report->status, ['pending', 'for_revision'])) {
            $report->update(['is_hidden_from_staff_index' => false]);
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

        $pdf = Pdf::loadView('staff.reports.pdf', compact('report'))
            ->setPaper('a4', 'portrait')
            ->setOption('dpi', 150)
            ->setOption('defaultFont', 'Times-Roman');

        return $pdf->download('report_' . $report->id . '.pdf');
    }

    public function pdf(Request $request, int $id)
    {
        return $this->exportPDF($request, $id);
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
