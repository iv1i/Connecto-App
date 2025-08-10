<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="image/x-icon" rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <title>Connecto-app</title>
    @vite(['resources/css/app.css'])
    @vite(['resources/css/notif.css'])
    @vite(['resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen bg-gray-50">
@yield('content')

@stack('scripts')
</body>
</html>
