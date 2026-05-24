@extends('staff.layouts.app')

@section('content')
@php
    // ADD THIS CODE
    $staffRouteBase = app(\App\Services\AuthFlowService::class)->staffPortalPrefix(optional(\App\Models\User::find(session('authenticated_user_id')))->role);
@endphp

<style>
body {
    background: linear-gradient(145deg, #f0f4f9 0%, #e8eef6 100%);
    font-family: 'Poppins', sans-serif;
    min-height: 100vh;
    margin: 0;
    padding: 0;
}

/* A4 card layout */
.card-a4 {
    max-width: 1200px;
    width: 100%;
    padding: 28px 28px 24px;
    margin: 24px auto 32px;
    background: #fff;
    border: 1px solid #d8dde3;
    box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
    border-radius: 16px;
    font-size: 12pt;
    line-height: 1.65;
}

/* Header */
.card-a4 .header {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    margin-bottom: 26px;
}

.card-a4 .header img {
    max-height: 70px;
    margin-bottom: 8px;
}

.card-a4 .header h4,
.card-a4 .header h5 {
    margin: 0;
    font-weight: 700;
}

/* Table */
.table {
    width: 100%;
    border-collapse: collapse;
}

.table th {
    background-color: #f8f9fa;
    text-align: center;
    font-weight: 600;
    border: 1px solid #404040;
    padding: 10px;
}

.table td {
    border: 1px solid #404040;
    padding: 10px;
    vertical-align: top;
}

/* TEXTAREA */
.table textarea {
    width: 100%;
    min-height: 60px;
    max-height: 250px;
    border: none;
    background: transparent;
    font-size: 11pt;
    font-family: 'Times New Roman', serif;
    resize: none;
    overflow: hidden;
    line-height: 1.5;
    padding: 6px;
}

.table textarea:focus {
    background: #f9fbff;
    outline: 1px solid #1f4e79;
    border-radius: 4px;
}

/* DATE INPUT */
.table input[type="date"] {
    border: none;
    background: transparent;
    font-size: 11pt;
}

.readonly-field {
    background: transparent;
    border: none;
    color: #212529;
    cursor: default;
}

/* ── BACK LINK ── */
.back-link {
    display: inline-flex; align-items: center; gap: 6px;
    text-decoration: none; color: #475569; font-size: .875rem; font-weight: 500;
    margin-bottom: 16px; transition: color .15s;
}
.back-link:hover { color: #1e40af; }
.back-link svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 2; }

/* ── PREMIUM BUTTONS ── */
.btn-pill {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 22px; border: none; border-radius: 50px;
    font-family: inherit; font-size: .875rem; font-weight: 600;
    cursor: pointer; transition: transform .18s, box-shadow .18s;
    text-decoration: none;
}
.btn-pill:hover { transform: translateY(-2px); }
.btn-pill:disabled, .btn-pill[disabled] {
    opacity: .55; cursor: not-allowed; transform: none !important;
}
.btn-pill-green  { background: linear-gradient(135deg,#16a34a,#15803d); color:#fff; box-shadow:0 4px 14px rgba(22,163,74,.3); }
.btn-pill-green:hover  { box-shadow:0 8px 22px rgba(22,163,74,.45); }
.btn-pill-blue   { background: linear-gradient(135deg,#1e40af,#1d4ed8); color:#fff; box-shadow:0 4px 14px rgba(29,78,216,.3); }
.btn-pill-blue:hover   { box-shadow:0 8px 22px rgba(29,78,216,.45); }
.btn-pill-slate  { background: linear-gradient(135deg,#475569,#334155); color:#fff; box-shadow:0 4px 14px rgba(51,65,85,.25); }
.btn-pill-red    { background: linear-gradient(135deg,#dc2626,#b91c1c); color:#fff; box-shadow:0 4px 14px rgba(220,38,38,.3); }
.btn-pill-red:hover    { box-shadow:0 8px 22px rgba(220,38,38,.45); }
.btn-pill-amber  { background: linear-gradient(135deg,#d97706,#b45309); color:#fff; box-shadow:0 4px 14px rgba(217,119,6,.3); }
.btn-pill-amber:hover  { box-shadow:0 8px 22px rgba(217,119,6,.45); }

.action-bar {
    display: flex; justify-content: space-between; align-items: center;
    margin-top: 20px; flex-wrap: wrap; gap: 10px;
}
.action-bar-right { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }

/* Responsive */
@media (max-width: 768px) {
    .table th, .table td { font-size: 10pt; padding: 6px; }
    .action-bar { flex-direction: column; align-items: stretch; }
    .action-bar-right { flex-direction: column; }
    .btn-pill { justify-content: center; }
}
</style>

<a href="{{ route($staffRouteBase . '.reports.index') }}" class="back-link">
    <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
    Back to Reports
</a>

<div class="card card-a4">

    @if($report->status === 'approved')
        <div class="alert alert-success mb-3">
            ✅ This report is already approved and can no longer be edited.
        </div>
    @endif

    @if($report->status === \App\Models\Report::STATUS_FOR_REVISION && $report->review_comment)
        <div class="alert alert-warning mb-3">
            <strong>Revision Comment:</strong> {{ $report->review_comment }}
        </div>
    @endif

    <div class="header">
        <img src="{{ asset('images/HEADER.png') }}">
        <h4>DAILY ACCOMPLISHMENT REPORT</h4>
        <h5 class="mt-2">{{ $report->file_name }}</h5>
    </div>

    <form id="updateForm" action="{{ route($staffRouteBase . '.reports.update',$report->id) }}" method="POST">
        @csrf
        @method('PUT')

        <table class="table">
            <thead>
                <tr>
                    <th style="width:15%">Date</th>
                    <th style="width:20%">Activity</th>
                    <th>Details</th>
                    <th style="width:15%">Remarks</th>
                </tr>
            </thead>
            <tbody id="reportTableBody">
                @foreach($report->entries as $entry)
                <tr>
                    <td>
                        <input type="hidden" name="entry_id[]" value="{{ $entry->id }}">
                        <input type="date" name="start_date[]" value="{{ $entry->start_date }}">
                        <input type="date" name="end_date[]" value="{{ $entry->end_date }}">
                    </td>

                    <td>
                        <textarea name="activity[]">{{ $entry->activity }}</textarea>
                    </td>

                    <td>
                        <textarea name="details[]">{{ $entry->details }}</textarea>
                    </td>

                    <td>
                        <textarea name="remarks[]">{{ $entry->remarks }}</textarea>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($report->status !== 'approved')
        <button type="button" class="btn-pill btn-pill-green mt-3" id="addRowBtn">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Row
        </button>
        @endif
    </form>

    <!-- Success Message Alert -->
    <div id="successAlert" class="alert alert-success alert-dismissible fade" role="alert" style="margin-top: 20px; display: none;">
        <strong>Success!</strong> Comment saved successfully.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <div class="action-bar">
        <div><!-- spacer --></div>
        <div class="action-bar-right">

            @if($report->status !== 'approved')
                <button type="submit" form="updateForm" class="btn-pill btn-pill-blue" id="saveBtn">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Save
                </button>
            @else
                <button class="btn-pill btn-pill-slate" disabled>
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    Locked
                </button>
            @endif

            @if($report->status !== 'approved')
            <form id="submitReportForm" action="{{ route($staffRouteBase . '.reports.submit',$report->id) }}" method="POST" style="display:contents;">
                @csrf
                <button type="button" class="btn-pill btn-pill-amber" id="submitBtn">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    Submit
                </button>
            </form>
            @endif

            <button
                class="btn-pill btn-pill-red {{ in_array($report->status, ['pending', 'for_revision']) ? 'opacity-50' : '' }}"
                {{ in_array($report->status, ['pending', 'for_revision']) ? 'disabled title="PDF export unavailable while pending or for revision"' : '' }}
                onclick="if (!{{ in_array($report->status, ['pending', 'for_revision']) ? 'true' : 'false' }}) window.location.href='{{ route($staffRouteBase . '.reports.pdf',$report->id) }}'"
            >
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                Export PDF
            </button>

        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    function autoResizeTextarea(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    }

    document.querySelectorAll('textarea').forEach(textarea => {
        autoResizeTextarea(textarea);
        textarea.addEventListener('input', function () {
            autoResizeTextarea(this);
        });
    });

    const addRowBtn = document.getElementById('addRowBtn');
    const tableBody = document.getElementById('reportTableBody');

    if (addRowBtn) {
        addRowBtn.addEventListener('click', function () {
            const newRow = document.createElement('tr');

            newRow.innerHTML = `
                <td>
                    <input type="hidden" name="entry_id[]" value="">
                    <input type="date" name="start_date[]">
                    <input type="date" name="end_date[]">
                </td>
                <td><textarea name="activity[]"></textarea></td>
                <td><textarea name="details[]"></textarea></td>
                <td><textarea name="remarks[]"></textarea></td>
            `;

            tableBody.appendChild(newRow);

            newRow.querySelectorAll('textarea').forEach(textarea => {
                autoResizeTextarea(textarea);
                textarea.addEventListener('input', function () {
                    autoResizeTextarea(this);
                });
            });
        });
    }

    // Success message for Save button
    const updateForm = document.getElementById('updateForm');
    const saveBtn = document.getElementById('saveBtn');
    const successAlert = document.getElementById('successAlert');

    if (updateForm && saveBtn) {
        updateForm.addEventListener('submit', function(e) {
            // Show success message
            successAlert.style.display = 'block';
            successAlert.classList.add('show');

            // Auto-hide after 4 seconds
            setTimeout(function() {
                successAlert.classList.remove('show');
                setTimeout(function() {
                    successAlert.style.display = 'none';
                }, 150);
            }, 4000);
        });
    }

    const submitBtn = document.getElementById('submitBtn');
    const submitReportForm = document.getElementById('submitReportForm');

    if (submitBtn && submitReportForm) {
        submitBtn.addEventListener('click', function() {
            if (typeof window.openStaffConfirmModal !== 'function') {
                submitReportForm.submit();
                return;
            }

            window.openStaffConfirmModal({
                title: 'Confirm Submission',
                message: 'Once submitted, this report will be reviewed by the Provincial Head. Please confirm that all information is complete and accurate before proceeding.',
                confirmText: 'Submit',
                cancelText: 'Cancel',
                variant: 'success',
                onConfirm: function () {
                    submitReportForm.submit();
                }
            });
        });
    }
});
</script>

@endsection
