<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Services\AuthFlowService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly AuthFlowService $authFlowService,
    ) {
    }

    public function index(Request $request): RedirectResponse
    {
        $user = $this->authFlowService->authenticatedUser($request);

        if (!$user) {
            return redirect()->route('login');
        }

        return redirect()->route('dashboard.home');
    }

    public function staff(Request $request): View|RedirectResponse
    {
        $user = $this->authFlowService->requireAuthenticated(
            $request,
            fn ($user) => in_array((string) $user->role, ['staff', 'interns'], true)
        );

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        // ✅ Get filter and search params
        $statusFilter        = $request->query('status');
        $searchTerm          = trim($request->query('search', ''));
        $dateSubmittedFrom   = trim($request->query('date_from', ''));
        $dateSubmittedTo     = trim($request->query('date_to', ''));
        $dateReturnedFrom    = trim($request->query('returned_from', ''));
        $dateReturnedTo      = trim($request->query('returned_to', ''));

        /*
        |--------------------------------------------------------------------------
        | ✅ TABLE DATA (THIS ONE IS FILTERED)
        |--------------------------------------------------------------------------
        */
        $reportsQuery = Report::query()
            ->where('user_id', $user->id)
            ->where('is_hidden_from_staff_dashboard', false)
            ->whereIn('status', [
                Report::STATUS_DRAFT,
                Report::STATUS_PENDING,
                Report::STATUS_APPROVED,
                Report::STATUS_FOR_REVISION,
            ])
            ->select(['id', 'user_id', 'file_name', 'status', 'submitted_at', 'created_at', 'reviewed_at', 'review_comment'])
            ->with(['entries' => fn ($q) => $q->orderBy('id')]);

        // Apply status filter ONLY to table
        if ($statusFilter && $statusFilter !== 'all') {
            $reportsQuery->where('status', $statusFilter);
        }

        // Apply search ONLY to table
        if ($searchTerm) {
            $reportsQuery->where(function ($q) use ($searchTerm) {
                $q->where('file_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('status', 'like', '%' . $searchTerm . '%')
                  ->orWhereRaw(
                      "DATE_FORMAT(COALESCE(submitted_at, created_at), '%m/%d/%Y') LIKE ?",
                      ['%' . $searchTerm . '%']
                  );
            });
        }

        // Date Submitted filter
        if ($dateSubmittedFrom) {
            $reportsQuery->whereRaw("DATE(COALESCE(submitted_at, created_at)) >= ?", [$dateSubmittedFrom]);
        }
        if ($dateSubmittedTo) {
            $reportsQuery->whereRaw("DATE(COALESCE(submitted_at, created_at)) <= ?", [$dateSubmittedTo]);
        }

        // Date Returned (reviewed_at) filter
        if ($dateReturnedFrom) {
            $reportsQuery->whereNotNull('reviewed_at')
                         ->whereDate('reviewed_at', '>=', $dateReturnedFrom);
        }
        if ($dateReturnedTo) {
            $reportsQuery->whereNotNull('reviewed_at')
                         ->whereDate('reviewed_at', '<=', $dateReturnedTo);
        }

        $perPage = max(5, min(100, (int) $request->query('per_page', 10)));
        $reports = $reportsQuery->latest()->paginate($perPage)->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | ✅ SUMMARY COUNTS (ALWAYS GLOBAL - NOT FILTERED)
        |--------------------------------------------------------------------------
        */
        $baseCountQuery = Report::query()
            ->where('user_id', $user->id)
            ->whereIn('status', [
                Report::STATUS_DRAFT,
                Report::STATUS_PENDING,
                Report::STATUS_APPROVED,
                Report::STATUS_FOR_REVISION,
            ]);

        // Clone query so each count is independent
        $submittedCount = (clone $baseCountQuery)
            ->whereNotNull('submitted_at')
            ->count();

        $approvedCount = (clone $baseCountQuery)
            ->where('status', Report::STATUS_APPROVED)
            ->count();

        $pendingCount = (clone $baseCountQuery)
            ->where('status', Report::STATUS_PENDING)
            ->count();

        $revisionCount = (clone $baseCountQuery)
            ->where('status', Report::STATUS_FOR_REVISION)
            ->count();

        return view('staff.dashboard', compact(
            'reports',
            'submittedCount',
            'approvedCount',
            'pendingCount',
            'revisionCount',
            'statusFilter',
            'searchTerm',
            'dateSubmittedFrom',
            'dateSubmittedTo',
            'dateReturnedFrom',
            'dateReturnedTo',
        ));
    }


    public function bulkDelete(Request $request): RedirectResponse
    {
        $user = $this->authFlowService->requireAuthenticated(
            $request,
            fn ($user) => in_array((string) $user->role, ['staff', 'interns'], true)
        );

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        $reportIds = $request->input('report_ids', []);
        
        if (!empty($reportIds)) {
            Report::whereIn('id', $reportIds)
                ->where('user_id', $user->id)
                ->update(['is_hidden_from_staff_dashboard' => true]);
        }

        return redirect()->back()->with('success', 'Selected reports have been DELETE from your dashboard.');
    }
}
