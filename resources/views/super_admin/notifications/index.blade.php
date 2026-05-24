@extends('admin.layouts.app')

@section('title', $title)
@section('body_class', 'super-admin-notifications-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/shared-dashboard-theme.css') }}?v={{ filemtime(public_path('css/shared-dashboard-theme.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/shared-navbar.css') }}?v={{ filemtime(public_path('css/shared-navbar.css')) }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body.super-admin-notifications-page {
            background: linear-gradient(135deg, #0d1b2a 0%, #112240 50%, #0a1628 100%);
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
        }

        .notif-shell {
            max-width: 860px;
            margin: 0 auto;
            padding: 32px 24px 48px;
        }

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

        .notif-unread-badge {
            background: linear-gradient(135deg, #4f7cff, #6c63ff);
            color: #fff;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 6px 16px;
            border-radius: 999px;
            white-space: nowrap;
            letter-spacing: 0.03em;
        }

        .notif-toolbar {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .notif-mark-all-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.07);
            color: #94a3b8;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.12);
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            transition: background 0.2s, color 0.2s;
        }

        .notif-mark-all-btn:hover {
            background: rgba(79,124,255,0.18);
            color: #e2e8f0;
            border-color: rgba(79,124,255,0.35);
        }

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
            align-items: center;
            gap: 16px;
            transition: background 0.2s, border-color 0.2s, transform 0.15s;
            position: relative;
            text-decoration: none;
            color: inherit;
        }

        .notif-card:hover {
            background: rgba(255,255,255,0.07);
            border-color: rgba(79, 124, 255, 0.3);
            transform: translateY(-1px);
        }

        .notif-card.unread {
            border-left: 3px solid #4f7cff;
        }

        .notif-type-icon {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .notif-type-icon--URGENT {
            background: rgba(239, 68, 68, 0.18);
            border: 2px solid rgba(239, 68, 68, 0.35);
            color: #f87171;
        }

        .notif-type-icon--REVIEW {
            background: rgba(79, 124, 255, 0.18);
            border: 2px solid rgba(79, 124, 255, 0.35);
            color: #818cf8;
        }

        .notif-type-icon--INFO {
            background: rgba(52, 211, 153, 0.18);
            border: 2px solid rgba(52, 211, 153, 0.35);
            color: #34d399;
        }

        .notif-body {
            flex: 1;
            min-width: 0;
        }

        .notif-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #e2e8f0;
            margin: 0 0 3px;
        }

        .notif-desc {
            font-size: 0.8rem;
            color: #94a3b8;
            margin: 0 0 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .notif-meta {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .notif-time {
            font-size: 0.73rem;
            color: #64748b;
        }

        .notif-type-badge {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 2px 9px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .notif-type-badge--URGENT { background: rgba(239,68,68,0.15); color: #f87171; }
        .notif-type-badge--REVIEW { background: rgba(79,124,255,0.15); color: #818cf8; }
        .notif-type-badge--INFO   { background: rgba(52,211,153,0.15); color: #34d399; }

        .notif-read-pill {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 2px 9px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .notif-read-pill.is-unread { background: rgba(251,191,36,0.15); color: #fbbf24; }
        .notif-read-pill.is-read   { background: rgba(100,116,139,0.15); color: #64748b; }

        .notif-action {
            flex-shrink: 0;
        }

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
            border: none;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
        }

        .notif-view-btn:hover {
            opacity: 0.88;
            transform: scale(1.03);
        }

        .notif-mark-read-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.06);
            color: #94a3b8;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 7px 14px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.1);
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            transition: background 0.2s, color 0.2s;
        }

        .notif-mark-read-btn:hover {
            background: rgba(79,124,255,0.15);
            color: #e2e8f0;
        }

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

        .notif-empty p {
            font-size: 0.9rem;
        }

        .notif-pagination {
            margin-top: 24px;
            display: flex;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .notif-pagination a,
        .notif-pagination span {
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

        .notif-pagination a:hover {
            background: rgba(79,124,255,0.2);
            color: #e2e8f0;
        }

        .notif-pagination span.active-page {
            background: linear-gradient(135deg, #4f7cff, #6c63ff);
            color: #fff;
            border-color: transparent;
        }

        .notif-card-actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 8px;
            flex-shrink: 0;
        }
    </style>
@endpush

@section('content')
    <div class="admin-page">
        <main class="admin-shell">
            <x-topbar :active="'dashboard'" :can-access-audit="$canAccessAudit" :user="$user" />

            <div class="notif-shell">
                {{-- Header --}}
                <div class="notif-header">
                    <div class="notif-header-copy">
                        <h1>Notifications</h1>
                        <p>Summarized alerts for OTP abuse, pending reviews, system issues, and daily report activity.</p>
                    </div>
                    <span class="notif-unread-badge">{{ $unreadCount }} Unread</span>
                </div>

                {{-- Toolbar --}}
                <div class="notif-toolbar">
                    <form method="POST" action="{{ route('super-admin.notifications.mark-all-read') }}">
                        @csrf
                        <button type="submit" class="notif-mark-all-btn">
                            <svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor">
                                <path d="M9 16.17 4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                            </svg>
                            Mark all as read
                        </button>
                    </form>
                </div>

                {{-- Notification List --}}
                <div class="notif-list">
                    @forelse ($notifications as $notification)
                        @php
                            $typeKey = strtoupper($notification->type ?? 'INFO');
                            $typeIcon = match($typeKey) {
                                'URGENT' => '⚠️',
                                'REVIEW' => '🔍',
                                default  => 'ℹ️',
                            };
                            $isUnread = ! $notification->read_status;
                        @endphp

                        <div class="notif-card {{ $isUnread ? 'unread' : '' }}">
                            {{-- Type Icon --}}
                            <div class="notif-type-icon notif-type-icon--{{ $typeKey }}">
                                {{ $typeIcon }}
                            </div>

                            {{-- Body --}}
                            <div class="notif-body">
                                <p class="notif-title">{{ $notification->title }}</p>
                                <p class="notif-desc">{{ $notification->message }}</p>
                                <div class="notif-meta">
                                    <span class="notif-time">
                                        {{ $notification->created_at?->diffForHumans() ?? 'Just now' }}
                                    </span>
                                    <span class="notif-type-badge notif-type-badge--{{ $typeKey }}">
                                        {{ $typeKey }}
                                    </span>
                                    <span class="notif-read-pill {{ $isUnread ? 'is-unread' : 'is-read' }}">
                                        {{ $isUnread ? 'Unread' : 'Read' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="notif-card-actions">
                                @if ($notification->action_url)
                                    <a href="{{ $notification->action_url }}" class="notif-view-btn">
                                        <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor">
                                            <path d="M12 5c5.5 0 9.55 4.03 10.75 6.22a1.45 1.45 0 0 1 0 1.56C21.55 14.97 17.5 19 12 19S2.45 14.97 1.25 12.78a1.45 1.45 0 0 1 0-1.56C2.45 9.03 6.5 5 12 5Zm0 2C7.64 7 4.23 10.02 3.28 12 4.23 13.98 7.64 17 12 17s7.77-3.02 8.72-5C19.77 10.02 16.36 7 12 7Zm0 2.5A2.5 2.5 0 1 1 9.5 12 2.5 2.5 0 0 1 12 9.5Z"/>
                                        </svg>
                                        {{ $notification->action_label ?: 'View Details' }}
                                    </a>
                                @endif

                                @if ($isUnread)
                                    <form method="POST" action="{{ route('super-admin.notifications.mark-read', $notification) }}">
                                        @csrf
                                        <button type="submit" class="notif-mark-read-btn">Mark as read</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="notif-empty">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 22a2.5 2.5 0 0 0 2.45-2h-4.9A2.5 2.5 0 0 0 12 22Zm7-4h-1v-5.1a6 6 0 0 0-4.5-5.82V6a1.5 1.5 0 0 0-3 0v1.08A6 6 0 0 0 6 12.9V18H5a1 1 0 0 0 0 2h14a1 1 0 1 0 0-2Zm-3 0H8v-5.1a4 4 0 1 1 8 0Z"/>
                            </svg>
                            <p>No notifications available right now.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if (method_exists($notifications, 'hasPages') && $notifications->hasPages())
                    <div class="notif-pagination">
                        @foreach ($notifications->links()->elements[0] ?? [] as $page => $url)
                            @if ($page == $notifications->currentPage())
                                <span class="active-page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </main>
    </div>
@endsection
