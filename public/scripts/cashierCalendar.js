let selectedDate = null;
let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();

document.addEventListener("DOMContentLoaded", function () {
    // Calendar variables

    console.log("DOM fully loaded and parsed");

    let currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();

    const tabs = document.querySelectorAll(".tab");
    const rentalsTable = document.getElementById("rentals-table");
    const appointmentsTable = document.getElementById("appointments-table");
    const bookRentalBtn = document.querySelector(".book-rental");
    const schedAppointmentBtn = document.querySelector(".sched-appointment");
    const appointmentPanel = document.getElementById("appointmentPanel"); // Make sure this exists

    // Get the calendar grid container
    const calendarGrid = document.getElementById("calendarGrid");

    const dateScheduleInput =
        document.getElementById("dateSchedule") ||
        document.createElement("input");

    if (!calendarGrid) {
        console.error("Calendar grid element not found");
        return;
    }

    // Initialize calendar
    generateCalendar(currentMonth, currentYear);

    // Date selection handler
    calendarGrid.addEventListener("click", function (e) {
        const dayElement = e.target.closest(".calendar-day");

        if (dayElement && !dayElement.classList.contains("disabled")) {
            const selectedDate = dayElement.getAttribute("data-date");

            // Format date for display (e.g., "September 15, 2023")
            const dateObj = new Date(selectedDate);
            const options = { year: "numeric", month: "long", day: "numeric" };
            const displayDate = dateObj.toLocaleDateString(undefined, options);

            // Set both value and display text
            dateScheduleInput.value = selectedDate; // For form submission
            dateScheduleInput.textContent = displayDate; // For visual display

            // If you want to show the date in the input's placeholder or value
            dateScheduleInput.setAttribute("placeholder", displayDate);
            // Or if it's a visible input field:
            dateScheduleInput.value = displayDate;

            // Visual feedback
            document.querySelectorAll(".calendar-day").forEach((day) => {
                day.classList.remove("selected");
            });
            dayElement.classList.add("selected");

            console.log("Date selected:", displayDate);
        }
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
                if (appointmentPanel) appointmentPanel.style.display = "none";
            } else {
                rentalsTable.style.display = "none";
                appointmentsTable.style.display = "table";
                bookRentalBtn.style.display = "none";
                schedAppointmentBtn.style.display = "block";
            }
        });
    });

    // Show appointment panel
    if (schedAppointmentBtn && appointmentPanel) {
        schedAppointmentBtn.addEventListener("click", function () {
            appointmentPanel.style.display = "block";
            // Reset selection when opening panel
            dateScheduleInput.value = "";
            document
                .querySelectorAll(".calendar-day.selected")
                .forEach((day) => {
                    day.classList.remove("selected");
                });
        });
    }

    // Calendar navigation
    document.getElementById("prevMonth").addEventListener("click", () => {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        generateCalendar(currentMonth, currentYear);
        // Clear selection when changing months
        dateScheduleInput.value = "";
    });

    document.getElementById("nextMonth").addEventListener("click", () => {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        generateCalendar(currentMonth, currentYear);
        // Clear selection when changing months
        dateScheduleInput.value = "";
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
        dayCell.className = "calendar-day"; // Add this class
        dayCell.textContent = i;

        // Add data-date attribute in YYYY-MM-DD format
        const dateStr = `${year}-${String(month + 1).padStart(2, "0")}-${String(
            i
        ).padStart(2, "0")}`;
        dayCell.setAttribute("data-date", dateStr);

        // Disable past dates
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        if (new Date(year, month, i) < today) {
            dayCell.classList.add("disabled");
        }

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
    const rentalRows = document.querySelectorAll(
        "#rentals-table tbody table-rows"
    );
    const appointRows = document.querySelectorAll(
        "#appointments-table tbody tr"
    );
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
