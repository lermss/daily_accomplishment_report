@extends('staff.layouts.app')

@section('content')
@php
    // ADD THIS CODE
    $staffRouteBase = app(\App\Services\AuthFlowService::class)->staffPortalPrefix(optional(\App\Models\User::find(session('authenticated_user_id')))->role);
@endphp

<style>
body { background: #f0f4f9; font-family: 'Poppins', sans-serif; }

/* ── BACK LINK ── */
.back-link {
    display: inline-flex; align-items: center; gap: 6px;
    text-decoration: none; color: #475569; font-size: .875rem; font-weight: 500;
    margin-bottom: 18px; transition: color .15s;
}
.back-link:hover { color: #1e40af; }
.back-link svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 2; }

/* ── A4 CARD ── */
.card-a4 {
    max-width: 1000px; margin: 0 auto 40px;
    background: #fff; border-radius: 20px;
    box-shadow: 0 6px 32px rgba(0,0,0,.10);
    overflow: hidden;
}

/* ── CARD HEADER ── */
.card-a4 .header {
    text-align: center;
    margin-bottom: 20px;
    padding: 20px 32px 10px;
}
.card-a4 .header img {
    max-height: 150px;
    margin-bottom: 10px;
    margin-top: 10px;
}
.card-a4 .header h4 {
    font-size: 1.05rem;
    font-weight: 700;
    color: #1e293b;
    letter-spacing: .5px;
    text-transform: uppercase;
    margin-bottom: 8px;
}

/* ── CARD BODY ── */
.card-a4-body { padding: 28px 32px 32px; }

/* ── FILE NAME FIELD ── */
.file-name-group { margin-bottom: 24px; }
.file-name-group label {
    display: block; font-size: .78rem; font-weight: 700;
    color: #64748b; letter-spacing: .5px; text-transform: uppercase; margin-bottom: 6px;
}
.file-name-group input {
    width: 100%; padding: 10px 14px; border-radius: 10px;
    border: 1.5px solid #e2e8f0; background: #f8fafc;
    font: inherit; font-size: .875rem; color: #64748b;
    outline: none; cursor: default;
}

/* ── TABLE ── */
.report-table {
    width: 100%; border-collapse: separate; border-spacing: 0;
    border-radius: 14px; overflow: hidden;
    border: 1.5px solid #e2e8f0;
    font-size: .875rem;
}
.report-table thead { background: linear-gradient(135deg, #f8fafc, #f1f5f9); }
.report-table th {
    padding: 13px 14px; font-size: .72rem; font-weight: 700;
    color: #64748b; letter-spacing: .6px; text-transform: uppercase;
    border-bottom: 2px solid #e2e8f0; text-align: center;
}
.report-table td {
    padding: 12px 12px; vertical-align: top;
    border-bottom: 1px solid #f1f5f9; background: #fff;
}
.report-table tbody tr:last-child td { border-bottom: none; }
.report-table tbody tr:hover td { background: #fafbff; }

/* ── DATE RANGE CELL ── */
.date-range-cell { display: flex; flex-direction: column; gap: 8px; }
.date-input-wrap { display: flex; flex-direction: column; gap: 3px; }
.date-input-label {
    font-size: .68rem; font-weight: 700; color: #94a3b8;
    letter-spacing: .5px; text-transform: uppercase;
}
.date-input-wrap input[type="date"] {
    width: 100%; padding: 7px 10px; border-radius: 8px;
    border: 1.5px solid #e2e8f0; background: #fff;
    font: inherit; font-size: .8rem; color: #374151; outline: none;
    transition: border-color .2s, box-shadow .2s;
}
.date-input-wrap input[type="date"]:focus {
    border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.12);
}

/* ── TEXTAREAS ── */
.report-table textarea {
    width: 100%; border: 1.5px solid #e2e8f0; border-radius: 8px;
    padding: 8px 10px; font-size: .84rem; font-family: inherit;
    min-height: 70px; max-height: 220px; resize: none;
    overflow-y: hidden; box-sizing: border-box; line-height: 1.55;
    transition: border-color .2s, box-shadow .2s, background .2s; outline: none;
}
.report-table textarea:focus {
    border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.12);
    background: #fafbff;
}

/* ── REMOVE BUTTON ── */
.removeRow {
    position: absolute; right: 8px; top: 50%; transform: translateY(-50%);
    width: 28px; height: 28px; display: flex; align-items: center;
    justify-content: center; font-size: 16px; font-weight: 700;
    border-radius: 8px; background: linear-gradient(135deg,#ef4444,#dc2626);
    color: #fff; border: none; cursor: pointer;
    transition: transform .2s, box-shadow .2s;
    box-shadow: 0 4px 10px rgba(220,38,38,.3);
}
.removeRow:hover { transform: translateY(-50%) scale(1.1); box-shadow: 0 6px 14px rgba(220,38,38,.4); }

/* Last column needs room for remove button */
.report-table td:last-child { position: relative; padding-right: 44px; min-width: 110px; }

/* ── FORM ACTIONS ── */
.form-actions {
    display: flex; justify-content: space-between; align-items: center;
    margin-top: 22px; gap: 12px; flex-wrap: wrap;
}
.btn-add-row, .btn-save {
    padding: 11px 26px; border: none; border-radius: 50px;
    font: inherit; font-size: .875rem; font-weight: 600;
    cursor: pointer; transition: transform .2s, box-shadow .2s;
    display: inline-flex; align-items: center; gap: 7px;
}
.btn-add-row {
    background: linear-gradient(135deg, #16a34a, #15803d);
    color: #fff; box-shadow: 0 4px 14px rgba(22,163,74,.3);
}
.btn-add-row:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(22,163,74,.4); }
.btn-save {
    background: linear-gradient(135deg, #1e40af, #1d4ed8);
    color: #fff; box-shadow: 0 4px 14px rgba(29,78,216,.3);
}
.btn-save:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(29,78,216,.4); }

/* ── RESPONSIVE ── */
@media (max-width: 768px) {
    .card-a4-body { padding: 20px 16px; }
    .card-a4-header { padding: 20px 16px 16px; }
    .report-table th, .report-table td { font-size: .78rem; padding: 10px 8px; }
    .form-actions { flex-direction: column-reverse; }
    .btn-add-row, .btn-save { width: 100%; justify-content: center; }
}
</style>

<a href="{{ route($staffRouteBase . '.reports') }}" class="back-link">
    <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
    Back to Reports
</a>

<div class="card-a4">

    <!-- Header -->
    <div class="header">
        <img src="{{ asset('images/HEADER.png') }}" alt="DAR Header">
        <h4>ACCOMPLISHMENT REPORT</h4>
    </div>

    <!-- Body -->
    <div class="card-a4-body">

        <form id="reportForm" action="{{ route($staffRouteBase . '.reports.store') }}" method="POST">
            @csrf

            @if ($errors->any())
                <div style="margin-bottom:16px;padding:12px 16px;border-radius:10px;background:#fef2f2;border:1.5px solid #fecaca;color:#b91c1c;font-size:.85rem;">
                    <strong>Please fix the following:</strong>
                    <ul style="margin:6px 0 0 16px;padding:0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- FILE NAME -->
            <input type="hidden" name="file_name" id="file_name" required>
            <div class="file-name-group">
                <label>Generated File Name</label>
                <input type="text" id="file_name_display" readonly placeholder="Fill in dates to auto-generate…">
            </div>

            <!-- TABLE -->
            <table class="report-table" id="Table">
                <thead>
                    <tr>
                        <th style="width:14%">Date Range</th>
                        <th style="width:22%">Activity / Task</th>
                        <th>Details / Description</th>
                        <th style="width:16%">Remarks <span style="font-weight:400;color:#94a3b8;font-size:.68rem"><br>(e.g. Done, Incomplete)</span></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="date-range-cell">
                                <div class="date-input-wrap">
                                    <span class="date-input-label">Start</span>
                                    <input type="date" name="start_date[]" required>
                                </div>
                                <div class="date-input-wrap">
                                    <span class="date-input-label">End</span>
                                    <input type="date" name="end_date[]">
                                </div>
                            </div>
                        </td>
                        <td><textarea name="activity[]"></textarea></td>
                        <td><textarea name="details[]"></textarea></td>
                        <td><textarea name="remarks[]"></textarea></td>
                    </tr>
                </tbody>
            </table>

            <div class="form-actions">
                <button type="button" onclick="addRow()" class="btn-add-row">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Add Row
                </button>
                <button type="submit" class="btn-save">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Save Report
                </button>
            </div>

        </form>
    </div>
</div>


<script>
const AUTO_SAVE_KEY = 'staff_report_draft_{{ session("authenticated_user_id", "guest") }}';
const reportForm = document.getElementById('reportForm');

/* ✅ AUTO RESIZE */
function autoResize(textarea) {
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
}

function initTextareas(scope = document) {
    scope.querySelectorAll('textarea').forEach(textarea => {
        autoResize(textarea);
        textarea.addEventListener('input', function () {
            autoResize(this);
        });
    });
}

/* ROW TEMPLATE */
function emptyRowMarkup(showRemoveButton = false, nextDate = '') {
    return `
        <td>
            <div class="date-range-cell">
                <div class="date-input-wrap">
                    <span class="date-input-label">Start</span>
                    <input type="date" name="start_date[]" value="${nextDate}">
                </div>
                <div class="date-input-wrap">
                    <span class="date-input-label">End</span>
                    <input type="date" name="end_date[]">
                </div>
            </div>
        </td>
        <td><textarea name="activity[]"></textarea></td>
        <td><textarea name="details[]"></textarea></td>
        <td>
            <textarea name="remarks[]"></textarea>
            ${showRemoveButton ? '<button type="button" class="removeRow">−</button>' : ''}
        </td>
    `;
}

/* ADD ROW */
function addRow() {
    let tbody = document.querySelector('#Table tbody');

    let rows = tbody.querySelectorAll('tr');
    let lastRow = rows[rows.length - 1];

    let prevBtn = lastRow.querySelector('.removeRow');
    if (prevBtn) prevBtn.remove();

    let start = lastRow.querySelector('input[name="start_date[]"]').value;
    let end = lastRow.querySelector('input[name="end_date[]"]').value;

    let baseDate = end || start;
    let nextDate = '';

    if (baseDate) {
        let d = new Date(baseDate);
        d.setDate(d.getDate() + 1);
        nextDate = d.toISOString().split('T')[0];
    }

    let newRow = tbody.insertRow();
    newRow.innerHTML = emptyRowMarkup(true, nextDate);

    initTextareas(newRow);       // auto-resize textareas
    initDateListeners(newRow);   // wire up date→filename generation

    generateFileName();
    saveDraft();
}

/* REMOVE ROW */
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('removeRow')) {
        let tbody = document.querySelector('#Table tbody');
        let rows = tbody.querySelectorAll('tr');

        if (rows.length > 1) {
            rows[rows.length - 1].remove();
        }
    }
});

/* FILE NAME */
function generateFileName() {
    let dates = [...document.querySelectorAll('input[type="date"]')]
        .map(i => i.value)
        .filter(v => v)
        .sort();

    if (!dates.length) return;

    let min = new Date(dates[0]);
    let max = new Date(dates[dates.length - 1]);

    let minMonth = min.toLocaleDateString('en-US', { month: 'long' });
    let maxMonth = max.toLocaleDateString('en-US', { month: 'long' });
    let name = minMonth === maxMonth
        ? `${minMonth} ${min.getDate()} - ${max.getDate()}`
        : `${minMonth} ${min.getDate()} - ${maxMonth} ${max.getDate()}`;
    document.getElementById('file_name').value = name;
    document.getElementById('file_name_display').value = name;
}

/* AUTOSAVE */
function collectDraftData() {
    return {
        rows: Array.from(document.querySelectorAll('#Table tbody tr')).map(row => ({
            start_date: row.querySelector('[name="start_date[]"]').value,
            end_date: row.querySelector('[name="end_date[]"]').value,
            activity: row.querySelector('[name="activity[]"]').value,
            details: row.querySelector('[name="details[]"]').value,
            remarks: row.querySelector('[name="remarks[]"]').value
        }))
    };
}

function saveDraft() {
    localStorage.setItem(AUTO_SAVE_KEY, JSON.stringify(collectDraftData()));
}

/* INIT */
document.addEventListener('DOMContentLoaded', function () {
    initTextareas();
    initDateListeners();

    /* Intercept submit: generate filename, then validate before sending */
    reportForm.addEventListener('submit', function (e) {
        generateFileName(); // ensure hidden field is populated

        const fileNameVal = document.getElementById('file_name').value.trim();
        if (!fileNameVal) {
            e.preventDefault();
            showInlineError('Please fill in at least one Start date so a file name can be generated.');
            return;
        }

        const startDates = [...document.querySelectorAll('input[name="start_date[]"]')]
            .map(i => i.value.trim()).filter(Boolean);
        if (!startDates.length) {
            e.preventDefault();
            showInlineError('At least one Start date is required.');
        }
    });
});

function initDateListeners(scope = document) {
    scope.querySelectorAll('input[type="date"]').forEach(input => {
        input.addEventListener('change', function () {
            generateFileName();
            saveDraft();
        });
    });
}

function showInlineError(msg) {
    let box = document.getElementById('js-error-box');
    if (!box) {
        box = document.createElement('div');
        box.id = 'js-error-box';
        box.style.cssText = 'margin-bottom:16px;padding:12px 16px;border-radius:10px;background:#fef2f2;border:1.5px solid #fecaca;color:#b91c1c;font-size:.85rem;';
        reportForm.insertAdjacentElement('beforebegin', box);
    }
    box.textContent = msg;
    box.scrollIntoView({ behavior: 'smooth', block: 'center' });
}
</script>

@endsection
