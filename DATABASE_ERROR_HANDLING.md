# Database Error Handling Implementation Guide

## Overview

This document describes the graceful database error handling system implemented for the Daily Accomplishment Report application. The system catches database connection errors and prevents raw SQL errors from being displayed to users.

---

## 🎯 What Has Been Implemented

### 1. **Global Exception Handler** (`bootstrap/app.php`)
- Catches `Illuminate\Database\QueryException`
- Catches `PDOException`
- Catches `Illuminate\Database\ConnectionException`
- Logs full error details for debugging
- Returns user-friendly messages to the frontend

### 2. **Database Connection Middleware** (`app/Http/Middleware/CheckDatabaseConnection.php`)
- Checks database connectivity on every request
- Prepended to the middleware stack for early detection
- Returns custom error view if connection fails
- Logs connection failures with detailed information

### 3. **Custom Error Views**
- **`resources/views/errors/database-error.blade.php`** - Beautiful error page with retry button
- Responsive design using Bootstrap 5
- Auto-retry functionality
- Clear messaging to users

### 4. **Notification Components**
- **`resources/views/components/alert-notification.blade.php`** - Bootstrap alerts
- **`resources/views/components/notifications.blade.php`** - Global notification display
- Auto-dismissing alerts (5 seconds by default)
- Support for success, error, warning, and info messages

### 5. **Error Handling Service** (`app/Services/DatabaseErrorService.php`)
- Centralized error handling logic
- Connection error detection
- Database status checking
- Reconnection attempts
- Comprehensive logging

### 6. **Helper Functions** (`app/Helpers/DatabaseErrorHelper.php`)
- `handle_db_error($exception, $context)` - Handle errors in services
- `is_db_error($exception)` - Check if error is DB-related
- `is_db_connection_error($exception)` - Check if it's a connection error
- `check_db_connection()` - Get connection status

### 7. **JavaScript Toast Notification** (`public/js/toast-notification.js`)
- Client-side notification system
- Supports success, error, warning, info
- Auto-dismiss or manual close
- Responsive design for mobile

---

## 📋 Usage Guide

### The System Works in Three Layers

#### **Layer 1: Global Exception Handler (Automatic)**
The handler automatically catches database errors at the application level. No code changes needed:

```php
// In bootstrap/app.php - Already configured
->withExceptions(function (Exceptions $exceptions): void {
    $exceptions->render(function (Throwable $e) {
        if ($e instanceof QueryException || $e instanceof PDOException) {
            // Logs error and shows user-friendly message
        }
    });
})->create();
```

#### **Layer 2: Request-Level Check (Automatic)**
The middleware checks database connection on every request:

```php
// In bootstrap/app.php - Already configured
->withMiddleware(function (Middleware $middleware): void {
    $middleware->prepend(\App\Http\Middleware\CheckDatabaseConnection::class);
}
```

#### **Layer 3: Service-Level Handling (Manual)**
Use in controllers or services for custom error handling:

```php
use App\Services\DatabaseErrorService;

// In a controller or service
try {
    // Your database query
    $user = User::find($id);
} catch (Exception $e) {
    if (is_db_error($e)) {
        $error = handle_db_error($e, 'user_retrieval');
        return response()->json($error, 503);
    }
}
```

---

## 🛠️ How to Use in Your Code

### 1. **In Controllers**

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::all();
            return response()->json($users);
        } catch (Exception $e) {
            if (is_db_connection_error($e)) {
                return response()->json([
                    'message' => 'Database connection unavailable',
                ], 503);
            }
            
            if (is_db_error($e)) {
                return response()->json(handle_db_error($e, 'fetch_users'), 503);
            }
            
            throw $e;
        }
    }
}
```

### 2. **In Services**

```php
<?php

namespace App\Services;

use App\Services\DatabaseErrorService;

class ReportService
{
    public function createReport($data)
    {
        try {
            return Report::create($data);
        } catch (Exception $e) {
            $errorInfo = DatabaseErrorService::handle($e, 'create_report');
            
            if (!$errorInfo['success']) {
                return $errorInfo; // Return to controller
            }
        }
    }
}
```

### 3. **Check Database Status (Health Check)**

```php
<?php

namespace App\Http\Controllers;

class HealthController extends Controller
{
    public function check()
    {
        $status = check_db_connection();
        
        if ($status['connected']) {
            return response()->json([
                'status' => 'healthy',
                'database' => 'connected',
            ]);
        }
        
        return response()->json([
            'status' => 'unhealthy',
            'database' => 'disconnected',
            'error' => $status['error'] ?? 'Unknown error',
        ], 503);
    }
}
```

### 4. **In Blade Templates (Show Flash Messages)**

```blade
<!-- In your layout or page template -->

<div class="container mt-4">
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <x-alert-notification type="error">
                {{ $error }}
            </x-alert-notification>
        @endforeach
    @endif

    @if (session()->has('success'))
        <x-alert-notification type="success">
            {{ session('success') }}
        </x-alert-notification>
    @endif

    @if (session()->has('error'))
        <x-alert-notification type="error">
            {{ session('error') }}
        </x-alert-notification>
    @endif

    <!-- Rest of your page -->
</div>
```

### 5. **Include Global Notifications**

Add this to your main layout file:

```blade
<!-- In resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <!-- Your head content -->
</head>
<body>
    <!-- Include the global notification component -->
    <x-notifications />

    <!-- Your main content -->
    @yield('content')

    <!-- Include the toast notification script -->
    <script src="{{ asset('js/toast-notification.js') }}"></script>
</body>
</html>
```

### 6. **Use JavaScript Toast Notifications**

```html
<!-- In your Blade templates or JavaScript files -->
<script>
    // Show success notification
    window.toast.success('Operation completed successfully!');

    // Show error notification
    window.toast.error('Unable to connect to the server. Please try again later.', {
        duration: 10000  // Stay for 10 seconds
    });

    // Show warning notification
    window.toast.warning('This action cannot be undone.');

    // Show info notification
    window.toast.info('New updates are available.');

    // Don't auto-dismiss
    window.toast.error('Critical error occurred', {
        duration: 0  // Won't auto-dismiss
    });
</script>
```

---

## 🔒 Security & Privacy

### What Gets Logged (Server-side)
✅ Full error details for debugging
✅ Database connection info (host, port, database name)
✅ Error traces and stack traces
✅ Exception type and error code

### What Never Gets Shown to Users
❌ Database credentials
❌ Full stack traces
❌ Raw SQL queries
❌ Database structure details
❌ Server paths or file locations

### User-Friendly Messages Only
- "Unable to connect to the server. Please try again later."
- "We're experiencing technical difficulties. Please try again."
- "Service temporarily unavailable."

---

## 📊 Logging

All database errors are logged to `storage/logs/laravel.log`:

```
[2026-04-11 10:30:45] production.ERROR: Database Connection Error {
    "message": "SQLSTATE[HY000] [2002] No connection could be made because the target machine actively refused it",
    "exception": "Illuminate\\Database\\QueryException",
    "trace": [...],
    "host": "127.0.0.1",
    "port": "8889",
    "database": "db_darsystem"
}
```

You can view logs in real-time:
```bash
php artisan pail --filter="Database"
```

---

## 🧪 Testing the Implementation

### Test 1: Stop MySQL to Trigger Connection Error

```bash
# On Windows (if using XAMPP)
# Stop MySQL from XAMPP Control Panel

# On Linux/Mac
service mysql stop

# Try to access the application
# You should see the custom error page
```

### Test 2: Test Flash Messages

```php
// In a controller
return back()->with('error', 'Test error message');
```

### Test 3: Test Toast Notifications

```javascript
// In browser console
window.toast.error('This is a test error!');
```

### Test 4: Check Database Status

Create a route for testing:

```php
Route::get('/health', function () {
    return check_db_connection();
});
```

---

## 🚀 Optional Enhancements

### 1. **Auto-Refresh on Connection Recovery**

```html
<script>
    const checkConnection = () => {
        fetch('/health')
            .then(res => res.json())
            .then(data => {
                if (data.connected) {
                    window.toast.success('Connection restored!');
                    setTimeout(() => location.reload(), 2000);
                }
            })
            .catch(err => console.log('Still disconnected'));
    };

    // Check every 5 seconds
    setInterval(checkConnection, 5000);
</script>
```

### 2. **Custom Error for Specific Exceptions**

```php
// In bootstrap/app.php, before the general handler
$exceptions->render(function (Illuminate\Database\ConnectionException $e) {
    Log::critical('MySQL Server Not Found', ['error' => $e->getMessage()]);
    return response()->view('errors.mysql-offline', [], 503);
});
```

### 3. **Slack/Email Notifications**

```php
// In DatabaseErrorService
if ($isDbError) {
    Log::error('Database Error', [...]);
    
    // Notify admin via email
    Mail::to(config('admin.email'))
        ->queue(new DatabaseErrorNotification($exception));
}
```

---

## 📝 Configuration

The error handling system uses Laravel's default configuration:

- **Debug Mode**: `.env` → `APP_DEBUG=false` in production
- **Logging**: `config/logging.php` → Default is `single` channel
- **Database**: `config/database.php` → MySQL configuration

To customize logging:

```php
// In config/logging.php
'database_errors' => [
    'driver' => 'single',
    'path' => storage_path('logs/database.log'),
    'level' => 'error',
],
```

Then update the handler:

```php
// In bootstrap/app.php
Log::channel('database_errors')->error('Database Connection Error', [...]);
```

---

## ✅ Checklist for Implementation

- [x] Exception handler configured in `bootstrap/app.php`
- [x] Middleware added to check database connection
- [x] Custom error views created
- [x] Blade components for notifications
- [x] Service class for error handling
- [x] Helper functions created and auto-loaded
- [x] JavaScript toast notification system
- [x] Comprehensive logging setup
- [x] Documentation provided

---

## 🆘 Troubleshooting

### Notifications not showing?
1. Ensure `<x-notifications />` is in your layout
2. Check that `session()` is working correctly
3. Verify Bootstrap CSS is loaded

### Errors still showing raw messages?
1. Ensure `APP_DEBUG=false` in `.env`
2. Verify exception handler is configured
3. Check that middleware is registered

### Database status endpoint returns 503?
1. Check MySQL connection in `config/database.php`
2. Verify MySQL is running
3. Check credentials in `.env`

### Logs not appearing?
1. Ensure `storage/logs` directory is writable: `chmod -R 777 storage/logs`
2. Check Laravel log channel in `config/logging.php`
3. Verify `APP_LOG_LEVEL` is set correctly

---

## 📞 Support

For questions or issues with this implementation:

1. Check the Laravel Documentation: https://laravel.com/docs
2. Review the error logs in `storage/logs/laravel.log`
3. Run `php artisan config:clear` to clear configuration cache
4. Run `composer dump-autoload` to refresh autoloader

---

**Last Updated:** April 11, 2026
**Version:** 1.0
