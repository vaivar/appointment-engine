<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'Appointment Engine')</title>

        <!-- Fonts
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet"> -->
        <link href="/css/normalize.css" rel="stylesheet">
        <link href="/css/index.css" rel="stylesheet">
        <link href="/css/elx-consultation-style.css" rel="stylesheet">
        <link href="/css/jquery-ui.min.css" rel="stylesheet">
        <link href="/css/jquery-ui.structure.min.css" rel="stylesheet">

        <script src="/js/jquery-3.5.1.min.js"></script>
        <script src="/js/jquery-ui.min.js"></script>
        <script src="/js/jquery.ui.datepicker-lt.js"></script>

        <style>
            body {
                font-family: 'Muli';
            }
        </style>
    </head>
    <body class="antialiased">
        @yield('content', 'This is some content')
        @yield('js')
    </body>
</html>