<x-layouts.app>
    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
    @endpush
    <div class="main">
        <header class="header">
            <h1 class="title">La Veste Rentals</h1>
            <div class="admin-info">
                <span>Admin {{ Auth::user()->name }}</span>
                <div class="avatar">
                    <img src="{{ asset('assets/images/catty.jpg') }}" alt="Admin Avatar">
                </div>
            </div>
        </header>

        <div class="main-container">
            <aside class="sidebar">
                <ul class="nav">
                    <li class="nav-item {{ request()->routeIs('dashboard.home') ? 'active' : '' }}">
                        <a href="{{ route('dashboard.home') }}">
                            <img src="{{ asset('/assets/icons/i2.png') }}" alt="Home">
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('dashboard.product.index') ? 'active' : '' }}">
                        <a href="{{ route('dashboard.product.index') }}">
                            <img src="{{ asset('/assets/icons/i3.png') }}" alt="Products">
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('dashboard.garment.index') ? 'active' : '' }}">
                        <a href="{{ route('dashboard.garment.index') }}">
                            <img src="{{ asset('/assets/icons/i4.png') }}" alt="Garments">
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('transactions') ? 'active' : '' }}">
                        <a href="{{ route('transactions') }}">
                            <img src="{{ asset('/assets/icons/i5.png') }}" alt="Transactions">
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('rented') ? 'active' : '' }}">
                        <a href="{{ route('rented') }}">
                            <img src="{{ asset('/assets/icons/i6.png') }}" alt="Rented">
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('dashboard.users') ? 'active' : '' }}">
                        <a href="{{ route('dashboard.users') }}">
                            <img src="{{ asset('/assets/icons/i7.png') }}" alt="Users">
                        </a>
                    </li>


                    <li class="nav-item">
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <img src="{{ asset('/assets/icons/i1.png') }}" alt="Logout">
                        </a>
                    </li>



                </ul>

            </aside>

            <div class="content-area">
                {{ $slot }}
            </div>
        </div>


    </div>
</x-layouts.app>