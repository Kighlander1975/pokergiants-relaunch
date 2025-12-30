<!doctype html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pokergiants.de - {{ $pageTitle ?? 'Startseite' }}</title>
    @vite(['resources/css/frontend/app.css', 'resources/js/app.js'])
</head>

<body class="flex flex-col min-h-screen">
    <x-navbar />
    @yield('content-title')
    <main class="flex-grow">
        <div class="glass-container mb-5">
            @yield('content-body')
        </div>
    </main>
    <x-footer />

    <div style="background: red; color: white; padding: 10px; position: fixed; top: 0; z-index: 9999;">
        Status: {{ Auth::check() ? 'Eingeloggt als ' . Auth::user()->email : 'Nicht eingeloggt' }}
    </div>

    @stack('scripts')
</body>

</html>