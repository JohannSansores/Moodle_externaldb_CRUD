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
<body class="font-sans text-gray-900 antialiased bg-white dark:bg-gray-900 dark:text-gray-100 flex flex-col" style="background-image: url({{ asset('images/edificio_central.jpg') }}); background-size: cover; background-position: center; background-attachment: fixed;">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        
        <div class="w-full sm:max-w-2xl mt-6 px-6 py-4 bg-white dark:bg-gray-800 overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
        
    </div>

    <!-- Footer -->
    <footer style="
        background:#002e5f;
        border-radius:30px;
        padding:20px 30px 20px;
        color:white;
        width:100%;
        box-sizing:border-box;
        font-family:Arial,sans-serif;
        overflow:hidden;
        margin-top:30px;
    ">
        <!-- Parte superior -->
        <div style="
            display:flex;
            flex-wrap:wrap;
            align-items:center;
            justify-content:space-between;
            gap:20px;
        ">
            <!-- Logo -->
            <div style="
                flex:0 0 200px;
                text-align:center;
            ">
                <img
                    src="{{ asset('images/logo-uady-blanco.png') }}"
                    alt="UADY"
                    style="
                        width:100%;
                        max-width:180px;
                        height:auto;
                    ">
            </div>
            <!-- Texto -->
            <div style="
                flex:1;
                min-width:280px;
                text-align:center;
            ">
                <p style="
                    margin:0 0 12px 0;
                    font-size:13px;
                    line-height:1.6;
                    color:white;
                    font-weight:500;
                ">
                    Esta página puede ser reproducida con fines no lucrativos,
                    siempre y cuando no se mutile, se cite la fuente completa
                    y su dirección electrónica.
                </p>
                <div style="
                    font-size:13px;
                    font-weight:600;
                    line-height:1.4;
                ">
                    Coordinación General de Tecnologías de Información
                </div>
            </div>
        </div>
        <!-- Separador -->
        <div style="
            height:1px;
            background:rgba(255,255,255,.20);
            margin:15px 0 15px;
        "></div>
        <!-- Parte inferior -->
        <div style="
            display:flex;
            flex-wrap:wrap;
            justify-content:space-between;
            align-items:center;
            gap:15px;
        ">
            <!-- Contacto -->
            <div style="
                display:flex;
                flex-wrap:wrap;
                align-items:center;
                gap:20px;
            ">
                <a href="https://uady.mx"
                    target="_blank"
                    style="
                        color:white;
                        text-decoration:none;
                        display:flex;
                        align-items:center;
                        gap:6px;
                        font-size:13px;
                        font-weight:600;
                    ">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="7.25"
                            stroke="currentColor"
                            stroke-width="1.5">
                        </circle>
                        <path d="M15.25 12C15.25 16.5 13.24 19.25 12 19.25C10.76 19.25 8.75 16.5 8.75 12C8.75 7.5 10.76 4.75 12 4.75C13.24 4.75 15.25 7.5 15.25 12Z"
                            stroke="currentColor"
                            stroke-width="1.5">
                        </path>
                        <path d="M5 12H19"
                            stroke="currentColor"
                            stroke-width="1.5">
                        </path>
                    </svg>
                    UADY
                </a>
                <a href="mailto:atencion.uadyvirtual@correo.uady.mx"
                    style="
                        color:white;
                        text-decoration:none;
                        display:flex;
                        align-items:center;
                        gap:6px;
                        font-size:13px;
                        font-weight:600;
                        word-break:break-word;
                    ">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24">
                        <path d="M4.75 7.75C4.75 6.64 5.64 5.75 6.75 5.75H17.25C18.35 5.75 19.25 6.64 19.25 7.75V16.25C19.25 17.35 18.35 18.25 17.25 18.25H6.75C5.64 18.25 4.75 17.35 4.75 16.25V7.75Z"
                            stroke="currentColor"
                            stroke-width="1.5">
                        </path>
                        <path d="M5.5 6.5L12 12.25L18.5 6.5"
                            stroke="currentColor"
                            stroke-width="1.5">
                        </path>
                    </svg>
                    atencion.uadyvirtual@correo.uady.mx
                </a>
            </div>
            <!-- Redes -->
            <div style="
                display:flex;
                align-items:center;
                gap:15px;
            ">
                <!-- Facebook -->
                <a href="https://www.facebook.com/uadyvirtual"
                    target="_blank"
                    style="
                        color:white;
                        text-decoration:none;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                    ">
                    <svg width="28" height="28"
                        viewBox="0 0 24 24"
                        fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12C2 16.84 5.44 20.87 10 21.8V14.89H7.5V12H10V9.8C10 7.3 11.49 5.92 13.78 5.92C14.87 5.92 16 6.11 16 6.11V8.56H14.75C13.52 8.56 13.14 9.32 13.14 10.1V12H15.88L15.44 14.89H13.14V21.8C17.7 20.87 21.14 16.84 21.14 12C21.14 6.48 16.66 2 12 2Z"/>
                    </svg>
                </a>
                <!-- YouTube -->
                <a href="https://www.youtube.com/@tecnologia_uady"
                    target="_blank"
                    style="
                        color:white;
                        text-decoration:none;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                    ">
                    <svg width="30" height="30"
                        viewBox="0 0 24 24"
                        fill="currentColor">
                        <path d="M21.58 7.19C21.35 6.33 20.67 5.65 19.81 5.42C18.25 5 12 5 12 5C12 5 5.75 5 4.19 5.42C3.33 5.65 2.65 6.33 2.42 7.19C2 8.75 2 12 2 12C2 12 2 15.25 2.42 16.81C2.65 17.67 3.33 18.35 4.19 18.58C5.75 19 12 19 12 19C12 19 18.25 19 19.81 18.58C20.67 18.35 21.35 17.67 21.58 16.81C22 15.25 22 12 22 12C22 12 22 8.75 21.58 7.19ZM10 15.5V8.5L16 12L10 15.5Z"/>
                    </svg>
                </a>
            </div>
        </div>
    </footer>
</body>
</html>