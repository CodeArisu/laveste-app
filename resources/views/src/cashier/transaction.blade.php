<x-layouts.cashlayout>
    @push('styles')
    <link rel="stylesheet" href="/css/cashier/home.css">
    <link rel="stylesheet" href="/css/cashier/appointside.css">
    <link rel="stylesheet" href="/css/cashier/rentalside.css">
    <link rel="stylesheet" href="/css/cashier/appointment.css">
    <link rel="stylesheet" href="/css/cashier/transaction.css">
    @endpush


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
                
            </div>
        </div>

        <div class="table-container">

            <div class="filter-container">
                <button class="filter-button" onclick="toggleFilterDropdown()">
                    <img src="/assets/icons/filter.png" alt="Filter" class="filter-icon">
                    <span>Filter</span>
                </button>
                

                <div class="filter-dropdown" id="filterDropdown" style="display: none;">
                    <ul>
                        <li><button class="filter-option" onclick="applyFilter('All')">All</button></li>
                        <li><button class="filter-option" onclick="applyFilter('Pending Pickup')">Pending Pickup</button></li>
                        <li><button class="filter-option" onclick="applyFilter('Rented Out')">Rented Out</button></li>
                        <li><button class="filter-option" onclick="applyFilter('Returned')">Returned</button></li>
                        <li><button class="filter-option" onclick="applyFilter('Overdue')">Overdue</button></li>
                        <li><button class="filter-option" onclick="applyFilter('Damaged')">Damaged</button></li>
                        <li><button class="filter-option" onclick="applyFilter('Missing')">Missing</button></li>
                        <li><button class="filter-option" onclick="applyFilter('Cancelled')">Cancelled</button></li>
                    </ul>
                </div>
            </div>
            
          
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
                    {{-- <tr class="rental-row">
                        <td>0001</td>
                        <td>Very nice gown</td>
                        <td>Juan Dela Cruz</td>
                        <td>3,600.00</td>
                        <td>March 20, 2025</td>
                        <td><span class="transaction_rentstatus">Overdue</span></td>
                    </tr> --}}

                    @foreach ($transactions as $transaction)
                        <tr class='rental-row'>
                            <td>{{ $transaction->id }}</td>
                            <td>{{ $transaction->getProductName(); }}</td>
                            <td>{{ $transaction->productRent->customerRent->customerDetail->name }}</td>
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
                        <td><span class="transaction_appstatus">Completed</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>



{{-- RENTAL SIDE PANEL --}}

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


        <div class="action-section">
            <a href="#" class="flag-button" onclick="openIncidentPanel()">Flag</a>
            <a href="#" class="completed-button1">Completed</a>
        </div>

    </div>


  
{{-- FILE INCIDENT SIDEPANEL  --}}
<div class="side-panel2" id="incidentSidePanel" style="display: none;">
    <div class="panel">
      <h1 class="title">File Incident</h1>
      
      <label class="section-title2">Incident Type</label>
      <div class="incident-type">
        <label><input type="radio" name="incident" /> Damaged</label>
        <label><input type="radio" name="incident" /> Missing</label>
      </div>
  
      <label for="date">Date</label>
      <input type="date" id="date" class="input-box"/>
  
      <label for="description">Description</label>
      <textarea id="description" class="textarea-box"></textarea>
  
      <div class="buttons">
        <button class="cancel" onclick="closePanel('incidentSidePanel')">Cancel</button>
        <button class="file">File</button>
      </div>
    </div>
  </div>


  <script>
    const tabs = document.querySelectorAll('.tab');
    const slider = document.querySelector('.slider');
    const rentalsTable = document.getElementById('rentals-table');
    const appointmentsTable = document.getElementById('appointments-table');
    const filterContainer = document.querySelector('.filter-container'); // Add this line
    const filterDropdown = document.getElementById('filterDropdown');


    tabs.forEach((tab, index) => {
        tab.addEventListener('click', () => {
            // Remove 'active' from any active tab
            document.querySelector('.tab.active')?.classList.remove('active');
            tab.classList.add('active');

            // Move the slider
            slider.style.left = `${index * 140 + 5}px`; // Adjust as needed

            // Show/Hide tables and filter button based on tab clicked
            const selectedTab = tab.getAttribute('data-tab');
            if (selectedTab === 'rentals') {
                rentalsTable.style.display = 'table';
                appointmentsTable.style.display = 'none';
                filterContainer.style.display = 'block'; // Show filter
            } else if (selectedTab === 'appointments') {
                rentalsTable.style.display = 'none';
                appointmentsTable.style.display = 'table';
                filterContainer.style.display = 'none'; // Hide filter
            }
        });
    });

    function toggleFilterDropdown() {
            const currentDisplay = filterDropdown.style.display;
            filterDropdown.style.display = currentDisplay === 'block' ? 'none' : 'block';
        }

        function applyFilter(status) {
    const rentalRows = document.querySelectorAll('.rental-row');
    rentalRows.forEach(row => {
        const statusCell = row.querySelector('.transaction_rentstatus');
        if (statusCell) {
            const rowStatus = statusCell.textContent.trim(); // trim any extra spaces
            if (status !== 'All' && rowStatus !== status) {
                row.style.display = 'none'; // Hide rows that don't match the filter
            } else {
                row.style.display = ''; // Show rows that match the filter
            }
        }
    });
    // Close the dropdown after applying filter
    filterDropdown.style.display = 'none';
}


    const rentalRows = document.querySelectorAll('.rental-row');
    const rentalSidePanel = document.getElementById('rentalSidePanel');

    rentalRows.forEach(row => {
        row.addEventListener('click', () => {
            rentalSidePanel.style.display = 'block';
        });
    });

    function openIncidentPanel() {
        document.getElementById('rentalSidePanel').style.display = 'none';
        document.getElementById('incidentSidePanel').style.display = 'block';
    }

    function closePanel(panelId) {
        document.getElementById(panelId).style.display = 'none';
    }
</script>

    
</x-layouts.cashlayout>