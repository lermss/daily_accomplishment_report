# Backend Code Area: Form Requests and Validation

## Form Request Classes

### `StoreReportRequest`

Used by `ReportController::storeReport()`.

| Function | Details |
| --- | --- |
| `authorize()` | Always returns true; route/session ownership is handled elsewhere. |
| `prepareForValidation()` | Normalizes `activity`, `details`, and `remarks` arrays before validation. |
| `rules()` | Requires `file_name`, at least one `start_date`, validates date/text arrays. |
| `normalizeTextArray()` | Private. Converts non-arrays to empty arrays; trims strings; turns empty activity into `N/A`, empty details/remarks into null. |

Rules:

- `file_name`: required string max 255
- `start_date`: required array min 1
- `start_date.*`: required date
- `end_date`: nullable array
- `end_date.*`: nullable date
- `activity`: nullable array
- `activity.*`: nullable string
- `details`: nullable array
- `details.*`: nullable string
- `remarks`: nullable array
- `remarks.*`: nullable string

### `UpdateReportRequest`

Used by `ReportController::update()`.

| Function | Details |
| --- | --- |
| `authorize()` | Always returns true; report ownership is checked by controller. |
| `prepareForValidation()` | Same text-array normalization as store request. |
| `rules()` | Validates optional entry ids and report entry arrays. |
| `normalizeTextArray()` | Private. Same behavior as store request. |

Rules:

- `entry_id`: nullable array
- `entry_id.*`: nullable integer exists in `report_entries.id`
- `start_date`: required array
- `start_date.*`: required date
- `end_date`: nullable array
- `end_date.*`: nullable date
- `activity`: nullable array
- `activity.*`: nullable string
- `details`: nullable array
- `details.*`: nullable string
- `remarks`: nullable array
- `remarks.*`: nullable string

## Inline Controller Validation

| Location | Validation |
| --- | --- |
| `AuthController::sendOtp()` | `email` required email |
| `AuthController::verify2fa()` | `code` required digits:6 |
| `ReportController::updateFile()` | `file_name` required string max 255 |
| `ReportController::storeEntry()` | report id exists, dates, optional text fields |
| `AdminDashboardController::updateReportStatus()` | `status` required in approved/for_revision, `comment` nullable string max 1000 |
| `UserManagementController::store()` | role/name/email plus role-specific details |
| `UserManagementController::update()` | role/name/email unique except target plus role-specific details |
| `ProfileController::update()` | profile names, office, optional profile/signature images |
| `ProvincialReminderController::saveSchedule()` | message max 500, `send_time` H:i, optional boolean |
| `ProvincialReminderController::sendNow()` | optional message max 500 |
