@extends('staff.layouts.app')

@section('content')
    @php
        // ADD THIS CODE
        $staffRouteBase = app(\App\Services\AuthFlowService::class)->staffPortalPrefix(session('authenticated_user_role', session('role', optional(\App\Models\User::find(session('authenticated_user_id')))->role)));
    @endphp
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }

        body { background: linear-gradient(145deg, #f0f4f9 0%, #e8eef6 100%); min-height: 100vh; }

        .container { padding: 32px 48px; max-width: 1440px; }

        /* ── STAT CARDS ── */
        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 28px;
        }

        .card {
            position: relative;
            border-radius: 20px;
            padding: 22px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ffffff;
            box-shadow: 0 2px 16px rgba(0,0,0,.07);
            overflow: hidden;
            transition: transform .3s ease, box-shadow .3s ease;
            cursor: pointer;
            text-decoration: none;
            border: 1.5px solid transparent;
        }

        .card::before {
            content: ''; position: absolute; top: 0; left: 0;
            width: 4px; height: 100%; border-radius: 20px 0 0 20px;
        }
        .card:nth-child(1)::before { background: linear-gradient(180deg,#6366f1,#4f46e5); }
        .card:nth-child(2)::before { background: linear-gradient(180deg,#22c55e,#16a34a); }
        .card:nth-child(3)::before { background: linear-gradient(180deg,#f59e0b,#d97706); }
        .card:nth-child(4)::before { background: linear-gradient(180deg,#ef4444,#dc2626); }

        .card::after {
            content: ''; position: absolute; width: 140px; height: 140px;
            border-radius: 50%; top: -40px; right: -40px; opacity: .06; transition: .4s;
        }
        .card:nth-child(1)::after { background: #6366f1; }
        .card:nth-child(2)::after { background: #22c55e; }
        .card:nth-child(3)::after { background: #f59e0b; }
        .card:nth-child(4)::after { background: #ef4444; }

        .card:hover { transform: translateY(-6px); box-shadow: 0 16px 40px rgba(0,0,0,.12); }
        .card:hover::after { transform: scale(1.4); opacity: .12; }

        .card:nth-child(1).active { border-color: #6366f1; box-shadow: 0 8px 28px rgba(99,102,241,.2); }
        .card:nth-child(2).active { border-color: #22c55e; box-shadow: 0 8px 28px rgba(34,197,94,.2); }
        .card:nth-child(3).active { border-color: #f59e0b; box-shadow: 0 8px 28px rgba(245,158,11,.2); }
        .card:nth-child(4).active { border-color: #ef4444; box-shadow: 0 8px 28px rgba(239,68,68,.2); }

        .card h4 {
            font-size: 11px; color: #6b7280; margin-bottom: 6px;
            font-weight: 600; letter-spacing: .6px; text-transform: uppercase;
        }
        .card h2 { font-size: 36px; font-weight: 700; color: #111827; line-height: 1; }

        .card-icon {
            width: 56px; height: 56px; border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; transition: transform .3s ease; flex-shrink: 0;
        }
        .card:hover .card-icon { transform: rotate(8deg) scale(1.1); }

        .icon-blue  { background: linear-gradient(135deg,#818cf8,#4f46e5); color:#fff; box-shadow: 0 8px 20px rgba(99,102,241,.35); }
        .icon-green { background: linear-gradient(135deg,#4ade80,#16a34a); color:#fff; box-shadow: 0 8px 20px rgba(34,197,94,.35); }
        .icon-yellow{ background: linear-gradient(135deg,#fcd34d,#d97706); color:#fff; box-shadow: 0 8px 20px rgba(245,158,11,.35); }
        .icon-red   { background: linear-gradient(135deg,#f87171,#dc2626); color:#fff; box-shadow: 0 8px 20px rgba(239,68,68,.35); }

        /* ── SEARCH ── */
        .search-box { margin-bottom: 18px; }
        .search-box form { display: flex; gap: 10px; align-items: center; }

        .search-box input[type="text"] {
            width: 340px; padding: 11px 20px; border-radius: 50px;
            border: 1.5px solid #e5e7eb; background: #fff; font: inherit;
            font-size: .875rem; color: #374151; outline: none;
            box-shadow: 0 2px 8px rgba(0,0,0,.06);
            transition: border-color .2s, box-shadow .2s;
        }
        .search-box input[type="text"]:focus {
            border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.12);
        }

        .search-box button, .search-box a {
            padding: 11px 22px; border-radius: 50px; border: 1.5px solid #e5e7eb;
            background: #fff; font: inherit; font-size: .875rem; color: #374151;
            cursor: pointer; text-decoration: none;
            transition: all .2s; box-shadow: 0 2px 8px rgba(0,0,0,.05);
        }
        .search-box button:hover, .search-box a:hover { background: #f3f4f6; border-color: #d1d5db; }

        /* ── TABLE PANEL ── */
        #bulkDeleteForm {
            background: #ffffff; border-radius: 20px;
            box-shadow: 0 4px 24px rgba(0,0,0,.07);
            overflow: hidden; border: 1px solid #f1f5f9;
        }

        .table { width: 100%; border-collapse: collapse; background: transparent; }

        .table thead { background: linear-gradient(135deg,#f8fafc,#f1f5f9); border-bottom: 2px solid #e9eef5; }

        .table th {
            padding: 14px 18px; text-align: left; font-size: 11px;
            font-weight: 700; color: #64748b; letter-spacing: .7px; text-transform: uppercase;
        }
        .table td {
            padding: 14px 18px; text-align: left; font-size: 13.5px;
            color: #374151; border-bottom: 1px solid #f1f5f9;
        }
        .table tbody tr { transition: background .15s; }
        .table tbody tr:hover { background: #fafbff; }
        .table tbody tr:last-child td { border-bottom: none; }

        .table a { color: #4f46e5; text-decoration: none; font-weight: 500; }
        .table a:hover { text-decoration: underline; }

        /* ── STATUS BADGES ── */
        .status {
            padding: 4px 12px; border-radius: 50px; font-size: 11.5px;
            font-weight: 600; display: inline-block; letter-spacing: .3px;
        }
        .pending      { background: #fef9c3; color: #854d0e; box-shadow: 0 0 0 1px #fde68a; }
        .approved     { background: #dcfce7; color: #14532d; box-shadow: 0 0 0 1px #86efac; }
        .for_revision { background: #fee2e2; color: #7f1d1d; box-shadow: 0 0 0 1px #fca5a5; }

        /* ── REVIEW NOTE ── */
        .review-note {
            margin-top: 5px; font-size: 11.5px; line-height: 1.45;
            color: #92400e; white-space: pre-wrap;
            background: #fffbeb; border-left: 3px solid #f59e0b;
            padding: 4px 8px; border-radius: 0 4px 4px 0;
        }

        /* ── EXPORT BUTTON ── */
        .export-btn {
            border: none; padding: 6px 16px; border-radius: 50px;
            background: linear-gradient(135deg,#1e40af,#1d4ed8);
            color: #fff; cursor: pointer; font-size: 12px; font-weight: 500;
            font-family: inherit; transition: box-shadow .2s, transform .2s;
            box-shadow: 0 4px 12px rgba(29,78,216,.3);
        }
        .export-btn:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(29,78,216,.4); }
        .export-btn:disabled { background: #d1d5db; color: #9ca3af; cursor: not-allowed; box-shadow: none; }

        /* ── DELETE SELECTED BUTTON ── */
        #deleteSelectedBtn {
            background: linear-gradient(135deg,#dc2626,#b91c1c) !important;
            border: none !important; color: #fff !important;
            padding: 10px 24px; border-radius: 50px !important;
            font: inherit; font-size: .875rem; font-weight: 600;
            cursor: pointer; box-shadow: 0 4px 16px rgba(220,38,38,.3);
            transition: box-shadow .2s, transform .2s; margin: 16px;
        }
        #deleteSelectedBtn:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(220,38,38,.4); }
        #deleteSelectedBtn:disabled { background: #d1d5db !important; box-shadow: none; cursor: not-allowed; }

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) { .cards { grid-template-columns: repeat(2,1fr); } }
        @media (max-width: 640px)  {
            .container { padding: 20px 16px; }
            .cards { grid-template-columns: 1fr; }
            .search-box input[type="text"] { width: 100%; }
        }

    </style>


    @php
        // Counts are passed from the controller and must remain static.
        // They reflect all reports for this user, not the currently filtered table.
    @endphp

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Cards Section -->
        <div class="cards">
            @php
                $searchQuery = $searchTerm ? '&search=' . urlencode($searchTerm) : '';
            @endphp

            <a href="{{ url()->current() }}?status=all{{ $searchQuery }}" class="card {{ $statusFilter === 'all' || !$statusFilter ? 'active' : '' }}">
                <div>
                    <h4>Submitted</h4>
                    <h2>{{ $submittedCount }}</h2>
                </div>
                <div class="card-icon icon-blue">
                    <i class="fa-solid fa-paper-plane"></i>
                </div>
            </a>

            <a href="{{ url()->current() }}?status=approved{{ $searchQuery }}" class="card {{ $statusFilter === 'approved' ? 'active' : '' }}">
                <div>
                    <h4>Approved</h4>
                    <h2>{{ $approvedCount }}</h2>
                </div>
                <div class="card-icon icon-green">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </a>

            <a href="{{ url()->current() }}?status=pending{{ $searchQuery }}" class="card {{ $statusFilter === 'pending' ? 'active' : '' }}">
                <div>
                    <h4>Pending</h4>
                    <h2>{{ $pendingCount }}</h2>
                </div>
                <div class="card-icon icon-yellow">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
            </a>

            <a href="{{ url()->current() }}?status=for_revision{{ $searchQuery }}" class="card {{ $statusFilter === 'for_revision' ? 'active' : '' }}">
                <div>
                    <h4>For Revision</h4>
                    <h2>{{ $revisionCount }}</h2>
                </div>
                <div class="card-icon icon-red">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
            </a>
        </div>

        <!-- Search Section -->
        <div class="search-box">
            <form method="GET" action="{{ url()->current() }}" style="display: flex; gap: 10px;">
                <input type="text" name="search" value="{{ $searchTerm }}" placeholder="Search by file name, date submitted, or status">
                @if($statusFilter)
                    <input type="hidden" name="status" value="{{ $statusFilter }}">
                @endif
                <button type="submit" style="padding: 10px 15px; border-radius: 20px; border: 1px solid #ddd; background: #f0f0f0;">Search</button>
                @if($searchTerm || $statusFilter)
                    <a href="{{ url()->current() }}" style="padding: 10px 15px; border-radius: 20px; border: 1px solid #ddd; background: #f0f0f0; text-decoration: none;">Clear</a>
                @endif
            </form>
        </div>

        <!-- Table Section -->
        <form id="bulkDeleteForm" method="POST" action="{{ route($staffRouteBase . '.dashboard.bulk-delete') }}">
            @csrf
            <table class="table" id="reports-table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Date Submitted</th>
                        <th>File Name</th>
                        <th>Status</th>
                        <th>Date Returned</th>
                        <th>Download</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td><input type="checkbox" name="report_ids[]" value="{{ $report->id }}" class="report-checkbox"></td>
                            <td>{{ optional($report->submitted_at ?? $report->created_at)->format('m/d/Y') }}</td>
                            <td>
                                <a href="{{ route($staffRouteBase . '.reports.show', $report->id) }}">
                                    {{ $report->file_name }}
                                </a>
                            </td>
                            <td>
                                <span class="status {{ $report->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                </span>
                                @if($report->status === 'for_revision' && $report->review_comment)
                                    <div class="review-note">Comment: {{ $report->review_comment }}</div>
                                @endif
                            </td>
                            <td>{{ optional($report->reviewed_at)->format('m/d/Y') ?? '-' }}</td>
                            <td>
                               @if(in_array($report->status, ['approved', 'draft'], true))
                                 @if($report->status === 'approved')
                                   <button type="button" class="export-btn" data-export-pdf-url="{{ route($staffRouteBase . '.reports.pdf', $report) }}">Export PDF</button>
                                 @else
                                   <a href="{{ route($staffRouteBase . '.reports.pdf', $report) }}" class="export-btn">Export PDF</a>
                                 @endif
                                @else
                              <button class="export-btn" disabled title="Export is only available for approved or draft reports">Export PDF</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No submitted reports yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div style="margin-top: 15px;">
                <button type="submit" id="deleteSelectedBtn" class="btn btn-danger" style="padding: 10px 15px; border-radius: 20px;" disabled>Delete Selected</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Bulk delete functionality
            const selectAllCheckbox = document.getElementById('selectAll');
            const reportCheckboxes = document.querySelectorAll('.report-checkbox');
            const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
            const bulkDeleteForm = document.getElementById('bulkDeleteForm');
            const exportButtons = document.querySelectorAll('[data-export-pdf-url]');

            function updateDeleteButton() {
                const checkedBoxes = document.querySelectorAll('.report-checkbox:checked');
                deleteSelectedBtn.disabled = checkedBoxes.length === 0;
            }

            selectAllCheckbox.addEventListener('change', function() {
                reportCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateDeleteButton();
            });

            reportCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const allChecked = Array.from(reportCheckboxes).every(cb => cb.checked);
                    const someChecked = Array.from(reportCheckboxes).some(cb => cb.checked);
                    
                    selectAllCheckbox.checked = allChecked;
                    selectAllCheckbox.indeterminate = someChecked && !allChecked;
                    
                    updateDeleteButton();
                });
            });

            bulkDeleteForm.addEventListener('submit', function(e) {
                const checkedBoxes = document.querySelectorAll('.report-checkbox:checked');
                if (checkedBoxes.length === 0) {
                    e.preventDefault();
                    return;
                }
                e.preventDefault();

                if (typeof window.openStaffConfirmModal !== 'function') {
                    bulkDeleteForm.submit();
                    return;
                }

                window.openStaffConfirmModal({
                    title: 'Confirm Delete',
                    message: `Are you sure you want to delete ${checkedBoxes.length} selected report(s) from your dashboard?`,
                    confirmText: 'Delete',
                    cancelText: 'Cancel',
                    variant: 'danger',
                    onConfirm: function () {
                        bulkDeleteForm.submit();
                    }
                });
            });

            exportButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    const pdfUrl = button.getAttribute('data-export-pdf-url');

                    if (!pdfUrl) {
                        return;
                    }

                    if (typeof window.openStaffConfirmModal !== 'function') {
                        window.location.href = pdfUrl;
                        return;
                    }

                    window.openStaffConfirmModal({
                        title: 'Confirm Export',
                        message: 'Do you want to export this file as PDF?',
                        confirmText: 'Export',
                        cancelText: 'Cancel',
                        variant: 'success',
                        onConfirm: function () {
                            window.location.href = pdfUrl;
                        }
                    });
                });
            });
        });
    </script>
@endsection
