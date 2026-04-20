<?php

namespace App\Services;

use App\Models\Report;
use App\Models\User;
use App\Support\ProvincialOffice;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class AdminPortalService
{
    public function __construct(
        private readonly AuthFlowService $authFlowService,
        private readonly ProvincialHeadAssignmentService $provincialHeadAssignmentService,
    ) {
    }

    public function userFormOptions(): array
    {
        return [
            'hr-super-admin' => [
                'label' => 'Human Resource- Super Admin',
                'fields' => ['name', 'email', 'position', 'project', 'bureau'],
                'projectOptions' => ['Di DigiGov', 'ILCDB', 'Cybersecurity', 'PNPKI', 'FW4A', 'IDB', 'MISS', 'NBP', 'GECS', 'DTC', 'SPARK', 'AFD'],
                'bureauOptions' => ['Regional Office', 'Provincial Office', 'Field Office', 'TCO', 'AFD'],
            ],
            'ph-admin' => [
                'label' => 'Provincial Head - Admin',
                'fields' => ['name', 'email', 'position', 'division', 'office'],
                'divisionOptions' => ['DigiGov', 'ILCDB', 'NPPB', 'Cybersecurity', 'PNPKI', 'ILCDB', 'MISS', 'NBP', 'GECS', 'OTC', 'SPARK', 'AFD'],
                'officeOptions' => $this->provincialHeadAssignmentService->officeOptions(),
            ],
            'staff' => [
                'label' => 'Staff',
                'fields' => ['name', 'email', 'position', 'project', 'bureau', 'office'],
                'projectOptions' => ['DigiGov', 'ILCDB', 'Cybersecurity', 'PNPKI', 'FW4A', 'ILD', 'MISS', 'NBP', 'GECS', 'OTC', 'SPARK', 'AFD'],
                'bureauOptions' => ['Regional Office', 'Provincial Office', 'Field Office', 'TCO', 'AFD'],
                'officeOptions' => $this->provincialHeadAssignmentService->officeOptions(),
            ],

                'interns' => [
                    'label' => 'Intern',
                    'fields' => ['name', 'email', 'position', 'project', 'bureau', 'office'],
                    'projectOptions' => ['DigiGov', 'ILCDB', 'Cybersecurity', 'PNPKI', 'FW4A', 'ILD', 'MISS', 'NBP', 'GECS', 'OTC', 'SPARK', 'AFD'],
                    'bureauOptions' => ['Regional Office', 'Provincial Office', 'Field Office', 'TCO', 'AFD'],
                    'officeOptions' => $this->provincialHeadAssignmentService->officeOptions(),
                ],
        ];
    }

    public function buildDashboardData(Request $request, User $user, string $mode = 'dashboard'): array
    {
        $search = trim((string) $request->query('search', ''));
        $scope = match ($mode) {
            'archive' => 'archive',
            'active' => 'active',
            default => null,
        };
        $availableRoleFilters = [
            'staff' => 'Staff',
            'ph-admin' => 'PH Admin',
            'hr-super-admin' => 'HR Super Admin',
            'interns' => 'Interns',
        
        ];
        $roleFilter = trim((string) $request->query('filter', ''));

        if (!array_key_exists($roleFilter, $availableRoleFilters)) {
            $roleFilter = '';
        }

        $users = $this->managedUsersQuery($search, $scope, $roleFilter)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'first_name',
                'middle_name',
                'last_name',
                'email',
                'avatar_path',
                'position',
                'project',
                'bureau',
                'division',
                'office',
                'institution',
                'role',
                'status',
            ]);

        $allUsersCount = $this->managedUsersQuery()->count();
        $archiveCount = $this->managedUsersQuery('', 'archive')->count();
        $activeCount = $this->managedUsersQuery('', 'active')->count();

        return [
            'title' => match ($mode) {
                'users' => 'Users',
                'archive' => 'Archive Users',
                'active' => 'Active Users',
                'reports' => 'Reports',
                default => 'Dashboard',
            },
            'mode' => $mode,
            'user' => $user,
            'search' => $search,
            'filter' => $roleFilter,
            'filterLabel' => 'Role',
            'filterOptions' => $availableRoleFilters,
            'users' => $users,
            'counts' => [
                'users' => $allUsersCount,
                'archive' => $archiveCount,
                'active' => $activeCount,
            ],
            'stats' => [
                [
                    'key' => 'users',
                    'label' => 'Users',
                    'count' => $allUsersCount,
                    'meta' => 'Registered accounts',
                    'tone' => 'purple',
                    'route' => route('dashboard.users'),
                ],
                [
                    'key' => 'archive',
                    'label' => 'Archive',
                    'count' => $archiveCount,
                    'meta' => 'Archived accounts',
                    'tone' => 'yellow',
                    'route' => route('dashboard.archive'),
                ],
                [
                    'key' => 'active',
                    'label' => 'Active',
                    'count' => $activeCount,
                    'meta' => 'Accessible accounts',
                    'tone' => 'green',
                    'route' => route('dashboard.active'),
                ],
            ],
            'canManageUsers' => $this->authFlowService->canManageUsers($user->role),
            'canAccessAudit' => $this->authFlowService->canAccessAudit($user->role),
            'userFormOptions' => $this->userFormOptions(),
            'initialRole' => old('role', 'hr-super-admin'),
        ];
    }

    public function buildAdminDashboardData(Request $request, User $user, string $mode = 'dashboard'): array
    {
        $search = trim((string) $request->query('search', ''));
        $status = match ($mode) {
            'approved' => 'approved',
            'pending' => 'pending',
            'revisions' => 'for_revision',
            default => null,
        };
        $availableStatusFilters = [
            '' => 'All Status',
            'pending' => 'Pending',
            'approved' => 'Approved',
            'for_revision' => 'For Revision',
        ];
        $statusFilter = trim((string) $request->query('status_filter', ''));

        if (! array_key_exists($statusFilter, $availableStatusFilters)) {
            $statusFilter = '';
        }

        $effectiveStatus = $status ?? ($statusFilter !== '' ? $statusFilter : null);
        $dateRange = $this->adminReportsDateRange($request);
        // Load the full report payload needed by the admin dashboard modal preview.
        $reports = Report::query()
            ->with([
                'user:id,name,avatar_path,signature_path,office',
                'assignedProvincialHead:id,name',
                'entries' => fn ($query) => $query
                    ->select(['id', 'report_id', 'start_date', 'end_date', 'activity', 'details', 'remarks'])
                    ->orderBy('start_date')
                    ->orderBy('id'),
            ])
            ->where('is_hidden_from_admin_dashboard', false)
            ->whereIn('status', ['pending', 'approved', 'for_revision'])
            ->tap(fn ($query) => $this->provincialHeadAssignmentService->scopeReportsForReviewer($query, $user))
            ->when($effectiveStatus !== null, fn ($query) => $query->where('status', $effectiveStatus))
            ->when($dateRange['from'], fn ($query, $fromDate) => $query->whereRaw('COALESCE(submitted_at, created_at) >= ?', [$fromDate]))
            ->when($dateRange['to'], fn ($query, $toDate) => $query->whereRaw('COALESCE(submitted_at, created_at) <= ?', [$toDate]))
            ->when($search !== '', function ($query) use ($search) {
                $like = '%' . $search . '%';

                $query->where(function ($subQuery) use ($like) {
                    $subQuery
                        ->whereHas('user', fn ($userQuery) => $userQuery->where('name', 'like', $like))
                        ->orWhere('file_name', 'like', $like)
                        ->orWhere('status', 'like', $like);
                });
            })
            ->orderByRaw('COALESCE(submitted_at, created_at) DESC')
            ->get()
            ->map(function (Report $report) {
                $report->setAttribute('user_name', $report->user?->name);
                $report->setAttribute('user_avatar_path', $report->user?->avatar_path);
                $report->setAttribute('user_signature_path', $report->user?->signature_path);
                $report->setAttribute('user_office', $report->user?->office);
                $report->setAttribute('review_comment_text', $report->review_comment);
                $report->setAttribute('approved_by_name', $report->assignedProvincialHead?->name);
                $report->setAttribute(
                    'entry_preview',
                    optional($report->entries->first(), fn ($entry) => $entry->details ?: $entry->activity ?: $entry->remarks)
                );

                return $report;
            });

        $counts = $this->reportSummaryCounts($user);
        $latestApprovedAt = $this->adminReportsQuery($user, '', 'approved')->max('reports.reviewed_at');
        $routePrefix = $this->authFlowService->isAdminRole($user->role) ? 'admin.dashboard' : 'reports';

        return [
            'title' => match ($mode) {
                'approved' => 'Approved Reports',
                'pending' => 'Pending Reports',
                'revisions' => 'Reports For Revision',
                'employees' => 'Employees Reports',
                'reports' => 'Reports',
                default => 'Admin Dashboard',
            },
            'mode' => $mode,
            'user' => $user,
            'search' => $search,
            'statusFilter' => $statusFilter,
            'statusFilterOptions' => $availableStatusFilters,
            'fromDate' => $dateRange['fromInput'],
            'toDate' => $dateRange['toInput'],
            'quickFilter' => $dateRange['quick'],
            'quickFilterOptions' => $dateRange['quickOptions'],
            'reports' => $reports,
            'counts' => $counts,
            'latestApprovedAt' => $latestApprovedAt,
            'isSuperAdminView' => $this->authFlowService->isSuperAdminRole($user->role),
            'canAccessAudit' => $this->authFlowService->canAccessAudit($user->role),
            'canManageReportRecords' => $this->authFlowService->isAdminRole($user->role),
            'provincialOfficeOptions' => ProvincialOffice::all(),
            'reportRoutes' => [
                'employees' => route($routePrefix . '.employees'),
                'approved' => route($routePrefix . '.approved'),
                'pending' => route($routePrefix . '.pending'),
                'revisions' => route($routePrefix . '.revisions'),
                'back' => $this->authFlowService->isAdminRole($user->role) ? route('dashboard.admin') : route('reports.index'),
            ],
        ];
    }

    public function reportSummaryCounts(?User $user = null): array
    {
        return [
            'employees' => $this->adminReportsQuery($user)->count(),
            'approved' => $this->adminReportsQuery($user, '', 'approved')->count(),
            'pending' => $this->adminReportsQuery($user, '', 'pending')->count(),
            'revisions' => $this->adminReportsQuery($user, '', 'for_revision')->count(),
        ];
    }

    public function buildAuditData(Request $request, User $user): array
    {
        $search = trim((string) $request->query('search', ''));
        $roleFilter = trim((string) $request->query('role', ''));
        $activityFilter = trim((string) $request->query('activity', ''));
        $dateFilter = trim((string) $request->query('date', ''));
        $logs = collect();
        $availableRoles = [];
        $availableActivities = [];
        [$dateFrom, $dateTo] = $this->auditDateFilterRange($dateFilter);

        if ($this->safeHasTable('activity_logs')) {
            try {
                $hasActionColumn = $this->safeHasColumn('activity_logs', 'action');
                $hasEventColumn = $this->safeHasColumn('activity_logs', 'event');
                $hasDescriptionColumn = $this->safeHasColumn('activity_logs', 'description');
                $hasDetailsColumn = $this->safeHasColumn('activity_logs', 'details');
                $hasRoleColumn = $this->safeHasColumn('activity_logs', 'role');
                $hasIpAddressColumn = $this->safeHasColumn('activity_logs', 'ip_address');

                $activityExpression = match (true) {
                    $hasActionColumn && $hasEventColumn => "COALESCE(activity_logs.action, activity_logs.event, 'activity')",
                    $hasActionColumn => "COALESCE(activity_logs.action, 'activity')",
                    $hasEventColumn => "COALESCE(activity_logs.event, 'activity')",
                    default => "'activity'",
                };

                $roleExpression = $hasRoleColumn
                    ? "COALESCE(users.role, activity_logs.role, '')"
                    : "COALESCE(users.role, '')";

                $baseQuery = DB::table('activity_logs')
                    ->leftJoin('users', 'users.id', '=', 'activity_logs.user_id');

                $availableRoles = DB::table('activity_logs')
                    ->leftJoin('users', 'users.id', '=', 'activity_logs.user_id')
                    ->selectRaw($roleExpression . ' as label')
                    ->distinct()
                    ->orderBy('label')
                    ->pluck('label')
                    ->filter()
                    ->values()
                    ->all();

                $availableActivities = DB::table('activity_logs')
                    ->selectRaw($activityExpression . ' as action_name')
                    ->distinct()
                    ->orderBy('action_name')
                    ->pluck('action_name')
                    ->map(fn ($action) => $this->auditActionMeta((string) $action)['label'])
                    ->unique()
                    ->values()
                    ->all();

                $query = (clone $baseQuery)
                    ->when($search !== '', function ($query) use ($search) {
                        $like = '%' . $search . '%';

                        $query->where(function ($subQuery) use ($like) {
                            $hasCondition = false;

                            foreach (['action', 'event', 'description', 'details'] as $column) {
                                if (! $this->safeHasColumn('activity_logs', $column)) {
                                    continue;
                                }

                                if (! $hasCondition) {
                                    $subQuery->where('activity_logs.' . $column, 'like', $like);
                                    $hasCondition = true;
                                    continue;
                                }

                                $subQuery->orWhere('activity_logs.' . $column, 'like', $like);
                            }

                            if (! $hasCondition) {
                                $subQuery->where('users.name', 'like', $like);
                                return;
                            }

                            $subQuery->orWhere('users.name', 'like', $like);
                        });
                    })
                    ->when($roleFilter !== '', function ($query) use ($roleFilter, $roleExpression) {
                        $query->whereRaw($roleExpression . ' = ?', [$roleFilter]);
                    })
                    ->when($activityFilter !== '', function ($query) use ($activityFilter, $hasActionColumn, $hasEventColumn) {
                        $query->where(function ($subQuery) use ($activityFilter, $hasActionColumn, $hasEventColumn) {
                            if (! $hasActionColumn && ! $hasEventColumn) {
                                return;
                            }

                            if ($activityFilter === 'otp_requested') {
                                if ($hasActionColumn) {
                                    $subQuery->whereIn('activity_logs.action', ['otp_requested', 'otp_resent']);
                                }

                                if ($hasEventColumn) {
                                    if ($hasActionColumn) {
                                        $subQuery->orWhereIn('activity_logs.event', ['otp_requested', 'otp_resent']);
                                    } else {
                                        $subQuery->whereIn('activity_logs.event', ['otp_requested', 'otp_resent']);
                                    }
                                }

                                return;
                            }

                            if ($hasActionColumn) {
                                $subQuery->where('activity_logs.action', $activityFilter);
                            }

                            if ($hasEventColumn) {
                                if ($hasActionColumn) {
                                    $subQuery->orWhere('activity_logs.event', $activityFilter);
                                } else {
                                    $subQuery->where('activity_logs.event', $activityFilter);
                                }
                            }
                        });
                    })
                    ->when($dateFrom, fn ($query, $from) => $query->where('activity_logs.created_at', '>=', $from))
                    ->when($dateTo, fn ($query, $to) => $query->where('activity_logs.created_at', '<=', $to))
                    ->orderByDesc('activity_logs.created_at')
                    ->limit(100);

                $logs = $query->get([
                    'activity_logs.user_id',
                    'activity_logs.created_at',
                    'users.name as user_name',
                    'users.role as user_role',
                    $hasActionColumn ? 'activity_logs.action' : DB::raw('NULL as action'),
                    $hasEventColumn ? 'activity_logs.event' : DB::raw('NULL as event'),
                    $hasDescriptionColumn ? 'activity_logs.description' : DB::raw('NULL as description'),
                    $hasDetailsColumn ? 'activity_logs.details' : DB::raw('NULL as details'),
                    $hasIpAddressColumn ? 'activity_logs.ip_address' : DB::raw('NULL as ip_address'),
                    $hasRoleColumn ? 'activity_logs.role' : DB::raw('NULL as role'),
                ])->map(function ($log) {
                    $action = (string) ($log->action ?? $log->event ?? 'activity');
                    $meta = $this->auditActionMeta($action);
                    $createdAt = $log->created_at ? Carbon::parse($log->created_at) : null;
                    $userName = $log->user_name ?: ($log->user_id ? 'User #' . $log->user_id : 'System');

                    return [
                        'action' => $action,
                        'activity' => $meta['icon'] . ' ' . $meta['label'],
                        'activity_icon' => $meta['icon'],
                        'activity_label' => $meta['label'],
                        'description' => $log->description ?? $log->details ?? 'System activity recorded.',
                        'status' => $meta['status'],
                        'status_tone' => $meta['tone'],
                        'created_at' => $createdAt,
                        'date_time' => $createdAt ? $createdAt->format('M d, Y') . ' • ' . $createdAt->format('h:i A') : 'N/A',
                        'user_id' => $log->user_id ?? null,
                        'user_name' => $userName,
                        'role' => $log->user_role ?: $log->role ?: 'N/A',
                        'role_label' => $this->formatRoleLabel($log->user_role ?: $log->role ?: 'N/A'),
                        'ip_address' => $log->ip_address ?: 'Not recorded',
                        'device' => 'Not recorded',
                    ];
                });
            } catch (\Throwable) {
                $logs = collect();
            }
        }

        $todayStart = now()->startOfDay();
        $summary = [
            'totalToday' => $logs->filter(fn ($log) => $log['created_at'] && $log['created_at']->gte($todayStart))->count(),
            'successfulLogins' => $logs->where('action', 'login')->count(),
            'profileUpdates' => $logs->where('action', 'profile_updated')->count(),
            'warnings' => $logs->where('status_tone', 'warning')->count(),
        ];

        return [
            'title' => 'Audit Log',
            'user' => $user,
            'logs' => $logs,
            'search' => $search,
            'roleFilter' => $roleFilter,
            'activityFilter' => $activityFilter,
            'dateFilter' => $dateFilter,
            'availableRoles' => $availableRoles,
            'availableRoleLabels' => collect($availableRoles)
                ->mapWithKeys(fn (string $role) => [$role => $this->formatRoleLabel($role)])
                ->all(),
            'availableActivities' => $availableActivities,
            'summary' => $summary,
            'canAccessAudit' => true,
        ];
    }

    public function formatRoleLabel(?string $role): string
    {
        $role = trim((string) $role);

        return $role === '' ? 'System' : ucwords(str_replace('_', ' ', str_replace('-', ' ', $role)));
    }

    public function buildProfileData(User $user): array
    {
        $positionOptions = User::query()
            ->whereNotNull('position')
            ->where('position', '!=', '')
            ->distinct()
            ->orderBy('position')
            ->pluck('position')
            ->all();

        $projectOptions = collect($this->userFormOptions())
            ->pluck('projectOptions')
            ->flatten()
            ->filter()
            ->unique()
            ->values()
            ->all();

        $bureauOptions = collect($this->userFormOptions())
            ->pluck('bureauOptions')
            ->flatten()
            ->filter()
            ->unique()
            ->values()
            ->all();

        $officeOptions = collect($this->userFormOptions())
            ->pluck('officeOptions')
            ->flatten()
            ->filter()
            ->unique()
            ->values()
            ->all();

        $fallbackNameParts = preg_split('/\s+/', trim($user->name ?? ''), 2) ?: ['', ''];
        $profileImageUrl = $user->avatar_path ? route('media.public', ['path' => ltrim($user->avatar_path, '/')]) : null;
        $signatureImageUrl = $user->signature_path ? route('media.public', ['path' => ltrim($user->signature_path, '/')]) : null;

        return [
            'title' => 'Edit Profile',
            'user' => $user,
            'firstName' => old('first_name', $user->first_name ?: ($fallbackNameParts[0] ?? '')),
            'middleName' => old('middle_name', $user->middle_name),
            'lastName' => old('last_name', $user->last_name ?: ($fallbackNameParts[1] ?? '')),
            'position' => old('position', $user->position),
            'project' => old('project', $user->project),
            'bureau' => old('bureau', $user->bureau),
            'office' => old('office', $user->office),
            'positionOptions' => array_values(array_unique(array_filter(array_merge($positionOptions, [$user->position])))),
            'projectOptions' => array_values(array_unique(array_filter(array_merge($projectOptions, [$user->project])))),
            'bureauOptions' => array_values(array_unique(array_filter(array_merge($bureauOptions, [$user->bureau])))),
            'officeOptions' => array_values(array_unique(array_filter(array_merge($officeOptions, [$user->office])))),
            'canAccessAudit' => $this->authFlowService->canAccessAudit($user->role),
            'profileImageUrl' => $profileImageUrl,
            'signatureImageUrl' => $signatureImageUrl,
        ];
    }

    public function createManagedUser(User $actor, array $validated, array $details): void
    {
        $this->provincialHeadAssignmentService->ensureValidManagedUserAssignment($validated, $details);

        User::query()->create([
            'name' => $validated['name'],
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?: null,
            'last_name' => $validated['last_name'],
            'email' => strtolower(trim($validated['email'])),
            'password' => bcrypt((string) str()->random(24)),
            'position' => $details['position'] ?? null,
            'project' => $details['project'] ?? null,
            'bureau' => $details['bureau'] ?? null,
            'division' => $details['division'] ?? null,
            'office' => $details['office'] ?? null,
            'institution' => $details['institution'] ?? null,
            'role' => $validated['role'],
            'status' => 'active',
            'is_authorized' => false,
            'otp_code' => null,
            'otp_hash' => null,
            'otp_expiration' => null,
            'google2fa_secret' => null,
            'google2fa_enabled' => false,
            'two_factor_confirmed_at' => null,
            'google2fa_authorization_code_hash' => null,
            'google2fa_authorization_code_expires_at' => null,
            'google2fa_authorization_sent_at' => null,
            'google2fa_authorized_by' => null,
            'google2fa_authorized_at' => null,
        ]);

        $this->logActivity($actor, 'user_created', 'Created user account for ' . $validated['email'] . '.');
    }

    public function updateManagedUser(User $actor, User $targetUser, array $validated, array $details): void
    {
        $this->provincialHeadAssignmentService->ensureValidManagedUserAssignment($validated, $details, $targetUser);

        $targetUser->forceFill([
            'name' => $validated['name'],
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?: null,
            'last_name' => $validated['last_name'],
            'email' => strtolower(trim($validated['email'])),
            'position' => $details['position'] ?? null,
            'project' => $details['project'] ?? null,
            'bureau' => $details['bureau'] ?? null,
            'division' => $details['division'] ?? null,
            'office' => $details['office'] ?? null,
            'institution' => $details['institution'] ?? null,
            'role' => $validated['role'],
        ])->save();

        $this->logActivity($actor, 'user_updated', 'Updated user account for ' . $targetUser->email . '.');
    }

    public function archiveManagedUser(User $actor, User $targetUser): void
    {
        $targetUser->forceFill(['status' => 'archived'])->save();

        $this->logActivity($actor, 'user_archived', 'Archived user account for ' . $targetUser->email . '.');
    }

    public function restoreManagedUser(User $actor, User $targetUser): void
    {
        $targetUser->forceFill(['status' => 'active'])->save();

        $this->logActivity($actor, 'user_restored', 'Restored user account for ' . $targetUser->email . '.');
    }

    public function updateReportStatus(Report $report, string $status, int $reviewerId, ?string $comment = null): void
    {
        // Centralize report review updates so both sync and modal requests behave the same way.
        $report->markAsReviewed($status, $reviewerId, $comment);
    }

    public function updateProfile(User $user, Request $request, array $validated): void
    {
        $updates = [
            'name' => trim($validated['first_name'] . ' ' . $validated['last_name']),
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?: null,
            'last_name' => $validated['last_name'],
            'position' => $validated['position'] ?: null,
            'project' => $validated['project'] ?: null,
            'bureau' => $validated['bureau'] ?: null,
            'office' => $validated['office'] ?: null,
        ];

        if ($request->hasFile('profile_image')) {
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }

            $updates['avatar_path'] = $request->file('profile_image')->store('profile-images', 'public');
        }

        if ($request->hasFile('signature_image')) {
            if ($user->signature_path) {
                Storage::disk('public')->delete($user->signature_path);
            }

            $updates['signature_path'] = $request->file('signature_image')->store('signature-images', 'public');
        }

        $user->forceFill($updates)->save();

        $this->logActivity($user, 'profile_updated', 'Updated personal profile.');
    }

    public function logActivity(?User $user, string $action, string $description): void
    {
        if (!$this->safeHasTable('activity_logs')) {
            return;
        }

        $payload = [
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $optionalColumns = [
            'user_id' => $user?->id,
            'action' => $action,
            'event' => $action,
            'description' => $description,
            'details' => $description,
        ];



        foreach ($optionalColumns as $column => $value) {
            if ($this->safeHasColumn('activity_logs', $column)) {
                $payload[$column] = $value;
            }
        }

        try {
            DB::table('activity_logs')->insert($payload);
        } catch (\Throwable) {
        }
    }

    private function managedUsersQuery(string $search = '', ?string $scope = null, string $roleFilter = '')
    {
        return User::query()
            ->whereIn('role', $this->authFlowService->managedRoles())
            ->when($scope === 'archive', fn ($query) => $query->where('status', '!=', 'active'))
            ->when($scope === 'active', fn ($query) => $query->where('status', 'active'))
            ->when($roleFilter !== '', fn ($query) => $query->where('role', $roleFilter))
            ->when($search !== '', function ($query) use ($search) {
                $like = '%' . $search . '%';

                $query->where(function ($subQuery) use ($like) {
                    $subQuery
                        ->where('name', 'like', $like)
                        ->orWhere('email', 'like', $like)
                        ->orWhere('position', 'like', $like)
                        ->orWhere('project', 'like', $like)
                        ->orWhere('bureau', 'like', $like)
                        ->orWhere('division', 'like', $like)
                        ->orWhere('office', 'like', $like)
                        ->orWhere('institution', 'like', $like);
                });
            });
    }

    private function auditActionMeta(string $action): array
    {
        $normalized = strtolower(trim($action));

        return match ($normalized) {
            'login' => ['label' => 'Login', 'icon' => '🔐', 'status' => 'Success', 'tone' => 'success'],
            'logout' => ['label' => 'Logout', 'icon' => '🚪', 'status' => 'Info', 'tone' => 'info'],
            'otp_requested', 'otp_resent' => ['label' => 'OTP Request', 'icon' => '🔑', 'status' => 'Info', 'tone' => 'info'],
            'profile_updated' => ['label' => 'Profile Update', 'icon' => '👤', 'status' => 'Updated', 'tone' => 'updated'],
            'user_created' => ['label' => 'Create Record', 'icon' => '➕', 'status' => 'Success', 'tone' => 'success'],
            'user_updated' => ['label' => 'Edit Record', 'icon' => '✏️', 'status' => 'Updated', 'tone' => 'updated'],
            'user_archived' => ['label' => 'Archive Record', 'icon' => '🗑️', 'status' => 'Warning', 'tone' => 'warning'],
            'user_restored' => ['label' => 'Restore Record', 'icon' => '♻️', 'status' => 'Success', 'tone' => 'success'],
            default => ['label' => ucwords(str_replace('_', ' ', $normalized ?: 'activity')), 'icon' => '📋', 'status' => 'Info', 'tone' => 'info'],
        };
    }

    private function auditDateFilterRange(string $value): array
    {
        return match ($value) {
            'today' => [now()->startOfDay(), now()->endOfDay()],
            'week' => [now()->startOfWeek(), now()->endOfWeek()],
            'month' => [now()->startOfMonth(), now()->endOfMonth()],
            default => [null, null],
        };
    }

    private function adminReportsQuery(?User $user = null, string $search = '', ?string $status = null, string $dateFilter = '')
    {
        return DB::table('reports')
            ->leftJoin('users', 'users.id', '=', 'reports.user_id')
            ->leftJoin('users as approvers', 'approvers.id', '=', 'reports.reviewed_by')
            ->whereIn('reports.status', ['pending', 'approved', 'for_revision'])
            ->when($user?->role === 'ph-admin', function ($query) use ($user) {
                $query->where(function ($scopedQuery) use ($user) {
                    $scopedQuery
                        ->where('reports.assigned_provincial_head_id', $user->id)
                        ->orWhere(function ($fallbackQuery) use ($user) {
                            $fallbackQuery
                                ->whereNull('reports.assigned_provincial_head_id')
                                ->where('users.office', $user->office);
                        });
                });
            })
            ->when($status !== null, fn ($query) => $query->where('reports.status', $status))
            ->when($dateFilter !== '', function ($query) use ($dateFilter) {
                $since = match ($dateFilter) {
                    'today' => now()->startOfDay(),
                    'week' => now()->subDays(7),
                    'month' => now()->subDays(30),
                    default => null,
                };

                if ($since) {
                    $query->whereRaw('COALESCE(reports.submitted_at, reports.created_at) >= ?', [$since]);
                }
            })
            ->when($search !== '', function ($query) use ($search) {
                $like = '%' . $search . '%';

                $query->where(function ($subQuery) use ($like) {
                    $subQuery
                        ->where('users.name', 'like', $like)
                        ->orWhere('reports.file_name', 'like', $like)
                        ->orWhere('reports.status', 'like', $like);
                });
            });
    }

    private function adminReportsDateRange(Request $request): array
    {
        $availableQuickFilters = [
            'today' => 'Today',
            'week' => 'This Week',
            'month' => 'This Month',
        ];
        $quickFilter = trim((string) $request->query('quick', ''));

        if (!array_key_exists($quickFilter, $availableQuickFilters)) {
            $quickFilter = '';
        }

        $fromDate = $this->parseReportDate($request->query('from_date'));
        $toDate = $this->parseReportDate($request->query('to_date'), true);

        if (($fromDate === null || $toDate === null) && $quickFilter !== '') {
            [$quickFrom, $quickTo] = $this->resolveQuickDateRange($quickFilter);
            $fromDate ??= $quickFrom;
            $toDate ??= $quickTo;
        }

        if ($fromDate && $toDate && $fromDate->gt($toDate)) {
            [$fromDate, $toDate] = [$toDate->copy()->startOfDay(), $fromDate->copy()->endOfDay()];
        }

        return [
            'from' => $fromDate,
            'to' => $toDate,
            'fromInput' => $fromDate?->format('Y-m-d') ?? trim((string) $request->query('from_date', '')),
            'toInput' => $toDate?->format('Y-m-d') ?? trim((string) $request->query('to_date', '')),
            'quick' => $quickFilter,
            'quickOptions' => $availableQuickFilters,
        ];
    }

    private function parseReportDate(?string $value, bool $endOfDay = false): ?Carbon
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        try {
            $date = Carbon::createFromFormat('Y-m-d', $value);
        } catch (\Throwable) {
            return null;
        }

        return $endOfDay ? $date->endOfDay() : $date->startOfDay();
    }

    private function resolveQuickDateRange(string $quickFilter): array
    {
        $today = now();

        return match ($quickFilter) {
            'today' => [$today->copy()->startOfDay(), $today->copy()->endOfDay()],
            'week' => [$today->copy()->startOfWeek(), $today->copy()->endOfWeek()],
            'month' => [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()],
            default => [null, null],
        };
    }

    private function safeHasTable(string $table): bool
    {
        static $cache = [];

        if (array_key_exists($table, $cache)) {
            return $cache[$table];
        }

        try {
            return $cache[$table] = Schema::hasTable($table);
        } catch (\Throwable) {
            return $cache[$table] = false;
        }
    }

    private function safeHasColumn(string $table, string $column): bool
    {
        static $cache = [];
        $key = $table . ':' . $column;

        if (array_key_exists($key, $cache)) {
            return $cache[$key];
        }

        try {
            return $cache[$key] = Schema::hasColumn($table, $column);
        } catch (\Throwable) {
            return $cache[$key] = false;
        }
    }
}
