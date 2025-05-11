<x-layouts.app>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/cashier/checkout2.css') }}?v={{ time() }}">
    @endpush

    <div class="checkout-container">
        <header class="checkout-header">
            <a href="{{ url()->previous() }}" class="back-arrow">&larr;</a>
            <h1>La Veste Rentals</h1>
            <h2>Checkout</h2>
        </header>
        <form action="{{ route('cashier.checkout.store', ['catalogs' => $catalog]) }}" method="POST">
            @csrf
            <div class="checkout-body">
                <div class="shipping-section">
                    <h3>Shipping information</h3>
                    <input name='catalog' value='{{ $catalog->id }}' hidden>

                    <label>Full name</label>
                    <input type="text" placeholder="Name" value="{{ $customerData['name'] ?? 'no name input' }}" name="name">

                    <label>Address</label>
                    <input type="text" placeholder="Address" value='{{ $customerData['address'] ?? 'no address input' }}' name="address">

                    <label>Amount</label>
                    <input name='payment' type="number" placeholder="100.00" >

                    <label>Payment Method</label>
                    <select name='payment_method' id="payment_method">
                        <option>Select payment method</option>
                        @foreach ($paymentMethods as $paymentMethod)
                            <option value='{{ $paymentMethod->value }}'>{{ ucfirst($paymentMethod->label()) }}</option>
                        @endforeach
                    </select>

                    <label>Leave note with your order</label>
                    <textarea rows="4"></textarea>
                </div>

                <div class="order-section">
                    <h3>Order</h3>
                    <div class="order-item">
                        <img src="{{ $catalog->getImageUrl() }}" alt="Ruffled One Shoulder Gown">
                        <div class="item-details">
                            <p class="item-name">{{ $catalog->garment->product->product_name }}</p>
                            <p class="item-size">{{ $catalog->garment->size->measurement }}</p>
                            <p>Start date : <span>{{ $formattedDates['rented_date'] }}</span></p>
                            <p>Return date : <span>{{ $formattedDates['return_date'] }}</span></p>
                        </div>
                        <div class="item-price">{{ $catalog->getFormattedRentPrice() }}</div>
                    </div>

                    <div class="discount-code">
                        <input type="text" placeholder="Discount Code">
                        <button>Apply</button>
                    </div>

                    <div class="order-summary">
                        <p>Sub total <span>{{ $catalog->getFormattedRentPrice() }}</span></p>
                        <p>VAT <span>25%</span></p>
                        <p class="total">Total <span>₱ {{ $totalPrice }}</span></p>
                    </div>
                    <button type="button" class="place-order" data-bs-toggle="modal" data-bs-target="#confirmationModal">Place Order</button>
                </div>
            </div>
            <x-fragments.confirmation-modal/>
        </form>
    </div>
</x-layouts.app>