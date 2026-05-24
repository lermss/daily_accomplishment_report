@extends('admin.layouts.app')

@section('title', $title)
@section('body_class', 'admin-dashboard-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v={{ filemtime(public_path('css/dashboard.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/shared-dashboard-theme.css') }}?v={{ filemtime(public_path('css/shared-dashboard-theme.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/shared-navbar.css') }}?v={{ filemtime(public_path('css/shared-navbar.css')) }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <style>
        /* ── KPI Secondary Cards ─────────────────────────────────── */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
            margin-top: 20px;
        }
        .kpi-card {
            background: #fff;
            border-radius: 18px;
            padding: 20px 22px;
            box-shadow: 0 6px 18px rgba(20,36,60,0.07);
            display: flex;
            align-items: center;
            gap: 16px;
            border: 1px solid rgba(215,228,244,0.7);
            transition: transform .18s ease, box-shadow .18s ease;
        }
        .kpi-card:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(20,36,60,0.12); }
        .kpi-icon-wrap {
            width: 48px; height: 48px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem; flex-shrink: 0;
        }
        .kpi-icon-wrap--blue   { background: rgba(59,130,246,0.12); color: #2563eb; }
        .kpi-icon-wrap--teal   { background: rgba(20,184,166,0.12); color: #0d9488; }
        .kpi-icon-wrap--navy   { background: rgba(30,58,138,0.12);  color: #1e3a8a; }
        .kpi-icon-wrap--rose   { background: rgba(225,29,72,0.12);  color: #be123c; }
        .kpi-icon-wrap--indigo { background: rgba(99,102,241,0.12); color: #4f46e5; }
        .kpi-icon-wrap--amber  { background: rgba(245,158,11,0.12); color: #b45309; }
        .kpi-icon-wrap--green  { background: rgba(16,185,129,0.12); color: #059669; }
        .kpi-copy { min-width: 0; }
        .kpi-label { display: block; font-size: .78rem; color: #64748b; font-weight: 600; letter-spacing: .03em; text-transform: uppercase; }
        .kpi-value { display: block; font-size: 1.7rem; font-weight: 700; color: #17324b; line-height: 1.1; }
        .kpi-meta  { display: block; font-size: .72rem; color: #94a3b8; margin-top: 2px; }

        /* ── Toast Popup (bottom-right) ──────────────────────────── */
        .toast-popup {
            position: fixed;
            bottom: 28px;
            right: 28px;
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 280px;
            max-width: 420px;
            padding: 14px 18px;
            border-radius: 16px;
            font-family: 'Poppins', sans-serif;
            font-size: .93rem;
            font-weight: 500;
            box-shadow: 0 12px 36px rgba(10,40,80,0.18);
            animation: toastIn .35s cubic-bezier(.34,1.56,.64,1) both;
        }
        @keyframes toastIn {
            from { opacity:0; transform: translateY(20px) scale(.96); }
            to   { opacity:1; transform: translateY(0) scale(1); }
        }
        .toast-popup--success { background: linear-gradient(135deg,#10b981,#059669); color:#fff; }
        .toast-popup--error   { background: linear-gradient(135deg,#ef4444,#dc2626); color:#fff; }
        .toast-popup__icon { font-size: 1.1rem; font-weight: 700; flex-shrink:0; }
        .toast-popup__msg  { flex: 1; }
        .toast-popup__close {
            background: rgba(255,255,255,0.25);
            border: 0; border-radius: 8px;
            color: inherit; cursor: pointer;
            width: 26px; height: 26px;
            font-size: 1.1rem; line-height:1;
            display:flex; align-items:center; justify-content:center;
            flex-shrink:0;
        }
        .toast-popup__close:hover { background: rgba(255,255,255,0.38); }

        /* ── KPI section title ───────────────────────────────────── */
        .kpi-section-title {
            margin: 28px 0 10px;
            font-size: 1rem;
            font-weight: 700;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .kpi-section-title::before {
            content: '';
            display: inline-block;
            width: 4px; height: 18px;
            border-radius: 4px;
            background: linear-gradient(180deg,#6c41de,#0c5ea0);
        }
    </style>
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

                {{-- Toast pop-up (bottom-right) for any flash messages --}}
                @if (session('user_status') || session('user_error'))
                    <div class="toast-popup {{ session('user_error') ? 'toast-popup--error' : 'toast-popup--success' }}" id="dashboardToast" role="alert" aria-live="polite">
                        <span class="toast-popup__icon">{{ session('user_error') ? '✕' : '✓' }}</span>
                        <span class="toast-popup__msg">{{ session('user_status') ?? session('user_error') }}</span>
                        <button type="button" class="toast-popup__close" onclick="this.parentElement.remove()" aria-label="Dismiss">×</button>
                    </div>
                @endif

                @if ($mode === 'dashboard')
                    {{-- ═══════════════════════════════════════════════════
                         Primary User Stat Cards (Total / Archived / Active)
                    ═══════════════════════════════════════════════════ --}}
                    @include('admin.partials.dashboard-summary-cards')

                    {{-- ═══════════════════════════════════════════════════
                         Secondary KPI Cards
                    ═══════════════════════════════════════════════════ --}}
                    <p class="kpi-section-title">Workforce &amp; Reports Overview</p>
                    <div class="kpi-grid">
                        @foreach ($kpiCards as $kpi)
                            <div class="kpi-card">
                                <div class="kpi-icon-wrap kpi-icon-wrap--{{ $kpi['tone'] }}">
                                    @if ($kpi['icon'] === 'staff')
                                        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3Zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3Zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5Z"/></svg>
                                    @elseif ($kpi['icon'] === 'intern')
                                        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2Zm-1 14H9V8h2Zm4 0h-2V8h2Z"/></svg>
                                    @elseif ($kpi['icon'] === 'admin')
                                        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M12 1 3 5v6c0 5.25 3.84 10.15 9 11.34C17.16 21.15 21 16.25 21 11V5Zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11Z"/></svg>
                                    @elseif ($kpi['icon'] === 'super')
                                        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M12 2a5 5 0 1 0 0 10A5 5 0 0 0 12 2Zm0 12c-5.33 0-8 2.67-8 4v2h16v-2c0-1.33-2.67-4-8-4Z"/></svg>
                                    @elseif ($kpi['icon'] === 'report')
                                        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Zm2 16H8v-2h8Zm0-4H8v-2h8Zm-3-5V3.5L18.5 9Z"/></svg>
                                    @elseif ($kpi['icon'] === 'pending')
                                        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm1 11h4v2h-6V7h2Z"/></svg>
                                    @elseif ($kpi['icon'] === 'approved')
                                        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="m9.55 17.45-4.5-4.5 1.4-1.4 3.1 3.08 7.99-8 1.42 1.42Z"/></svg>
                                    @elseif ($kpi['icon'] === 'revision')
                                        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M12 6V3L8 7l4 4V8c2.76 0 5 2.24 5 5a5 5 0 0 1-8.66 3.46l-1.42 1.42A7 7 0 1 0 12 6Z"/></svg>
                                    @else
                                        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M12 1 3 5v6c0 5.25 3.84 10.15 9 11.34C17.16 21.15 21 16.25 21 11V5Zm-1 14l-3-3 1.41-1.41L11 12.17l4.59-4.58L17 9Z"/></svg>
                                    @endif
                                </div>
                                <div class="kpi-copy">
                                    <span class="kpi-label">{{ $kpi['label'] }}</span>
                                    <span class="kpi-value">{{ $kpi['count'] }}</span>
                                    <span class="kpi-meta">{{ $kpi['meta'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- ═══════════════════════════════════════════════════
                         Universal Chart Filter Bar
                    ═══════════════════════════════════════════════════ --}}
                    <section class="chart-filter-bar" aria-label="Chart date filter">
                        <form method="GET" action="{{ url()->current() }}" class="chart-filter-form" id="chartFilterForm">
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

                        {{-- PH Admins by Office — Horizontal Bar --}}
                        <div class="chart-card" style="grid-column: 1 / -1;">
                            <div class="chart-card-header">
                                <div class="chart-card-title">
                                    <span class="chart-card-icon chart-card-icon--navy">
                                        <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M12 1 3 5v6c0 5.25 3.84 10.15 9 11.34C17.16 21.15 21 16.25 21 11V5Zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11Z"/></svg>
                                    </span>
                                    <h2>PH Admins per Office</h2>
                                </div>
                                <span class="chart-card-badge">{{ array_sum($phAdminByOffice) }} total PH Admins</span>
                            </div>
                            <div class="chart-card-body" style="min-height:120px;">
                                @if(empty($phAdminByOffice))
                                    <p style="color:#94a3b8;font-size:.875rem;padding:20px 0;">No active PH Admins assigned to offices yet.</p>
                                @else
                                    <canvas id="phAdminOfficeChart" aria-label="PH Admins per Office chart" role="img"></canvas>
                                @endif
                            </div>
                        </div>

                    </section>

                    {{-- Embed chart data for JS --}}
                    <script id="chart-data" type="application/json">
                        {!! json_encode([
                            'usersByRole'     => $chartData['usersByRole'],
                            'reportMonths'    => $chartData['reportMonths'],
                            'reportsByStatus' => $chartData['reportsByStatus'],
                            'phAdminByOffice' => $phAdminByOffice,
                        ], JSON_UNESCAPED_UNICODE) !!}
                    </script>

                @endif

            </section>
        </main>
    </div>

    <x-confirm-modal />

@endsection

@push('scripts')
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
            new Chart(usersCtx, {
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
                data: { labels: raw.reportMonths, datasets: reportDatasets },
                options: {
                    responsive           : true,
                    maintainAspectRatio  : false,
                    interaction          : { mode: 'index', intersect: false },
                    scales: {
                        x: { stacked: true, grid: { display: false }, ticks: { color: '#64748b' } },
                        y: { stacked: true, beginAtZero: true, grid: { color: 'rgba(100,116,139,0.1)' }, ticks: { color: '#64748b', stepSize: 1, precision: 0 } }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: { title: items => items[0]?.label ?? '', label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y}` } }
                    },
                    animation: { duration: 700, easing: 'easeOutQuart' },
                }
            });

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

        // ─────────────────────────────────────────────
        // 3.  PH ADMINS BY OFFICE — HORIZONTAL BAR
        // ─────────────────────────────────────────────
        const phOfficeCtx = document.getElementById('phAdminOfficeChart');
        if (phOfficeCtx && raw.phAdminByOffice && Object.keys(raw.phAdminByOffice).length) {
            const officeLabels = Object.keys(raw.phAdminByOffice);
            const officeData   = Object.values(raw.phAdminByOffice);
            const officeColors = [
                'rgba(30,58,138,0.80)', 'rgba(59,130,246,0.80)',
                'rgba(99,102,241,0.80)', 'rgba(20,184,166,0.80)',
                'rgba(245,158,11,0.80)', 'rgba(239,68,68,0.80)',
            ];
            new Chart(phOfficeCtx, {
                type: 'bar',
                data: {
                    labels: officeLabels,
                    datasets: [{
                        label: 'PH Admins',
                        data: officeData,
                        backgroundColor: officeColors.slice(0, officeLabels.length),
                        borderRadius: 6,
                        borderSkipped: false,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { beginAtZero: true, ticks: { stepSize: 1, precision: 0, color: '#64748b' }, grid: { color: 'rgba(100,116,139,0.1)' } },
                        y: { ticks: { color: '#334155', font: { weight: '600' } }, grid: { display: false } }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: { label: ctx => ` ${ctx.parsed.x} PH Admin${ctx.parsed.x !== 1 ? 's' : ''}` } }
                    },
                    animation: { duration: 700, easing: 'easeOutQuart' },
                }
            });
            // Set canvas height proportional to office count
            phOfficeCtx.style.height = Math.max(80, officeLabels.length * 52) + 'px';
        }

        // Auto-submit date range when both dates are filled
        const form = document.getElementById('chartFilterForm');
        if (form) {
            const fromInput = form.querySelector('[name="chart_from"]');
            const toInput   = form.querySelector('[name="chart_to"]');
            [fromInput, toInput].forEach(el => {
                if (!el) return;
                el.addEventListener('change', () => {
                    if (fromInput.value && toInput.value) {
                        const qBtn = form.querySelector('.chart-quick-btn.active');
                        if (qBtn && qBtn.value !== '') qBtn.classList.remove('active');
                    }
                });
            });
        }

        // Auto-dismiss toast
        const toast = document.getElementById('dashboardToast');
        if (toast) setTimeout(() => toast.remove(), 5000);
    }());
    </script>
    @endif

    {{-- ── Real-time new-submission polling for Super Admin dashboard ──────── --}}
    <script>
    (function () {
        const POLL_URL = '/dashboard/super-admin/poll';
        const INTERVAL = 20000; // every 20 s
        let knownPendingCount = null;
        let knownLatestAt = null;

        function showSuperAdminToast(message) {
            const prev = document.getElementById('sa-poll-toast');
            if (prev) prev.remove();

            const toast = document.createElement('div');
            toast.id = 'sa-poll-toast';
            toast.style.cssText = [
                'position:fixed', 'bottom:28px', 'right:28px', 'z-index:9999',
                'background:linear-gradient(135deg,#10b981,#059669)',
                'color:#fff',
                'border-radius:14px', 'padding:14px 22px', 'font-size:0.92rem',
                'font-weight:600', 'box-shadow:0 8px 28px rgba(0,0,0,0.18)',
                'display:flex', 'align-items:center', 'gap:10px',
                'min-width:260px', 'max-width:400px',
                'animation:saPollIn 0.3s ease'
            ].join(';');

            if (!document.getElementById('sa-poll-kf')) {
                const s = document.createElement('style');
                s.id = 'sa-poll-kf';
                s.textContent = '@keyframes saPollIn{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:none}}';
                document.head.appendChild(s);
            }

            toast.innerHTML = '📋 ' + message
                + ' <a href="/dashboard/admin/pending" style="color:#fff;text-decoration:underline;margin-left:4px;">View</a>'
                + ' <span style="cursor:pointer;opacity:0.7;margin-left:auto;padding-left:8px;" onclick="this.parentElement.remove()">✕</span>';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 8000);
        }

        // Initialise with current state, then start interval
        fetch(POLL_URL, { headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' } })
            .then(r => r.json())
            .then(d => {
                knownPendingCount = d.counts?.pending ?? null;
                knownLatestAt = d.latest_pending_at ?? null;
            })
            .catch(() => {});

        setInterval(async function () {
            try {
                const res = await fetch(POLL_URL, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' }
                });
                if (!res.ok) return;
                const data = await res.json();

                const freshPending = data.counts?.pending ?? 0;
                const freshAt = data.latest_pending_at ?? null;

                const newSubmission = (knownPendingCount !== null && freshPending > knownPendingCount)
                    || (knownLatestAt && freshAt && freshAt !== knownLatestAt);

                if (newSubmission) {
                    showSuperAdminToast('A new report has been submitted!');
                }

                knownPendingCount = freshPending;
                if (freshAt) knownLatestAt = freshAt;
            } catch (_) { /* silently ignore */ }
        }, INTERVAL);
    })();
    </script>
@endpush
