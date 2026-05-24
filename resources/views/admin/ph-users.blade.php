@extends('admin.layouts.app')

@section('title', $title)
@section('body_class', 'admin-dashboard-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v={{ filemtime(public_path('css/dashboard.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/shared-dashboard-theme.css') }}?v={{ filemtime(public_path('css/shared-dashboard-theme.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/shared-navbar.css') }}?v={{ filemtime(public_path('css/shared-navbar.css')) }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .office-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 999px;
            background: linear-gradient(135deg, #0a3f72, #0c5ea0);
            color: #fff;
            font-size: .8rem;
            font-weight: 600;
            letter-spacing: .03em;
            margin-top: 6px;
        }
        .office-badge svg { width: 13px; height: 13px; }
        .role-pill {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: .76rem;
            font-weight: 600;
        }
        .role-pill--staff   { background: #dbeafe; color: #1d4ed8; }
        .role-pill--interns { background: #dcfce7; color: #166534; }
        .status-dot {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: .82rem;
        }
        .status-dot::before {
            content: '';
            width: 8px; height: 8px;
            border-radius: 50%;
            display: inline-block;
        }
        .status-dot--active::before   { background: #10b981; }
        .status-dot--archived::before { background: #ef4444; }
    </style>
@endpush

@section('content')
    <div class="dashboard-page">
        <main class="dashboard-shell">
            <x-topbar
                :active="'users'"
                :can-access-audit="$canAccessAudit"
                :user="$user"
            />

            <section class="dashboard-content">

                {{-- Page Header --}}
                <div class="section-header">
                    <div>
                        <h1>{{ $title }}</h1>
                        <div>
                            <span class="office-badge">
                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7Zm0 9.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5Z"/></svg>
                                {{ $officeName }}
                            </span>
                        </div>
                        <div class="section-subcopy" style="margin-top:8px">
                            <span>{{ $users->count() }} assigned personnel</span>
                        </div>
                    </div>
                </div>

                {{-- Users Table Panel --}}
                <section class="table-panel" style="margin-top: 15px">
                    <div class="table-toolbar">
                        <div class="toolbar-main-actions">
                            <form method="GET" action="{{ url()->current() }}" class="search-filter-bar" data-search-filter-form>
                                <div class="search-form">
                                    <input type="search" name="search" value="{{ $search }}" placeholder="Search name, email, or position" aria-label="Search users" data-live-search>
                                    <button type="submit" aria-label="Search">
                                        <svg viewBox="0 0 24 24"><path d="M10 4a6 6 0 1 0 3.87 10.59l4.27 4.27a1 1 0 0 0 1.42-1.42l-4.27-4.27A6 6 0 0 0 10 4Zm0 2a4 4 0 1 1-4 4 4 4 0 0 1 4-4Z"/></svg>
                                    </button>
                                </div>
                                <div class="toolbar-filter-actions">
                                    <label class="filter-select">
                                        <span>{{ $filterLabel }}</span>
                                        <select name="filter" aria-label="Filter by role" data-live-filter>
                                            <option value="">All roles</option>
                                            @foreach ($filterOptions as $filterValue => $filterText)
                                                <option value="{{ $filterValue }}" {{ $filter === $filterValue ? 'selected' : '' }}>{{ $filterText }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                    @if ($search !== '' || $filter !== '')
                                        <a href="{{ url()->current() }}" class="filter-reset">Reset</a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Position</th>
                                    <th>Office</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $listedUser)
                                    @php
                                        $displayName = trim(collect([$listedUser->first_name, $listedUser->last_name])->filter()->implode(' ')) ?: $listedUser->name;
                                        $initials    = strtoupper(collect(explode(' ', $displayName))->filter()->map(fn ($p) => substr($p, 0, 1))->take(2)->implode(''));
                                        $avatarUrl   = $listedUser->avatar_path ? route('media.public', ['path' => ltrim($listedUser->avatar_path, '/')]) : null;
                                    @endphp
                                    <tr>
                                        <td>
                                            @if ($avatarUrl)
                                                <img src="{{ $avatarUrl }}" alt="{{ $displayName }}" class="avatar-badge avatar-badge-image">
                                            @else
                                                <div class="avatar-badge">{{ $initials }}</div>
                                            @endif
                                        </td>
                                        <td>{{ $displayName }}</td>
                                        <td>{{ $listedUser->email }}</td>
                                        <td>{{ $listedUser->position ?: 'N/A' }}</td>
                                        <td>{{ $listedUser->office ?: 'N/A' }}</td>
                                        <td>
                                            <span class="role-pill role-pill--{{ $listedUser->role }}">
                                                {{ $listedUser->role === 'interns' ? 'Intern' : 'Staff' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-dot status-dot--{{ $listedUser->status }}">
                                                {{ ucfirst($listedUser->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="empty-state">No staff or interns assigned to your office yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </section>
        </main>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/search-filter.js') }}" defer></script>
@endpush
