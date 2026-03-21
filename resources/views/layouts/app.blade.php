<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="font-sans antialiased text-gray-700 h-screen overflow-hidden">
        <div class="h-screen flex flex-col bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow shrink-0 h-16 relative z-10">
                    <div class="max-w-7xl mx-auto h-full px-4 sm:px-6 lg:px-8 flex items-center">
                        <div class="w-full">{{ $header }}</div>
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>
        <script type="module">
            import mermaid from 'https://cdn.jsdelivr.net/npm/mermaid@11/dist/mermaid.esm.min.mjs';
            mermaid.initialize({
                startOnLoad: true,
                theme: 'neutral',
                flowchart: { curve: 'basis', padding: 15 },
                themeVariables: {
                    fontFamily: 'Ubuntu, sans-serif',
                    fontSize: '14px',
                }
            });
            window.mermaid = mermaid;
        </script>
        @stack('scripts')
    </body>
</html>
