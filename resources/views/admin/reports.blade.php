{{-- This page shows the admin and super admin report dashboard, including summary cards, the reports table, and the report review modal. --}}
@extends('admin.layouts.app')

@section('title', $title)
@section('body_class', 'admin-reports-page')

{{-- Styles loaded for the shared admin dashboard look and the report-page-specific design. --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}?v={{ filemtime(public_path('css/admin-dashboard.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/admin-reports.css') }}?v={{ filemtime(public_path('css/admin-reports.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/shared-dashboard-theme.css') }}?v={{ filemtime(public_path('css/shared-dashboard-theme.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/shared-navbar.css') }}?v={{ filemtime(public_path('css/shared-navbar.css')) }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@endpush

@section('content')
    {{-- Main layout structure for the report dashboard page. --}}
    <div class="admin-page">
        <main class="admin-shell">
            {{-- Top navigation bar for admin pages. --}}
            <x-topbar :active="$isSuperAdminView ? 'reports' : 'dashboard'" :can-access-audit="$canAccessAudit" :user="$user" />


            {{-- Main dashboard container.
                 `data-admin-dashboard` lets JavaScript detect this page.
                 `data-dashboard-mode` tells the script which report mode is active.
                 `data-can-manage` tells the script if the current user can update report status.
                 `data-csrf-token` provides the CSRF token for secure AJAX review actions. --}}
                 
            <section class="admin-content" data-admin-dashboard data-dashboard-mode="{{ $mode }}" data-can-manage="{{ $canManageReportRecords ? 'true' : 'false' }}" data-csrf-token="{{ csrf_token() }}">
                {{-- Flash/status message shown after successful actions. --}}
                @if (session('status'))
                    <div class="status-message">{{ session('status') }}</div>
                @endif

                {{-- Summary cards for quick report totals and status navigation. --}}
                @include('admin.partials.reports-summary')
                {{-- Main reports table with filters, rows, and action buttons. --}}
                @include('admin.partials.reports-table')
            </section>
        </main>
    </div>

    {{-- Reusable modal for viewing report details and review actions. --}}
    @include('admin.partials.reports-modal')
@endsection

{{-- JavaScript files used for shared filtering helpers and report dashboard interactions. --}}
@push('scripts')
    <script src="{{ asset('js/search-filter.js') }}" defer></script>
    <script src="{{ asset('js/admin-reports.js') }}?v={{ filemtime(public_path('js/admin-reports.js')) }}" defer></script>
@endpush
