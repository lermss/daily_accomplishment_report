@php
    $staffLayoutUserId = session('authenticated_user_id');
    $staffLayoutUser = $staffLayoutUserId ? \App\Models\User::find($staffLayoutUserId) : null;
    $staffNotifications = collect();
    $staffUnreadNotificationsCount = 0;
    $staffHasNotificationsReadColumn = \Illuminate\Support\Facades\Schema::hasColumn('users', 'notifications_read_at');

    if ($staffLayoutUser && in_array((string) $staffLayoutUser->role, ['staff', 'special_access'], true)) {
        $staffNotifications = \App\Models\Report::query()
            ->where('user_id', $staffLayoutUser->id)
            ->whereIn('status', [\App\Models\Report::STATUS_APPROVED, \App\Models\Report::STATUS_FOR_REVISION])
            ->whereNotNull('reviewed_at')
            ->orderByDesc('reviewed_at')
            ->limit(10)
            ->get(['id', 'file_name', 'status', 'reviewed_at']);

        $staffUnreadNotificationsCount = \App\Models\Report::query()
            ->where('user_id', $staffLayoutUser->id)
            ->whereIn('status', [\App\Models\Report::STATUS_APPROVED, \App\Models\Report::STATUS_FOR_REVISION])
            ->whereNotNull('reviewed_at')
            ->when(
                $staffHasNotificationsReadColumn && $staffLayoutUser->notifications_read_at,
                fn ($query) => $query->where('reviewed_at', '>', $staffLayoutUser->notifications_read_at)
            )
            ->count();
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
        <a href="{{ route('staff.home') }}" class="{{ request()->routeIs('staff.home') ? 'active' : '' }}">
            Home
        </a>
        <a href="{{ route('staff.dashboard') }}" class="{{ request()->routeIs('staff.dashboard') || request()->routeIs('dashboard.staff') ? 'active' : '' }}">
            Dashboard
        </a>
        <a href="{{ route('staff.reports') }}" class="{{ request()->routeIs('staff.reports') || request()->routeIs('staff.reports.*') ? 'active' : '' }}">
            Reports
        </a>
        <div class="notification-wrapper position-relative">
            <button
                type="button"
                class="notification-trigger border-0 bg-transparent p-0"
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

            <div class="notification-panel position-absolute end-0 mt-2 shadow border rounded bg-white" data-notification-panel hidden style="min-width: 320px; max-width: 420px; z-index: 1050;">
                <div class="notification-panel-header d-flex justify-content-between align-items-center py-2 border-bottom">
                    <strong class="m-0">Notifications</strong>
                    <a href="{{ route('staff.dashboard') }}" class="text-decoration-none small">View all</a>
                </div>
                <div class="notification-panel-body " id="staffNotificationsList">
                    @forelse ($staffNotifications as $notification)
                        <a href="{{ route('staff.reports.show', $notification->id) }}" class="staff-notification-item-link text-decoration-none" data-notification-id="{{ $notification->id }}">
                            <div class="staff-notification-item ">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div>
                                        <div class="fw-semibold">
                                            {{ $notification->status === \App\Models\Report::STATUS_APPROVED ? 'Your report has been approved' : 'Your report needs revision' }}
                                        </div>
                                        <div class="text-muted small">{{ $notification->file_name ?: 'Untitled report' }}</div>
                                        <div class="text-muted small">{{ optional($notification->reviewed_at)->format('M d, Y h:i A') }}</div>
                                    </div>
                                    <span class="staff-notification-status {{ $notification->status }}">
                                        {{ $notification->status === \App\Models\Report::STATUS_APPROVED ? 'Approved' : 'Needs Revision' }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <p class="text-muted mb-0" id="staffNotificationsEmpty">No report notifications yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <a href="{{ route('staff.profile') }}" aria-label="Profile">
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
        const notificationWrapper = toggleButton?.closest('.notification-wrapper');

        if (!toggleButton || !notificationPanel || !notificationsList) {
            return;
        }

        const emptyMarkup = '<p class="text-muted mb-0" id="staffNotificationsEmpty">No report notifications yet.</p>';

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
                const statusClass = notification.status === 'approved' ? 'approved' : 'for_revision';
                const statusLabel = notification.status === 'approved' ? 'Approved' : 'Needs Revision';
                const reviewedAt = notification.reviewed_at ? `<div class="text-muted small">${notification.reviewed_at}</div>` : '';

                return `
                    <a href="${notification.route}" class="staff-notification-item-link text-decoration-none" data-notification-id="${notification.id}">
                        <div class="staff-notification-item mb-2">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <div class="fw-semibold">${notification.message}</div>
                                    <div class="text-muted small">${notification.file_name || 'Untitled report'}</div>
                                    ${reviewedAt}
                                </div>
                                <span class="staff-notification-status ${statusClass}">${statusLabel}</span>
                            </div>
                        </div>
                    </a>
                `;
            }).join('');

            // Add click listeners to the new notification links
            addNotificationClickListeners();
        };

        const fetchNotifications = async () => {
            try {
                const response = await fetch("{{ route('staff.notifications.index') }}", {
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
                const response = await fetch("{{ route('staff.notifications.read') }}", {
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
