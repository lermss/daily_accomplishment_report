# Database Error Handling - Quick Reference Guide

## 🚀 Quick Start

### For Users
Nothing to do! The system automatically handles database errors and shows user-friendly messages.

### For Developers
Use helper functions to handle database errors in your code:

```php
// In Controllers, Services, or elsewhere
try {
    // Your database operation
    $data = User::all();
} catch (Exception $e) {
    if (is_db_error($e)) {
        // Handle database errors gracefully
        $error = handle_db_error($e, 'fetch_users');
        return response()->json($error, 503);
    }
}
```

---

## 📌 Common Tasks

### Task 1: Show a Database Error to User (Flash Message)

```php
// In Controller
catch (Exception $e) {
    if (is_db_error($e)) {
        return back()->with('error', 'Unable to complete this action. Please try again.');
    }
}
```

### Task 2: Return JSON Error Response (API)

```php
// In API Controller
catch (Exception $e) {
    if (is_db_connection_error($e)) {
        return response()->json([
            'success' => false,
            'message' => 'Database server is temporarily unavailable',
        ], 503);
    }
}
```

### Task 3: Show Toast Notification (JavaScript)

```html
<script>
    // In your Blade template
    window.toast.error('Unable to connect to the server. Please try again later.');
</script>
```

### Task 4: Check Database Status

```php
// In Controller
$status = check_db_connection();

if ($status['connected']) {
    echo "Database is online";
} else {
    echo "Database is offline: " . $status['error'];
}
```

### Task 5: Add Health Check Route

```php
// In routes/web.php
Route::get('/health', [\App\Http\Controllers\HealthCheckController::class, 'status']);
Route::get('/health/database', [\App\Http\Controllers\HealthCheckController::class, 'database']);
```

---

## 🔧 Files Overview

| File | Purpose |
|------|---------|
| `bootstrap/app.php` | Global exception handler |
| `app/Http/Middleware/CheckDatabaseConnection.php` | Connection check middleware |
| `app/Services/DatabaseErrorService.php` | Error handling service |
| `app/Helpers/DatabaseErrorHelper.php` | Helper functions (auto-loaded) |
| `resources/views/errors/database-error.blade.php` | Custom error page |
| `resources/views/components/alert-notification.blade.php` | Alert component |
| `resources/views/components/notifications.blade.php` | Global notifications |
| `public/js/toast-notification.js` | Toast notification system |
| `DATABASE_ERROR_HANDLING.md` | Full documentation |

---

## 🎯 Error Handling Flow

```
User Request
    ↓
[Middleware: CheckDatabaseConnection] → If fails → Show error page
    ↓
Route Handler (Controller)
    ↓
Try Database Operation
    ↓
[Exception Handler in bootstrap/app.php] → If DB error → Log + Show user message
    ↓
Response to User
```

---

## 📊 What Gets Logged?

Database errors are logged to `storage/logs/laravel.log`:

```
[2026-04-11 10:30:45] production.ERROR: Database Connection Error {
    "message": "SQLSTATE[HY000] [2002] No connection could be made...",
    "exception": "Illuminate\\Database\\QueryException",
    "host": "127.0.0.1",
    "port": "8889",
    "database": "db_darsystem"
}
```

---

## ✅ What's Protected

✅ Database credentials - Never shown to users
✅ Stack traces - Only logged server-side
✅ SQL queries - Logged but not exposed
✅ File paths - Hidden from error pages
✅ Error details - Replaced with friendly messages

---

## 🧪 Testing

### Test 1: See Error Page (Stop MySQL)
```bash
# Stop MySQL service
service mysql stop

# Visit application → See custom error page
```

### Test 2: Test Helper Functions
```php
// In tinker
>>> is_db_error(new Exception())
=> false

>>> check_db_connection()
=> ['connected' => true, ...]
```

### Test 3: Check Health Endpoint
```bash
curl http://127.0.0.1:8000/health
```

---

## 🛠️ Debugging

### Issue: Errors still showing raw messages?
**Solution:** 
- Set `APP_DEBUG=false` in `.env`
- Clear config: `php artisan config:clear`
- Restart server

### Issue: Notifications not showing?
**Solution:**
- Ensure `<x-notifications />` in layout
- Check Bootstrap CSS is loaded
- Verify session is working

### Issue: Middleware not running?
**Solution:**
- Run `php artisan config:clear`
- Check `bootstrap/app.php` middleware registration
- Run `composer dump-autoload`

---

## 📝 Helper Functions Reference

```php
// Check if error is database-related
is_db_error($exception): bool

// Check if error is connection error
is_db_connection_error($exception): bool

// Get detailed error info
handle_db_error($exception, $context): array
// Returns: ['success' => false, 'message' => '...', 'error_code' => '...']

// Get database connection status
check_db_connection(): array
// Returns: ['connected' => bool, 'host' => '...', 'port' => '...', 'database' => '...', 'error' => '...']
```

---

## 🎨 JavaScript Toast Methods

```javascript
// Success notification
window.toast.success('Operation completed!');

// Error notification
window.toast.error('An error occurred!');

// Warning notification
window.toast.warning('Please be careful!');

// Info notification
window.toast.info('Information!');

// Custom options
window.toast.error('Error!', {
    title: 'Custom Title',
    duration: 10000,  // 10 seconds (0 = no auto-dismiss)
    type: 'error'
});
```

---

## 📚 Related Laravel Documentation

- [Exception Handling](https://laravel.com/docs/12.x/errors#handling-exceptions)
- [Middleware](https://laravel.com/docs/12.x/middleware)
- [Database Errors](https://laravel.com/docs/12.x/database#running-database-queries)
- [Logging](https://laravel.com/docs/12.x/logging)

---

## 💡 Pro Tips

1. **Always log context** - Use `handle_db_error($e, 'specific_operation')` for better debugging
2. **Use flash messages** - Show user-friendly errors with `.with('error', 'message')`
3. **Health monitoring** - Set up `/health` endpoint for monitoring
4. **Test offline** - Regularly test with MySQL stopped
5. **Monitor logs** - Use `php artisan pail` to watch logs in real-time

---

## 🔗 Quick Links

- Full Documentation: [DATABASE_ERROR_HANDLING.md](DATABASE_ERROR_HANDLING.md)
- Health Check: `GET /health`
- Database Status: `GET /health/database`
- Error Logs: `storage/logs/laravel.log`

---

**Last Updated:** April 11, 2026
**Version:** 1.0
