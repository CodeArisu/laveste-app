// clearForm.js
document.addEventListener('DOMContentLoaded', function() {
    // Get the clear button and form
    const clearBtn = document.querySelector('.clear-btn');
    const form = document.querySelector('form');

    if (clearBtn && form) {
        clearBtn.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent form submission
            
            // Reset the form (this clears all inputs)
            form.reset();
            
            // For select elements, set them back to their first option
            const selects = form.querySelectorAll('select');
            selects.forEach(select => {
                select.selectedIndex = 0;
            });
            
            // Clear any manually added type/subtype inputs if they're visible
            const newTypeInput = form.querySelector('input[name="new_type"]');
            const newSubtypeInput = form.querySelector('input[name="new_subtype"]');
            
            if (newTypeInput) newTypeInput.value = '';
            if (newSubtypeInput) newSubtypeInput.value = '';
            
            // Clear any error messages
            const errorMessages = form.querySelectorAll('.text-red-500');
            errorMessages.forEach(error => {
                error.textContent = '';
            });
            
            // Clear the success message if present
            const successAlert = document.querySelector('.alert-success');
            if (successAlert) {
                successAlert.remove();
            }
        });
    }
});