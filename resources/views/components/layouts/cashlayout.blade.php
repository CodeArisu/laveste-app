<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier</title>
    @stack('styles')
    <link rel="stylesheet" href="/css/cashier/cashierhead.css">
</head>

<body>
    <x-layouts.navbar/>

    <main id="cashier-container">
        {{ $slot }}
    </main>

    @stack('scripts')
    <script src="{{ asset('scripts/navbarHandler.js') }}"></script>
</body>
</html>