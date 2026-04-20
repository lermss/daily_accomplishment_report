@extends('admin.layouts.app')

@section('title', $title)
@section('body_class', 'admin-dashboard-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v={{ filemtime(public_path('css/dashboard.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/shared-dashboard-theme.css') }}?v={{ filemtime(public_path('css/shared-dashboard-theme.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/shared-navbar.css') }}?v={{ filemtime(public_path('css/shared-navbar.css')) }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
@endpush

@section('content')
    <div class="dashboard-page">
        <main class="dashboard-shell">
            <x-topbar
                :active="$mode === 'reports' ? 'reports' : 'dashboard'"
                :can-access-audit="$canAccessAudit"
                :user="$user"
            />

            <section class="dashboard-content">
                @if (session('user_status'))
                    <p class="flash-message flash-success">{{ session('user_status') }}</p>
                @endif

                @if (session('user_error'))
                    <p class="flash-message flash-error">{{ session('user_error') }}</p>
                @endif

                @if ($errors->any())
                    <p class="flash-message flash-error">{{ $errors->first() }}</p>
                @endif

                @if ($mode === 'dashboard')
                    @include('admin.partials.dashboard-summary-cards')

                    {{-- ═══════════════════════════════════════════════════
                         Universal Chart Filter Bar
                    ═══════════════════════════════════════════════════ --}}
                    <section class="chart-filter-bar" aria-label="Chart date filter">
                        <form method="GET" action="{{ url()->current() }}" class="chart-filter-form" id="chartFilterForm">
                            {{-- Preserve any existing user-table filters --}}
                            @if ($search !== '')
                                <input type="hidden" name="search" value="{{ $search }}">
                            @endif
                            @if ($filter !== '')
                                <input type="hidden" name="filter" value="{{ $filter }}">
                            @endif

                            <div class="chart-filter-inner">
                                <span class="chart-filter-label">
                                    <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M3 4h1v16H3V4Zm17 0h1v16h-1V4ZM9 4h6v2H9V4Zm0 7h6v2H9v-2Zm0 7h6v2H9v-2Z"/></svg>
                                    Filter Charts
                                </span>

                                <div class="chart-quick-btns">
                                    <button type="submit" name="chart_quick" value=""
                                        class="chart-quick-btn {{ $chartQuick === '' && $chartFromInput === '' && $chartToInput === '' ? 'active' : '' }}">All Time</button>
                                    @foreach ($chartQuickOptions as $qVal => $qLabel)
                                        <button type="submit" name="chart_quick" value="{{ $qVal }}"
                                            class="chart-quick-btn {{ $chartQuick === $qVal ? 'active' : '' }}">{{ $qLabel }}</button>
                                    @endforeach
                                </div>

                                <div class="chart-date-range">
                                    <label class="chart-date-label">From
                                        <input type="date" name="chart_from" value="{{ $chartFromInput }}" class="chart-date-input" max="{{ date('Y-m-d') }}">
                                    </label>
                                    <span class="chart-date-sep">—</span>
                                    <label class="chart-date-label">To
                                        <input type="date" name="chart_to" value="{{ $chartToInput }}" class="chart-date-input" max="{{ date('Y-m-d') }}">
                                    </label>
                                    <button type="submit" class="chart-date-apply">Apply</button>
                                </div>
                            </div>
                        </form>
                    </section>

                    {{-- ═══════════════════════════════════════════════════
                         Charts Section
                    ═══════════════════════════════════════════════════ --}}
                    <section class="charts-section" aria-label="Analytics charts">

                        {{-- Users by Role — Doughnut --}}
                        <div class="chart-card">
                            <div class="chart-card-header">
                                <div class="chart-card-title">
                                    <span class="chart-card-icon chart-card-icon--purple">
                                        <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3Zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3Zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5Zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5Z"/></svg>
                                    </span>
                                    <h2>Users by Role</h2>
                                </div>
                                <span class="chart-card-badge">{{ array_sum($chartData['usersByRole']) }} total</span>
                            </div>
                            <div class="chart-card-body chart-card-body--doughnut">
                                <div class="doughnut-wrap">
                                    <canvas id="usersChart" aria-label="Users by role doughnut chart" role="img"></canvas>
                                    <div class="doughnut-center" id="doughnutCenter">
                                        <strong>{{ array_sum($chartData['usersByRole']) }}</strong>
                                        <span>users</span>
                                    </div>
                                </div>
                                <ul class="chart-legend" id="usersLegend" aria-label="Users chart legend"></ul>
                            </div>
                        </div>

                        {{-- Reports by Status — Stacked Bar --}}
                        <div class="chart-card">
                            <div class="chart-card-header">
                                <div class="chart-card-title">
                                    <span class="chart-card-icon chart-card-icon--blue">
                                        <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M5 9.2h3V19H5V9.2ZM10.6 5h2.8v14h-2.8V5ZM16.2 13H19v6h-2.8v-6Z"/></svg>
                                    </span>
                                    <h2>Reports Overview</h2>
                                </div>
                                <span class="chart-card-badge">Last {{ count($chartData['reportMonths']) }} months</span>
                            </div>
                            <div class="chart-card-body">
                                <canvas id="reportsChart" aria-label="Reports by status stacked bar chart" role="img"></canvas>
                            </div>
                            <div class="chart-legend-row" id="reportsLegend" aria-label="Reports chart legend"></div>
                        </div>

                    </section>

                    {{-- Embed chart data for JS --}}
                    <script id="chart-data" type="application/json">
                        {!! json_encode([
                            'usersByRole'     => $chartData['usersByRole'],
                            'reportMonths'    => $chartData['reportMonths'],
                            'reportsByStatus' => $chartData['reportsByStatus'],
                        ], JSON_UNESCAPED_UNICODE) !!}
                    </script>

                @else
                    <div class="section-header">
                       
                        <div>
                            <h1>{{ $title }}</h1>
                            <div class="section-subcopy">
                                <span class="user-badge-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24"><path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm0 2c-4.42 0-8 2.24-8 5a1 1 0 0 0 2 0c0-1.45 2.61-3 6-3s6 1.55 6 3a1 1 0 0 0 2 0c0-2.76-3.58-5-8-5Z"/></svg>
                                </span>
                                <span>
                                    {{ $mode === 'archive' ? $counts['archive'] : ($mode === 'active' ? $counts['active'] : $counts['users']) }}
                                    {{ $mode === 'archive' ? 'archived users' : 'users' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif

                <section class="table-panel" style="margin-top: 15px">
                    <div class="table-toolbar">
                        <div class="toolbar-main-actions">
                            <form method="GET" action="{{ url()->current() }}" class="search-filter-bar" data-search-filter-form>
                                <div class="search-form">
                                    <input type="search" name="search" value="{{ $search }}" placeholder="Search users, email, bureau, or position" aria-label="Search users" data-live-search>
                                    <button type="submit" aria-label="Search">
                                        <svg viewBox="0 0 24 24"><path d="M10 4a6 6 0 1 0 3.87 10.59l4.27 4.27a1 1 0 0 0 1.42-1.42l-4.27-4.27A6 6 0 0 0 10 4Zm0 2a4 4 0 1 1-4 4 4 4 0 0 1 4-4Z"/></svg>
                                    </button>
                                </div>
                                <div class="toolbar-filter-actions">
                                    <label class="filter-select">
                                        <span>{{ $filterLabel }}</span>
                                        <select name="filter" aria-label="Filter users by role" data-live-filter>
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

                        @if ($canManageUsers && $mode !== 'reports')
                            <button type="button" class="add-button" data-open-user-modal aria-label="Add user">
                                <span class="add-button-icon" aria-hidden="true">+</span>
                                <span>Add User</span>
                            </button>
                        @endif
                    </div>

                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Position</th>
                                    <th>{{ $mode === 'reports' ? 'Role' : 'Project' }}</th>
                                    <th>{{ $mode === 'reports' ? 'Status' : 'Bureau' }}</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $listedUser)
                                    @php
                                        $displayName = trim(collect([$listedUser->first_name, $listedUser->last_name])->filter()->implode(' ')) ?: $listedUser->name;
                                        $initials = strtoupper(collect(explode(' ', $displayName))->filter()->map(fn ($part) => substr($part, 0, 1))->take(2)->implode(''));
                                        $avatarUrl = $listedUser->avatar_path ? route('media.public', ['path' => ltrim($listedUser->avatar_path, '/')]) : null;
                                        $editPayload = [
                                            'id' => $listedUser->id,
                                            'name' => $listedUser->name,
                                            'first_name' => $listedUser->first_name,
                                            'middle_name' => $listedUser->middle_name,
                                            'last_name' => $listedUser->last_name,
                                            'email' => $listedUser->email,
                                            'position' => $listedUser->position,
                                            'project' => $listedUser->project,
                                            'bureau' => $listedUser->bureau,
                                            'division' => $listedUser->division,
                                            'office' => $listedUser->office,
                                            'institution' => $listedUser->institution,
                                            'role' => $listedUser->role,
                                        ];
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
                                        <td>{{ $mode === 'reports' ? str_replace('_', ' ', $listedUser->role) : ($listedUser->project ?: $listedUser->division ?: 'N/A') }}</td>
                                        <td>{{ $mode === 'reports' ? ucfirst($listedUser->status) : ($listedUser->bureau ?: $listedUser->office ?: 'N/A') }}</td>
                                        <td>
                                            <div class="action-icons">
                                                @if ($canManageUsers)
                                                    @if ($listedUser->status === 'active')
                                                        <form id="archive-user-{{ $listedUser->id }}" method="POST" action="{{ route('dashboard.users.archive', $listedUser) }}">
                                                            @csrf
                                                        </form>
                                                        <button
                                                            type="button"
                                                            class="row-action"
                                                            data-confirm-trigger
                                                            data-form-id="archive-user-{{ $listedUser->id }}" >
                                                            <svg viewBox="0 0 24 24"><path d="M12 5c5.23 0 9.27 4.62 10 6-.73 1.38-4.77 6-10 6S2.73 12.38 2 11c.73-1.38 4.77-6 10-6Zm0 2.5A3.5 3.5 0 1 0 15.5 11 3.5 3.5 0 0 0 12 7.5Zm-7.71 10.29 14-14 1.42 1.42-14 14Z"/></svg>
                                                        </button>
                                                    @else
                                                        <form id="restore-user-{{ $listedUser->id }}" method="POST" action="{{ route('dashboard.users.restore', $listedUser) }}">
                                                            @csrf
                                                        </form>
                                                        <button
                                                            type="button"
                                                            class="row-action row-action-restore"
                                                            data-confirm-trigger
                                                            data-form-id="restore-user-{{ $listedUser->id }}">
                                                            <svg viewBox="0 0 24 24"><path d="M12 5a7 7 0 1 1-6.92 8H3l2.75-3L8.5 13H6.94A5 5 0 1 0 12 7a4.94 4.94 0 0 0-3.13 1.1L7.45 6.68A7 7 0 0 1 12 5Z"/></svg>
                                                        </button>
                                                    @endif
                                                @endif


                                                @if ($canManageUsers && $mode !== 'reports')
                                                    <button type="button" class="row-action row-action-edit" data-open-user-modal data-mode="edit" data-user='@json($editPayload)'>
                                                        <svg viewBox="0 0 24 24"><path d="m4 16.25 9.19-9.19 3.75 3.75L7.75 20H4Zm13.71-9.04a1 1 0 0 0 0-1.42l-1.5-1.5a1 1 0 0 0-1.42 0l-.89.89 3.75 3.75Z"/></svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="empty-state">No records found for the current filter.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </section>
        </main>
    </div>

    @if ($canManageUsers)
        <div class="modal-backdrop {{ $errors->any() ? 'is-visible' : '' }}" data-user-modal>
            <section class="user-modal" role="dialog" aria-modal="true" aria-labelledby="user-modal-title">
                <header class="user-modal-header">
                    <h2 id="user-modal-title" data-user-modal-title>Create New User</h2>
                </header>

                <form
                    method="POST"
                    action="{{ route('dashboard.users.store') }}"
                    class="user-form"
                    data-user-form
                    data-store-action="{{ route('dashboard.users.store') }}"
                    data-update-template="{{ url('/dashboard/users/__USER__') }}"
                >
                    @csrf
                    <input type="hidden" name="_method" value="" data-user-form-method>

                    <div class="role-options">
                        @foreach ($userFormOptions as $roleValue => $config)
                            <label class="radio-option">
                                <input type="radio" name="role" value="{{ $roleValue }}" data-role-radio {{ $initialRole === $roleValue ? 'checked' : '' }}>
                                <span>{{ $config['label'] }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="field-stack">
                        <div class="field-row field-row--name" data-field="name">
                            <input type="hidden" name="name" value="{{ old('name') }}" data-combined-name required>
                            <div class="name-parts">
                                <input type="text" name="first_name" placeholder="First Name" value="{{ old('first_name') }}" data-name-part="first_name" required>
                                <input type="text" name="middle_name" placeholder="Middle Name" value="{{ old('middle_name') }}" data-name-part="middle_name">
                                <input type="text" name="last_name" placeholder="Last Name" value="{{ old('last_name') }}" data-name-part="last_name" required>
                            </div>
                        </div>
                        <div class="field-row" data-field="email">
                            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                        </div>
                        <div class="field-row" data-field="position">
                            <input type="text" name="position" placeholder="Position" value="{{ old('position') }}">
                        </div>
                        <div class="field-row" data-field="institution">
                            <input type="text" name="institution" placeholder="Institution" value="{{ old('institution') }}">
                        </div>
                        <div class="field-row" data-field="project">
                            <select name="project"><option value="">Project</option></select>
                        </div>
                        <div class="field-row" data-field="bureau">
                            <select name="bureau"><option value="">Bureau</option></select>
                        </div>
                        <div class="field-row" data-field="division">
                            <select name="division" data-division-select><option value="">Division</option></select>
                        </div>
                        <div class="field-row" data-field="office">
                            <select name="office"><option value="">Office</option></select>
                        </div>
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="modal-button modal-button-secondary" data-close-user-modal>Cancel</button>
                        <button type="submit" class="modal-button modal-button-primary">Save</button>
                    </div>
                </form>
            </section>
        </div>
    @endif

    <x-confirm-modal />

@endsection

@push('scripts')
    <script id="dashboard-config" type="application/json">
        {!! json_encode([
            'userFormOptions' => $userFormOptions,
            'initialRole' => $initialRole,
            'oldValues' => [
                'role' => old('role'),
                'name' => old('name'),
                'first_name' => old('first_name'),
                'middle_name' => old('middle_name'),
                'last_name' => old('last_name'),
                'email' => old('email'),
                'position' => old('position'),
                'institution' => old('institution'),
                'project' => old('project'),
                'bureau' => old('bureau'),
                'division' => old('division'),
                'office' => old('office'),
            ],
        ]) !!}
    </script>
    <script>
        window.dashboardConfig = JSON.parse(document.getElementById('dashboard-config').textContent);
    </script>
    <script src="{{ asset('js/dashboard.js') }}" defer></script>
    <script src="{{ asset('js/search-filter.js') }}" defer></script>

    @if ($mode === 'dashboard')
    <script>
    (function () {
        'use strict';

        // ── Palette matching the existing DAR design tokens ──────────────
        const PALETTE = {
            purple : { bg: 'rgba(108, 65, 222, 0.85)', border: 'rgba(108, 65, 222, 1)', light: 'rgba(108, 65, 222, 0.12)' },
            yellow : { bg: 'rgba(245, 158, 11, 0.85)',  border: 'rgba(245, 158, 11, 1)',  light: 'rgba(245, 158, 11, 0.12)'  },
            green  : { bg: 'rgba(16, 185, 129, 0.85)',  border: 'rgba(16, 185, 129, 1)',  light: 'rgba(16, 185, 129, 0.12)'  },
            blue   : { bg: 'rgba(59, 130, 246, 0.85)',  border: 'rgba(59, 130, 246, 1)',  light: 'rgba(59, 130, 246, 0.12)'  },
            red    : { bg: 'rgba(239, 68, 68, 0.85)',   border: 'rgba(239, 68, 68, 1)',   light: 'rgba(239, 68, 68, 0.12)'   },
            navy   : { bg: 'rgba(31, 78, 121, 0.85)',   border: 'rgba(31, 78, 121, 1)',   light: 'rgba(31, 78, 121, 0.12)'   },
        };

        const raw = JSON.parse(document.getElementById('chart-data').textContent);
        if (!raw) return;

        Chart.defaults.font.family = "'Poppins', sans-serif";
        Chart.defaults.font.size   = 12;

        // ─────────────────────────────────────────────
        // 1.  USERS DOUGHNUT CHART
        // ─────────────────────────────────────────────
        const roleColors = [PALETTE.purple, PALETTE.yellow, PALETTE.navy, PALETTE.blue];
        const roleLabels = Object.keys(raw.usersByRole);
        const roleData   = Object.values(raw.usersByRole);
        const total      = roleData.reduce((a, b) => a + b, 0);

        const usersCtx = document.getElementById('usersChart');
        if (usersCtx) {
            const usersChart = new Chart(usersCtx, {
                type: 'doughnut',
                data: {
                    labels: roleLabels,
                    datasets: [{
                        data: roleData,
                        backgroundColor: roleColors.map(c => c.bg),
                        borderColor    : roleColors.map(c => c.border),
                        borderWidth    : 2,
                        hoverOffset    : 8,
                    }]
                },
                options: {
                    cutout     : '72%',
                    responsive : true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => {
                                    const pct = total > 0 ? Math.round(ctx.parsed / total * 100) : 0;
                                    return ` ${ctx.label}: ${ctx.parsed} (${pct}%)`;
                                }
                            }
                        }
                    },
                    animation: { animateScale: true, duration: 700, easing: 'easeOutQuart' },
                }
            });

            // Build custom legend
            const legend = document.getElementById('usersLegend');
            if (legend) {
                roleLabels.forEach((label, i) => {
                    const pct = total > 0 ? Math.round(roleData[i] / total * 100) : 0;
                    const li = document.createElement('li');
                    li.className = 'chart-legend-item';
                    li.innerHTML = `
                        <span class="chart-legend-dot" style="background:${roleColors[i].border}"></span>
                        <span class="chart-legend-text">${label}</span>
                        <span class="chart-legend-val">${roleData[i]}</span>
                        <span class="chart-legend-pct">${pct}%</span>
                    `;
                    legend.appendChild(li);
                });
            }
        }

        // ─────────────────────────────────────────────
        // 2.  REPORTS STACKED BAR CHART
        // ─────────────────────────────────────────────
        const statusMeta = {
            pending     : { label: 'Pending',      color: PALETTE.yellow },
            approved    : { label: 'Approved',     color: PALETTE.green  },
            for_revision: { label: 'For Revision', color: PALETTE.red    },
        };

        const reportDatasets = Object.entries(raw.reportsByStatus).map(([status, values]) => ({
            label          : statusMeta[status]?.label ?? status,
            data           : values,
            backgroundColor: statusMeta[status]?.color.bg     ?? 'rgba(180,180,180,0.7)',
            borderColor    : statusMeta[status]?.color.border  ?? 'rgba(180,180,180,1)',
            borderWidth    : 1.5,
            borderRadius   : 4,
            borderSkipped  : false,
        }));

        const reportsCtx = document.getElementById('reportsChart');
        if (reportsCtx) {
            new Chart(reportsCtx, {
                type: 'bar',
                data: {
                    labels  : raw.reportMonths,
                    datasets: reportDatasets,
                },
                options: {
                    responsive           : true,
                    maintainAspectRatio  : false,
                    interaction          : { mode: 'index', intersect: false },
                    scales: {
                        x: {
                            stacked   : true,
                            grid      : { display: false },
                            ticks     : { color: '#64748b' },
                        },
                        y: {
                            stacked   : true,
                            beginAtZero: true,
                            grid      : { color: 'rgba(100,116,139,0.1)' },
                            ticks     : {
                                color    : '#64748b',
                                stepSize : 1,
                                precision: 0,
                            },
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                title: items => items[0]?.label ?? '',
                                label: ctx  => ` ${ctx.dataset.label}: ${ctx.parsed.y}`,
                            }
                        }
                    },
                    animation: { duration: 700, easing: 'easeOutQuart' },
                }
            });

            // Build custom legend row
            const reportsLegend = document.getElementById('reportsLegend');
            if (reportsLegend) {
                Object.entries(statusMeta).forEach(([, meta]) => {
                    const item = document.createElement('span');
                    item.className = 'chart-legend-item chart-legend-item--inline';
                    item.innerHTML = `<span class="chart-legend-dot" style="background:${meta.color.border}"></span>${meta.label}`;
                    reportsLegend.appendChild(item);
                });
            }
        }

        // ── Auto-submit date range when both dates are filled ────────────
        const form = document.getElementById('chartFilterForm');
        if (form) {
            const fromInput = form.querySelector('[name="chart_from"]');
            const toInput   = form.querySelector('[name="chart_to"]');
            [fromInput, toInput].forEach(el => {
                if (!el) return;
                el.addEventListener('change', () => {
                    if (fromInput.value && toInput.value) {
                        // Clear quick filter when a custom range is set
                        const qBtn = form.querySelector('.chart-quick-btn.active');
                        if (qBtn && qBtn.value !== '') qBtn.classList.remove('active');
                    }
                });
            });
        }
    }());
    </script>
    @endif
@endpush
