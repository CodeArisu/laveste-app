<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <link rel="stylesheet" href="{{ asset('css/cashier/receipt.css') }}?v={{ time() }}">
</head>

<body>
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
                <div class="order-number">Order #00001</div>
            </div>
            <div class="receipt-body">
                <p class="thanks">Thanks for your order!</p>
                <div class="item-section">
                    <img src="{{ asset('/assets/images/h1.png') }}" alt="Gown" class="item-image">
                    <div class="item-details">
                        <p class="item-name">Gown ito soaper wow and nice</p>
                        <p class="item-size">Medium</p>
                        <p>Start date: &nbsp; mm/dd/yyyy &emsp; <span class="price">₱ 3,000.00</span></p>
                        <p>Return date: mm/dd/yyyy</p>
                    </div>
                </div>
                <div class="billing-section">
                    <p class="label">Billed to:</p>
                    <p>Jhon Doe</p>
                    <p>Somewhere, Davao City</p>
                    <p>0912 231 4563</p>
                </div>
                <div class="summary">
                    <p>Shipping <span class="price">₱ 100.00</span></p>
                    <p class="total">Total Amount <span class="price bold">₱ 3,100.00</span></p>
                </div>
            </div>
        </div>
        <button class="print-btn">Print Receipt</button>
    </div>
</body>

</html>