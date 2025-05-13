<x-layouts.app>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
    @endpush
    <div class="main">
        <header class="header">
            <h1 class="title">La Veste Rentals</h1>
            <div class="admin-info d-flex align-items-center">
                <span>Admin {{ Auth::user()->name }}</span>
                <div class="avatar">
                    <img src="{{ asset('assets/images/catty.jpg') }}" alt="Admin Avatar">
                    
                </div>
                <form action="{{ route('logout') }}" method="POST" class="p-0 d-inline-block">
                    @csrf
                    <button type="submit" class="btn">Sign Out</button>
                </form>
            </div>
        </header>

        <div class="main-container">
            <aside class="sidebar">
                <ul class="nav">
                    {{-- route to home --}}
                    <li class="nav-item {{ request()->is('/dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard.home') }}">
                            <img src="{{ asset('assets/icons/v1.png') }}" alt="Home Icon" class="icon">
                        </a>
                    </li>
                    {{-- route to products --}}
                    <li class="nav-item {{ request()->is('/dashboard/products') ? 'active' : '' }}">
                        <a href="{{ route('dashboard.product.index') }}">
                            <img src="{{ asset('assets/icons/v2.png') }}" alt="User Icon" class="icon">
                        </a>
                    </li>
                    <li class="nav-item {{ request()->is('/dashboard/garments') ? 'active' : '' }}">
                        <a href="{{ route('dashboard.garment.index') }}">
                            <img src="{{ asset('assets/icons/v3.png') }}" alt="Tag Icon" class="icon">
                        </a>
                    </li>
                    <li class="nav-item {{ request()->is('/dashboard/transactions') ? 'active' : '' }}">
                        <a href="{{ route('transactions') }}">
                            <img src="{{ asset('assets/icons/v4.png') }}" alt="Handshake Icon" class="icon">
                        </a>
                    </li>
                    <li class="nav-item {{ request()->is('/dashboard/rented') ? 'active' : '' }}">
                        <a href="{{ route('rented') }}">
                            <img src="{{ asset('assets/icons/v5.png') }}" alt="File Icon" class="icon">
                        </a>
                        
                    </li>
                    <li class="nav-item {{ request()->is('/dashboard/users') ? 'active' : '' }}">
                        <a href="{{ route('dashboard.users') }}">
                            <img src="{{ asset('assets/icons/v6.png') }}" alt="Users Icon" class="icon">
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
