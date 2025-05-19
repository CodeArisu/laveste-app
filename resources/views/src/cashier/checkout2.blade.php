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
                    <h3>Transaction information</h3>
                    <input name='catalog' value='{{ $catalog->id }}' hidden>

                    <label>Full name<span class='importance'>*</span></label>
                    <input type="text" placeholder="Name" value="{{ $customerData['name'] ?? 'no name input' }}" name="name">

                    <label>Address<span class='importance'>*</span></label>
                    <input type="text" placeholder="Address" value='{{ $customerData['address'] ?? 'no address input' }}' name="address">

                    <label>Amount<span class='importance'>*</span></label>
                    <input name='payment' type="number" placeholder="100.00">
                     @error('payment')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror

                    <label>Payment Method<span class='importance'>*</span></label>
                    <select name='payment_method' id="payment_method">
                        <option>Select payment method</option>
                        @foreach ($paymentMethods as $paymentMethod)
                            <option value='{{ $paymentMethod->value }}'>{{ ucfirst($paymentMethod->label()) }}</option>
                        @endforeach
                    </select>
                    @error('payment_method')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror

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
                        <input type="text" placeholder="Discount Code" name='discount_code' value='{{ old('discount_code') ?? null }}'>
                        <button>Apply</button>
                    </div>

                    <div class="order-summary">
                        <p>Sub total <span>{{ $catalog->getFormattedRentPrice() }}</span></p>
                        <p>VAT <span>12%</span></p>
                        <p class="total">Total <span>₱ {{ $totalPrice }}</span></p>
                    </div>
                    <button type="button" class="place-order" data-bs-toggle="modal" data-bs-target="#confirmationModal">Place Order</button>
                </div>
            </div>
            <x-fragments.confirmation-modal>
                <button type='submit' class="modal-confirm">Confirm</button>
            </x-fragments.confirmation-modal>
        </form>
    </div>
</x-layouts.app>