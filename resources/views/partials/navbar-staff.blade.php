@php
    $staffLayoutUserId = session('authenticated_user_id');
    $staffLayoutUser = $staffLayoutUserId ? \App\Models\User::find($staffLayoutUserId) : null;
    // ADD THIS CODE
    $staffPortalPrefix = app(\App\Services\AuthFlowService::class)->staffPortalPrefix($staffLayoutUser?->role);
    $staffPortalLabel = (string) $staffLayoutUser?->role === 'interns' ? 'Intern' : 'Staff';
    $staffNotifications = collect();
    $staffUnreadNotificationsCount = 0;
    $staffHasNotificationsReadColumn = \Illuminate\Support\Facades\Schema::hasColumn('users', 'notifications_read_at');
    $staffReminderService = app(\App\Services\ProvincialReminderService::class);

    if ($staffLayoutUser && in_array((string) $staffLayoutUser->role, ['staff', 'interns', 'special_access'], true)) {
        $staffReportNotifications = \App\Models\Report::query()
            ->where('user_id', $staffLayoutUser->id)
            ->whereIn('status', [\App\Models\Report::STATUS_APPROVED, \App\Models\Report::STATUS_FOR_REVISION])
            ->whereNotNull('reviewed_at')
            ->orderByDesc('reviewed_at')
            ->get(['id', 'file_name', 'status', 'reviewed_at']);

        $staffReminderNotifications = $staffReminderService
            ->reminderNotificationsForStaff($staffLayoutUser)
            ->map(function ($reminder) {
                return (object) [
                    'id' => 'reminder-' . $reminder->id,
                    'route' => null,
                    'file_name' => $reminder->message,
                    'status' => 'reminder',
                    'reviewed_at' => $reminder->triggered_at,
                ];
            });

        $staffNotifications = $staffReportNotifications
            ->map(function ($notification) use ($staffPortalPrefix) {
                $notification->route = route($staffPortalPrefix . '.reports.show', $notification->id);

                return $notification;
            })
            ->concat($staffReminderNotifications)
            ->sortByDesc(fn ($notification) => optional($notification->reviewed_at)->timestamp ?? 0)
            ->take(20)
            ->values();

        $staffUnreadNotificationsCount = \App\Models\Report::query()
            ->where('user_id', $staffLayoutUser->id)
            ->whereIn('status', [\App\Models\Report::STATUS_APPROVED, \App\Models\Report::STATUS_FOR_REVISION])
            ->whereNotNull('reviewed_at')
            ->when(
                $staffHasNotificationsReadColumn && $staffLayoutUser->notifications_read_at,
                fn ($query) => $query->where('reviewed_at', '>', $staffLayoutUser->notifications_read_at)
            )
            ->count();

        $staffUnreadNotificationsCount += $staffReminderService->unreadReminderCountForStaff($staffLayoutUser);
    }
@endphp

<div class="navbar">
    <div class="nav-left">
        <div class="logos">
            <img src="{{ asset('images/dict_logo.png') }}" alt="DICT Logo">
            <img src="{{ asset('images/bagong_pilipinas.png') }}" alt="Bagong Pilipinas Logo">
        </div>
    </div>

    <div class="nav-right">
        <a href="{{ route($staffPortalPrefix . '.home') }}" class="{{ request()->routeIs('staff.home') || request()->routeIs('intern.home') ? 'active' : '' }}">
            Home
        </a>
        <a href="{{ route($staffPortalPrefix . '.dashboard') }}" class="{{ request()->routeIs('staff.dashboard') || request()->routeIs('intern.dashboard') || request()->routeIs('dashboard.staff') || request()->routeIs('dashboard.intern') ? 'active' : '' }}">
            Dashboard
        </a>
        <a href="{{ route($staffPortalPrefix . '.reports') }}" class="{{ request()->routeIs('staff.reports') || request()->routeIs('staff.reports.*') || request()->routeIs('intern.reports') || request()->routeIs('intern.reports.*') ? 'active' : '' }}">
            Reports
        </a>
        @if ((string) $staffLayoutUser?->role === 'interns')
            {{-- // ADD THIS CODE --}}
            <a href="{{ route('intern.audit.index') }}" class="{{ request()->routeIs('intern.audit.index') ? 'active' : '' }}">
                Audit Log
            </a>
        @endif
        <div class="notification-menu" data-notification-menu>
            <button
                type="button"
                class="notification-trigger notification-toggle"
                aria-label="View notifications"
                aria-haspopup="true"
                aria-expanded="false"
                data-notification-toggle
                id="staffNotificationsTrigger"
            >
                <i class="fa-regular fa-bell"></i>
                @if ($staffUnreadNotificationsCount > 0)
                    <span class="notification-badge" id="staffNotificationBadge">
                        {{ $staffUnreadNotificationsCount > 99 ? '99+' : $staffUnreadNotificationsCount }}
                    </span>
                @endif
            </button>

            <div class="notification-panel" data-notification-panel hidden>
                <div class="notification-panel-header">
                    <div>
                        <strong>Notifications</strong>
                        {{-- // ADD THIS CODE --}}
                        <p class="notification-panel-subtitle">Latest {{ strtolower($staffPortalLabel) }} report submissions and review alerts.</p>
                    </div>
                    <a href="{{ route($staffPortalPrefix . '.dashboard') }}">View all</a>
                </div>
                <div class="notification-panel-body" id="staffNotificationsList">
                    @forelse ($staffNotifications as $notification)
                        <a href="{{ $notification->route ?: route($staffPortalPrefix . '.dashboard') }}"
                           class="notification-item {{ $notification->status === 'reminder' ? 'notification-item--reminder' : ($notification->status === \App\Models\Report::STATUS_APPROVED ? 'notification-item--approved' : 'notification-item--revision') }} staff-notification-item-link"
                           data-notification-id="{{ $notification->id }}">
                            <span class="notification-indicator" aria-hidden="true"></span>
                            <span class="notification-copy">
                                <span class="notification-title">
                                    @if ($notification->status === 'reminder')
                                        🔔 Office Reminder
                                    @elseif ($notification->status === \App\Models\Report::STATUS_APPROVED)
                                        ✅ Report Approved
                                    @else
                                        🔄 Needs Revision
                                    @endif
                                </span>
                                <span class="notification-description">{{ $notification->file_name ?: 'Untitled' }}</span>
                                <span class="notification-meta">
                                    <small>{{ optional($notification->reviewed_at)->format('M d, Y h:i A') }}</small>
                                    <span class="notification-status {{ $notification->status === \App\Models\Report::STATUS_APPROVED ? 'notification-status--success' : ($notification->status === 'reminder' ? 'notification-status--reminder' : 'notification-status--warning') }}">
                                        {{ $notification->status === \App\Models\Report::STATUS_APPROVED ? 'Approved' : ($notification->status === 'reminder' ? 'Reminder' : 'Needs Revision') }}
                                    </span>
                                </span>
                            </span>
                        </a>
                    @empty
                        <p class="notification-empty" id="staffNotificationsEmpty">No notifications yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <a href="{{ route($staffPortalPrefix . '.profile') }}" aria-label="Profile">
            <i class="fa-regular fa-user"></i>
        </a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleButton = document.querySelector('[data-notification-toggle]');
        const notificationPanel = document.querySelector('[data-notification-panel]');
        const notificationsList = document.getElementById('staffNotificationsList');
        const notificationBadge = document.getElementById('staffNotificationBadge');
        const notificationWrapper = toggleButton?.closest('.notification-menu');

        if (!toggleButton || !notificationPanel || !notificationsList) {
            return;
        }

        const emptyMarkup = '<p class="notification-empty" id="staffNotificationsEmpty">No report notifications yet.</p>';

        const updateBadge = (count) => {
            if (!notificationBadge) {
                return;
            }

            if (count > 0) {
                notificationBadge.textContent = count > 99 ? '99+' : String(count);
                notificationBadge.hidden = false;
                return;
            }

            notificationBadge.hidden = true;
        };

        const renderNotifications = (notifications) => {
            if (!Array.isArray(notifications) || notifications.length === 0) {
                notificationsList.innerHTML = emptyMarkup;
                return;
            }

            notificationsList.innerHTML = notifications.map((notification) => {
                const isApproved  = notification.status === 'approved';
                const isReminder  = notification.status === 'reminder' || notification.type === 'office_reminder';
                const isRevision  = !isApproved && !isReminder;

                const itemClass   = isReminder ? 'notification-item--reminder'
                                  : isApproved ? 'notification-item--approved'
                                  : 'notification-item--revision';
                const statusClass = isApproved  ? 'notification-status--success'
                                  : isReminder  ? 'notification-status--reminder'
                                  : 'notification-status--warning';
                const statusLabel = isApproved  ? 'Approved'
                                  : isReminder  ? 'Reminder'
                                  : 'Needs Revision';
                const titleIcon   = isReminder  ? '🔔 Office Reminder'
                                  : isApproved  ? '✅ Report Approved'
                                  : '🔄 Needs Revision';
                const description = isReminder
                                  ? (notification.message || 'Reminder from your Provincial Head')
                                  : (notification.file_name || 'Untitled report');
                const timeLabel   = notification.reviewed_at ? `<small>${notification.reviewed_at}</small>` : '<small>Just now</small>';

                return `
                    <a href="${notification.route || '#'}" class="notification-item ${itemClass} staff-notification-item-link" data-notification-id="${notification.id}">
                        <span class="notification-indicator" aria-hidden="true"></span>
                        <span class="notification-copy">
                            <span class="notification-title">${titleIcon}</span>
                            <span class="notification-description">${description}</span>
                            <span class="notification-meta">
                                ${timeLabel}
                                <span class="notification-status ${statusClass}">${statusLabel}</span>
                            </span>
                        </span>
                    </a>
                `;
            }).join('');

            // Add click listeners to the new notification links
            addNotificationClickListeners();
        };

        const fetchNotifications = async () => {
            try {
                // ADD THIS CODE
                const response = await fetch("{{ route($staffPortalPrefix . '.notifications.index') }}", {
                    headers: {
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    return null;
                }

                return await response.json();
            } catch (error) {
                console.error('Unable to load notifications.', error);
                return null;
            }
        };

        const markNotificationsRead = async () => {
            try {
                // ADD THIS CODE
                const response = await fetch("{{ route($staffPortalPrefix . '.notifications.read') }}", {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({})
                });

                if (!response.ok) {
                    return;
                }

                const payload = await response.json();
                updateBadge(payload.unread_count ?? 0);
            } catch (error) {
                console.error('Unable to mark notifications as read.', error);
            }
        };

        // Handle notification link clicks
        const handleNotificationClick = async (event) => {
            event.preventDefault();
            const link = event.currentTarget;
            const href = link.getAttribute('href');

            // Mark notifications as read
            await markNotificationsRead();

            // Redirect to the notification's page
            window.location.href = href;
        };

        // Add click listeners to notification links
        const addNotificationClickListeners = () => {
            document.querySelectorAll('.staff-notification-item-link').forEach(link => {
                link.addEventListener('click', handleNotificationClick);
            });
        };

        const openPanel = async () => {
            notificationPanel.hidden = false;
            toggleButton.setAttribute('aria-expanded', 'true');

            const payload = await fetchNotifications();
            if (payload?.notifications) {
                renderNotifications(payload.notifications);
                updateBadge(payload.unread_count ?? 0);
            }

            await markNotificationsRead();
        };

        const closePanel = () => {
            notificationPanel.hidden = true;
            toggleButton.setAttribute('aria-expanded', 'false');
        };

        toggleButton.addEventListener('click', async (event) => {
            event.stopPropagation();

            if (notificationPanel.hidden) {
                await openPanel();
                return;
            }

            closePanel();
        });

        document.addEventListener('click', (event) => {
            if (!notificationWrapper || notificationWrapper.contains(event.target)) {
                return;
            }
            closePanel();
        });

        // Add click listeners to initial notification links
        addNotificationClickListeners();
    });
</script>
