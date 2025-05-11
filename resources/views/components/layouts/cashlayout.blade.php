<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier</title>
    @stack('styles')
    <link rel="stylesheet" href="/css/cashier/cashierhead.css">
</head>

<body>
    <nav class="navbar">
        <div class="navdiv">
            <div class="logo"><a href="#">La Veste Rentals</a></div>

            @auth
            <div class="cashier">
                <h3>{{ Auth::user()->name }}</h3>
                <img src="/assets/images/catty.jpg" alt="CashierProfile" id="cashierImage">
                <div class="logout-menu" id="logoutMenu">
                    <a href="/login" style="color: #973131; text-decoration: none; font-weight: 400;">Logout</a>
                </div>
            </div>
            @else
            <div class="cashier">
                <h3>Cashier</h3>
                <img src="/assets/images/catty.jpg" alt="CashierProfile" id="cashierImage">
                <div class="logout-menu" id="logoutMenu">
                    <a href="/login" style="color: #973131; text-decoration: none; font-weight: 400;">Logout</a>
                </div>
            </div>
            @endauth

        </div>

        <ul class="nav-links">
            <li><a href="{{ route('cashier.home') }}" class="{{ request()->routeIs('cashier.home') ? 'active' : '' }}">Home</a></li>
            <li><a href="{{ route('cashier.index') }}" class="{{ request()->routeIs('cashier.index') ? 'active' : '' }}">Products</a></li>
            <li><a href="{{ route('cashier.transaction') }}" class="{{ request()->routeIs('cashier.transaction') ? 'active' : '' }}">Transactions</a></li>
        </ul>
        
    </nav>

    <main id="cashier-container">
        {{ $slot }}
    </main>

    <script>
        

        document.addEventListener('DOMContentLoaded', function() {
    const image = document.getElementById('cashierImage');
    const menu = document.getElementById('logoutMenu');

    // Make sure it's hidden when page loads
    menu.style.display = 'none';

    image.addEventListener('click', function(event) {
        event.stopPropagation(); // Prevent event bubbling
        menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
    });

    // Hide when clicking outside
    document.addEventListener('click', function(event) {
        if (!menu.contains(event.target) && !image.contains(event.target)) {
            menu.style.display = 'none';
        }
    });
});




    </script>
</body>

</html>