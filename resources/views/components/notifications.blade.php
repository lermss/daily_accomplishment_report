@php
    // Prepare notification data
    $notifications = [];
    
    // Check for flash messages
    if (session()->has('error')) {
        $notifications[] = [
            'message' => session('error'),
            'type' => 'error',
        ];
    }
    
    if (session()->has('success')) {
        $notifications[] = [
            'message' => session('success'),
            'type' => 'success',
        ];
    }
    
    if (session()->has('warning')) {
        $notifications[] = [
            'message' => session('warning'),
            'type' => 'warning',
        ];
    }
    
    if (session()->has('info')) {
        $notifications[] = [
            'message' => session('info'),
            'type' => 'info',
        ];
    }
@endphp

<div class="notification-container" id="notifications-container">
    @foreach($notifications as $notification)
        <x-alert-notification type="{{ $notification['type'] }}" dismissible autoDismiss="5000">
            {{ $notification['message'] }}
        </x-alert-notification>
    @endforeach
</div>

<style>
    .notification-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
    }
    
    .notification-container .alert {
        margin-bottom: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        animation: slideIn 0.3s ease-in-out;
    }
    
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
    
    .notification-container .alert.hide {
        animation: slideOut 0.3s ease-in-out;
    }
    
    @media (max-width: 576px) {
        .notification-container {
            left: 10px;
            right: 10px;
            max-width: none;
        }
    }
</style>
