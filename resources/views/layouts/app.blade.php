<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        {{-- Meta Tags --}}
        <meta charset="utf-8">
        <meta name="author" content="RODRIGO FREITAS">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Título Browser --}}
        <title>@yield('browser')</title>

        {{-- Favicon --}}
        <link rel="shortcut icon" href="{{ asset('img/internal/sis/logo.png?' . Illuminate\Support\Str::random(10) ) }}">

        {{-- Fonts --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- Scripts --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Bootstrap CSS | CDN --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">

        {{-- Bootstrap Icons | CDN --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

        {{-- CSS Pessoal --}}
        <link href="{{ asset('css/style.css ') }}" rel="stylesheet">

        {{-- Styles --}}
        @livewireStyles
		
		<meta name="theme-color" content="#212529">
    </head>

    <body class="font-sans antialiased">
        {{-- Banner --}}
        <x-banner />

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            {{-- Menu Navegação--}}
            @livewire('navigation-menu')

            {{-- Cabeçalho --}}
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
                        {{ $header }}

                        {{-- Menu --}}
                        <x-layout.menu/>
                    </div>
                </header>
            @endif

            {{-- Conteúdo --}}
            <main>
                <div class="py-2">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                            {{ $slot }}

                            @if(Auth()->User()->usergroup_id != App\Models\Usergroup::where('name', 'FUNCIONARIO')->first()->id)
                                {{-- Footer --}}
                                <x-layout.footer/>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Botão Up --}}
                <x-layout.button.up/>

                {{-- Botão Whatsapp --}}
                <x-layout.button.whatsapp/>
            </main>
        </div>

        {{-- jQuery Scripts --}}
        <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

        {{-- Bootstrap Scripts --}}
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js">></script>

        {{-- Person Scripts --}}
        <script src="{{ asset('js/script.js ') }}"></script>

        {{-- Scripts --}}
        @stack('script')

        {{-- Modals --}}
        @stack('modals')

        {{-- Livewire Scripts --}}
        @livewireScripts

        {{-- Script --}}
        @yield('script')
    </body>

    <footer class="bg-white dark:bg-gray-800 shadow">
        {{-- Copyright --}}
        <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8 text-center">
            © Copyright 2022 a {{ date('Y'); }}
            <br>
            by 
            <a class="fw-bold text-decoration-none" href="#">
                SIS INFORMÁTICA
            </a>
        </div>
    </footer>
</html>
