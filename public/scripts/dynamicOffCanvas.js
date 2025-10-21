document.addEventListener("DOMContentLoaded", function () {
    const rentButtons = document.querySelectorAll(".product-row");

    rentButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const name = this.getAttribute("data-name");
            const rentedPrice = this.getAttribute("data-rented-price");
            const image = this.getAttribute("data-image");
            const description = this.getAttribute("data-description");
            const condition = this.getAttribute("data-condition");
            const size = this.getAttribute("data-size");

            document.getElementById("catalogDetails").innerHTML = `
                <div class="container-fluid color-default"> 
                    <div class="row g-4">      
                        <div class="col-md-6">
                            <!-- Column 1 content -->
                            <div class="h-100 rounded">
                                <img src=${image} class='shadow shadow-md w-100 h-100 rounded' style='max-height: 30em;'>  
                            </div>
                        </div>
                        <div class="col-md-6 d-flex flex-column">  
                            <div class="h-100 rounded">
                                <h4 class="fs-1 pb-1">${name}</h4>
                                <p class="fs-4">Rent Price: ${rentedPrice}</p>
                                <p>Condition: 
                                    <span class='bg-success px-3 py-1 text-white rounded-2 fw-bold'>${condition}</span>
                                </p>
                                <p>Size: 
                                    <span class='border border-danger px-2 py-1 rounded-2 fw-bold'>${size}</span>
                                </p>
                                <span>
                                    Description:
                                    <p>${description}</p>
                                </span>
                            </div>
                            <a href="#" class="btn btn-warning mt-auto">Update</a>
                        </div>
                    </div>
                </div>
            `;
        });
    });
});
