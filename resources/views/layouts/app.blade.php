<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jadwal Sholat & Imsak')</title>
    @vite('resources/css/app.css')
    <!-- Tambahkan Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @yield('styles')
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Prayer alert notification -->
    <div id="prayer-alert" class="hidden fixed w-full top-0 bg-emerald-600 text-white py-2 z-50">
        <div class="marquee-container overflow-hidden">
            <div class="marquee-content whitespace-nowrap">
                <span class="inline-block" id="prayer-message"></span>
            </div>
        </div>
    </div>

    @include('layouts.partials.nav')

    <main class="flex-grow">
        @yield('content')
    </main>

    @include('layouts.partials.footer')

    @yield('scripts')
</body>
</html>
