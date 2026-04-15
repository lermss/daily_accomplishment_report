@props(['type' => 'info', 'dismissible' => true, 'autoDismiss' => 5000])

@php
    $colors = [
        'success' => ['bg' => 'alert-success', 'icon' => 'bi-check-circle', 'title' => 'Success'],
        'error' => ['bg' => 'alert-danger', 'icon' => 'bi-exclamation-circle', 'title' => 'Error'],
        'warning' => ['bg' => 'alert-warning', 'icon' => 'bi-exclamation-triangle', 'title' => 'Warning'],
        'info' => ['bg' => 'alert-info', 'icon' => 'bi-info-circle', 'title' => 'Info'],
    ];

    $config = $colors[$type] ?? $colors['info'];

    $alertId = 'notification-' . uniqid();
@endphp

<div class="alert {{ $config['bg'] }} alert-dismissible fade show" role="alert" id="{{ $alertId }}">
    <div class="d-flex align-items-center">
        <i class="bi {{ $config['icon'] }} me-2"></i>
        <div>
            <strong>{{ $config['title'] }}:</strong>
            {{ $slot }}
        </div>
    </div>

    @if($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>

@if($autoDismiss > 0 && $dismissible)
    
        document.addEventListener('DOMContentLoaded', function () {
            const alertElement = document.getElementById("{{ $alertId }}");

            if (alertElement) {
                setTimeout(function () {
                    const alertInstance = bootstrap.Alert.getOrCreateInstance(alertElement);
                    alertInstance.close();
                }, {{ $autoDismiss }});
}
        });
    </script>
@endif