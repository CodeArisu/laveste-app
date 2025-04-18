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
            
            <div class="cashier">
                <h3>Cashier Anna</h3>
                <img src="/assets/images/cash.jpg" alt="CashierProfile">
            </div>
        </div>

        <ul class="nav-links">
            <li><a href="/cashier/home" id="home-link">Home</a></li>
            <li><a href="/cashier/products" id="products-link">Products</a></li>
            <li><a href="/cashier/transactions" id="transactions-link">Transactions</a></li>
        </ul>
    </nav>

    
    <main id="cashier-container">
        {{ $slot }}
    </main>

    <script>
        const currentUrl = window.location.pathname;
        const links = document.querySelectorAll('.nav-links li a');

        links.forEach(link => link.classList.remove('active'));

        if (currentUrl.includes('home')) {
            document.getElementById('home-link').classList.add('active');
        } else if (currentUrl.includes('products')) {
            document.getElementById('products-link').classList.add('active');
        } else if (currentUrl.includes('transactions')) {
            document.getElementById('transactions-link').classList.add('active');
        }

        links.forEach(link => {
            link.addEventListener('click', function() {
                links.forEach(link => link.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>
