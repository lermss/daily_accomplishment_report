<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    @stack('styles')
</head>
<body class="@yield('body_class')">
    {{-- Shared shell for admin and super admin dashboard-style screens. --}}
    @yield('content')

    @stack('scripts')
</body>
</html>
