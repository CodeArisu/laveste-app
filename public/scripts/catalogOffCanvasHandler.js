document.addEventListener('DOMContentLoaded', function() {
    const rentButtons = document.querySelectorAll('.rent-btn');
    
    rentButtons.forEach(button => {
        button.addEventListener('click', function() {
            const title = this.getAttribute('data-title');
            const price = this.getAttribute('data-price');
            const status = this.getAttribute('data-status');
            const image = this.getAttribute('data-image');
            const description = this.getAttribute('data-description')
            const route = this.getAttribute('route');
            
            document.getElementById('catalogDetails').innerHTML = `
                <div class="container-fluid"> 
                    <div class="row g-4">      
                        <div class="col-md-6">
                            <!-- Column 1 content -->
                            <div class="h-100 rounded">
                                <img src=${image} class='w-100 h-100 rounded'>  
                            </div>
                        </div>
                        
                         <div class="col-md-6 d-flex flex-column">  
                            <div class="h-100 rounded">
                                <h4 class="fs-1">${title}</h4>
                                <p class="fs-4">Price: ${price}</p>
                                <p>Status: ${status}</p>

                                <p>${description}</p>
                            </div>
                            
                            <a href="${route}" class="btn btn-success mt-auto">Rent</a>
                        </div>
                    </div>
                </div>
            `;
        });
    });
});