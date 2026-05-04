# Backend Code Area: Helpers, Support, Mail, View Components, and Providers

## Helpers

### `app/Helpers/DatabaseErrorHelper.php`

Autoloaded through `composer.json`.

| Function | Details |
| --- | --- |
| `handle_db_error(Exception, string)` | Calls `DatabaseErrorService::handle()`. |
| `is_db_error(Exception)` | Checks if exception is `QueryException` or `PDOException`. |
| `is_db_connection_error(Exception)` | Calls `DatabaseErrorService::isConnectionError()`. |
| `check_db_connection()` | Calls `DatabaseErrorService::getConnectionStatus()`. |

## Support Classes

### `ProvincialOffice`

Final class containing supported office constants.

| Function/constant | Details |
| --- | --- |
| `LA_UNION` | `La Union` |
| `ILOCOS_NORTE` | `Ilocos Norte` |
| `ILOCOS_SUR` | `Ilocos Sur` |
| `PANGASINAN` | `Pangasinan` |
| `ALL` | Array of all supported offices. |
| `__construct()` | Private to prevent instantiation. |
| `all()` | Returns all supported offices. |
| `isValid(?string)` | Strictly checks whether the office is supported. |

## Mail Classes

### `GoogleAuthenticatorProvisioningMail`

Sent when Super Admin provisions Google Authenticator access.

| Function | Details |
| --- | --- |
| `__construct()` | Stores recipient email, manual setup key, and optional QR image. |
| `envelope()` | Sets subject to `DICT Google Authenticator Access`. |
| `content()` | Uses HTML view `emails.google-authenticator-provisioning`, text view `emails.google-authenticator-provisioning-text`, and passes recipient/setup/QR data. |

## View Components

### `Topbar`

Builds role-aware navigation and notification data for the shared topbar.

| Function | Details |
| --- | --- |
| `__construct(string $active, bool $canAccessAudit, ?User $user)` | Computes navigation flags, route targets, notification collections, and unread counts based on current user role. |
| `render()` | Returns `components.topbar`. |

Important constructor behavior:

- Admin navigation is true for `admin` and `ph-admin`.
- Super admin navigation is true for `super_admin` and `hr-super-admin`.
- Staff navigation is true for `staff` and `interns`.
- Super admins load `SuperAdminNotificationService` preview/unread data.
- Admin/PH Admin users load pending report submission previews.
- PH Admin pending previews are scoped by assignment or same office.
- Staff/intern users load reviewed report notifications and office reminders.

## Providers

### `AppServiceProvider`

| Function | Details |
| --- | --- |
| `register()` | Empty application service registration hook. |
| `boot()` | In production, forces HTTPS when `APP_FORCE_HTTPS` is true. |

## Bootstrap Configuration Notes

`bootstrap/app.php` also configures important backend behavior:

- Routes web file and `/up` health endpoint.
- Prepends `CheckDatabaseConnection`.
- Appends `SetSecurityHeaders`.
- Defines middleware aliases.
- Renders database connection exceptions into JSON or `errors.database-error`.
- Schedules `audit:cleanup` daily at 02:00.
