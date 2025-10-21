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
    <a href="{{ route('cashier.index') }}" 
       id="products-link"
       class="{{ request()->routeIs('cashier.index') ? 'active' : '' }}">
      Products
    </a>
  </li>
  <li>
    <a href="{{ route('cashier.home') }}" 
       id="transactions-link"
       class="{{ request()->routeIs('cashier.home') ? 'active' : '' }}">
      Transactions
    </a>
  </li>
</ul>


</nav>


<script>
    document.addEventListener('DOMContentLoaded', () => {
    const cashierImage = document.getElementById('cashierImage');
    const logoutMenu = document.getElementById('logoutMenu');

    cashierImage.addEventListener('click', () => {
      if (logoutMenu.style.display === 'block') {
        logoutMenu.style.display = 'none';
      } else {
        logoutMenu.style.display = 'block';
      }
    });

    // Optional: hide logout menu if clicked outside
    document.addEventListener('click', (e) => {
      if (!cashierImage.contains(e.target) && !logoutMenu.contains(e.target)) {
        logoutMenu.style.display = 'none';
      }
    });
  });
</script>