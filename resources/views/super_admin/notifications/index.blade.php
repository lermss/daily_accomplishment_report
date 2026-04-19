@extends('admin.layouts.app')

@section('title', $title)
@section('body_class', 'admin-reports-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-reports.css') }}?v={{ filemtime(public_path('css/admin-reports.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/shared-dashboard-theme.css') }}?v={{ filemtime(public_path('css/shared-dashboard-theme.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/shared-navbar.css') }}?v={{ filemtime(public_path('css/shared-navbar.css')) }}">
    <style>
        .super-admin-notifications-page .admin-content {
            display: grid;
            gap: 24px;
        }

        .notification-page-hero,
        .notification-page-toolbar,
        .notification-page-card {
            border: 1px solid rgba(209, 220, 232, 0.8);
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.94);
            box-shadow: 0 18px 34px rgba(18, 40, 64, 0.08);
        }

        .notification-page-hero,
        .notification-page-toolbar {
            padding: 24px;
        }

        .notification-page-card {
            padding: 22px 24px;
        }

        .notification-page-hero {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            flex-wrap: wrap;
        }

        .notification-page-hero h1 {
            margin: 0 0 8px;
            color: #17324b;
        }

        .notification-page-hero p,
        .notification-page-toolbar p,
        .notification-card-copy p {
            margin: 0;
            color: #60758b;
            line-height: 1.6;
        }

        .notification-hero-count {
            min-width: 130px;
            padding: 16px 18px;
            border-radius: 18px;
            background: linear-gradient(135deg, #0a3f72, #0c5ea0);
            color: #fff;
            text-align: center;
            box-shadow: 0 18px 30px rgba(10, 63, 114, 0.22);
        }

        .notification-hero-count strong {
            display: block;
            font-size: 2rem;
            line-height: 1;
        }

        .notification-page-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .notification-action-button,
        .notification-secondary-button {
            min-height: 44px;
            padding: 0 16px;
            border-radius: 14px;
            border: 1px solid transparent;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .notification-action-button {
            background: linear-gradient(135deg, #0a3f72, #0c5ea0);
            color: #fff;
            box-shadow: 0 14px 24px rgba(10, 63, 114, 0.2);
        }

        .notification-secondary-button {
            background: #fff;
            border-color: #d3dfeb;
            color: #52667a;
        }

        .notification-action-button:hover,
        .notification-secondary-button:hover {
            transform: translateY(-1px);
        }

        .notification-feed {
            display: grid;
            gap: 18px;
        }

        .notification-page-card {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 18px;
            flex-wrap: wrap;
        }

        .notification-card-main {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            flex: 1 1 520px;
            min-width: 0;
        }

        .notification-type-badge {
            min-width: 96px;
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            text-align: center;
        }

        .notification-type-badge--URGENT {
            background: #fee2e2;
            color: #b91c1c;
        }

        .notification-type-badge--REVIEW {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .notification-type-badge--INFO {
            background: #dcfce7;
            color: #166534;
        }

        .notification-card-copy {
            display: grid;
            gap: 8px;
            min-width: 0;
        }

        .notification-card-copy h2 {
            margin: 0;
            font-size: 1.08rem;
            color: #17324b;
        }

        .notification-card-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            color: #6a7f94;
            font-size: 0.82rem;
        }

        .notification-read-flag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            background: #eef4fb;
            color: #45627d;
            font-size: 0.74rem;
            font-weight: 700;
        }

        .notification-read-flag.is-unread {
            background: #fff4d6;
            color: #b7791f;
        }

        .notification-card-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .notification-empty-state {
            text-align: center;
            padding: 30px 24px;
            color: #60758b;
        }

        .notification-pagination {
            margin-top: 8px;
        }

        @media (max-width: 768px) {
            .notification-page-card {
                padding: 18px;
            }

            .notification-card-main {
                flex-direction: column;
            }
        }
    </style>
@endpush

@section('content')
    <div class="admin-page super-admin-notifications-page">
        <main class="admin-shell">
            <x-topbar :active="'dashboard'" :can-access-audit="$canAccessAudit" :user="$user" />

            <section class="admin-content">
                @if (session('status'))
                    <div class="status-message">{{ session('status') }}</div>
                @endif

                <section class="notification-page-hero">
                    <div>
                        <h1>Super Admin Notifications</h1>
                        <p>Summarized alerts for OTP abuse, pending reviews, system issues, and daily report activity.</p>
                    </div>

                    <div class="notification-hero-count">
                        <strong>{{ $unreadCount }}</strong>
                        <span>Unread</span>
                    </div>
                </section>

                <section class="notification-page-toolbar">
                    <p>Only super admin accounts can access this notification center.</p>

                    <form method="POST" action="{{ route('super-admin.notifications.mark-all-read') }}">
                        @csrf
                        <button type="submit" class="notification-action-button">Mark All As Read</button>
                    </form>
                </section>

                <section class="notification-feed">
                    @forelse ($notifications as $notification)
                        <article class="notification-page-card">
                            <div class="notification-card-main">
                                <span class="notification-type-badge notification-type-badge--{{ $notification->type }}">{{ $notification->type }}</span>

                                <div class="notification-card-copy">
                                    <h2>{{ $notification->title }}</h2>
                                    <p>{{ $notification->message }}</p>
                                    <div class="notification-card-meta">
                                        <span>{{ $notification->created_at?->format('M d, Y h:i A') ?? 'N/A' }}</span>
                                        <span class="notification-read-flag {{ $notification->read_status ? '' : 'is-unread' }}">
                                            {{ $notification->read_status ? 'Read' : 'Unread' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="notification-card-actions">
                                @if ($notification->action_url)
                                    <a href="{{ $notification->action_url }}" class="notification-action-button">
                                        {{ $notification->action_label ?: 'View Details' }}
                                    </a>
                                @endif

                                @if (! $notification->read_status)
                                    <form method="POST" action="{{ route('super-admin.notifications.mark-read', $notification) }}">
                                        @csrf
                                        <button type="submit" class="notification-secondary-button">Mark As Read</button>
                                    </form>
                                @endif
                            </div>
                        </article>
                    @empty
                        <div class="notification-page-card notification-empty-state">
                            No notifications available right now.
                        </div>
                    @endforelse
                </section>

                @if (method_exists($notifications, 'links'))
                    <div class="notification-pagination">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </section>
        </main>
    </div>
@endsection
