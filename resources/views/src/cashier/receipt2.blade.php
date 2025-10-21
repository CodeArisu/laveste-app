<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
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
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="left-group">
                <a type='button' href="{{ route('cashier.home') }}" class="back">&#8592;</a>
                <div class="title">La Veste Rentals</div>
            </div>

            <div class="checkout">Checkout</div>
        </div>

        <br>
        <br>

        <div class="receipt-card" id='receipt-content'>
            <div class="receipt-header">
                <div class="receipt-title">RECEIPT</div>
                <div class="order-number">Order #00{{ $appointment->id }}</div>
            </div>
            <div class="receipt-body">
                <p class="thanks">Appointment</p>
                <div class="item-section">
                    <img src="{{ asset('/assets/images/h5.png') }}" alt="Gown" class="item-image">
                    <div class="item-details">
                        <p class="item-name">Appointment</p>
                        <p class="time">2 hours</p>
                        <p>Date : <span>{{ $appointment->appointment_date->format('F j, Y') }} </span></p>
                        <p>Time : <span>{{ $appointment->appointment_time->format('h:i:s A') }}</span></p>
                    </div>
                </div>
                <div class="billing-section">
                    <p class="label">Billed to:</p>
                    <p>{{ $appointment->customerDetail->name }}</p>
                    <p>{{ $appointment->customerDetail->address }}</p>
                    <p>{{ $appointment->customerDetail->contact }}</p>
                </div>
                <div class="summary">
                    <p class="total">Total Amount <span class="price bold">₱ 100</span></p>
                </div>
            </div>
        </div>
        <button onclick='window.print()' class="print-btn">Print Receipt</button>
    </div>

    <script>
        // Optional: Auto-focus the print button
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('button').focus();
        });
    </script>
</body>

</html>
