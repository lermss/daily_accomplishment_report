
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
        @if ($isSuperAdminNavigation)
            <a href="{{ $reportsRoute }}" class="{{ $active === 'reports' ? 'active' : '' }}">Reports</a>
        @endif
        @if ($canManageReminders)
            <a href="{{ route('admin.dashboard.reminders.index') }}" class="{{ $active === 'reminders' ? 'active' : '' }}">Reminders</a>
        @endif
        @if ($canViewOfficeUsers)
            <a href="{{ route('dashboard.admin.users') }}" class="{{ $active === 'users' ? 'active' : '' }}">Users</a>
        @endif
        @if ($canViewSuperAdminUsers)
            <a href="{{ route('dashboard.users') }}" class="{{ $active === 'users' ? 'active' : '' }}">Users</a>
        @endif
        @if ($canManageAuthenticatorAccess)
            <a href="{{ route('super-admin.authenticator.index') }}" class="{{ $active === 'authenticator' ? 'active' : '' }}">Authenticator Access</a>
        @endif

        @if ($canAccessAudit || $isAdminNavigation)
            <a href="{{ route('audit.index') }}" class="{{ $active === 'audit' ? 'active' : '' }}">Audit Log</a>
        @endif

        <div class="notification-menu" data-notification-menu>
            <button type="button" class="notification-trigger notification-toggle" aria-label="Notifications" aria-expanded="false" data-notification-toggle>
                <svg viewBox="0 0 24 24" aria-hidden="true" width="18" height="18" fill="currentColor">
                    <path d="M12 22a2.5 2.5 0 0 0 2.45-2h-4.9A2.5 2.5 0 0 0 12 22Zm7-4h-1v-5.1a6 6 0 0 0-4.5-5.82V6a1.5 1.5 0 0 0-3 0v1.08A6 6 0 0 0 6 12.9V18H5a1 1 0 0 0 0 2h14a1 1 0 1 0 0-2Zm-3 0H8v-5.1a4 4 0 1 1 8 0Z"/>
                </svg>
                @if ($isSuperAdminNavigation && $superAdminUnreadCount > 0)
                    <span class="notification-badge">{{ $superAdminUnreadCount > 99 ? '99+' : $superAdminUnreadCount }}</span>
                @elseif ($canViewNotifications && $pendingNotificationsCount > 0)
                    <span class="notification-badge">{{ $pendingNotificationsCount > 99 ? '99+' : $pendingNotificationsCount }}</span>
                @elseif ($isStaffNavigation && $staffUnreadCount > 0)
                    <span class="notification-badge">{{ $staffUnreadCount > 99 ? '99+' : $staffUnreadCount }}</span>
                @endif
            </button>

            <div class="notification-panel" data-notification-panel hidden>
                <div class="notification-panel-header">
                    <div>
                        <strong>Notifications</strong>
                        <p class="notification-panel-subtitle">
                            {{ $isSuperAdminNavigation ? 'Summarized alerts for super admin oversight.' : 'Latest report submissions and review alerts.' }}
                        </p>
                    </div>
                    @if ($canViewNotifications || $isSuperAdminNavigation)
                        <a href="{{ $notificationRoute }}">View all</a>
                    @elseif ($isStaffNavigation)
                        @php
                            $staffNotifAllRoute = app(\App\Services\AuthFlowService::class)->staffPortalRoute($user?->role, 'notifications.page');
                        @endphp
                        <a href="{{ route($staffNotifAllRoute) }}">View all</a>
                    @endif
                </div>


                @if ($isSuperAdminNavigation)
                    @if ($superAdminNotifications->isEmpty())
                        <p class="notification-empty">No super admin notifications right now.</p>
                    @else
                        <div class="notification-panel-body">
                            <div class="notification-list">
                                @foreach ($superAdminNotifications as $notification)
                                    <a href="{{ $notification->action_url ?: route('super-admin.notifications.index') }}" class="notification-item {{ $notification->read_status ? 'notification-item--read' : 'notification-item--unread' }}">
                                        <span class="notification-indicator" aria-hidden="true"></span>
                                        <span class="notification-copy">
                                            <span class="notification-title">{{ $notification->title }}</span>
                                            <span class="notification-description">{{ $notification->message }}</span>
                                            <span class="notification-meta">
                                                <small>{{ $notification->created_at?->diffForHumans() ?? 'Just now' }}</small>
                                                <span class="notification-status">{{ $notification->type }}</span>
                                            </span>
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @elseif ($isStaffNavigation)
                    {{-- ── Staff / Intern notification panel ──── --}}
                    @if ($staffNotifications->isEmpty())
                        <p class="notification-empty">No new notifications yet. Reminders and report reviews will appear here.</p>
                    @else
                        <div class="notification-list" style="display:flex;flex-direction:column;gap:10px;">
                            @foreach ($staffNotifications as $notif)
                                <div class="notification-item {{ $notif->type === 'office_reminder' ? 'notification-item--reminder' : ($notif->status === 'approved' ? 'notification-item--approved' : 'notification-item--revision') }}" style="margin-bottom:0;">
                                    <span class="notification-indicator" aria-hidden="true"></span>
                                    <span class="notification-copy">
                                        <span class="notification-icon-label">
                                            @if ($notif->type === 'office_reminder')
                                                🔔
                                            @elseif ($notif->status === 'approved')
                                                ✅
                                            @else
                                                🔄
                                            @endif
                                            <span class="notification-title">{{ $notif->title }}</span>
                                        </span>
                                        <span class="notification-description">{{ $notif->message }}</span>
                                        @if ($notif->comment)
                                            <span class="notification-comment">{{ $notif->comment }}</span>
                                        @endif
                                        <span class="notification-meta">
                                            <small>{{ $notif->time_label }}</small>
                                            <span class="notification-status notification-status--{{ $notif->type === 'office_reminder' ? 'reminder' : $notif->status }}">{{ $notif->type === 'office_reminder' ? 'Reminder' : ucfirst(str_replace('_',' ',$notif->status)) }}</span>
                                        </span>
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                @elseif (! $canViewNotifications)\n                    <p class="notification-empty">Notifications are available for admin and super admin accounts.</p>
                @elseif ($submissionNotifications->isEmpty())
                    <p class="notification-empty">No pending submissions right now.</p>
                @else
                    <div class="notification-panel-body">
                        <div class="notification-list">
                            @foreach ($submissionNotifications as $notification)
                                @php
                                    $isEdit      = isset($notification->notif_type) && $notification->notif_type === 'edit';
                                    $sortTime    = $notification->sort_time ?? $notification->submitted_at ?? $notification->created_at;
                                    $timeLabel   = $sortTime
                                        ? \Illuminate\Support\Carbon::parse($sortTime)->diffForHumans()
                                        : 'Just now';
                                    $readAt      = $user?->notifications_read_at;
                                    $isUnread    = ! $readAt || ($sortTime && \Illuminate\Support\Carbon::parse($sortTime)->greaterThan($readAt));
                                @endphp
                                <a href="{{ $notification->route }}"
                                   class="notification-item {{ $isUnread ? 'notification-item--unread' : 'notification-item--read' }} {{ $isEdit ? 'notification-item--revision' : '' }}">
                                    <span class="notification-indicator" aria-hidden="true"></span>
                                    <span class="notification-copy">
                                        <span class="notification-title">
                                            {{ $isEdit ? '✏️' : '📋' }}
                                            {{ $notification->user_name ?: 'A user' }}
                                        </span>
                                        <span class="notification-description">
                                            @if ($isEdit)
                                                edited "{{ $notification->file_name ?: 'a report' }}"
                                            @else
                                                submitted {{ $notification->file_name ? '"' . $notification->file_name . '"' : 'a report' }}
                                            @endif
                                        </span>
                                        <span class="notification-meta">
                                            <small>{{ $timeLabel }}</small>
                                            @if ($isEdit)
                                                <span class="notification-status notification-status--for_revision">Edited</span>
                                            @else
                                                <span class="notification-status">Pending</span>
                                            @endif
                                        </span>
                                    </span>
                                </a>
                            @endforeach
                        </div>
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
