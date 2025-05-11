<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="{{ asset('css/cashier/checkout.css') }}?v={{ time() }}">
</head>

<body>
    <form action="{{ route('cashier.details.store', ['catalogs' => $catalogs]) }}" method='POST'>
        @csrf
        <div class="rental-header">
            <a href="{{ url()->previous() }}" class="back">&#8592;</a>
            <h1>Rental Details</h1>
        </div>

        <div class="section-title">Rented Date</div>

        <div class="form-container">
            <div class="calendar-container">
                <div class="calendar">
                    <div class="calendar-header">
                        <button id="prevMonth">&lt;</button>
                        <h3 id="monthLabel">Month</h3>
                        <button id="nextMonth">&gt;</button>
                    </div>
                    <table id="calendarTable"></table>
                </div>
            </div>

            <div class="date-inputs">
                <label for="start">Start</label>
                <input name='rented_date' type="text" id="start" placeholder="Select start date" readonly>
                <label for="end">End</label>
                <input name='return_date' type="text" id="end" placeholder="Select end date" readonly>
            </div>

        </div>

        <div class="details-section">
            <div class="details-group">
                <div class="section-title">Customer Details</div>
                <label for="customer-name">Customer Name</label>
                <input name='name' type="text" id="customer-name">

                <label for="address">Address</label>
                <input name='address' type="text" id="address">

                <label for="contact">Contact Number</label>
                <input name='contact' type="tel" id="contact">

                <label>Regular Customer</label>
                <div class="radio-group">
                    <input name='is_regular' type="radio" id="yes" name="regular" value="1">
                    <label for="yes">Yes</label>
                    <input name='is_regular' type="radio" id="no" name="regular" value="0">
                    <label for="no">No</label>
                </div>
            </div>

            <div class="details-group">
                <div class="section-title">Rental Details</div>

                <div class="date-pair">
                    <div class="input-block">
                        <label for="pickup">Pick - up Date</label>
                        <input name='pickup_date' type="date" id="pickup">
                    </div>

                    {{-- <div class="input-block">
                        <label for="return">Return Date</label>
                        <input name='return_date' type="date" id="return">
                    </div> --}}
                </div>
                

                <div class="input-block">
                    <label for="pickup">Pick - up Date</label>
                    <input type="date" id="pickup" readonly>
                    <label for="venue">Venue</label>
                    <input name='venue' type="text" id="venue">
                </div>

                <div class="input-block">
                    <label for="return">Return Date</label>
                    <input type="date" id="return" readonly>
                </div>
            </div>
        
            <div class="input-block">
                <label for="venue">Venue</label>
                <input type="text" id="venue">
            </div>
        
            <div class="input-block">
                <label for="reason">Reason for Renting</label>
                <input type="text" id="reason">
            </div>
        
            <div class="input-block">
                <label for="event-date">Event Date</label>
                <input type="date" id="event-date" readonly>
            </div>
        </div>
        
    </div>

    <div class="checkout-button">
        <button type="button">Checkout</button>
    </div>
    
    <script>
       const monthLabel = document.getElementById('monthLabel');
const calendarTable = document.getElementById('calendarTable');
const prevBtn = document.getElementById('prevMonth');
const nextBtn = document.getElementById('nextMonth');
const startInput = document.getElementById('start');
const endInput = document.getElementById('end');

// New inputs
const pickupInput = document.getElementById('pickup');
const eventDateInput = document.getElementById('event-date');
const returnInput = document.getElementById('return');

let currentDate = new Date();
let selectedStart = null;
let selectedEnd = null;

function formatDate(date) {
    const year = date.getFullYear();
    const month = ('0' + (date.getMonth() + 1)).slice(-2);
    const day = ('0' + date.getDate()).slice(-2);
    return `${year}-${month}-${day}`;
}

function renderCalendar(date) {
    calendarTable.innerHTML = '';

    const year = date.getFullYear();
    const month = date.getMonth();
    const firstDay = new Date(year, month, 1).getDay();
    const lastDate = new Date(year, month + 1, 0).getDate();

    monthLabel.textContent = `${date.toLocaleString('default', { month: 'long' })} ${year}`;

    // Weekday headers
    const daysRow = document.createElement('tr');
    ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'].forEach(d => {
        const th = document.createElement('td');
        th.textContent = d;
        daysRow.appendChild(th);
    });
    calendarTable.appendChild(daysRow);

    // Fill initial empty cells
    let row = document.createElement('tr');
    for (let i = 0; i < firstDay; i++) {
        row.appendChild(document.createElement('td'));
    }

    // Fill day cells
    for (let day = 1; day <= lastDate; day++) {
        const td = document.createElement('td');
        td.textContent = day;

        const fullDate = new Date(year, month, day);
        td.dataset.date = formatDate(fullDate);
        td.addEventListener('click', () => selectDate(fullDate));

        // Highlight selected range
        if (selectedStart && selectedEnd) {
            if (fullDate >= selectedStart && fullDate <= selectedEnd) {
                td.style.backgroundColor = '#e7c4a2';
            }
        }

        row.appendChild(td);

        if (row.children.length === 7) {
            calendarTable.appendChild(row);
            row = document.createElement('tr');
        }
    }

    // Append final row if needed
    if (row.children.length > 0) {
        calendarTable.appendChild(row);
    }
}

function selectDate(date) {
    selectedStart = date;

    // Calculate end date (+2 days)
    selectedEnd = new Date(date);
    selectedEnd.setDate(selectedEnd.getDate() + 2);

    // Cap end date at the end of the month
    const lastDateOfMonth = new Date(date.getFullYear(), date.getMonth() + 1, 0);
    if (selectedEnd > lastDateOfMonth) {
        selectedEnd = lastDateOfMonth;
    }

    // Fill rental period inputs
    startInput.value = formatDate(selectedStart);
    endInput.value = formatDate(selectedEnd);

    // Auto-fill pick-up, event, and return dates
    const pickUpDate = new Date(selectedStart);
    const eventDate = new Date(selectedStart);
    eventDate.setDate(eventDate.getDate() + 1);
    const returnDate = new Date(selectedStart);
    returnDate.setDate(returnDate.getDate() + 2);

    pickupInput.value = formatDate(pickUpDate);
    eventDateInput.value = formatDate(eventDate);
    returnInput.value = formatDate(returnDate);

    renderCalendar(currentDate);
}

// Navigation buttons
prevBtn.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar(currentDate);
});

nextBtn.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar(currentDate);
});

// Initial render
renderCalendar(currentDate);

        </script>

</body>

</html>
