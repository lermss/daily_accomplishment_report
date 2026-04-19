<section class="table-panel">
            <div class="report-filters-row">
                <div class="filters-left">
                    <div class="search-form report-search-form">
                        <input type="search" name="search" value="{{ $search }}" placeholder="Search by staff name, file name, or status" aria-label="Search reports" data-report-search>
                        <button type="submit" aria-label="Search"><svg viewBox="0 0 24 24"><path d="M10 4a6 6 0 1 0 3.87 10.59l4.27 4.27a1 1 0 0 0 1.42-1.42l-4.27-4.27A6 6 0 0 0 10 4Zm0 2a4 4 0 1 1-4 4 4 4 0 0 1 4-4Z"/></svg></button>
                    </div>
                    <select name="status_filter" class="status-select" aria-label="Filter by status" data-status-filter>
                        @foreach ($statusFilterOptions as $filterValue => $filterLabel)
                            <option value="{{ $filterValue }}" {{ $statusFilter === $filterValue ? 'selected' : '' }}>{{ $filterLabel }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filters-right">
                    <button type="button" id="deleteSelectedBtn" class="btn btn-danger" style="padding: 8px 16px; border-radius: 6px;" disabled>Delete Selected</button>
                </div>
                <div class="results-summary" data-results-summary><strong>{{ $reports->count() }}</strong> visible {{ $reports->count() === 1 ? 'report' : 'reports' }}</div>
            </div>
        </form>
    </div>

    <form id="bulkDeleteForm" method="POST" action="{{ $isSuperAdminView ? route('reports.bulk-delete') : route('admin.dashboard.bulk-delete') }}">
        @csrf
        <div class="table-wrap">
            <table>
                <thead><tr><th><input type="checkbox" id="selectAll"></th><th>Name</th><th>Filename</th><th>Date Submitted</th><th>Status</th><th>Action</th></tr></thead>
            <tbody data-reports-body>
                @forelse ($reports as $report)
                    @php
                        $initials = strtoupper(collect(explode(' ', $report->user_name ?? 'Un'))->filter()->map(fn ($part) => substr($part, 0, 1))->take(2)->implode(''));
                        $avatarUrl = $report->user_avatar_path ? route('media.public', ['path' => ltrim($report->user_avatar_path, '/')]) : null;
                        $signatureUrl = $report->user_signature_path ? route('media.public', ['path' => ltrim($report->user_signature_path, '/')]) : null;
                        $statusClass = match($report->status) {'approved' => 'status-approved','pending' => 'status-pending','for_revision' => 'status-revision',default => 'status-default'};
                        $submittedAt = $report->submitted_at ?: $report->created_at;
                        $reportPayload = [
                            'id' => $report->id,
                            'user_name' => $report->user_name ?: 'Unassigned User',
                            'file_name' => $report->file_name ?: 'No file uploaded',
                            'status' => $report->status,
                            'status_label' => ucfirst(str_replace('_', ' ', $report->status ?? 'unknown')),
                            'submitted_at' => $submittedAt ? \Illuminate\Support\Carbon::parse($submittedAt)->format('F d, Y') : 'N/A',
                            'review_comment' => $report->review_comment_text,
                            'signature_url' => $signatureUrl,
                            'download_url' => $report->file_path && ! $isSuperAdminView ? asset($report->file_path) : null,
                            'status_url' => $canManageReportRecords ? route('admin.dashboard.reports.status', $report->id) : null,
                            'entries' => $report->entries->map(fn ($entry) => [
                                'period' => trim(collect([
                                    $entry->start_date ? \Illuminate\Support\Carbon::parse($entry->start_date)->format('m/d/Y') : null,
                                    $entry->end_date ? 'to ' . \Illuminate\Support\Carbon::parse($entry->end_date)->format('m/d/Y') : null,
                                ])->filter()->implode(' ')),
                                'activity' => $entry->activity ?: 'Not provided',
                                'details' => $entry->details ?: 'No details provided.',
                                'remarks' => $entry->remarks ?: 'No remarks.',
                            ])->values()->all(),
                        ];
                    @endphp
                    <tr data-report-row data-report-id="{{ $report->id }}" data-status="{{ $report->status }}" data-search="{{ strtolower(trim(($report->user_name ?: 'unassigned user') . ' ' . ($report->file_name ?: '') . ' ' . ($report->status ?: ''))) }}">
                        <td>
                            <input type="checkbox" name="report_ids[]" value="{{ $report->id }}" class="report-checkbox" {{ $report->status !== 'approved' ? 'disabled' : '' }}>
                        </td>
                        <td>
                            <div class="name-cell">@if ($avatarUrl)<img src="{{ $avatarUrl }}" alt="{{ $report->user_name ?: 'Unassigned User' }}" class="avatar-badge avatar-badge-image">
                            @else
                            <div class="avatar-badge">{{ $initials }}</div>@endif<div class="name-copy"><strong>{{ $report->user_name ?: 'Unassigned User' }}</strong>
                            <span>Prepared by staff member</span>
                        </div>
                    </div>
                </div>
            </td>
            <td>
                <div class="file-cell">
                     <strong>{{ $report->file_name ?: 'No file uploaded' }}</strong>
                        <small>{{ $report->entry_preview ?: 'No details provided yet.' }}</small>
                </div>
                    </td>
                        <td>{{ $submittedAt ? \Illuminate\Support\Carbon::parse($submittedAt)->format('m/d/Y') : 'N/A' }}</td>
                    <td>
                       <span class="status-pill {{ $statusClass }}" data-status-pill>{{ ucfirst(str_replace('_', ' ', $report->status ?? 'unknown')) }}</span>
                    </td>
                        <td>
                            <div class="action-stack">
                                <button type="button" class="action-button" data-open-report-modal data-report='@json($reportPayload)'>
                                    <svg viewBox="0 0 24 24"><path d="M12 5c5.5 0 9.55 4.03 10.75 6.22a1.45 1.45 0 0 1 0 1.56C21.55 14.97 17.5 19 12 19S2.45 14.97 1.25 12.78a1.45 1.45 0 0 1 0-1.56C2.45 9.03 6.5 5 12 5Zm0 2C7.64 7 4.23 10.02 3.28 12 4.23 13.98 7.64 17 12 17s7.77-3.02 8.72-5C19.77 10.02 16.36 7 12 7Zm0 2.5A2.5 2.5 0 1 1 9.5 12 2.5 2.5 0 0 1 12 9.5Z"/></svg>
                                    <span>{{ $canManageReportRecords ? 'View & Review' : 'View' }}</span>
                                </button>
                        @if ($report->file_path && ! $isSuperAdminView)<a href="{{ asset($report->file_path) }}" class="icon-action" download title="Download report"><svg viewBox="0 0 24 24"><path d="M12 3a1 1 0 0 1 1 1v8.59l2.3-2.29 1.4 1.41L12 16.41l-4.7-4.7 1.4-1.41L11 12.59V4a1 1 0 0 1 1-1Zm-7 14h14v3H5Z"/></svg>
                    </a>
                    @endif
                </div>
            </td>
                    </tr>
                @empty
                    <tr data-empty-state-row><td colspan="6" class="empty-state">No reports found yet. Once staff submissions are stored, they will appear here.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </form>
