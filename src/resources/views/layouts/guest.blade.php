<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <script src="https://cdn.tailwindcss.com"></script> 
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
        <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/favicon.ico') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @isset($head)
            {!! $head !!}
        @endisset
    </head>
<body class="font-sans text-gray-900 antialiased bg-white dark:bg-gray-900 dark:text-gray-100" style="background-image: url({{ asset('images/edificio_central.jpg') }}); background-size: cover; background-position: center; background-attachment: fixed;">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        
        <div class="w-full sm:max-w-2xl mt-6 px-6 py-4 bg-white dark:bg-gray-800 overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
        
    </div>
</body>
</html>