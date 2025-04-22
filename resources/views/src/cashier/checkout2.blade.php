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
            <a href="/cashier/checkout" class="back-arrow">&larr;</a>
            <h1>La Veste Rentals</h1>
            <h2>Checkout</h2>
        </header>
        

        <div class="checkout-body">
            <div class="shipping-section">
                <h3>Shipping information</h3>
                <label>Full name</label>
                <input type="text" placeholder="">

                <label>Address</label>
                <input type="text" placeholder="">

                <label>Phone number</label>
                <input type="text" placeholder="">

                <label>Payment Method</label>
                <select>
                    <option>Select payment method</option>
                    <option>Credit Card</option>
                    <option>GCash</option>
                    <option>Cash on Delivery</option>
                </select>

                <label>Leave note with your order</label>
                <textarea rows="4"></textarea>
            </div>

            <div class="order-section">
                <h3>Order</h3>
                <div class="order-item">
                    <img src="/assets/images/h1.png" alt="Ruffled One Shoulder Gown">
                    <div class="item-details">
                        <p class="item-name">Ruffled One Shoulder Gown</p>
                        <p class="item-size">Medium</p>
                        <p>Start date : <span>mm/dd/yyyy</span></p>
                        <p>Return date : <span>mm/dd/yyyy</span></p>
                    </div>
                    <div class="item-price">₱ 3,000.00</div>
                </div>

                <div class="discount-code">
                    <input type="text" placeholder="Discount Code">
                    <button>Apply</button>
                </div>

                <div class="order-summary">
                    <p>Sub total <span>₱ 3,000.00</span></p>
                    <p>VAT <span>₱ 100.00</span></p>
                    <p class="total">Total <span>₱ 3,100.00</span></p>
                </div>


                <form action="{{ url('/cashier/receipt') }}" method="GET">
                    <button type="submit" class="place-order">Place Order</button>
                </form>
                
            </div>
        </div>
    </div>
</body>
</html>
