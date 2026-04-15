<?php

namespace App\Services;

use App\Models\Report;
use App\Models\ReportEntry;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ReportWorkflowService
{
    private const DEFAULT_ACTIVITY = 'N/A';

    public function __construct(
        private readonly ProvincialHeadAssignmentService $provincialHeadAssignmentService,
    ) {
    }

    public function staffReportsFor(?User $staffUser, string $searchTerm = ''): Collection
    {
        return Report::query()
            ->with('entries')
            ->when($staffUser, fn ($query) => $query->where('user_id', $staffUser->id))
            ->when($searchTerm !== '', function ($query) use ($searchTerm) {
                $query->where(function ($innerQuery) use ($searchTerm) {
                    $innerQuery
                        ->where('file_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('status', 'like', '%' . $searchTerm . '%')
                        ->orWhereRaw("DATE_FORMAT(updated_at, '%m/%d/%Y') LIKE ?", ['%' . $searchTerm . '%'])
                        ->orWhereRaw("DATE_FORMAT(submitted_at, '%m/%d/%Y') LIKE ?", ['%' . $searchTerm . '%']);
                });
            })
            ->latest()
            ->get();
    }

    public function createDraftReport(?User $staffUser, array $validated): Report
    {
        return DB::transaction(function () use ($staffUser, $validated): Report {
            // Reports stay editable until staff explicitly submit them for review.
            $report = Report::create([
                'user_id' => $staffUser?->id,
                'file_name' => $validated['file_name'],
                'status' => Report::STATUS_DRAFT,
            ]);

            $this->syncReportEntries($report, $validated);

            return $report;
        });
    }

    public function updateReport(Report $report, array $validated): void
    {
        DB::transaction(function () use ($report, $validated): void {
            // Handle both existing entries (to update) and newly added rows (to create)
            $entryIds = $validated['entry_id'] ?? [];
            $startDates = $validated['start_date'] ?? [];

            foreach ($startDates as $index => $startDate) {
                $entryId = $entryIds[$index] ?? null;
                $entryPayload = $this->entryPayload($report->id, $validated, $index, $startDate);

                if (!empty($entryId)) {
                    ReportEntry::where('id', $entryId)->update($entryPayload);
                    continue;
                }

                ReportEntry::create($entryPayload);
            }
        });
    }

    public function submitReport(Report $report, User $staffUser): void
    {
        // Submission moves the report from draft mode into the routed provincial review queue.
        $provincialHead = $this->provincialHeadAssignmentService->resolveProvincialHeadForStaff($staffUser);

        $report->submit($provincialHead->id);
    }

    private function syncReportEntries(Report $report, array $validated): void
    {
        foreach ($validated['start_date'] as $index => $startDate) {
            ReportEntry::create($this->entryPayload($report->id, $validated, $index, $startDate));
        }
    }

    private function entryPayload(int $reportId, array $validated, int $index, string $startDate): array
    {
        return [
            'report_id' => $reportId,
            'start_date' => $startDate,
            'end_date' => $validated['end_date'][$index] ?? null,
            'activity' => $validated['activity'][$index] ?? self::DEFAULT_ACTIVITY,
            'details' => $validated['details'][$index] ?? null,
            'remarks' => $validated['remarks'][$index] ?? null,
        ];
    }
}
