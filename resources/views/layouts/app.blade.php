<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Solomon')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-page text-ink font-sans min-h-screen flex flex-col antialiased">
    @yield('topbar')

    <div class="max-w-[960px] w-full mx-auto px-5 sm:px-7 py-6 flex-1">
        @yield('content')
    </div>

    @yield('content-full')

    @yield('footer')

    @yield('scripts')
</body>
</html>