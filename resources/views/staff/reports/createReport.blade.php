@extends('staff.layouts.app')

@section('content')

<style>
body {
    background: #f3f5f7;
    font-family: 'Poppins', sans-serif;
}

/* A4 Card Style */
.card-a4 {
    padding: 20px;
    margin: 20px auto;
    background: #fff;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    font-size: 12pt;
    page-break-after: always;
    max-width: 1000px;
}

/* Header */
.card-a4 .header {
    text-align: center;
    margin-bottom: 20px;
}

.card-a4 .header img {
    max-height: 100px;
    margin-bottom: 10px;
}

/* Table */
.table {
    width: 100%;
    border-collapse: collapse;
}

.table th, .table td {
    border: 1px solid #000;
    padding: 6px 8px;
    vertical-align: top;
}

.table th {
    background-color: #f8f9fa;
    text-align: center;
    font-weight: 600;
}

/* Date inputs */
.table input[type="date"] {
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 5px;
    font-size: 11pt;
}

/* ✅ TEXTAREA FIX (MAIN IMPROVEMENT) */
.table textarea {
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 6px;
    padding: 8px 10px;
    font-size: 11pt;
    font-family: 'Times New Roman', serif;

    min-height: 70px;
    max-height: 220px;

    resize: none;
    overflow-y: hidden;

    box-sizing: border-box;
    line-height: 1.5;
    transition: all 0.15s ease-in-out;
}

/* Focus UX */
.table textarea:focus {
    border-color: #1f4e79;
    box-shadow: 0 0 0 2px rgba(31, 78, 121, 0.1);
    background: #f9fbff;
}

/* Remove Button */
.removeRow {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-weight: bold;
    border-radius: 4px;
    background: #dc3545;
    color: white;
    border: none;
    cursor: pointer;
}

.removeRow:hover {
    background: #c82333;
}

/* Last column */
.table td:last-child {
    position: relative;
    padding-right: 45px;
    min-width: 100px;
}

/* Responsive */
@media (max-width: 768px) {
    .table th, .table td {
        font-size: 10pt;
    }
}
</style>

<div class="d-flex justify-content-start mb-3">
    <a href="{{ route('staff.reports') }}" class="btn btn-secondary"> < Back</a>
</div>

<div class="card card-a4">

    <div class="header">
        <img src="{{ asset('images/HEADER.png') }}">
        <h4 class="fw-bold">ACCOMPLISHMENT REPORT</h4>
    </div>

    <form id="reportForm" action="{{ route('staff.reports.store') }}" method="POST">
        @csrf

        <!-- FILE NAME -->
        <input type="hidden" name="file_name" id="file_name" required>
        <div class="mb-3">
            <label class="form-label">Generated File Name:</label>
            <input type="text" id="file_name_display" class="form-control" readonly>
        </div>

        <!-- TABLE -->
        <table class="table text-center" id="Table">
            <thead>
                <tr>
                    <th style="width:12%">Date Range</th>
                    <th style="width:22%">Activity / Task</th>
                    <th>Details / Description</th>
                    <th style="width:15%">Remarks</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <input type="date" name="start_date[]" required>
                        <input type="date" name="end_date[]">
                    </td>
                    <td><textarea name="activity[]"></textarea></td>
                    <td><textarea name="details[]"></textarea></td>
                    <td><textarea name="remarks[]"></textarea></td>
                </tr>
            </tbody>
        </table>

        <div class="d-flex justify-content-between mt-3">
            <button type="button" onclick="addRow()" class="btn btn-success">+ Add Row</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>

    </form>
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
            <input type="date" name="start_date[]" value="${nextDate}">
            <input type="date" name="end_date[]">
        </td>
        <td><textarea name="activity[]"></textarea></td>
        <td><textarea name="details[]"></textarea></td>
        <td>
            <textarea name="remarks[]"></textarea>
            ${showRemoveButton ? '<button type="button" class="removeRow">-</button>' : ''}
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

    initTextareas(newRow); // 🔥 IMPORTANT

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

    let name = `${min.toLocaleDateString()} - ${max.toLocaleDateString()}`;
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
    initTextareas(); // 🔥 MAIN FIX
});
</script>

@endsection