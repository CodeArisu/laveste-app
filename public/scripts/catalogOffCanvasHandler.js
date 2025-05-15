document.addEventListener('DOMContentLoaded', function() {
    const rentButtons = document.querySelectorAll('.rent-btn');
    
    rentButtons.forEach(button => {
        button.addEventListener('click', function() {
            const title = this.getAttribute('data-title');
            const price = this.getAttribute('data-price');
            const image = this.getAttribute('data-image');
            const description = this.getAttribute('data-description')
            const size = this.getAttribute('data-size')
            const route = this.getAttribute('route');
            
            const type = this.getAttribute('data-type');
            const subtype = this.getAttribute('data-subtype');
            
            document.getElementById('catalogDetails').innerHTML = `
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
                                <h4 class="fs-1 pb-1">${title}</h4>
                                <div class='d-flex flex-column justify-content-between pb-3'>
                                    <span>
                                        Type: ${type}
                                    </span>
                                    <span>
                                        Subtypes: ${subtype}
                                    </span>
                                </div>
                                <p class="fs-4">Price: ${price}</p>
                                <p>Size: 
                                    <span class='border border-danger p-1 rounded-2 fw-bold'>${size}</span>
                                </p>
                                <span>
                                    Description:
                                    <p>${description}</p>
                                </span>
                            </div>
                            <a href="${route}" class="btn btn-success mt-auto">Rent</a>
                        </div>
                    </div>
                </div>`;    
        });
    });
});