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

        {{-- Toast notifications --}}
        <div id="toast-container" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[100] flex flex-col items-center gap-2 pointer-events-none"></div>

        <script>
            // Auto-show toast for Laravel session flash messages
            document.addEventListener('DOMContentLoaded', function() {
                @if(session('success'))
                    showToast(@json(session('success')));
                @endif
                @if(session('error'))
                    showToast(@json(session('error')), 'error');
                @endif
            });

            window.showToast = function(message, type) {
                type = type || 'success';
                var container = document.getElementById('toast-container');
                if (!container) return;

                var toast = document.createElement('div');
                toast.className = 'pointer-events-auto px-4 py-2.5 rounded-lg shadow-lg text-sm font-medium flex items-center gap-2 transform transition-all duration-300 translate-y-4 opacity-0';

                if (type === 'success') {
                    toast.className += ' bg-gray-800 text-white';
                    toast.innerHTML = '<svg class="w-4 h-4 text-green-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>' + message;
                } else if (type === 'error') {
                    toast.className += ' bg-red-600 text-white';
                    toast.innerHTML = '<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>' + message;
                }

                container.appendChild(toast);

                // Animate in
                requestAnimationFrame(function() {
                    toast.classList.remove('translate-y-4', 'opacity-0');
                });

                // Animate out after 2s
                setTimeout(function() {
                    toast.classList.add('translate-y-4', 'opacity-0');
                    setTimeout(function() { toast.remove(); }, 300);
                }, 2000);
            };
        </script>

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
