<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        {{-- Meta Tags --}}
        <meta charset="utf-8">
        <meta name="author" content="SIS Informática">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Title Browser --}}
        <title>@yield('browser')</title>

        {{-- Favicon --}}
        <link rel="shortcut icon" href="{{ asset('img/internal/sis/favicon.ico?' . Illuminate\Support\Str::random(10)) }}">

        {{-- Fonts --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- Scripts --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Bootstrap CSS | CDN --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">

        {{-- Bootstrap Icons | CDN --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" rel="stylesheet">

        {{-- Person Styles --}}
        <link href="{{ asset('css/style.css ') }}" rel="stylesheet">
        
        {{-- Styles --}}
        @livewireStyles
    </head>

    <body class="font-sans antialiased">
        {{-- Conteúdo --}}
        <div>{{ $slot }}</div>

        {{-- paginação --}}
        <script type='text/php'>if (isset($pdf)) {$pdf->page_text(60, $pdf->get_height() - 50,"{PAGE_NUM} de {PAGE_COUNT}", null, 12, array(0,0,0));}</script>
    </body>
</html>
