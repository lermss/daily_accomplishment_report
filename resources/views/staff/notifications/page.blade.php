@extends('staff.layouts.app')

@section('title', 'All Notifications')
@section('body_class', 'staff-notifications-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/shared-navbar.css') }}?v={{ filemtime(public_path('css/shared-navbar.css')) }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body.staff-notifications-page {
            background: linear-gradient(135deg, #0d1b2a 0%, #112240 50%, #0a1628 100%);
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
        }

        /* Override the staff layout container so we get full-width dark bg */
        body.staff-notifications-page .container {
            max-width: 100%;
            padding: 0;
            margin: 0;
        }

        .notif-shell {
            max-width: 860px;
            margin: 0 auto;
            padding: 32px 24px 60px;
        }

        /* ── Header ── */
        .notif-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            gap: 16px;
            flex-wrap: wrap;
        }

        .notif-header-copy h1 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #e2e8f0;
            margin: 0 0 4px;
        }

        .notif-header-copy p {
            font-size: 0.82rem;
            color: #64748b;
            margin: 0;
        }

        .notif-count-badge {
            background: linear-gradient(135deg, #4f7cff, #6c63ff);
            color: #fff;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 6px 18px;
            border-radius: 999px;
            white-space: nowrap;
            letter-spacing: 0.03em;
        }

        /* ── Filter toolbar ── */
        .notif-toolbar {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            padding: 14px 18px;
            margin-bottom: 24px;
        }

        .notif-toolbar label {
            font-size: 0.78rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .notif-toolbar select {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 999px;
            color: #cbd5e1;
            font-family: 'Poppins', sans-serif;
            font-size: 0.83rem;
            padding: 7px 16px;
            cursor: pointer;
            outline: none;
            transition: border-color 0.2s;
            appearance: auto;
        }

        .notif-toolbar select:focus {
            border-color: rgba(79,124,255,0.5);
        }

        .notif-toolbar select option {
            background: #112240;
            color: #cbd5e1;
        }

        .notif-clear-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 999px;
            color: #94a3b8;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 7px 16px;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
        }

        .notif-clear-btn:hover {
            background: rgba(248,113,113,0.15);
            color: #f87171;
            border-color: rgba(248,113,113,0.3);
        }

        /* ── Notification list ── */
        .notif-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .notif-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.09);
            border-radius: 14px;
            padding: 18px 22px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            text-decoration: none;
            color: inherit;
            transition: background 0.2s, border-color 0.2s, transform 0.15s;
            position: relative;
        }

        .notif-card:hover {
            background: rgba(255,255,255,0.07);
            border-color: rgba(79,124,255,0.3);
            transform: translateY(-1px);
        }

        .notif-card--approved  { border-left: 3px solid #22c55e; }
        .notif-card--revision  { border-left: 3px solid #f59e0b; }
        .notif-card--reminder  { border-left: 3px solid #6366f1; }

        /* Icon circle */
        .notif-icon {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        .notif-icon--approved { background: rgba(34,197,94,0.15);  border: 2px solid rgba(34,197,94,0.3); }
        .notif-icon--revision { background: rgba(245,158,11,0.15); border: 2px solid rgba(245,158,11,0.3); }
        .notif-icon--reminder { background: rgba(99,102,241,0.15); border: 2px solid rgba(99,102,241,0.3); }

        /* Body */
        .notif-body { flex: 1; min-width: 0; }

        .notif-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #e2e8f0;
            margin: 0 0 3px;
            display: block;
        }

        .notif-desc {
            font-size: 0.8rem;
            color: #94a3b8;
            margin: 0 0 4px;
            display: block;
            overflow-wrap: anywhere;
        }

        .notif-comment {
            display: block;
            margin: 4px 0 6px;
            padding: 6px 10px;
            border-radius: 8px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            font-size: 0.76rem;
            color: #94a3b8;
            font-style: italic;
            line-height: 1.5;
        }

        .notif-meta {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 6px;
        }

        .notif-time { font-size: 0.73rem; color: #64748b; }

        .notif-badge {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 2px 10px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .notif-badge--approved   { background: rgba(34,197,94,0.15);  color: #4ade80; }
        .notif-badge--for_revision { background: rgba(245,158,11,0.15); color: #fbbf24; }
        .notif-badge--reminder   { background: rgba(99,102,241,0.15); color: #a5b4fc; }

        /* Action button */
        .notif-action { flex-shrink: 0; align-self: center; }

        .notif-view-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #4f7cff, #6c63ff);
            color: #fff;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 999px;
            text-decoration: none;
            transition: opacity 0.2s, transform 0.15s;
            white-space: nowrap;
        }

        .notif-view-btn:hover {
            opacity: 0.88;
            transform: scale(1.03);
            color: #fff;
        }

        /* Empty state */
        .notif-empty {
            text-align: center;
            padding: 64px 24px;
            color: #475569;
        }

        .notif-empty svg {
            width: 56px;
            height: 56px;
            margin: 0 auto 16px;
            display: block;
            opacity: 0.35;
        }

        .notif-empty p { font-size: 0.9rem; }

        /* ── Pagination ── */
        .notif-pagination {
            margin-top: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            padding: 18px 4px;
        }

        .notif-pagination__info {
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 500;
        }

        .notif-pagination__links {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .np-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 500;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.09);
            color: #94a3b8;
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
        }

        .np-btn:hover {
            background: rgba(79,124,255,0.2);
            color: #e2e8f0;
        }

        .np-btn--active {
            background: linear-gradient(135deg, #4f7cff, #6c63ff);
            color: #fff;
            border-color: transparent;
            cursor: default;
        }

        .np-btn--active:hover {
            background: linear-gradient(135deg, #4f7cff, #6c63ff);
            color: #fff;
        }

        .np-btn--disabled {
            opacity: 0.35;
            cursor: not-allowed;
            pointer-events: none;
        }

        @media (max-width: 600px) {
            .notif-card { flex-direction: column; }
            .notif-action { align-self: flex-start; }
            .notif-header { flex-direction: column; align-items: flex-start; }
        }
    </style>
@endpush

@section('full_width_content')
    <div class="notif-shell">

        {{-- Header --}}
        <div class="notif-header">
            <div class="notif-header-copy">
                <h1>Notifications</h1>
                <p>Your report reviews and office reminders — filtered and paginated.</p>
            </div>
            <span class="notif-count-badge">{{ $paginator->total() }} Total</span>
        </div>

        {{-- Filter toolbar --}}
        <form method="GET" action="{{ url()->current() }}" class="notif-toolbar" id="notifFilterForm">
            <label for="notifType">Type</label>
            <select name="type" id="notifType" onchange="document.getElementById('notifFilterForm').submit()">
                <option value=""               {{ !$typeFilter || $typeFilter === 'all' ? 'selected' : '' }}>All Types</option>
                <option value="report_review"  {{ $typeFilter === 'report_review'   ? 'selected' : '' }}>Report Reviews</option>
                <option value="office_reminder"{{ $typeFilter === 'office_reminder' ? 'selected' : '' }}>Reminders</option>
            </select>

            <label for="notifStatus">Status</label>
            <select name="status" id="notifStatus" onchange="document.getElementById('notifFilterForm').submit()">
                <option value=""              {{ !$statusFilter || $statusFilter === 'all' ? 'selected' : '' }}>All Status</option>
                <option value="approved"      {{ $statusFilter === 'approved'     ? 'selected' : '' }}>Approved</option>
                <option value="for_revision"  {{ $statusFilter === 'for_revision' ? 'selected' : '' }}>For Revision</option>
            </select>

            @if($typeFilter || $statusFilter)
                <a href="{{ url()->current() }}" class="notif-clear-btn">
                    <svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor"><path d="M19 6.41 17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                    Clear filters
                </a>
            @endif
        </form>

        {{-- Notification list --}}
        @if ($paginator->isEmpty())
            <div class="notif-empty">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 22a2.5 2.5 0 0 0 2.45-2h-4.9A2.5 2.5 0 0 0 12 22Zm7-4h-1v-5.1a6 6 0 0 0-4.5-5.82V6a1.5 1.5 0 0 0-3 0v1.08A6 6 0 0 0 6 12.9V18H5a1 1 0 0 0 0 2h14a1 1 0 1 0 0-2Zm-3 0H8v-5.1a4 4 0 1 1 8 0Z"/>
                </svg>
                <p>No notifications match the selected filters.</p>
            </div>
        @else
            <div class="notif-list">
                @foreach ($paginator as $notif)
                    @php
                        $isReminder = $notif->type === 'office_reminder';
                        $isApproved = !$isReminder && $notif->status === 'approved';
                        $isRevision = !$isReminder && !$isApproved;

                        $cardClass  = $isReminder ? 'notif-card--reminder'
                                    : ($isApproved ? 'notif-card--approved' : 'notif-card--revision');
                        $iconClass  = $isReminder ? 'notif-icon--reminder'
                                    : ($isApproved ? 'notif-icon--approved' : 'notif-icon--revision');
                        $emoji      = $isReminder ? '🔔' : ($isApproved ? '✅' : '🔄');

                        $badgeClass = 'notif-badge--' . ($isReminder ? 'reminder' : $notif->status);
                        $badgeLabel = $isReminder ? 'Reminder' : ucfirst(str_replace('_', ' ', $notif->status));

                        $titleText  = $isReminder ? 'Office Reminder'
                                    : ($isApproved ? 'Report Approved' : 'Needs Revision');
                    @endphp

                    <a href="{{ $notif->route }}" class="notif-card {{ $cardClass }}">
                        {{-- Icon --}}
                        <div class="notif-icon {{ $iconClass }}">{{ $emoji }}</div>

                        {{-- Body --}}
                        <div class="notif-body">
                            <span class="notif-title">{{ $titleText }}</span>
                            <span class="notif-desc">{{ $notif->message }}</span>
                            @if($notif->comment)
                                <span class="notif-comment">"{{ $notif->comment }}"</span>
                            @endif
                            <div class="notif-meta">
                                <span class="notif-time">{{ $notif->time_label }}</span>
                                <span class="notif-badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                            </div>
                        </div>

                        {{-- Action (only for report reviews) --}}
                        @if (!$isReminder)
                            <div class="notif-action">
                                <span class="notif-view-btn">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor">
                                        <path d="M12 5c5.5 0 9.55 4.03 10.75 6.22a1.45 1.45 0 0 1 0 1.56C21.55 14.97 17.5 19 12 19S2.45 14.97 1.25 12.78a1.45 1.45 0 0 1 0-1.56C2.45 9.03 6.5 5 12 5Zm0 2C7.64 7 4.23 10.02 3.28 12 4.23 13.98 7.64 17 12 17s7.77-3.02 8.72-5C19.77 10.02 16.36 7 12 7Zm0 2.5A2.5 2.5 0 1 1 9.5 12 2.5 2.5 0 0 1 12 9.5Z"/>
                                    </svg>
                                    View Report
                                </span>
                            </div>
                        @endif
                    </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if ($paginator->hasPages())
                <div class="notif-pagination">
                    <div class="notif-pagination__info">
                        Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }} notifications
                    </div>
                    <div class="notif-pagination__links">
                        @if ($paginator->onFirstPage())
                            <span class="np-btn np-btn--disabled">&laquo;</span>
                        @else
                            <a href="{{ $paginator->previousPageUrl() }}" class="np-btn">&laquo;</a>
                        @endif

                        @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="np-btn np-btn--active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="np-btn">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($paginator->hasMorePages())
                            <a href="{{ $paginator->nextPageUrl() }}" class="np-btn">&raquo;</a>
                        @else
                            <span class="np-btn np-btn--disabled">&raquo;</span>
                        @endif
                    </div>
                </div>
            @endif
        @endif

    </div>
@endsection
