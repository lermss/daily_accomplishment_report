@extends('staff.layouts.app')

@section('content')
@php
    // ADD THIS CODE
    $staffRouteBase = app(\App\Services\AuthFlowService::class)->staffPortalPrefix(optional(\App\Models\User::find(session('authenticated_user_id')))->role);
@endphp

<link rel="stylesheet" href="{{ asset('css/admin-reports.css') }}?v={{ filemtime(public_path('css/admin-reports.css')) }}">
<link rel="stylesheet" href="{{ asset('css/shared-dashboard-theme.css') }}?v={{ filemtime(public_path('css/shared-dashboard-theme.css')) }}">

<style>
    .admin-reports-page {
        --staff-reports-content-width: min(1162px, calc(100vw - 72px));
        --staff-reports-search-width: min(660px, 100%);
        --staff-reports-table-min-width: 720px;
    }

    .admin-reports-page .admin-content {
        max-width: 1240px;
        width: var(--staff-reports-content-width);
    }

    .admin-reports-page .page-intro {
        gap: 18px;
        align-items: stretch;
    }

    .admin-reports-page .page-intro > div:first-child {
        flex: 1 1 auto;
        min-width: 0;
    }

    .admin-reports-page .page-intro,
    .admin-reports-page .report-filters-row,
    .admin-reports-page .filters,
    .admin-reports-page .table-wrap {
        width: 100%;
    }

    .admin-reports-page .filters {
        flex: 1 1 100%;
        min-width: 0;
    }

    .admin-reports-page .report-search-form {
        width: var(--staff-reports-search-width);
        max-width: 100%;
    }

    .admin-reports-page .search-input-wrapper,
    .admin-reports-page .search-input {
        width: 100%;
    }

    .admin-reports-page .search-input {
        min-width: 0;
    }

    .admin-reports-page .table-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .admin-reports-page .table-wrap table {
        width: 100%;
        min-width: var(--staff-reports-table-min-width);
    }

    .admin-reports-page .table-actions {
        flex-wrap: nowrap;
        justify-content: flex-end;
    }

    .admin-reports-page .summary-card {
        width: 100%;
        min-height: 140px;
    }

    .admin-reports-page .summary-icon {
        width: 68px;
        height: 68px;
    }

    .admin-reports-page .summary-icon svg {
        width: 24px;
        height: 24px;
    }

    .admin-reports-page .summary-copy {
        min-width: 0;
    }

    .admin-reports-page .summary-value-row {
        flex-wrap: wrap;
        row-gap: 4px;
    }

    .admin-reports-page .summary-value-row strong {
        font-size: clamp(2rem, 4vw, 2.6rem);
        line-height: 1;
    }

    .admin-reports-page .summary-meta {
        display: block;
        line-height: 1.5;
    }

    .admin-reports-page .action-button {
        align-self: center;
        flex: 0 0 auto;
        min-height: 44px;
    }

    @media (max-width: 1068px) {
        .admin-reports-page {
            --staff-reports-content-width: calc(100vw - 40px);
            --staff-reports-search-width: 100%;
            --staff-reports-table-min-width: 680px;
        }

        .admin-reports-page .summary-card {
            min-height: 128px;
        }

        .admin-reports-page .summary-icon {
            width: 62px;
            height: 62px;
        }
    }

    @media (max-width: 768px) {
        .admin-reports-page {
            --staff-reports-content-width: calc(100vw - 24px);
            --staff-reports-table-min-width: 620px;
        }

        .admin-reports-page .page-intro {
            gap: 14px;
            flex-direction: column;
        }

        .admin-reports-page .table-panel {
            padding: 18px;
        }

        .admin-reports-page .summary-card {
            min-height: 0;
        }

        .admin-reports-page .summary-icon {
            width: 58px;
            height: 58px;
        }

        .admin-reports-page .action-button {
            align-self: flex-start;
        }
    }

    @media (max-width: 576px) {
        .admin-reports-page {
            --staff-reports-content-width: calc(100vw - 16px);
            --staff-reports-table-min-width: 560px;
        }

        .admin-reports-page .table-panel {
            padding: 16px;
        }

        .admin-reports-page .action-button {
            width: 100%;
            justify-content: center;
            align-self: stretch;
        }

        .admin-reports-page .summary-card {
            padding: 18px 16px;
        }

        .admin-reports-page .summary-icon {
            width: 54px;
            height: 54px;
        }

        .admin-reports-page .summary-value-row strong {
            font-size: clamp(1.8rem, 8vw, 2.3rem);
        }
    }

    .icon-action-edit i {
        color: green;
    }

    .icon-action-delete i {
        color: red;
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
