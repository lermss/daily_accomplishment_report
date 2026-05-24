@props(['notifications', 'notificationRoute', 'canViewNotifications', 'isSuperAdminNavigation'])

<div class="notification-panel" data-notification-panel hidden>
    <div class="notification-panel-header">
        <div>
            <strong>Notifications</strong>
            <p class="notification-panel-subtitle">Latest report submissions and review alerts.</p>
        </div>
        @if ($canViewNotifications)
            <a href="{{ $notificationRoute }}">View all</a>
        @endif
    </div>

    @if (! $canViewNotifications)
        <p class="notification-empty">Notifications are available for admin and super admin accounts.</p>
    @elseif (count($notifications) === 0)
        <p class="notification-empty">No pending submissions right now.</p>
    @else
        <div class="notification-list">
            @foreach ($notifications as $notification)
                <a
                    href="{{ $notification['route'] }}"
                    class="notification-item {{ $notification['is_unread'] ? 'notification-item--unread' : 'notification-item--read' }}"
                    data-notification-item
                    data-notification-url="{{ $notification['route'] }}"
                    data-notification-id="{{ $notification['id'] }}"
                >
                    <span class="notification-indicator" aria-hidden="true"></span>
                    <div class="notification-copy">
                        <div class="notification-title">{{ $notification['title'] }}</div>
                        <div class="notification-description">{{ $notification['description'] }}</div>
                        <div class="notification-meta">
                            <small>{{ $notification['timestamp'] }}</small>
                            @if ($notification['is_unread'])
                                <span class="notification-status">New</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
