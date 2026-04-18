@extends('staff.layouts.app')

@section('content')

<style>
body {
    background: #f3f5f7;
    font-family: 'Poppins', sans-serif;
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

/* TEXTAREA FIX (MAIN IMPROVEMENT) */
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

/* readonly */
.readonly-field {
    background: transparent;
    border: none;
    color: #212529;
    cursor: default;
}

/* Buttons */
.btn {
    border-radius: 6px;
    padding: 6px 14px;
    font-weight: 500;
}

/* Responsive */
@media (max-width: 768px) {
    .table th,
    .table td {
        font-size: 10pt;
        padding: 6px;
    }
}
</style>

<a href="{{ route('staff.reports.index') }}" class="btn btn-secondary mb-3">
    &lt; Back
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

    <form id="updateForm" action="{{ route('staff.reports.update',$report->id) }}" method="POST">
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
        <button type="button" class="btn btn-info mt-2" id="addRowBtn">
            + Add Row
        </button>
        @endif
    </form>

    <!-- Success Message Alert -->
    <div id="successAlert" class="alert alert-success alert-dismissible fade" role="alert" style="margin-top: 20px; display: none;">
        <strong>Success!</strong> Comment saved successfully.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <div class="mt-3 d-flex justify-content-end gap-2">

        @if($report->status !== 'approved')
            <button type="submit" form="updateForm" class="btn btn-primary" id="saveBtn">
                Save
            </button>
        @else
            <button class="btn btn-secondary" disabled>
                Locked
            </button>
        @endif

        @if($report->status !== 'approved')
        <form id="submitReportForm" action="{{ route('staff.reports.submit',$report->id) }}" method="POST" style="display: contents;">
            @csrf
            <button type="button" class="btn btn-success" id="submitBtn" data-bs-toggle="modal" data-bs-target="#submitConfirmModal">Submit</button>
        </form>
        @endif

        <button 
            class="btn btn-danger {{ in_array($report->status, ['pending', 'for_revision']) ? 'opacity-50 cursor-not-allowed' : '' }}"
            {{ in_array($report->status, ['pending', 'for_revision']) ? 'disabled title="PDF export is unavailable while status is pending or for revision"' : '' }}
            onclick="if (!{{ in_array($report->status, ['pending', 'for_revision']) ? 'true' : 'false' }}) window.location.href='{{ route('staff.reports.pdf',$report->id) }}'"
        >
            Export PDF
        </button>

    </div>

</div>

<!-- Submit Confirmation Modal -->
<div class="modal fade" id="submitConfirmModal" tabindex="-1" aria-labelledby="submitConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="submitConfirmModalLabel">Confirm Submission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Once submitted, this report will be reviewed by the Provincial Head. Please confirm that all information is complete and accurate before proceeding.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmSubmitBtn">Yes, Submit</button>
            </div>
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

    // Submit confirmation modal
    const submitBtn = document.getElementById('submitBtn');
    const submitConfirmModal = document.getElementById('submitConfirmModal');
    const confirmSubmitBtn = document.getElementById('confirmSubmitBtn');
    const submitReportForm = document.getElementById('submitReportForm');

    if (confirmSubmitBtn && submitReportForm) {
        confirmSubmitBtn.addEventListener('click', function() {
            // Submit the actual form
            submitReportForm.submit();
        });
    }
});
</script>

@endsection