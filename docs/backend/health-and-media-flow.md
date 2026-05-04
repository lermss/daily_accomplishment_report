# Backend Documentation: Health Check, Database Reconnect, and Public Media Flow

## Purpose

This document covers backend health endpoints, database connection reporting/reconnect, and authenticated access to files stored on the public disk.

Main files:

- `routes/web.php`
- `app/Http/Controllers/HealthCheckController.php`
- `app/Services/DatabaseErrorService.php`
- `app/Helpers/DatabaseErrorHelper.php`
- `app/Http/Middleware/CheckDatabaseConnection.php`
- `app/Http/Controllers/Shared/MediaController.php`

## Health Check Routes

| Method | URI | Route name | Method | Purpose |
| --- | --- | --- | --- | --- |
| `GET` | `/health` | `health.status` | `HealthCheckController@status` | App and database health payload |
| `GET` | `/health/database` | `health.database` | `HealthCheckController@database` | Database connection payload |
| `GET` | `/health/reconnect` | `health.reconnect` | `HealthCheckController@reconnect` | Attempt database reconnect |

## Health Status Process

`HealthCheckController::status()`:

1. Calls `DatabaseErrorService::getConnectionStatus()`.
2. Returns JSON with:
   - overall `status`: `healthy` or `unhealthy`
   - timestamp
   - database connected/host/port/database/error
   - app debug/environment
3. HTTP status is `200` when connected, `503` when not connected.

## Database Status Process

`database()` returns only database connection status fields:

- `connected`
- `host`
- `port`
- `database`
- `error`

HTTP status follows connection state.

## Reconnect Process

`reconnect()` calls `DatabaseErrorService::attemptReconnect()` and returns:

- `success`
- `message`: `Reconnection successful` or `Reconnection failed`

HTTP status is `200` on success and `503` on failure.

## Public Media Route

| Method | URI | Route name | Method | Purpose |
| --- | --- | --- | --- | --- |
| `GET` | `/media/public/{path}` | `media.public` | `MediaController@showPublic` | Serve a file from public storage disk |

The route allows any nested path through `where('path', '.*')`.

## Public Media Process

1. `MediaController::showPublic()` requires authenticated user through `AuthFlowService::requireAuthenticated()`.
2. The incoming path is normalized by:
   - replacing backslashes with slashes
   - trimming leading slashes
3. Request aborts with 404 when:
   - path is empty
   - path contains `../`
   - path starts with `/`
   - file does not exist on `Storage::disk('public')`
4. Valid files are returned through `response()->file()`.

## Risks / Notes

- Health routes are not protected by auth middleware in `routes/web.php`.
- Reconnect uses `GET /health/reconnect`, so a browser visit can trigger a reconnect attempt.
- Media files require authentication, but access is not role-scoped once signed in.
