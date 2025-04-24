<x-layouts.admin>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
    @endpush

    <div class="dashboard">
        <h2 class="dashboard-title">Dashboard</h2>
        <div class="dashboard-cards">
            <div class="card">
                <img src="{{ asset('assets/icons/d1.png') }}" alt="Sales Icon" class="card-icon">
                <div class="card-info">
                    <p class="card-label">Total Sales</p>
                </div>
            </div>
            <div class="card">
                <img src="{{ asset('assets/icons/d2.png') }}" alt="Products Icon" class="card-icon">
                <div class="card-info">
                    <p class="card-label">Total Products</p>
                    <span class="card-value">{{ $productCount }}</span>
                </div>
            </div>
            <div class="card">
                <img src="{{ asset('assets/icons/d3.png') }}" alt="Displayed Products Icon" class="card-icon">
                <div class="card-info">
                    <p class="card-label">Displayed Products</p>
                    <span class="card-value">0</span>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>
