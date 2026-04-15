<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Super Admin')</title>
    @stack('styles')
</head>
<body class="@yield('body_class')">
    {{-- Shared layout for super admin authentication screens. --}}
    @yield('content')
    @stack('scripts')
</body>
</html>
