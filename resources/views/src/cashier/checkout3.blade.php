<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - La Veste Rentals</title>
    <link rel="stylesheet" href="{{ asset('css/cashier/checkout2.css') }}?v={{ time() }}">

</head>
<body>
    <div class="checkout-container">
        <header class="checkout-header">
            <a href="{{ route('cashier.home') }}" class="back-arrow">&larr;</a>
            <h1>La Veste Rentals</h1>
            <h2>Checkout</h2>
        </header>

        <div class="checkout-body">
            <div class="shipping-section">
                <h3>Billing Address</h3>
                <label>Full name</label>
                <input type="text" placeholder="">

                <label>Address</label>
                <input type="text" placeholder="">

                <label>Phone number</label>
                <input type="text" placeholder="">

                <label>Leave note with your order</label>
                <textarea rows="4"></textarea>
            </div>

            <div class="order-section">
                <h3>Order</h3>
                <div class="order-item">
                    <img src="/assets/images/h5.png" alt="Ruffled One Shoulder Gown">
                    <div class="item-details">
                        <p class="item-name">Appointment</p>
                        <p class="item-size">2 Hours</p>
                        <p>Date : <span>mm/dd/yyyy</span></p>
                        <p>Time : <span>09:00 - 11:00 AM</span></p>
                    </div>
                    <div class="item-price">₱ 3,000.00</div>
                </div>

                <div class="additional-info">
                    <p>Size : <span>Small</span></p>
                    <p>height : <span>5'6 ft</span></p>
                    <p>Preferred Style : <span>Luxurious</span></p>
                    <p>Event Type : <span>Prom Ball</span></p>
                    <p>Event Date : <span>mm/dd/yyyy</span></p>
                </div>

                {{-- <div class="discount-code">
                    <input type="text" placeholder="Discount Code">
                    <button>Apply</button>
                </div> --}}

                <div class="order-summary">
                    <p class="total">Total <span>₱ 3,100.00</span></p>
                </div>

                <form action="" method="GET">
                    <a type="button" href="{{ route('appointment.receipt') }}" class="place-order">Place Order</a>
                </form>
                
            </div>
        </div>
    </div>
</body>
</html>
