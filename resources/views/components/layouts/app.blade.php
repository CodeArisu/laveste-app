<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>@yield('title', 'web')</title>

        @vite(['resource/css/app.css', 'resources/js/app.js', 'resources/scss/app.scss'])
    </head>
    <body class='container'>
        @isset($slot)
            {{ $slot }}
        @endisset
    </body>
</html>