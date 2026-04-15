@extends('staff.layouts.app')

@section('content')
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f4f6f9;
        }

        .container {
            padding: 30px 60px;
        }

        /* Cards */
        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 22px;
            margin-bottom: 25px;
        }

        .card {
            position: relative;
            border-radius: 18px;
            padding: 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #ffffff, #f8fafc);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            transition: all 0.35s ease;
            cursor: pointer;
        }

        .card.active {
            border: 2px solid #6366f1;
        }

        .card::after {
            content: "";
            position: absolute;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            top: -30px;
            right: -30px;
            opacity: 0.15;
            transition: 0.4s;
        }

        .card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 18px 35px rgba(0, 0, 0, 0.12);
        }

        .card:hover::after {
            transform: scale(1.3);
            opacity: 0.25;
        }

        .card h4 {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 6px;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        .card h2 {
            font-size: 34px;
            font-weight: 700;
            color: #111827;
        }

        .card-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            transition: 0.3s;
        }

        .card:hover .card-icon {
            transform: rotate(6deg) scale(1.1);
        }

        /* Card Color Themes */
        .icon-blue {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
        }

        .card:nth-child(1)::after {
            background: #6366f1;
        }

        .icon-green {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
        }

        .card:nth-child(2)::after {
            background: #22c55e;
        }

        .icon-yellow {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .card:nth-child(3)::after {
            background: #f59e0b;
        }

        .icon-red {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .card:nth-child(4)::after {
            background: #ef4444;
        }

        /* Search Bar */
        .search-box {
            margin-bottom: 15px;
        }

        .search-box input {
            width: 300px;
            padding: 10px 15px;
            border-radius: 20px;
            border: 1px solid #ddd;
        }

        /* Table */
        .table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }

        .table thead {
            background: #e9eef3;
        }

        .table th,
        .table td {
            padding: 14px;
            text-align: left;
            font-size: 14px;
        }

        .table tbody tr {
            border-bottom: 1px solid #eee;
        }

        /* Status Badges */
        .status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }

        .pending {
            background: #fff4d6;
            color: #b7791f;
        }

        .approved {
            background: #dcfce7;
            color: #166534;
        }

        .for_revision {
            background: #ffe4e6;
            color: #b91c1c;
        }

        /* Export Buttons */
        .export-btn {
            border: none;
            padding: 6px 16px;
            border-radius: 20px;
            background: #1f4e79;
            color: white;
            cursor: pointer;
            font-size: 13px;
        }

        .export-btn:hover:not(:disabled) {
            opacity: 0.9;
        }

        .export-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            opacity: 0.6;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 500px) {
            .cards {
                grid-template-columns: 1fr;
            }
        }
    </style>

    @php
        // Counts are passed from the controller and must remain static.
        // They reflect all reports for this user, not the currently filtered table.
    @endphp

    <div class="container">
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
        <table class="table" id="reports-table">
            <thead>
                <tr>
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
                        <td>{{ optional($report->submitted_at ?? $report->created_at)->format('m/d/Y') }}</td>
                        <td>{{ $report->file_name }}</td>
                        <td>
                            <span class="status {{ $report->status }}">
                                {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                            </span>
                        </td>
                        <td>{{ optional($report->reviewed_at)->format('m/d/Y') ?? '-' }}</td>
                        <td>
                           @if(in_array($report->status, ['approved', 'draft'], true))
                             <a href="{{ route('staff.reports.pdf', $report) }}" class="export-btn">Export</a>
                            @else
                          <button class="export-btn" disabled title="Export is only available for approved or draft reports">Export</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No submitted reports yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
