<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="@yield("meta-description")">

        <title>{{ config('app.name', 'Laravel') }} @yield("title")</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        @livewireStyles
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
        @stack('custom-styles') {{-- @push("custom-styles") ... @endpush --}}
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <livewire:layout.navigation />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            @livewireScripts

            <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    document.notyf = new Notyf();

                    window.addEventListener('show-toast', event => {
                        let data = event.__livewire.params[0];

                        document.notyf[data.type]({
                            message: data.message,
                            duration: 4000,
                            icon: false,
                            dismissible: true,
                        });
                    });

                    @if (session('toast'))
                        document.notyf.{{ session('toast')["type"] }}({
                            message: `{{ session('toast')["message"] }}`,
                            duration: 4000,
                            icon: false,
                            dismissible: true,
                        });
                    @endif
                });
            </script>

            @stack('custom-scripts') {{-- @push("custom-scripts") ... @endpush --}}
        </div>
    </body>
</html>
