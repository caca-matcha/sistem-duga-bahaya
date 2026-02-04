<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>

<body class="font-sans antialiased">
    <div class="flex h-screen bg-gray-200 overflow-hidden text-gray-900">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden transition-all duration-500 ease-in-out">
            <!-- Topbar (Fixed at the very top, never scrolls) -->
            <div class="flex-shrink-0 bg-white shadow-md z-50">
                @include('layouts.topbar')
            </div>

            <!-- Main Content Area (Scrollable) -->
            <div class="flex-1 overflow-y-auto bg-gray-50 flex flex-col">
                <!-- Page Heading (Sticky at the top of the scroll area) -->
                @isset($header)
                    <header class="bg-white/40 backdrop-blur-md border-b border-white/20 sticky top-0 z-40">
                        <div class="max-w-[1920px] mx-auto py-3 px-4 sm:px-8 lg:px-16">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>

</html>