document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".product-row, .appointment-row").forEach((row) => {
        row.addEventListener("click", function (e) {
            if (e.target.closest("button")) return;

            const type = this.getAttribute("data-type"); // Correct way to get data attribute
            const canvasBody = document.querySelector("#dynamicCanvas .offcanvas-body");

            if (type === "appointment") {
                // Render appointment data
                canvasBody.innerHTML = `
                    <div class="container-fluid color-default"> 
                        <div class="row g-4">      
                            <div class="col-md-6">
                                <div class="h-100 rounded">
                                    <img src=${this.getAttribute("data-image")} class='shadow shadow-md w-100 h-100 rounded' style='max-height: 30em;'>  
                                </div>
                            </div>
                            <div class="col-md-6 d-flex flex-column">  
                                <div class="h-100 rounded">
                                    <p>Status:
                                        <span class='bg-success px-3 py-1 text-white rounded-2 fw-bold'>${this.getAttribute("data-status")}</span>
                                    </p>
                                    <p>Appointment Date: 
                                        <span class='px-2 py-1 rounded-2 fw-bold'>${this.getAttribute("data-date")}</span>
                                    </p>
                                    <p>Appointment Time: 
                                        <span class='px-2 py-1 rounded-2 fw-bold'>${this.getAttribute("data-time")}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            } else if (type === "product") {
                // Render product rental data (your existing product code)
                canvasBody.innerHTML = `
                    <div class="container-fluid color-default"> 
                        <div class="row g-4">      
                            <div class="col-md-6">
                                <div class="h-100 rounded">
                                    <img src=${this.getAttribute("data-image")} class='shadow shadow-md w-100 h-100 rounded' style='max-height: 30em;'>  
                                </div>
                            </div>
                            <div class="col-md-6 d-flex flex-column">  
                                <div class="h-100 rounded">
                                    <h4 class="fs-1 pb-1">${this.getAttribute("data-name")}</h4>
                                    <p class="fs-4">Rent Price: ${this.getAttribute("data-rented-price")}</p>
                                    <p>Status: 
                                        <span class='bg-success px-3 py-1 text-white rounded-2 fw-bold'>${this.getAttribute("data-condition")}</span>
                                    </p>
                                    <p>Size: 
                                        <span class='border border-danger px-2 py-1 rounded-2 fw-bold'>${this.getAttribute("data-size")}</span>
                                    </p>
                                    <p>Rented Date: 
                                        <span class='px-2 py-1 rounded-2 fw-bold'>${this.getAttribute("data-start")}</span>
                                    </p>
                                    <p>Returned Date: 
                                        <span class='px-2 py-1 rounded-2 fw-bold'>${this.getAttribute("data-end")}</span>
                                    </p>
                                    <span>
                                        Description:
                                        <p>${this.getAttribute("data-description")}</p>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
        });
    });
});