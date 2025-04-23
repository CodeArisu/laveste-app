<x-layouts.app>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
    @endpush
    <div class="main">
        <header class="header">
            <h1 class="title">La Veste Rentals</h1>
            <div class="admin-info">
                <span>Admin Lara</span>
                <div class="avatar">
                    <img src="{{ asset('assets/images/catty.jpg') }}" alt="Admin Avatar">
                </div>
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
                    <li class="nav-item {{ request()->is('admin/garment') ? 'active' : '' }}"
                        onclick="window.location.href='/admin/garment'">
                        <img src="{{ asset('assets/icons/v3.png') }}" alt="Tag Icon" class="icon">
                    </li>
                    <li class="nav-item {{ request()->is('admin/transactions') ? 'active' : '' }}"
                        onclick="window.location.href='/admin/transactions'">
                        <img src="{{ asset('assets/icons/v4.png') }}" alt="Handshake Icon" class="icon">
                    </li>
                    <li class="nav-item {{ request()->is('/admin/prodrented') ? 'active' : '' }}"
                        onclick="window.location.href='/admin/prodrented'">
                        <img src="{{ asset('assets/icons/v5.png') }}" alt="File Icon" class="icon">
                    </li>
                    <li class="nav-item {{ request()->is('/admin/users') ? 'active' : '' }}"
                        onclick="window.location.href='/admin/users'">
                        <img src="{{ asset('assets/icons/v6.png') }}" alt="Users Icon" class="icon">
                    </li>
                </ul>
            </aside>

            <div class="content-area">
                {{ $slot }}
            </div>
        </div>
    </div>
</x-layouts.app>
