<!doctype html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pokergiants.de - {{ $pageTitle ?? 'Startseite' }}</title>
    @vite(['resources/css/frontend/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-navbar />
    @yield('content-title')
    <div class="glass-container mb-5">
        @yield('content-body')
    </div>
    <x-footer />

    <div style="background: red; color: white; padding: 10px; position: fixed; top: 0; z-index: 9999;">
        Status: {{ Auth::check() ? 'Eingeloggt als ' . Auth::user()->email : 'Nicht eingeloggt' }}
    </div>
</body>

</html>