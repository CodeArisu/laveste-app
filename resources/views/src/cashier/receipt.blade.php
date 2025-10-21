<x-layouts.app>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/cashier/receipt.css') }}?v={{ time() }}">

        <style>
            @media print {
                body * {
                    visibility: hidden;
                }

                #receipt-content,
                #receipt-content * {
                    visibility: visible;
                }

                #receipt-content {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                }
            }
        </style>
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
        <div class="receipt-card" id='receipt-content'>
            <div class="receipt-header">
                <div class="receipt-title">RECEIPT</div>
                <div class="order-number">Rent #{{ '00' . $transactions->id }}</div>
            </div>
            <div class="receipt-body">
                <p class="thanks">Thanks for your order!</p>
                <div class="item-section">
                    {{-- <img src="{{ asset('/assets/images/h1.png') }}" alt="Gown" class="item-image"> --}}
                    <div class="item-details">
                        <h2 class="item-name">{{ ucfirst($transactions->getProductName()) }}</h2>
                        <span class="item-size fs-5">{{ ucfirst($transactions->getClothingSize()) }}</span>
                        <div class='d-flex flex-column mt-2'>
                            <span class="price">Original Price: ₱{{ $originalPrice }}</span>
                            <span>Start date: {{ $formattedDates['pickup_date'] }}</span>
                            <span>Return date: {{ $formattedDates['return_date'] }}</span>
                        </div>

                    </div>
                </div>
                <div class="billing-section">
                    <p class="label">Billed to:</p>
                    <div class='d-flex flex-column mt-2'>
                        <span>{{ $transactions->productRent->customerRent->customerDetail->name }}</span>
                        <span>{{ $transactions->productRent->customerRent->customerDetail->address }}</span>
                        <span>{{ $transactions->productRent->customerRent->customerDetail->contact }}</span>
                    </div>
                </div>
                <div class="summary">
                    <p>VAT <span class="price">₱{{ $totalPrice - $originalPrice }}</span></p>
                    <p class="total">Total Amount <span class="price bold">₱{{ $transactions->total_amount }}</span></p>
                    <p>Change: <span>₱{{ $totalChange }}</span></p>
                </div>
            </div>
        </div>
        <button onclick='window.print()' class="print-btn">Print Receipt</button>
    </div>

    @push('scripts')
        <script>
            // Optional: Auto-focus the print button
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelector('button').focus();
            });
        </script>
    @endpush

</x-layouts.app>
