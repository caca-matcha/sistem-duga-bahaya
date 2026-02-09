<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:200,300,400,500,600,700,800&display=swap"
        rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #ffffff;
            background-image:
                radial-gradient(circle at center, #fff5f5 0%, #ffffff 100%),
                radial-gradient(#e5e7eb 1px, transparent 1px);
            background-size: 100% 100%, 24px 24px;
            background-attachment: fixed;
        }

        [x-cloak] {
            display: none !important;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-float-delayed {
            animation: float 6s ease-in-out infinite;
            animation-delay: 2s;
        }
    </style>
</head>

<body class="antialiased text-gray-900 min-h-screen relative overflow-x-hidden">
    <div class="relative z-10 w-full min-h-screen">
        {{ $slot }}
    </div>
</body>

</html>