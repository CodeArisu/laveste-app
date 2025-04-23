<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
    @stack('styles')
</head>
<body>

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

        <div class="container">
            <aside class="sidebar">
                <ul class="nav">
                    <li class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}" onclick="window.location.href='/admin/dashboard'">
                        <img src="{{ asset('assets/icons/v1.png') }}" alt="Home Icon" class="icon">
                    </li>                
                    <li class="nav-item {{ request()->is('admin/adproduct') ? 'active' : '' }}" onclick="window.location.href='/admin/adproduct'">
                        <img src="{{ asset('assets/icons/v2.png') }}" alt="User Icon" class="icon">
                    </li>
                    <li class="nav-item {{ request()->is('admin/garment') ? 'active' : '' }}" onclick="window.location.href='/admin/garment'">
                        <img src="{{ asset('assets/icons/v3.png') }}" alt="Tag Icon" class="icon">
                    </li>
                    <li class="nav-item {{ request()->is('admin/transactions') ? 'active' : '' }}" onclick="window.location.href='/admin/transactions'">
                        <img src="{{ asset('assets/icons/v4.png') }}" alt="Handshake Icon" class="icon">
                    </li>
                    <li class="nav-item {{ request()->is('/admin/prodrented') ? 'active' : '' }}" onclick="window.location.href='/admin/prodrented'">
                        <img src="{{ asset('assets/icons/v5.png') }}" alt="File Icon" class="icon">
                    </li>
                    <li class="nav-item {{ request()->is('/admin/users') ? 'active' : '' }}" onclick="window.location.href='/admin/users'">
                        <img src="{{ asset('assets/icons/v6.png') }}" alt="Users Icon" class="icon">
                    </li>
                </ul>
            </aside>

            <div class="content-area">
                {{ $slot }}
            </div>
        </div>
    </div>

</body>
</html>
