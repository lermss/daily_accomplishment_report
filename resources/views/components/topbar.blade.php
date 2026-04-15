
<header class="navbar">
    <div class="nav-left">
        <div class="logos">
            <img src="{{ asset('images/dict_logo.png') }}" alt="DICT Logo">
            <img src="{{ asset('images/bagong_pilipinas.png') }}" alt="Bagong Pilipinas Logo">
        </div>
    </div>
    <nav class="nav-right" aria-label="Primary">
        <a href="{{ route('dashboard.home') }}" class="{{ $active === 'home' ? 'active' : '' }}">Home</a>
        <a href="{{ route('dashboard') }}" class="{{ $active === 'dashboard' ? 'active' : '' }}">Dashboard</a>
        <a href="{{ $reportsRoute }}" class="{{ $active === 'reports' ? 'active' : '' }}">Reports</a>

        @if ($canAccessAudit || $isAdminNavigation)
            <a href="{{ route('audit.index') }}" class="{{ $active === 'audit' ? 'active' : '' }}">Audit Log</a>
        @endif

        <div class="notification-menu" data-notification-menu>
            <button type="button" class="notification-trigger notification-toggle" aria-label="Notifications" aria-expanded="false" data-notification-toggle>
                <svg viewBox="0 0 24 24" aria-hidden="true" width="18" height="18" fill="currentColor">
                    <path d="M12 22a2.5 2.5 0 0 0 2.45-2h-4.9A2.5 2.5 0 0 0 12 22Zm7-4h-1v-5.1a6 6 0 0 0-4.5-5.82V6a1.5 1.5 0 0 0-3 0v1.08A6 6 0 0 0 6 12.9V18H5a1 1 0 0 0 0 2h14a1 1 0 1 0 0-2Zm-3 0H8v-5.1a4 4 0 1 1 8 0Z"/>
                </svg>
                @if ($canViewNotifications && $pendingNotificationsCount > 0)
                    <span class="notification-badge">{{ $pendingNotificationsCount > 99 ? '99+' : $pendingNotificationsCount }}</span>
                @endif
            </button>

            <div class="notification-panel" data-notification-panel hidden>
                <div class="notification-panel-header">
                    <strong>Submission Alerts</strong>
                    @if ($canViewNotifications)
                        <a href="{{ $notificationRoute }}">View all</a>
                    @endif
                </div>

                @if (! $canViewNotifications)
                    <p class="notification-empty">Notifications are available for admin and super admin accounts.</p>
                @elseif ($submissionNotifications->isEmpty())
                    <p class="notification-empty">No pending submissions right now.</p>
                @else
                    <div class="notification-list">
                        @foreach ($submissionNotifications as $notification)
                            @php
                                $submittedAt = $notification->submitted_at ?: $notification->created_at;
                                $submissionLabel = $isSuperAdminNavigation
                                    ? 'submitted a report'
                                    : 'submitted ' . ($notification->file_name ?: 'a report file');
                            @endphp
                            <a href="{{ $notificationRoute }}" class="notification-item">
                                <span class="notification-dot" aria-hidden="true"></span>
                                <span class="notification-copy">
                                    <strong>{{ $notification->user_name ?: 'A user' }}</strong>
                                    <span>{{ $submissionLabel }}</span>
                                    <small>
                                        {{ $submittedAt ? \Illuminate\Support\Carbon::parse($submittedAt)->diffForHumans() : 'Just now' }}
                                    </small>
                                </span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <a href="{{ route('profile.edit') }}" aria-label="Edit profile">
            <svg viewBox="0 0 24 24" aria-hidden="true" width="18" height="18" fill="currentColor">
                <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm0 2c-4.42 0-8 2.24-8 5a1 1 0 0 0 2 0c0-1.45 2.61-3 6-3s6 1.55 6 3a1 1 0 0 0 2 0c0-2.76-3.58-5-8-5Zm0-11a2 2 0 1 1-2 2 2 2 0 0 1 2-2Z"/>
            </svg>
        </a>
    </nav>
    </div>
</header>
<script src="{{ asset('js/topbar.js') }}" defer></script>
