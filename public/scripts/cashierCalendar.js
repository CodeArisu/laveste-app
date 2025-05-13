let selectedDate = null;
let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();

document.addEventListener("DOMContentLoaded", function () {
    const tabs = document.querySelectorAll(".tab");
    const rentalsTable = document.getElementById("rentals-table");
    const appointmentsTable = document.getElementById("appointments-table");
    const bookRentalBtn = document.querySelector(".book-rental");
    const schedAppointmentBtn = document.querySelector(".sched-appointment");
    const appointmentPanel = document.getElementById("appointmentPanel");

    const backBtn = document.getElementById("backBtn");
    backBtn.addEventListener("click", function () {
        document.getElementById("appointmentPanel").style.display = "none";
    });

    // Tab switching logic
    tabs.forEach((tab) => {
        tab.addEventListener("click", function () {
            tabs.forEach((tab) => tab.classList.remove("active"));
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
    schedAppointmentBtn.addEventListener("click", function () {
        appointmentPanel.style.display = "block";
    });

    // Handle appointment confirmation
    document
        .getElementById("nextAppointmentBtn")
        .addEventListener("click", () => {
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
    days.forEach((day) => {
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
        dayCell.addEventListener("click", function () {
            document
                .querySelectorAll("#calendarGrid div")
                .forEach((d) => d.classList.remove("selected"));
            this.classList.add("selected");
            selectedDate = new Date(year, month, i);
        });
        calendarGrid.appendChild(dayCell);
    }

    const monthNames = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
    ];
    monthYear.textContent = `${monthNames[month]} ${year}`;
}

document.addEventListener("DOMContentLoaded", function () {
    const rentalRows = document.querySelectorAll("#rentals-table tbody table-rows");
    const appointRows = document.querySelectorAll(
        "#appointments-table tbody tr"
    );

    const rentalPanel = document.getElementById("rentalSidePanel");
    const appointPanel = document.getElementById("appointmentSidePanel");

    // Hide panels initially
    rentalPanel.style.display = "none";
    appointPanel.style.display = "none";

    rentalRows.forEach((row) => {
        row.addEventListener("click", () => {
            rentalPanel.style.display = "block";
            appointPanel.style.display = "none";
        });
    });

    appointRows.forEach((row) => {
        row.addEventListener("click", () => {
            rentalPanel.style.display = "none";
            appointPanel.style.display = "block";
        });
    });

    // Optional: hide panels when switching tabs
    const tabs = document.querySelectorAll(".tab");
    tabs.forEach((tab) => {
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
const nextAppointmentBtn = document.getElementById("nextAppointmentBtn");
const sidePanel = document.querySelector(".side-panel-container3");

// Function to toggle side panel visibility
function toggleSidePanel() {
    // Toggle visibility of the side panel
    sidePanel.style.display =
        sidePanel.style.display === "none" || sidePanel.style.display === ""
            ? "flex"
            : "none";
}

// Initially hide the side panel when page loads
sidePanel.style.display = "none";

// Add event listener to the Next button to toggle the side panel on click
nextAppointmentBtn.addEventListener("click", toggleSidePanel);

const heightSelect = document.getElementById("height");

for (let feet = 4; feet <= 7; feet++) {
    for (let inches = 0; inches <= 11; inches++) {
        if (feet === 7 && inches > 0) break; // Limit to 7'0"
        const value = `${feet}-${inches}`;
        const text = `${feet}'${inches}"`;
        const option = new Option(text, value);
        heightSelect.appendChild(option);
    }
}

const tabs = document.querySelectorAll(".tab");
const slider = document.querySelector(".slider");

tabs.forEach((tab, index) => {
    tab.addEventListener("click", () => {
        document.querySelector(".tab.active")?.classList.remove("active");
        tab.classList.add("active");
        slider.style.left = `${index * 140 + 5}px`; // 140 = button width, 5 = padding
    });
});
