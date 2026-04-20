@extends('admin.layouts.app')

@section('title', $title)
@section('body_class', 'admin-dashboard-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/shared-dashboard-theme.css') }}?v={{ filemtime(public_path('css/shared-dashboard-theme.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/shared-navbar.css') }}?v={{ filemtime(public_path('css/shared-navbar.css')) }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .auth-shell { padding: 32px; }
        .auth-panel { background: #fff; border-radius: 24px; box-shadow: 0 18px 42px rgba(15, 23, 42, 0.08); padding: 24px; }
        .auth-header { display: flex; justify-content: space-between; gap: 16px; align-items: end; margin-bottom: 20px; flex-wrap: wrap; }
        .auth-header h1 { margin: 0; font-size: 1.8rem; color: #17324b; }
        .auth-subcopy { margin: 8px 0 0; color: #5f7285; max-width: 60ch; }
        .auth-search { display: flex; gap: 12px; flex-wrap: wrap; }
        .auth-search input { min-width: 280px; height: 46px; padding: 0 14px; border: 1px solid #d6e0eb; border-radius: 14px; }
        .auth-search button { height: 46px; padding: 0 18px; border: 0; border-radius: 14px; background: #0c5ea0; color: #fff; font-weight: 600; }
        .auth-flash { margin-bottom: 16px; padding: 12px 14px; border-radius: 14px; }
        .auth-flash-success { background: #e8f7ef; color: #146c43; }
        .auth-flash-error { background: #fdecec; color: #b42318; }
        .auth-table-wrap { overflow-x: auto; }
        .auth-table { width: 100%; border-collapse: collapse; }
        .auth-table th, .auth-table td { padding: 14px 12px; border-bottom: 1px solid #e6edf4; text-align: left; vertical-align: top; }
        .auth-table th { color: #5f7285; font-size: .82rem; text-transform: uppercase; letter-spacing: .08em; }
        .auth-meta { color: #5f7285; font-size: .92rem; }
        .auth-badge { display: inline-flex; align-items: center; padding: 6px 10px; border-radius: 999px; font-size: .82rem; font-weight: 600; }
        .auth-badge-green { background: #e8f7ef; color: #146c43; }
        .auth-badge-yellow { background: #fff5db; color: #8a5b00; }
        .auth-badge-red { background: #fdecec; color: #b42318; }
        .auth-actions { display: flex; gap: 10px; flex-wrap: wrap; }
        .auth-actions form { margin: 0; }
        .auth-button { min-height: 38px; padding: 0 14px; border: 0; border-radius: 12px; font-weight: 600; }
        .auth-button-primary { background: #0c5ea0; color: #fff; }
        .auth-button-secondary { background: #eef4fb; color: #17324b; }
        .auth-empty { padding: 20px 0 4px; color: #5f7285; }
    </style>
@endpush

@section('content')
    <div class="dashboard-page">
        <main class="dashboard-shell">
            <x-topbar active="authenticator" :can-access-audit="$canAccessAudit" :user="$user" />

            <section class="auth-shell">
                <div class="auth-panel">
                    <div class="auth-header">
                        <div>
                            <h1>{{ $title }}</h1>
                            <p class="auth-subcopy">Super admins control who may sign in and who receives the Google Authenticator access email. That email now contains the QR code and manual setup key, so users can link Google Authenticator immediately and log in with their stored email plus the current app code.</p>
                        </div>

                        <form method="GET" action="{{ route('super-admin.authenticator.index') }}" class="auth-search">
                            <input type="search" name="search" value="{{ $search }}" placeholder="Search name, email, role, or office">
                            <button type="submit">Search</button>
                        </form>
                    </div>

                    @if (session('authenticator_status'))
                        <p class="auth-flash auth-flash-success">{{ session('authenticator_status') }}</p>
                    @endif

                    @if (session('authenticator_error'))
                        <p class="auth-flash auth-flash-error">{{ session('authenticator_error') }}</p>
                    @endif

                    @if ($errors->any())
                        <p class="auth-flash auth-flash-error">{{ $errors->first() }}</p>
                    @endif

                    <div class="auth-table-wrap">
                        <table class="auth-table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Login Access</th>
                                    <th>Authenticator</th>
                                    <th>Access Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $listedUser)
                                    @php
                                        $displayName = trim(collect([$listedUser->first_name, $listedUser->last_name])->filter()->implode(' ')) ?: $listedUser->name;
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $displayName }}</strong>
                                            <div class="auth-meta">{{ $listedUser->email }}</div>
                                            <div class="auth-meta">{{ ucwords(str_replace('-', ' ', str_replace('_', ' ', $listedUser->role))) }}{{ $listedUser->office ? ' • ' . $listedUser->office : '' }}</div>
                                        </td>
                                        <td>
                                            <span class="auth-badge {{ $listedUser->is_authorized ? 'auth-badge-green' : 'auth-badge-red' }}">
                                                {{ $listedUser->is_authorized ? 'Authorized' : 'Blocked' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="auth-badge {{ $listedUser->google2fa_enabled ? 'auth-badge-green' : 'auth-badge-yellow' }}">
                                                {{ $listedUser->google2fa_enabled ? 'Provisioned' : 'Not Provisioned' }}
                                            </span>
                                            <div class="auth-meta">
                                                {{ $listedUser->two_factor_confirmed_at ? 'Verified ' . $listedUser->two_factor_confirmed_at->diffForHumans() : 'Waiting for first successful login' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="auth-meta">
                                                {{ $listedUser->google2fa_authorization_sent_at ? 'Sent ' . $listedUser->google2fa_authorization_sent_at->diffForHumans() : 'No access email sent yet' }}
                                            </div>
                                            <div class="auth-meta">
                                                {{ $listedUser->two_factor_confirmed_at ? 'Authenticator already linked' : 'Resend to rotate the QR and manual key' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="auth-actions">
                                                <form method="POST" action="{{ route('super-admin.authenticator.authorize', $listedUser) }}">
                                                    @csrf
                                                    <button type="submit" class="auth-button auth-button-primary">
                                                        {{ $listedUser->google2fa_enabled ? 'Send Access Email' : 'Authorize & Send Access' }}
                                                    </button>
                                                </form>

                                                @if ($listedUser->is_authorized)
                                                    <form method="POST" action="{{ route('super-admin.authenticator.revoke', $listedUser) }}">
                                                        @csrf
                                                        <button type="submit" class="auth-button auth-button-secondary">Revoke Access</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="auth-empty">No managed users matched the current search.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>
    </div>
@endsection
