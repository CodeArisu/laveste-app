<x-layouts.admin>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
    @endpush
    <div class="dashboard">

        @if (Session('success'))
            <x-fragments.alert-response message="{{ Session('success') }}" type="success" />
        @endif

        <h2 class="dashboard-title">Dashboard</h2>
        <div class="dashboard-cards">
            <div class="card">
                <div class="card-left">
                    <img src="{{ asset('assets/icons/d1.png') }}" alt="Sales Icon" class="card-icon">
                    <p class="card-label">Total Sales</p>
                </div>
                <span class="card-value">₱0.00</span>
            </div>
            <div class="card">
                <div class="card-left">
                    <img src="{{ asset('assets/icons/d2.png') }}" alt="Products Icon" class="card-icon">
                    <p class="card-label">Total Products</p>
                </div>
                <span class="card-value">{{ $productCount }}</span>
            </div>
            <div class="card">
                <div class="card-left">
                    <img src="{{ asset('assets/icons/d3.png') }}" alt="Displayed Products Icon" class="card-icon">
                    <p class="card-label">Displayed Products</p>
                </div>
                <span class="card-value">30</span>
            </div>
        </div>
    </div>
</x-layouts.admin>
