@push('styles')
    <link rel="stylesheet" href="/css/cashier/home.css">
    <link rel="stylesheet" href="/css/cashier/appointside.css">
    <link rel="stylesheet" href="/css/cashier/rentalside.css">
    <link rel="stylesheet" href="/css/cashier/appointment.css">
@endpush

<x-layouts.cashlayout>
    <div class="home-page">
        <div id="home-container">
            <!-- Tabs= -->
            <div class="tabs-container">
                <div class="tabs-wrapper">
                    <div class="tabs">
                        <div class="slider"></div>
                        <button class="tab active" data-tab="rentals">Rentals</button>
                        <button class="tab" data-tab="appointments">Appointment</button>
                    </div>
                </div>  
                <div class="buttons">
                    <a href="{{ route('cashier.index') }}" class="book-rental">
                        <img src="/assets/icons/hanger.png" alt="Book Rental Icon" class="icon"> Book Rental
                    </a>
                    <button class="sched-appointment" style="display: none;">
                        <img src="/assets/icons/appoint.png" alt="Schedule Appointment Icon" class="icon"> Schedule
                        Appointment
                    </button>
                </div>
            </div>
        </div>

        <div class="table-container">
            <!-- Rentals Table -->
            <table id="rentals-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Product</th>
                        <th>Customer Name</th>
                        <th>Amount Due</th>
                        <th>Pickup Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productRents as $productRent)
                        <tr>
                            <td>{{ $productRent->id }}</td>
                            <td>{{ $productRent->catalog->garment->product->product_name }}</td>
                            <td>{{ $productRent->customerRent->customerDetail->name }}</td>
                            <td>{{ $productRent->catalog->getFormattedRentPrice() }}</td>
                            <td>{{ $productRent->customerRent->convertDateFormat() }}</td>
                            <td>
                                @if($productRent->productRentedStatus->status_name === 'rented')
                                    <span class="status pending">Rented</span>
                                @elseif ($productRent->productRentedStatus->status_name === 'returned')
                                    <span class="status confirmed">Returned</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Appointments Table -->
            <table id="appointments-table" style="display: none;">
                <thead>
                    <tr>
                        <th>Appointment ID</th>
                        <th>Customer Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1001</td>
                        <td>Maria Santos</td>
                        <td>April 1, 2025</td>
                        <td>10:00 AM</td>
                        <td><span class="status confirmed">Confirmed</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="appointment-side-panel" id="appointmentPanel" style="display: none;">
            <button class="back-btn" id="backBtn">
                &#8592; Back
            </button>

            <h2>Schedule Appointment</h2>

            <div class="calendar-container">
                <div class="calendar-header">
                    <button id="prevMonth">&lt;</button>
                    <span id="monthYear">September 2025</span>
                    <button id="nextMonth">&gt;</button>
                </div>
                <div class="calendar-grid" id="calendarGrid">
                    <!-- Days will be generated here by JS -->
                </div>
            </div>

            <div class="card time-card">
                <select id="appointmentTime" class="time-select">
                    <option value="" disabled selected>Select time</option>
                    <option value="09:00">09:00-10:00 AM</option>
                    <option value="10:00">10:00-11:00 AM</option>
                    <option value="11:00">01:00-02:00 PM</option>
                    <option value="13:00">02:00-03:00 PM</option>
                    <option value="14:00">03:00-04:00 PM</option>
                    <option value="15:00">04:00-05:00 PM</option>
                </select>
            </div>

            <button class="next-btn" id="nextAppointmentBtn">
                Next <span>&#x2192;</span>
            </button>
        </div>

    </div>

    <div class="side-panel-container3">
        <div class="appointment-container3">
            <h1 class="page-title">Schedule Appointment</h1>
            <h2 class="section-title">Customer Information</h2>
            <form action="#" method="POST" class="appointment-form">
                <label class="form-label">Customer Name</label>
                <input type="text" name="customerName" class="form-input">

                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-input">

                <label class="form-label">Contact Number</label>
                <input type="text" name="contactNumber" class="form-input">

                <h2 class="section-title">Fitting Details</h2>

                <div class="inline-inputs">
                    <div class="input-group">
                        <label class="form-label" for="size">Size</label>
                        <select name="size" id="size" class="form-input">
                            <option value="">Select size</option>
                            <option value="xs">Extra Small</option>
                            <option value="s">Small</option>
                            <option value="m">Medium</option>
                            <option value="l">Large</option>
                            <option value="xl">Extra Large</option>
                            <option value="xxl">2XL</option>
                            <option value="xxxl">3XL</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label class="form-label" for="height">Height (in feet)</label>
                        <select name="height" id="height" class="form-input">
                            <option value="">Select height</option>
                        </select>
                    </div>
                </div>

                <div class="inline-inputs">
                    <div class="input-group">
                        <label class="form-label">Event Type</label>
                        <input type="text" name="eventType" class="form-input">
                    </div>
                    <div class="input-group">
                        <label class="form-label">Event Date</label>
                        <input type="date" name="eventDate" class="form-input">
                    </div>
                </div>

                <label class="form-label">Style Preference</label>
                <input type="text" name="stylePreference" class="form-input">

                <br><br>

                <div class="button-row3">
                    <a href="" class="back-btn3" id="backBtn3">← Back</a>
                    <a href="{{ route('appointment.checkout') }}" class="next-btn3" id="nextBtn3">Next →</a>
                </div>
            </form>
        </div>
    </div>




    <div class="side-panel1" id="rentalSidePanel" style="display: none;">
        <button class="back-btn" onclick="closePanel('rentalSidePanel')">&#8592; Back</button>
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


        <div class="billing-section">
            <h3>Billing</h3>
            <p><span class="label1">Sub Total</span> <span class="value1">₱3,500.00</span></p>
            <p><span class="label1">Shipping Fee</span> <span class="value1"> ₱100.00</span></p>
        </div>


        <div class="total-section">
            <a href="#" class="collect-button">Collected</a>
            <p><strong>Total:</strong> ₱3,600.00</p>
        </div>

    </div>



    <div class="side-panel" id="appointmentSidePanel" style="display: none;">
        <div class="image-container">
            <button class="back-btn" onclick="closePanel('appointmentSidePanel')">&#8592; Back</button>
            <img src="/assets/images/appside.png" alt="Product Image">
        </div>

        <div class="rent-section">
            <h3>User Information</h3>
            <div class="userinfo-section">
                <p><span class="label">Name</span> <span class="value">John Doe</span></p>
                <p><span class="label">Address</span> <span class="value">Matina, Davao City</span></p>
                <p><span class="label">Contact Information</span> <span class="value">09640879654</span></p>
            </div>
        </div>


        <div class="fitdetails-section">
            <h3>Fitting Details</h3>
            <div class="det1">
                <p><span class="label">Size</span> <span class="value">M</span></p>
                <p><span class="label">height</span> <span class="value">5'5 ft</span></p>
                <p><span class="label">Preferred Styles</span> <span class="value">Classy</span></p>
            </div>
            <div class="det2">
                <p><span class="label">Event Type</span> <span class="value">Wedding</span></p>
                <p><span class="label">Event Type</span> <span class="value">march31,2025</span></p>
            </div>
        </div>


        <div class="appointment-section">
            <h3>Appointment</h3>
            <p><span class="label">Date</span> <span class="value">March 23,2025</span></p>
            <p><span class="label">Time</span> <span class="value"> 9:00-11:00</span></p>
        </div>



        <div class="btn-section">
            <a href="#" class="cancel-button">Canceled</a>
            <a href="#" class="completed-button">Completed</a>
        </div>

    </div>

    <script>
        let selectedDate = null;
        let currentMonth = new Date().getMonth();
        let currentYear = new Date().getFullYear();

        document.addEventListener("DOMContentLoaded", function() {
            const tabs = document.querySelectorAll(".tab");
            const rentalsTable = document.getElementById("rentals-table");
            const appointmentsTable = document.getElementById("appointments-table");
            const bookRentalBtn = document.querySelector(".book-rental");
            const schedAppointmentBtn = document.querySelector(".sched-appointment");
            const appointmentPanel = document.getElementById("appointmentPanel");

            const backBtn = document.getElementById("backBtn");
            backBtn.addEventListener("click", function() {
                document.getElementById("appointmentPanel").style.display = "none";
            });

            // Tab switching logic
            tabs.forEach(tab => {
                tab.addEventListener("click", function() {
                    tabs.forEach(tab => tab.classList.remove("active"));
                    this.classList.add("active");

                    if (this.getAttribute("data-tab") === "rentals") {
                        rentalsTable.style.display = "table";
                        appointmentsTable.style.display = "none";
                        bookRentalBtn.style.display = "block";
                        schedAppointmentBtn.style.display = "none";
                        appointmentPanel.style.display = "none";
                    } else {
                        rentalsTable.style.display = "none";
                        appointmentsTable.style.display = "table";
                        bookRentalBtn.style.display = "none";
                        schedAppointmentBtn.style.display = "block";
                    }
                });
            });

            // Show appointment panel
            schedAppointmentBtn.addEventListener("click", function() {
                appointmentPanel.style.display = "block";
            });

            // Handle appointment confirmation
            document.getElementById("nextAppointmentBtn").addEventListener("click", () => {
                const time = document.getElementById("appointmentTime").value;

                if (!selectedDate || !time) {
                    alert("Please select both date and time.");
                    return;
                }

                const dateStr = selectedDate.toISOString().split("T")[0];
                alert(`Appointment scheduled on ${dateStr} at ${time}`);
            });


            // Calendar setup
            generateCalendar(currentMonth, currentYear);

            document.getElementById("prevMonth").addEventListener("click", () => {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                generateCalendar(currentMonth, currentYear);
            });

            document.getElementById("nextMonth").addEventListener("click", () => {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                generateCalendar(currentMonth, currentYear);
            });
        });

        function generateCalendar(month, year) {
            const calendarGrid = document.getElementById("calendarGrid");
            const monthYear = document.getElementById("monthYear");
            const date = new Date(year, month, 1);

            const days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
            calendarGrid.innerHTML = "";

            // Day labels
            days.forEach(day => {
                const dayLabel = document.createElement("div");
                dayLabel.style.fontWeight = "bold";
                dayLabel.textContent = day;
                calendarGrid.appendChild(dayLabel);
            });

            const firstDay = date.getDay();
            const totalDays = new Date(year, month + 1, 0).getDate();

            for (let i = 0; i < firstDay; i++) {
                calendarGrid.appendChild(document.createElement("div"));
            }

            for (let i = 1; i <= totalDays; i++) {
                const dayCell = document.createElement("div");
                dayCell.textContent = i;
                dayCell.addEventListener("click", function() {
                    document.querySelectorAll("#calendarGrid div").forEach(d => d.classList.remove("selected"));
                    this.classList.add("selected");
                    selectedDate = new Date(year, month, i);
                });
                calendarGrid.appendChild(dayCell);
            }

            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September",
                "October", "November", "December"
            ];
            monthYear.textContent = `${monthNames[month]} ${year}`;
        }


        document.addEventListener("DOMContentLoaded", function() {
            const rentalRows = document.querySelectorAll("#rentals-table tbody tr");
            const appointRows = document.querySelectorAll("#appointments-table tbody tr");

            const rentalPanel = document.getElementById("rentalSidePanel");
            const appointPanel = document.getElementById("appointmentSidePanel");

            // Hide panels initially
            rentalPanel.style.display = "none";
            appointPanel.style.display = "none";

            rentalRows.forEach(row => {
                row.addEventListener("click", () => {
                    rentalPanel.style.display = "block";
                    appointPanel.style.display = "none";
                });
            });

            appointRows.forEach(row => {
                row.addEventListener("click", () => {
                    rentalPanel.style.display = "none";
                    appointPanel.style.display = "block";
                });
            });

            // Optional: hide panels when switching tabs
            const tabs = document.querySelectorAll(".tab");
            tabs.forEach(tab => {
                tab.addEventListener("click", () => {
                    rentalPanel.style.display = "none";
                    appointPanel.style.display = "none";
                });
            });
        });

        document.addEventListener("click", (e) => {
            const clickedInsideRental = e.target.closest("#rentals-table");
            const clickedInsideAppoint = e.target.closest("#appointments-table");

            if (!clickedInsideRental && !clickedInsideAppoint) {
                rentalPanel.style.display = "none";
                appointPanel.style.display = "none";
            }
        });

        function closePanel(panelId) {
            document.getElementById(panelId).style.display = "none";
        }



        // Get the necessary elements
        const nextAppointmentBtn = document.getElementById('nextAppointmentBtn');
        const sidePanel = document.querySelector('.side-panel-container3');

        // Function to toggle side panel visibility
        function toggleSidePanel() {
            // Toggle visibility of the side panel
            sidePanel.style.display = (sidePanel.style.display === 'none' || sidePanel.style.display === '') ? 'flex' :
                'none';
        }

        // Initially hide the side panel when page loads
        sidePanel.style.display = 'none';

        // Add event listener to the Next button to toggle the side panel on click
        nextAppointmentBtn.addEventListener('click', toggleSidePanel);



        const heightSelect = document.getElementById('height');

        for (let feet = 4; feet <= 7; feet++) {
            for (let inches = 0; inches <= 11; inches++) {
                if (feet === 7 && inches > 0) break; // Limit to 7'0"
                const value = `${feet}-${inches}`;
                const text = `${feet}'${inches}"`;
                const option = new Option(text, value);
                heightSelect.appendChild(option);
            }
        }



        const tabs = document.querySelectorAll('.tab');
        const slider = document.querySelector('.slider');

        tabs.forEach((tab, index) => {
            tab.addEventListener('click', () => {
                document.querySelector('.tab.active')?.classList.remove('active');
                tab.classList.add('active');
                slider.style.left = `${index * 140 + 5}px`; // 140 = button width, 5 = padding
            });
        });



        
    </script>
</x-layouts.cashlayout>
