@extends('staff.layouts.app')

@section('content')
@php
    $staffRouteBase = app(\App\Services\AuthFlowService::class)->staffPortalPrefix(optional(\App\Models\User::find(session('authenticated_user_id')))->role);
@endphp

<link rel="stylesheet" href="{{ asset('css/admin-reports.css') }}?v={{ filemtime(public_path('css/admin-reports.css')) }}">
<link rel="stylesheet" href="{{ asset('css/shared-dashboard-theme.css') }}?v={{ filemtime(public_path('css/shared-dashboard-theme.css')) }}">

<style>
    .admin-reports-page {
        --staff-reports-content-width: min(1162px, calc(100vw - 72px));
        --staff-reports-search-width: min(660px, 100%);
        --staff-reports-table-min-width: 720px;
        background: #ffffff;
        min-height: 100vh;
    }

    .admin-reports-page .admin-content {
        max-width: 1240px;
        width: var(--staff-reports-content-width);
        padding: 32px 0;
    }

    /* ── SUMMARY CARD ── */
    .admin-reports-page .summary-card {
        width: 100%; min-height: 140px;
        background: linear-gradient(135deg, #1e3a5f 0%, #0c5ea0 60%, #1976d2 100%);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(10, 63, 114, 0.28);
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .admin-reports-page .summary-card::after {
        content: '';
        position: absolute;
        width: 200px; height: 200px;
        border-radius: 50%;
        background: rgba(255,255,255,.06);
        top: -60px; right: -60px;
        pointer-events: none;
    }
    .admin-reports-page .summary-label {
        font-size: 11px; font-weight: 600;
        letter-spacing: .7px; text-transform: uppercase;
        color: rgba(255,255,255,.7);
    }
    .admin-reports-page .summary-value-row strong {
        font-size: clamp(2rem, 4vw, 2.6rem); line-height: 1; color: #fff;
    }
    .admin-reports-page .summary-value-row span { color: rgba(255,255,255,.7); font-size: .9rem; }
    .admin-reports-page .summary-meta { display: block; line-height: 1.5; color: rgba(255,255,255,.55); font-size: .8rem; }
    .admin-reports-page .summary-icon {
        width: 68px; height: 68px; border-radius: 18px;
        background: rgba(255,255,255,.15);
        backdrop-filter: blur(6px);
        display: flex; align-items: center; justify-content: center;
    }
    .admin-reports-page .summary-icon svg { width: 24px; height: 24px; stroke: #fff; fill: none; stroke-width: 2; }

    /* ── ADD REPORT BUTTON ── */
    .admin-reports-page .action-button {
        align-self: center; flex: 0 0 auto; min-height: 44px;
        background: rgba(255,255,255,.18);
        backdrop-filter: blur(8px);
        border: 1.5px solid rgba(255,255,255,.35);
        border-radius: 50px;
        color: #fff; font-size: .875rem; font-weight: 600;
        padding: 0 22px;
        display: inline-flex; align-items: center; gap: 8px;
        text-decoration: none;
        transition: background .2s, transform .2s, box-shadow .2s;
        box-shadow: 0 4px 14px rgba(0,0,0,.12);
    }
    .admin-reports-page .action-button:hover {
        background: rgba(255,255,255,.28);
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(0,0,0,.18);
    }
    .admin-reports-page .action-button svg { width: 18px; height: 18px; fill: #fff; }

    /* ── TABLE PANEL ── */
    .admin-reports-page .table-panel {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(0,0,0,.07);
        border: 1px solid #f1f5f9;
        overflow: hidden;
        margin-top: 20px;
    }
    .admin-reports-page .table-toolbar { padding: 18px 20px 14px; border-bottom: 1px solid #f1f5f9; }

    /* ── SEARCH ── */
    .admin-reports-page .report-filters-row,
    .admin-reports-page .filters,
    .admin-reports-page .table-wrap { width: 100%; }
    .admin-reports-page .filters { flex: 1 1 100%; min-width: 0; }
    .admin-reports-page .report-search-form { width: var(--staff-reports-search-width); max-width: 100%; }
    .admin-reports-page .search-input-wrapper,
    .admin-reports-page .search-input { width: 100%; }
    .admin-reports-page .search-input { min-width: 0; }

    /* ── TABLE ── */
    .admin-reports-page .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .admin-reports-page .table-wrap table { width: 100%; min-width: var(--staff-reports-table-min-width); border-collapse: collapse; }
    .admin-reports-page .table-wrap thead {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-bottom: 2px solid #e9eef5;
    }
    .admin-reports-page .table-wrap th {
        padding: 13px 18px; font-size: 11px; font-weight: 700;
        color: #64748b; letter-spacing: .7px; text-transform: uppercase; text-align: left;
    }
    .admin-reports-page .table-wrap td {
        padding: 14px 18px; font-size: 13.5px; color: #374151;
        border-bottom: 1px solid #f1f5f9; text-align: left;
    }
    .admin-reports-page .table-wrap tbody tr { transition: background .15s; }
    .admin-reports-page .table-wrap tbody tr:hover { background: #fafbff; }
    .admin-reports-page .table-wrap tbody tr:last-child td { border-bottom: none; }

    /* ── FILE NAME LINK ── */
    .admin-reports-page .report-title {
        color: #4f46e5; text-decoration: none; font-weight: 500;
        transition: color .15s;
    }
    .admin-reports-page .report-title:hover { color: #3730a3; text-decoration: underline; }

    /* ── STATUS PILLS ── */
    .admin-reports-page .status-pill {
        display: inline-block; padding: 4px 12px; border-radius: 50px;
        font-size: 11.5px; font-weight: 600; letter-spacing: .3px;
    }
    .admin-reports-page .status-approved  { background:#dcfce7; color:#14532d; box-shadow: 0 0 0 1px #86efac; }
    .admin-reports-page .status-pending   { background:#fef9c3; color:#854d0e; box-shadow: 0 0 0 1px #fde68a; }
    .admin-reports-page .status-revision  { background:#fee2e2; color:#7f1d1d; box-shadow: 0 0 0 1px #fca5a5; }
    .admin-reports-page .status-default   { background:#f3f4f6; color:#374151; box-shadow: 0 0 0 1px #e5e7eb; }

    /* ── REVIEW NOTE ── */
    .admin-reports-page .review-note {
        margin-top: 5px; font-size: 11.5px; line-height: 1.45; color: #92400e;
        background: #fffbeb; border-left: 3px solid #f59e0b;
        padding: 4px 8px; border-radius: 0 4px 4px 0; white-space: pre-wrap;
    }

    /* ── ACTION BUTTONS ── */
    .admin-reports-page .table-actions { flex-wrap: nowrap; justify-content: flex-end; display: flex; gap: 6px; }
    .admin-reports-page .icon-action {
        width: 34px; height: 34px; border-radius: 10px; border: none;
        display: inline-flex; align-items: center; justify-content: center;
        cursor: pointer; transition: background .2s, transform .2s;
        font-size: 15px; background: #f3f4f6;
    }
    .admin-reports-page .icon-action:hover { transform: scale(1.1); }
    .admin-reports-page .icon-action-edit  { color: #16a34a; }
    .admin-reports-page .icon-action-edit:hover  { background: #dcfce7; }
    .admin-reports-page .icon-action-delete { color: #dc2626; }
    .admin-reports-page .icon-action-delete:hover { background: #fee2e2; }

    /* ── EMPTY STATE ── */
    .admin-reports-page .text-center { text-align: center; padding: 48px 0 !important; color: #6b7280; }
    .admin-reports-page .text-center h5 { font-size: 1.1rem; font-weight: 600; color: #374151; margin-bottom: 6px; }
    .admin-reports-page .text-muted { font-size: .9rem; color: #9ca3af; }

    /* ── SUMMARY VALUE ROW ── */
    .admin-reports-page .summary-value-row { flex-wrap: wrap; row-gap: 4px; display: flex; align-items: baseline; gap: 8px; }
    .admin-reports-page .summary-copy { min-width: 0; display: flex; flex-direction: column; gap: 4px; }
    .admin-reports-page .page-intro { gap: 18px; align-items: stretch; display: flex; flex-direction: column; }
    .admin-reports-page .page-intro > div:first-child { flex: 1 1 auto; min-width: 0; }
    .admin-reports-page .page-intro .summary-card { display: flex; align-items: center; padding: 24px 28px; gap: 20px; }

    /* ── RESPONSIVE ── */
    @media (max-width: 1068px) {
        .admin-reports-page { --staff-reports-content-width: calc(100vw - 40px); --staff-reports-search-width: 100%; --staff-reports-table-min-width: 680px; }
        .admin-reports-page .summary-card { min-height: 128px; }
        .admin-reports-page .summary-icon { width: 62px; height: 62px; }
    }
    @media (max-width: 768px) {
        .admin-reports-page { --staff-reports-content-width: calc(100vw - 24px); --staff-reports-table-min-width: 620px; }
        .admin-reports-page .table-panel { border-radius: 14px; }
        .admin-reports-page .action-button { align-self: flex-start; }
    }
    @media (max-width: 576px) {
        .admin-reports-page { --staff-reports-content-width: calc(100vw - 16px); --staff-reports-table-min-width: 560px; }
        .admin-reports-page .action-button { width: 100%; justify-content: center; align-self: stretch; }
        .admin-reports-page .summary-card { padding: 18px 16px; }
        .admin-reports-page .summary-icon { width: 54px; height: 54px; }
        .admin-reports-page .summary-value-row strong { font-size: clamp(1.8rem, 8vw, 2.3rem); }
    }

    /* ── Disable card hover lift ── */
    .admin-reports-page .summary-card:hover,
    .admin-reports-page article.summary-card:hover {
        transform: none !important;
        box-shadow: 0 8px 32px rgba(10, 63, 114, 0.28) !important;
    }
</style>


<div class="admin-reports-page">
    <div class="admin-content">
        <div class="page-intro">
            <div>
                <article class="summary-card summary-card-blue">
                    <div class="summary-copy">
                        <span class="summary-label">Total Reports</span>
                        <div class="summary-value-row">
                            <strong>{{ $reports->count() }}</strong>
                            <span>Reports</span>
                        </div>
                        <span class="summary-meta">
                            Last update: {{ $reports->first()?->updated_at?->format('m/d/Y h:i A') ?? 'No updates yet' }}
                        </span>
                    </div>
                    <a href="{{ route($staffRouteBase . '.reports.create') }}" class="action-button" aria-label="Add new report">
                        <svg viewBox="0 0 24 24"><path d="M11 5h2v14h-2zM5 11h14v2H5z"/></svg>
                        <span>Add Report</span>
                    </a>
                </article>
            </div>
        </div>

        <section class="table-panel">
            <div class="table-toolbar">
                <div class="report-filters-row">
                    <div class="filters">
                        <form method="GET" action="{{ route($staffRouteBase . '.reports') }}" class="search-form report-search-form" id="reportSearchForm">
                            <div class="search-input-wrapper">
                                <input
                                    type="search"
                                    name="search"
                                    id="reportSearch"
                                    value="{{ request('search') }}"
                                    placeholder="Search by file name, date submitted, or status"
                                    aria-label="Search reports"
                                    class="search-input"
                                >
                                @if (request()->filled('search'))
                                    <button type="button" class="search-clear" aria-label="Clear search" onclick="document.getElementById('reportSearch').value = ''; document.getElementById('reportSearchForm').submit();">
                                        <svg viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                                    </button>
                                @endif
                                <button type="submit" aria-label="Search reports" class="search-submit">
                                    <svg viewBox="0 0 24 24"><path d="M10 4a6 6 0 1 0 3.87 10.59l4.27 4.27a1 1 0 0 0 1.42-1.42l-4.27-4.27A6 6 0 0 0 10 4Zm0 2a4 4 0 1 1-4 4 4 4 0 0 1 4-4Z"/></svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="table-wrap">
                @forelse($reports as $report)
                    @if ($loop->first)
                    <table>
                        <thead>
                            <tr>
                                <th>File Name</th>
                                <th>Status</th>
                                <th>Last Edited</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                    @endif

                    @php
                        $statusClass = str_replace(' ', '_', strtolower($report->status ?? 'draft'));
                        $statusTone = match ($statusClass) {
                            'approved' => 'approved',
                            'pending' => 'pending',
                            'for_revision' => 'revision',
                            default => 'default',
                        };
                    @endphp

                    <tr>
                        <td>
                            <a href="{{ route($staffRouteBase . '.reports.show',$report->id) }}" class="report-title">
                                {{ $report->file_name }}
                            </a>
                        </td>

                        <td>
                            <span class="status-pill status-{{ $statusTone }}">
                                {{ ucfirst(str_replace('_',' ', $statusClass)) }}
                            </span>

                            @if($statusClass === 'for_revision' && $report->review_comment)
                                <div class="review-note">Comment: {{ $report->review_comment }}</div>
                            @endif
                        </td>

                        <td>
                            {{ $report->updated_at->format('m/d/Y') }}
                        </td>

                        <td class="text-end">
                            <div class="table-actions">
                                <button
                                    class="icon-action icon-action-edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#reportActionModal"
                                    data-action="edit"
                                    data-report-id="{{ $report->id }}"
                                    data-file-name="{{ $report->file_name }}"
                                    title="Edit report file name"
                                    aria-label="Edit report: {{ $report->file_name }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <button
                                    type="button"
                                    class="icon-action icon-action-delete"
                                    data-delete-report
                                    data-report-id="{{ $report->id }}"
                                    data-file-name="{{ $report->file_name }}"
                                    title="Delete report"
                                    aria-label="Delete report: {{ $report->file_name }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    @if ($loop->last)
                        </tbody>
                    </table>
                    @endif
                @empty
                    <table>
                        <thead>
                            <tr>
                                <th>File Name</th>
                                <th>Status</th>
                                <th>Last Edited</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <h5>No Reports Yet</h5>
                                <p class="text-muted">Create your first report to get started</p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                @endforelse
            </div>
        </section>
    </div>
</div>

<div class="modal fade" id="reportActionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div id="editModalContent" style="display: none;">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title">Edit File Name</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <input
                            type="text"
                            id="fileNameInput"
                            name="file_name"
                            class="form-control"
                            required
                            aria-label="File name">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submitEditBtn">
                            <span class="submit-text">Save Changes</span>
                            <span class="submit-spinner" style="display: none;">
                                <span class="spinner-border spinner-border-sm me-2"></span>Saving...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('reportActionModal');
    const deleteButtons = document.querySelectorAll('[data-delete-report]');
    // ADD THIS CODE
    const updateFileUrlTemplate = "{{ route($staffRouteBase . '.reports.updateFile', ['id' => '__REPORT_ID__']) }}";
    const deleteUrlTemplate = "{{ route($staffRouteBase . '.reports.destroy', ['id' => '__REPORT_ID__']) }}";

    modal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const action = button.getAttribute('data-action');
        const reportId = button.getAttribute('data-report-id');
        const fileName = button.getAttribute('data-file-name');
        const editContent = document.getElementById('editModalContent');

        if (action === 'edit') {
            editContent.style.display = 'block';
            document.getElementById('fileNameInput').value = fileName;
            document.getElementById('editForm').action = updateFileUrlTemplate.replace('__REPORT_ID__', reportId);
            document.getElementById('fileNameInput').focus();
        }
    });

    document.getElementById('editForm').addEventListener('submit', function() {
        const submitBtn = document.getElementById('submitEditBtn');
        const text = submitBtn.querySelector('.submit-text');
        const spinner = submitBtn.querySelector('.submit-spinner');
        submitBtn.disabled = true;
        text.style.display = 'none';
        spinner.style.display = 'inline-flex';
    });

    deleteButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            const reportId = button.getAttribute('data-report-id');
            const fileName = button.getAttribute('data-file-name');

            if (!reportId) {
                return;
            }

            const deleteAction = deleteUrlTemplate.replace('__REPORT_ID__', reportId);

            const submitDelete = function () {
                const deleteForm = document.createElement('form');
                deleteForm.method = 'POST';
                deleteForm.action = deleteAction;
                deleteForm.innerHTML = '@csrf @method("DELETE")';
                document.body.appendChild(deleteForm);
                deleteForm.submit();
            };

            if (typeof window.openStaffConfirmModal !== 'function') {
                submitDelete();
                return;
            }

            window.openStaffConfirmModal({
                title: 'Delete Report',
                message: `Are you sure you want to delete "${fileName}"?`,
                confirmText: 'Delete',
                cancelText: 'Cancel',
                variant: 'danger',
                onConfirm: submitDelete
            });
        });
    });

    modal.addEventListener('hidden.bs.modal', function() {
        document.getElementById('editModalContent').style.display = 'none';
    });
});
</script>

@if(session('clear_report_draft'))
<script>
    try {
        localStorage.removeItem('staff_report_draft_{{ session("authenticated_user_id", "guest") }}');
    } catch (error) {
        // Ignore storage cleanup errors to avoid interrupting the page flow.
    }
</script>
@endif

@endsection
