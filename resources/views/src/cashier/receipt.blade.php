<x-layouts.app>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/cashier/receipt.css') }}?v={{ time() }}">
    @endpush
    <div class="container">
        <div class="header">
            <div class="left-group">
                <a href="{{ route('cashier.home') }}" class="back">&#8592;</a>
                <div class="title">La Veste Rentals</div>
            </div>

            <div class="checkout">Checkout</div>
        </div>
        <br>
        <br>
        <div class="receipt-card">
            <div class="receipt-header">
                <div class="receipt-title">RECEIPT</div>
                <div class="order-number">Rent #{{ "00" . $transactions->id }}</div>
            </div>
            <div class="receipt-body">
                <p class="thanks">Thanks for your order!</p>
                <div class="item-section">
                    {{-- <img src="{{ asset('/assets/images/h1.png') }}" alt="Gown" class="item-image"> --}}
                    <div class="item-details">
                        <p class="item-name">{{ $transactions->getProductName() }}</p>
                        <p class="item-size">{{ $transactions->getClothingSize() }}</p>
                        <p>Start date: &nbsp; {{ $formattedDates['pickup_date'] }}&emsp; <span class="price">₱{{ $originalPrice }}</span></p>
                        <p>Return date: {{ $formattedDates['return_date'] }}</p>
                    </div>
                </div>
                <div class="billing-section">
                    <p class="label">Billed to:</p>
                    <p>{{ $transactions->productRent->customerRent->customerDetail->name }}</p>
                    <p>{{ $transactions->productRent->customerRent->customerDetail->address }}</p>
                    <p>{{ $transactions->productRent->customerRent->customerDetail->contact }}</p>
                </div>
                <div class="summary">
                    <p>VAT <span class="price">₱{{ $totalPrice - $originalPrice }}</span></p>
                    <p class="total">Total Amount <span class="price bold">₱{{ $totalPrice }}</span></p>
                    <p>Change: <span>₱{{ $totalChange }}</span></p>
                </div>
            </div>
        </div>
        <button class="print-btn">Print Receipt</button>
    </div>
</x-layouts.app>