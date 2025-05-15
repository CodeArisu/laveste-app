<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier</title>
    @stack('styles')
    <link rel="stylesheet" href="/css/cashier/cashierhead.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/js/app.js', 'resources/scss/app.scss'])
</head>

<body>
    <x-layouts.navbar/>

    <main id="cashier-container">
        {{ $slot }}
    </main>

    @stack('scripts')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('scripts/navbarHandler.js') }}"></script>
</body>
</html>