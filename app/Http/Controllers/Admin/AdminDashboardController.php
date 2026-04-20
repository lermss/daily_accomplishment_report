<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;


use App\Models\Report;
use App\Models\User;
use App\Services\AdminPortalService;
use App\Services\AuthFlowService;
use App\Services\ProvincialHeadAssignmentService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __construct(
        private readonly AuthFlowService $authFlowService,
        private readonly AdminPortalService $adminPortalService,
        private readonly ProvincialHeadAssignmentService $provincialHeadAssignmentService,
    ) {
    }

    public function superAdminDashboard(Request $request): View|RedirectResponse
    {
        return $this->renderSuperAdminReports($request, 'dashboard');
    }

    public function adminDashboard(Request $request): View|RedirectResponse
    {
        return $this->renderAdminReports($request, 'dashboard');
    }

    public function adminEmployees(Request $request): View|RedirectResponse
    {
        return $this->renderAdminReports($request, 'employees');
    }

    public function adminApproved(Request $request): View|RedirectResponse
    {
        return $this->renderAdminReports($request, 'approved');
    }

    public function adminPending(Request $request): View|RedirectResponse
    {
        return $this->renderAdminReports($request, 'pending');
    }

    public function adminRevisions(Request $request): View|RedirectResponse
    {
        return $this->renderAdminReports($request, 'revisions');
    }

    public function reportsIndex(Request $request): View|RedirectResponse
    {
        return $this->renderSuperAdminReports($request, 'reports');
    }

    public function reportsEmployees(Request $request): View|RedirectResponse
    {
        return $this->renderSuperAdminReports($request, 'employees');
    }

    public function reportsApproved(Request $request): View|RedirectResponse
    {
        return $this->renderSuperAdminReports($request, 'approved');
    }

    public function reportsPending(Request $request): View|RedirectResponse
    {
        return $this->renderSuperAdminReports($request, 'pending');
    }

    public function reportsRevisions(Request $request): View|RedirectResponse
    {
        return $this->renderSuperAdminReports($request, 'revisions');
    }

    public function updateReportStatus(Request $request, Report $report): JsonResponse|RedirectResponse
    {
        // Route middleware already limits this action to admin roles.
        $user = $this->authenticatedUser($request);

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        $validated = $request->validate([
            'status' => ['required', 'in:approved,for_revision'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $report->loadMissing('user:id,office');

        abort_unless(
            $this->provincialHeadAssignmentService->canReviewReport($user, $report),
            403,
            'You are not authorized to review reports outside your assigned office.'
        );

        $comment = trim((string) ($validated['comment'] ?? ''));

        // Status changes and optional revision comments move through one shared service path.
        $this->adminPortalService->updateReportStatus(
            $report,
            $validated['status'],
            $user->id,
            $comment !== '' ? $comment : null
        );

        if ($comment !== '') {
            $action = $validated['status'] === 'approved' ? 'report_approved' : 'report_returned';
            $statusLabel = $validated['status'] === 'approved' ? 'approved' : 'returned for revision';

            $this->adminPortalService->logActivity(
                $user,
                $action,
                'Report "' . ($report->file_name ?: ('#' . $report->id)) . '" was ' . $statusLabel . '. Note: ' . $comment
            );
        }

        // The dashboard modal saves status updates asynchronously and expects JSON here.
        if ($request->expectsJson()) {
            $freshReport = $report->fresh();

            return response()->json([
                'message' => 'Report review status updated successfully.',
                'report' => [
                    'id' => $report->id,
                    'status' => $freshReport?->status,
                    'status_label' => ucfirst(str_replace('_', ' ', $freshReport?->status ?? '')),
                    'reviewed_at' => optional($freshReport?->reviewed_at)->format('m/d/Y') ?: 'N/A',
                    'review_comment' => $freshReport?->review_comment,
                ],
                'counts' => $this->adminPortalService->reportSummaryCounts($user),
            ]);
        }

        return back()->with('status', 'Report review status updated successfully.');
    }

    public function exportReportPDF(Request $request, int $id)
    {
        $user = $this->authenticatedUser($request);

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        $report = Report::with('entries')->findOrFail($id);

        // Admin can export any report
        $pdf = Pdf::loadView('staff.reports.pdf', compact('report'))
            ->setPaper('a4', 'portrait')
            ->setOption('dpi', 150)
            ->setOption('defaultFont', 'Times-Roman');

        return $pdf->download('report_' . $report->id . '.pdf');
    }

    private function renderAdminReports(Request $request, string $mode): View|RedirectResponse
    {
        $user = $this->authenticatedUser($request);

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        // Admin report screens now live under the dedicated admin view namespace.
        return view('admin.reports', array_merge(
            $this->adminPortalService->buildAdminDashboardData($request, $user, $mode),
            ['autoOpenReportId' => (string) $request->query('open_report', '')]
        ));
    }

    private function renderSuperAdminReports(Request $request, string $mode): View|RedirectResponse
    {
        $user = $this->authenticatedUser($request);

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        $view = $mode === 'dashboard' ? 'admin.dashboard' : 'super_admin.reports-table';
        $data = $mode === 'dashboard'
            ? $this->adminPortalService->buildDashboardData($request, $user, 'dashboard')
            : $this->adminPortalService->buildAdminDashboardData($request, $user, $mode);

        return view($view, $data);
    }

    private function authenticatedUser(Request $request, ?callable $guard = null): User|RedirectResponse
    {
        return $this->authFlowService->requireAuthenticated($request, $guard);
    }

    public function bulkDelete(Request $request): RedirectResponse
    {
        $user = $this->authenticatedUser($request);

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        $reportIds = $request->input('report_ids', []);
        
        if (!empty($reportIds)) {
            // Only allow deleting approved reports
            Report::whereIn('id', $reportIds)
                ->where('status', 'approved')
                ->where(function ($query) use ($user) {
                    // Ensure the user can only delete reports they can review
                    $this->provincialHeadAssignmentService->scopeReportsForReviewer($query, $user);
                })
                ->update(['is_hidden_from_admin_dashboard' => true]);

            // Check if any reports are now hidden from all views and permanently delete them
            $this->permanentlyDeleteFullyHiddenReports();
        }

        return redirect()->back()->with('success', 'Selected approved reports have been hidden from admin dashboard.');
    }

    private function permanentlyDeleteFullyHiddenReports(): void
    {
        // Find reports that are hidden from all views (admin dashboard, staff dashboard, staff index)
        $fullyHiddenReports = Report::where('is_hidden_from_admin_dashboard', true)
            ->where('is_hidden_from_staff_dashboard', true)
            ->where('is_hidden_from_staff_index', true)
            ->pluck('id');

        if ($fullyHiddenReports->isNotEmpty()) {
            Report::whereIn('id', $fullyHiddenReports)->delete();
        }
    }
}


