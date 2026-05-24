@php
    $homePageUser = auth()->user() ?? ($user ?? null);

    if (! $homePageUser && session()->has('authenticated_user_id')) {
        $homePageUser = \App\Models\User::find(session('authenticated_user_id'));
    }

    $homePageRole = auth()->user()?->role ?? $homePageUser?->role;
    // ADD THIS CODE
    $staffPortalRouteBase = app(\App\Services\AuthFlowService::class)->staffPortalPrefix($homePageRole);
    $isStaffHome = in_array((string) $homePageRole, ['staff', 'interns', 'special_access'], true);
    $homePageCanAccessAudit = $canAccessAudit
        ?? in_array((string) $homePageRole, ['super_admin', 'hr-super-admin', 'admin', 'ph-admin'], true);
    $pageTitle = $title ?? 'Home Page';
@endphp

@if ($isStaffHome)
    <!DOCTYPE html>
    <html>
    <head>
        <title>Daily Accomplishment Report</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/index.css') }}">
        <link rel="stylesheet" href="{{ asset('css/shared-navbar.css') }}?v={{ filemtime(public_path('css/shared-navbar.css')) }}">
        <link rel="stylesheet" href="{{ asset('css/shared-homepage.css') }}?v={{ filemtime(public_path('css/shared-homepage.css')) }}">
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <style>
            .report-actions button{
            border:none;
            background:#f8f9fa;
            width:35px;
            height:35px;
            border-radius:8px;
            font-size:16px;
            margin-left:6px;
            display:flex;
            align-items:center;
            justify-content:center;
            transition:0.2s;
            }

            .report-actions button:hover{
            transform:scale(1.1);
            }

            .edit-btn{
            color:#198754;
            }

            .edit-btn:hover{
            background:#e8f5e9;
            }

            .delete-btn{
            color:#dc3545;
            }

            .delete-btn:hover{
            background:#fdecea;
            }

            .staff-notification-item{
            border:1px solid #eef1f4;
            border-radius:12px;
            padding:12px 14px;
            background:#fff;
            }

            .staff-notification-item + .staff-notification-item{
            margin-top:10px;
            }

            .staff-notification-status{
            font-size:12px;
            font-weight:600;
            }

            .staff-notification-status.approved{
            color:#198754;
            }

            .staff-notification-status.for_revision{
            color:#fd7e14;
            }

            navbar{
                position: sticky;
                top: 0;
                z-index: 1000;
            }
        </style>
    </head>

    <body>
        @include('partials.navbar-staff')

        <div>
            <div class="shared-homepage">
                @include('partials.homepage-content', ['dashboardRoute' => route($staffPortalRouteBase . '.dashboard')])
            </div>
        </div>

        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script>
            AOS.init({ duration: 800, once: true });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    </body>
    </html>
@else
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $pageTitle }}</title>
        <link rel="stylesheet" href="{{ asset('css/shared-navbar.css') }}?v={{ filemtime(public_path('css/shared-navbar.css')) }}">
        <link rel="stylesheet" href="{{ asset('css/shared-homepage.css') }}?v={{ filemtime(public_path('css/shared-homepage.css')) }}">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    </head>
    <body>
        <x-topbar
            active="home"
            :can-access-audit="$homePageCanAccessAudit"
            :user="$homePageUser"
        />

        <div class="page-shell shared-homepage">
            <main class="home-card">
                @include('partials.homepage-content', ['dashboardRoute' => route('dashboard')])
            </main>
        </div>

        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (window.AOS) {
                    window.AOS.init({ duration: 800, once: true });
                }
            });
        </script>
    </body>
    </html>
@endif
