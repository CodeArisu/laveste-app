<x-layouts.admin>
    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/tables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/garment.css') }}">

    <link rel="stylesheet" href="/css/cashier/rentalside.css">
    @endpush

    <div class="product-page">
        <div class="header-section">
            <h2 class="section-title">Product Rented</h2>
        </div>

        <table id="rentals-table" class="product-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Price</th>
                    <th>Type</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <!-- Only one tbody here -->
                <tr class="product-row">
                    <td>0001</td>
                    <td>Jane Gow</td>
                    <td>3,600.00</td>
                    <td>Gown</td>
                    <td><span class="status good">Rented</span></td>
                </tr>
                <tr class="product-row">
                    <td>0001</td>
                    <td>Shienna Laredo</td>
                    <td>3,600.00</td>
                    <td>Filipiniana</td>
                    <td><span class="status good">Missing</span></td>
                </tr>
            </tbody> <!-- Only one tbody here -->
        </table>



        <div class="side-panel1" id="rentalSidePanel" style="display: none;">
            <a class="back-btn1" href="/admin/prodrented" >&#8592; Back</a>
            <div class="side-content1">
                <div class="image-container1">
                    <img src="/assets/images/cat1.png" alt="Product Image">
                </div>

                <div class="rent-section">
                    <h2 class="name1">Verry Pretty Long Gown</h2>
                    <br>
                    <h3>Rent Information</h3>
                    <div class="rent-section">
                        <p><span class="label1">Pickup Date</span> <span class="value1">March 23, 2025</span></p>
                        <p><span class="label1">Start Date</span> <span class="value1">March 23, 2025</span></p>
                        <p><span class="label1">Return Date</span> <span class="value1">March 27, 2025</span></p>
                        <p><span class="label1">Price</span> <span class="value1">₱3,500.00</span></p>
                    </div>
                </div>
            </div>

            <div class="info-section">
                <h3>Customer Information</h3>
                <p><span class="label1">Name</span> <span class="value1">John Doe</span></p>
                <p><span class="label1">Address</span> <span class="value1">Matina, Davao City</span></p>
                <p><span class="label1">Contact Information</span> <span class="value1">09640879654</span></p>
                <p><span class="label1">Mode of Delivery</span> <span class="value1">Delivery</span></p>
            </div>

            <div class="product-information">
                <h3>Product Information</h3>
                <div class="status-wrapper1">
                    <p class="lab">Status</p>
                    <span class="status-pill1">Damaged</span>
                </div>
                <div class="status-wrapper1">
                    <p class="descp">Description</p>
                    <span class="descp1">Burned</span>
                </div>
            </div>

            <br><br>
            <div class="but">
                <button class="archive">Archive</button>
                <button class="display">Display Product</button> <!-- not "dsiplay" -->
            </div>


        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const rentalRows = document.querySelectorAll("#rentals-table tbody tr");

            const rentalPanel = document.getElementById("rentalSidePanel");

            // Hide panels initially
            rentalPanel.style.display = "none";

            rentalRows.forEach(row => {
                row.addEventListener("click", () => {
                    rentalPanel.style.display = "block";
                });
            });

            appointRows.forEach(row => {
                row.addEventListener("click", () => {
                    rentalPanel.style.display = "none";
                });
            });

            // Optional: hide panels when switching tabs
            const tabs = document.querySelectorAll(".tab");
            tabs.forEach(tab => {
                tab.addEventListener("click", () => {
                    rentalPanel.style.display = "none";
                });
            });
        });
    </script>


</x-layouts.admin>