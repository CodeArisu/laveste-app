<x-layouts.admin>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/tables.css') }}">
        <link rel="stylesheet" href="{{ asset('css/admin/garment.css') }}">
    @endpush

    <div class="product-page">
        <div class="header-section">
            <h2 class="section-title">Garments</h2>          
        </div>

        <table class="product-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Type</th>
                    <th>Sub-type</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody> <!-- Only one tbody here -->
                <tr class="product-row">
                    <td>0001</td>
                    <td>Very nice gown</td>
                    <td>3,600.00</td>
                    <td>Gown</td>
                    <td>Evening</td>
                    <td><span class="status good">Good</span></td>
                </tr>
                <tr class="product-row" >
                    <td>0002</td>
                    <td>Stylish dress</td>
                    <td>4,500.00</td>
                    <td>Dress</td>
                    <td>Casual</td>
                    <td><span class="status damaged">Damaged</span></td>
                </tr>
            </tbody> <!-- Only one tbody here -->
        </table>


        <div class="sidepanel">
            <a href="{{ url('/admin/garment') }}" class="back-btn">←</a>
            <br><br>
            <h3 class="sidepanel-title">Product Information</h3>
            <div class="sidepanel-body">
              <div class="sidepanel-content">
                <img src="/assets/images/h1.png" alt="Gown Image" class="product-img">
                <div class="product-info">
                  <h2 class="product-name">Very pretty cute gown</h2>
                  <br><br><br>
                  <div class="info-row">
                    <span class="label">Type</span>
                    <span class="value">Gown</span>
                  </div>
                  <div class="info-row">
                    <span class="label">Sub-type</span>
                    <span class="value">Evening Gown</span>
                  </div>
                  <div class="info-row">
                    <span class="label">Size</span>
                    <span class="value">Medium</span>
                  </div>
                  <div class="info-row">
                    <span class="label">Rental Price</span>
                    <span class="value">3,500.00</span>
                  </div>
                </div>
              </div>
          
              <div class="description-section">
                <h4>Description</h4>
                <br>
                <p>
                    This elegant gown features a chic one-shoulder design with cascading ruffles that add a dramatic flair and graceful movement. 
                    The bodice is form-fitting, accentuating the waist before flowing into a full-length skirt that sways beautifully with each step. 
                    Crafted from premium satin fabric, it offers a luxurious feel and subtle sheen under the light. 
                    Perfect for formal occasions, evening galas, or red-carpet events, this gown combines timeless sophistication with a touch of modern glamour — 
                    ensuring you make a lasting impression wherever you go.
                  </p>
                  
              </div>

              <br>
          
              <div class="status-section">
                <h4>Status</h4>
                <div class="status-pill">Good</div>
              </div>
          
              <div class="action-footer">
                <button class="add-button">Add to Display</button>
              </div>
            </div>
        
          </div>
          
        
        
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const rows = document.querySelectorAll('.product-row');
            const sidePanel = document.querySelector('.sidepanel');
    
            rows.forEach(row => {
                row.addEventListener('click', () => {
                    sidePanel.classList.add('visible');
                });
            });
        });
    </script>
</x-layouts.admin>
