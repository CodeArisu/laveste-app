<nav class="custom-navbar ">
    <div class="navdiv">
        <div class="logo"><a href="#">La Veste Rentals</a></div>
        @auth
            <div class="cashier">
                <h3>{{ Auth::user()->name }}</h3>
                <img src="/assets/images/catty.jpg" alt="CashierProfile" id="cashierImage">
               <div class="text-center" id="logoutMenu">
                    <form action="{{ route('logout') }}" method="POST" class="p-0 d-inline-block mt-3">
                        @csrf
                        <button type="submit" class="btn">Sign Out</button>
                    </form>
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
            <li>
                <a href="{{ route('cashier.index') }}" id="products-link">Products</a>
            </li>
            <li>
                <a href="{{ route('cashier.home') }}" id="transactions-link">Transactions</a>
            </li>
        </ul>
</nav>