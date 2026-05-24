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

            <section class="admin-content" data-admin-dashboard data-dashboard-mode="{{ $mode }}" data-can-manage="false" data-csrf-token="{{ csrf_token() }}">
                @if (session('status'))
                    <div class="status-message">{{ session('status') }}</div>
                @endif

                @include('admin.partials.reports-summary')

                @if ($isSuperAdminView)
                    <div class="super-admin-view-banner" style="margin-top: 20px;">
                        You are currently viewing the dashboard as a super admin. Report files are not accessible in this view.
                    </div>
                @endif

            </section>
        </main>
    </div>
@endsection
