@push('styles')
    <link rel="stylesheet" href="/css/cashier/home.css">
    <link rel="stylesheet" href="/css/cashier/appointside.css">
    <link rel="stylesheet" href="/css/cashier/rentalside.css">
    <link rel="stylesheet" href="/css/cashier/appointment.css">
@endpush

<x-layouts.cashlayout>

    @if (Session('success') && !str_contains(Session('success'), 'logged in'))
        <x-fragments.alert-response message="{{ Session('success') }}" type='success' />
    @endif

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
                        <th>Transaction ID</th>
                        <th>Product</th>
                        <th>Customer Name</th>
                        <th>Amount Due</th>
                        <th>Pickup Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productRents as $productRent)
                        <tr class='product-row' data-bs-toggle='offcanvas' data-bs-target='#dynamicCanvas'
                            data-type="product"
                            data-name="{{ $productRent->catalog->garment->product->product_name }}"
                            data-rented-price="{{ $productRent->catalog->garment->getFormattedRentedPrice() }}"
                            data-image="{{ $productRent->catalog->garment->getImageUrl() }}"
                            data-description="{{ $productRent->catalog->garment->additional_description }}"
                            data-condition="{{ ucfirst($productRent->productRentedStatus->status_name) }}"
                            data-size="{{ $productRent->catalog->garment->size->measurement }}"
                            data-start="{{ $productRent->customerRent->convertStartDateFormat() }}"
                            data-end="{{ $productRent->customerRent->convertEndDateFormat() }}">
                            <td>{{ $productRent->id }}</td>
                            <td>{{ $productRent->catalog->garment->product->product_name }}</td>
                            <td>{{ $productRent->customerRent->customerDetail->name }}</td>
                            <td>{{ $productRent->catalog->getFormattedRentPrice() }}</td>
                            <td>{{ $productRent->customerRent->convertDateFormat() }}</td>
                            <td>
                                @if ($productRent->productRentedStatus->status_name === 'rented')
                                    <span class="status pending">Rented</span>
                                @elseif ($productRent->productRentedStatus->status_name === 'returned')
                                    <span class="status confirmed">Returned</span>
                                @endif
                            </td>
                            <td class='status_col'>
                                <form action="{{ route('cashier.receive', $productRent->id) }}" method='POST'>
                                    @if ($productRent->productRentedStatus->status_name === 'rented')
                                        @csrf
                                        <button type='submit' class='btn btn-danger'>Returned</button>
                                    @else
                                        <span>No action needed</span>
                                    @endif
                                </form>
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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($appointments as $appointment)
                        <tr class='appointment-row' data-bs-toggle='offcanvas' data-bs-target='#dynamicCanvas'
                        data-type="appointment"
                                data-image="{{ asset('assets/images/h5.png') }}"
                                data-date="{{ $appointment->appointment_date->format('F j, Y') }}"
                                data-time="{{ $appointment->appointment_time->format('h:i:s A')  }}"
                                data-status="{{ ucfirst($appointment->appointmentStatus->status_name) }}"
                            >
                            <td>{{ $appointment->id }}</td>
                            <td>{{ $appointment->customerDetail->name }}</td>
                            <td>{{ $appointment->appointment_date->format('F j, Y') }}</td>
                            <td>{{ $appointment->appointment_time->format('h:i:s A') }}</td>
                            <td>
                                @if ($appointment->appointmentStatus->id == 2)
                                    <span
                                        class="status pending">{{ ucfirst($appointment->appointmentStatus->status_name) }}</span>
                                @elseif ($appointment->appointmentStatus->id == 1)
                                    <span
                                        class="status confirmed">{{ ucfirst($appointment->appointmentStatus->status_name) }}</span>
                                @elseif ($appointment->appointmentStatus->id == 4)
                                    <span
                                        class="status bg-danger">{{ ucfirst($appointment->appointmentStatus->status_name) }}</span>
                                @endif
                            </td>
                            <td style="padding: 0;">
                                <div class='d-flex flex-row gap-2 align-items-center w-100 h-100' style="padding: 0;">
                                    @if ($appointment->appointmentStatus->id == 2)
                                        <form action="{{ route('cashier.appointment.completed', $appointment) }}"
                                            method='POST' class='p-0 m-0'>
                                            @csrf
                                            <button type='submit' class='btn btn-success'>Complete</button>
                                        </form>
                                        <form action="{{ route('cashier.appointment.cancelled', $appointment) }}"
                                            method='POST' class='p-0 m-0'>
                                            @csrf
                                            <button type='submit' class='btn btn-danger'>Cancel</button>
                                        </form>
                                    @else
                                        No action needed
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

          <x-fragments.catalog-off-canvas canvasId='dynamicCanvas' />

        <div class="appointment-side-panel" id="appointmentPanel" style="display: none;">
            <button class="back-btn" id="backBtn">
                &#8592; Back
            </button>

            @if ($errors->has('internal_error'))
                <div class="alert alert-danger text-wrap mt-1">
                    <strong>Error:</strong> {{ $errors->first('internal_error') }}
                    <p class='p-0 m-0'>{{ $errors->first('internal_error_description') }}</p>
                </div>
            @endif

            <form action="{{ route('cashier.appointment.session') }}" method="POST">
                @csrf
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
                    <input type="hidden" id="dateSchedule" name="date_schedule" placeholder="Select a date" readonly
                        class="date-display-input">
                </div>

                <div class="card time-card">
                    <select name="appointment_time" id="appointmentTime" class="time-select">
                        <option value="" disabled selected>Select time</option>
                        <option value="09:00 AM">09:00-10:00 AM</option>
                        <option value="10:00 AM">10:00-11:00 AM</option>
                        <option value="02:00 PM">02:00-03:00 PM</option>
                        <option value="03:00 PM">03:00-04:00 PM</option>
                    </select>
                </div>

                <button type='submit' class="next-btn" id="nextAppointmentBtn">
                    Next <span>&#x2192;</span>
                </button>
            </form>
        </div>
    </div>

    {{-- <div class="side-panel1" id="rentalSidePanel" style="display: none;">
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

    </div> --}}

    @push('scripts')
        <script src={{ asset('scripts/cashierCalendar.js') }}></script>
        <script src='{{ asset('scripts/appointmentCanvas.js') }}'></script>
    @endpush
</x-layouts.cashlayout>
