<x-layouts.app>
    <div class="container h-100 py-5">
        <h1 class="page-title p-0 m-0">Schedule Appointment</h1>
        <h2 class="section-title p-0 m-0">Customer Information</h2>
        <hr class="mb-4" style="border-top: 3px solid; border-color: #ca1212;">
        </hr>
        <form action="{{ route('cashier.appointment.store') }}" method="POST">
            @csrf
            <div class='row g-3 align-items-center mb-5'>
                <div class='col'>
                    <label class="form-label">Customer Name <span class='importance'>*</span></label>
                    <input type="text" name="name" class="form-control rounded-pill form-input">
                </div>
                <div class='col'>
                    <label class="form-label">Address <span class='importance'>*</span></label>
                    <input type="text" name="address" class="form-control rounded-pill form-input">
                </div>
                <div class='col'>
                    <label class="form-label">Contact Number <span class='importance'>*</span></label>
                    <input type="text" name="contact" class="form-control rounded-pill form-input">
                </div>
                <div>
                    <input name='appointment_date' type="hidden"
                        value='{{ old('appointment_date', Session('date_schedule')) }}'>
                    <input name='appointment_time' type="hidden"
                        value='{{ old('appointment_time', Session('appointment_time')) }}'>
                </div>
            </div>
            <h2 class="section-title">Fitting Details (Optional)</h2>
            <hr class="mb-4" style="border-top: 3px solid; border-color: #ca1212;">
            <div class="row g-3 align-items-center mb-5">
                <div class="col">
                    <label class="form-label" for="size">Size</label>
                    <select name="size" id="size" class="form-control rounded-pill form-input">
                        <option value="">Select size</option>
                    </select>
                </div>
                <div class="col">
                    <label class="form-label" for="height">Height (in feet)</label>
                    <select name="height" id="height" class="form-control rounded-pill form-input">
                        <option value="">Select height</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Style Preference</label>
                    <input type="text" name="preference" class="form-control rounded-pill form-input">
                </div>
            </div>
            {{-- <div class="row g-3 align-items-start mb-5"> <!-- Changed to align-items-start -->
                <!-- Left Column (Event Type and Date) -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Venue</label>
                        <input type="text" name="venue" class="form-control rounded-pill form-input">
                    </div>
                    <div>
                        <label class="form-label">Event Date</label>
                        <input type="date" name="event_date" class="form-control rounded-pill form-input">
                    </div>
                </div>

                <!-- Right Column (Style Preference) -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Reason For Renting</label>
                        <input type="text" name="reason_for_renting" class="form-control rounded-pill form-input">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Style Preference</label>
                        <input type="text" name="stylePreference" class="form-control rounded-pill form-input">
                    </div>
                    <!-- Empty div to occupy same vertical space as Event Date -->
                    <div style="visibility: hidden; height: 20px;"></div>
                </div>
            </div> --}}

            <div class="btn-group">
                <a href="{{ route('cashier.home') }}" class="btn" style='background-color: #973131; color: #ffff;'>←
                    Back</a>
                <button type='submit' class="btn" style='background-color: #973131; color: #ffff;'>Next →</button>
            </div>
        </form>
    </div>
</x-layouts.app>
