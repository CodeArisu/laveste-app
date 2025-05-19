document.addEventListener("DOMContentLoaded", function () {
    // Get the clear button and form
    const clearButton = document.querySelector(".clear-btn");
    const productForm = document.querySelector("form");

    if (!clearButton || !productForm) {
        console.error("Clear button or form not found");
        return;
    }

    clearButton.addEventListener("click", function (e) {
        e.preventDefault();
        console.log("Clear button clicked - resetting form");

        // 1. Reset all standard form inputs
        productForm.reset();

        // 2. Handle select elements specially
        const selectElements = productForm.querySelectorAll("select");
        selectElements.forEach((select) => {
            select.selectedIndex = 0; // Reset to first option
            select.dispatchEvent(new Event("change")); // Trigger change event
        });

        // 3. Clear and hide additional input fields
        const additionalInputs = [
            { name: "new_type", container: "new-type-container" },
            { name: "new_subtype", container: "new-subtype-container" },
        ];

        additionalInputs.forEach((input) => {
            const inputField = productForm.querySelector(
                `input[name="${input.name}"]`
            );
            if (inputField) {
                inputField.value = "";
                const container = document.getElementById(input.container);
                if (container) container.style.display = "none";
            }
        });

        // 4. Clear all error messages
        const errorMessages = productForm.querySelectorAll(".text-red-500");
        errorMessages.forEach((error) => {
            error.textContent = "";
            error.style.display = "none";
        });

        // 5. Clear any success messages
        const successMessages = document.querySelectorAll(".alert-success");
        successMessages.forEach((message) => message.remove());

        console.log("Form cleared successfully");
    });

    // For debugging
    console.log("Form clearing script initialized");
});