// document.addEventListener("DOMContentLoaded", function () {
//     // Show product info sidepanel when a product row is clicked
//     const rows = document.querySelectorAll(".product-row");
//     const sidePanel = document.querySelector(".sidepanel");

//     rows.forEach((row) => {
//         row.addEventListener("click", () => {
//             sidePanel.classList.add("visible");
//         });
//     });

//     // Show edit garment panel when edit button is clicked
//     const editButton = document.querySelector(".edit-button");
//     const editGarmentPanel = document.getElementById("editGarmentPanel");

//     if (editButton) {
//         editButton.addEventListener("click", () => {
//             editGarmentPanel.style.display = "block";
//         });
//     }

//     // Hide edit garment panel when back button is clicked
//     const backBtn2 = document.querySelector(".back-btn2");

//     if (backBtn2) {
//         backBtn2.addEventListener("click", (e) => {
//             e.preventDefault();
//             editGarmentPanel.style.display = "none";
//         });
//     }
// });

$(document).ready(function () {
    $(".product-row").click(function () {
        const garmentId = $(this).data('garment-id');

        loadGarmentDetails(garmentId);
        loadEditForm(garmentId);
    });

    function loadGarmentDetails(garmentId) {
        $.ajax({
            url: `garment/${garmentId}/details`,
            type: "GET",
            success: function (response) {
                $("#garmentSidePanel").html(response);
            },
            error: function (xhr) {
                console.error(xhr.responseText);
            },
        });
    }

    function loadEditForm(garmentId) {
        $.ajax({
            url: `/garment/${garmentId}/edit`,
            type: "GET",

            success: function ($response) {
                $("#editGarmentPanel").html(response);
            },

            error: function (xhr) {
                console.error(xhr.responseText);
            },
        });
    }

    $(document).on("click", ".back-btn", function (e) {
        e.preventDefault();
        $("#garmentSidePanel").empty();
        $("#editGarmentPanel").empty();
        window.history.back();
    });
});