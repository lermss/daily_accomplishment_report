@extends('super_admin.layouts.app')

@section('title', $title)
@section('body_class', 'admin-reports-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}?v={{ filemtime(public_path('css/admin-dashboard.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/admin-reports.css') }}?v={{ filemtime(public_path('css/admin-reports.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/shared-dashboard-theme.css') }}?v={{ filemtime(public_path('css/shared-dashboard-theme.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/shared-navbar.css') }}?v={{ filemtime(public_path('css/shared-navbar.css')) }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@endpush

@section('content')
    <div class="admin-page">
        <main class="admin-shell">
            <x-topbar active="reports" :can-access-audit="$canAccessAudit" :user="$user" />

            <section class="admin-content" data-admin-dashboard data-dashboard-mode="{{ $mode }}" data-can-manage="{{ $canManageReportRecords ? 'true' : 'false' }}" data-csrf-token="{{ csrf_token() }}">
                @if (session('status'))
                    <div class="status-message">{{ session('status') }}</div>
                @endif

                @include('admin.partials.reports-summary')

                <section class="table-panel">
                    @if ($isSuperAdminView)
                        <div class="super-admin-view-banner">You are currently viewing the dashboard as a super admin. Report files are not accessible in this view.</div>
                    @endif

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
                        <div class="results-summary" data-results-summary><strong>{{ $reports->count() }}</strong> visible {{ $reports->count() === 1 ? 'report' : 'reports' }}</div>
                    </div>

                    <div class="table-wrap">
                        <table>
                            <thead><tr><th>Name</th><th>Filename</th><th>Date Submitted</th><th>Status</th><th>Action</th></tr></thead>
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
                                            'download_url' => null,
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
                                        <td><div class="name-cell">@if ($avatarUrl)<img src="{{ $avatarUrl }}" alt="{{ $report->user_name ?: 'Unassigned User' }}" class="avatar-badge avatar-badge-image">@else<div class="avatar-badge">{{ $initials }}</div>@endif<div class="name-copy"><strong>{{ $report->user_name ?: 'Unassigned User' }}</strong><span>Prepared by staff member</span></div></div></td>
                                        <td><div class="file-cell"><strong>{{ $report->file_name ?: 'No file uploaded' }}</strong></div></td>
                                        <td>{{ $submittedAt ? \Illuminate\Support\Carbon::parse($submittedAt)->format('m/d/Y') : 'N/A' }}</td>
                                        <td><span class="status-pill {{ $statusClass }}" data-status-pill>{{ ucfirst(str_replace('_', ' ', $report->status ?? 'unknown')) }}</span></td>
                                        <td><div class="action-stack"><button type="button" class="action-button" data-open-report-modal data-report='@json($reportPayload)' disabled><svg viewBox="0 0 24 24"><path d="M12 5c5.5 0 9.55 4.03 10.75 6.22a1.45 1.45 0 0 1 0 1.56C21.55 14.97 17.5 19 12 19S2.45 14.97 1.25 12.78a1.45 1.45 0 0 1 0-1.56C2.45 9.03 6.5 5 12 5Zm0 2C7.64 7 4.23 10.02 3.28 12 4.23 13.98 7.64 17 12 17s7.77-3.02 8.72-5C19.77 10.02 16.36 7 12 7Zm0 2.5A2.5 2.5 0 1 1 9.5 12 2.5 2.5 0 0 1 12 9.5Z"/></svg>
                                        <span>{{ $canManageReportRecords ? 'View & Review' : 'View' }}</span></button></div></td>
                                    </tr>
                                @empty
                                    <tr data-empty-state-row><td colspan="5" class="empty-state">No reports found yet. Once staff submissions are stored, they will appear here.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </section>
        </main>
    </div>

    @include('admin.partials.reports-modal')
@endsection

@push('scripts')
    <script src="{{ asset('js/search-filter.js') }}" defer></script>
    <script src="{{ asset('js/admin-reports.js') }}?v={{ filemtime(public_path('js/admin-reports.js')) }}" defer></script>
@endpush
