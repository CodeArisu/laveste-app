function updateTypeField() {
    const typeSelect = document.getElementById("type");
    const newTypeContainer = document.getElementById("newTypeContainer");

    if (typeSelect.value === "new_type") {
        typeSelect.style.display = "none";
        newTypeContainer.style.display = "block";
        document.getElementById("newTypeInput").focus();
    }
}

function updateSubtypeField() {
    const subtypeSelect = document.getElementById("sub-type");
    const newSubtypeContainer = document.getElementById("newSubtypeContainer");

    if (subtypeSelect.value === "new_subtype") {
        subtypeSelect.style.display = "none";
        newSubtypeContainer.style.display = "block";
        document.getElementById("newSubtypeInput").focus();
    }
}

// Optional: Add function to revert back to select if needed
function cancelNewType() {
    const typeSelect = document.getElementById("type");
    typeSelect.style.display = "block";
    typeSelect.value = "Select Type"; // Reset to default option
    document.getElementById("newTypeContainer").style.display = "none";
}

function cancelNewSubtype() {
    const subtypeSelect = document.getElementById("sub-type");
    subtypeSelect.style.display = "block";
    subtypeSelect.value = "Select Sub-type"; // Reset to default option
    document.getElementById("newSubtypeContainer").style.display = "none";
}
