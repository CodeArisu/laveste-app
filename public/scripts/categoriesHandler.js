document.addEventListener("DOMContentLoaded", function () {
    // Show/hide new type field
    const typeSelect = document.getElementById("type");
    const newTypeContainer = document.getElementById("new-type-container");

    typeSelect.addEventListener("change", function () {
        newTypeContainer.style.display =
            this.value === "new_type" ? "block" : "none";
    });

    // Show/hide new subtype field
    const subtypeSelect = document.getElementById("sub-type");
    const newSubtypeContainer = document.getElementById(
        "new-subtype-container"
    );

    subtypeSelect.addEventListener("change", function () {
        newSubtypeContainer.style.display =
            this.value === "new_subtype" ? "block" : "none";
    });
});
