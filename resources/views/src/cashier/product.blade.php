<x-layouts.cashlayout>
    @push('styles')
    <link rel="stylesheet" href="/css/products/product.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/css/products/custom-filter.css">
    @endpush

    <div class="container">
        <div class="filters">
            <div class="styled-select">
                <select id="type-select" onchange="updateSubType()">
                    <option disabled selected>Select type</option>
                    <option value="gown">Gown</option>
                    <option value="tuxedo">Tuxedo</option>
                    <option value="barong">Barong</option>
                    <option value="filipiniana">Filipiniana</option>
                </select>
            </div>

            <div class="styled-select">
                <select id="subtype-select">
                    <option disabled selected>Select sub-type</option>
                </select>
            </div>

            <div class="search-container">
                <input type="text" placeholder="Search products..." />
                <span class="search-icon"><i class="fas fa-search"></i></span>
            </div>
        </div>

        <div class="product-grid">

            <div class="product-card" onclick="openPanel('/assets/images/h1.png', 'Long Gown', '₱ 3,500', 'Small', 'This is a gorgeous long gown, perfect for formal events. Designed to make a statement, this dress features a flattering silhouette and exquisite detailing that highlights timeless elegance. Whether it’s for a wedding, gala night, or any special occasion, this piece ensures you’ll stand out with grace and sophistication.')">
                <img src="/assets/images/h1.png" alt="Product Image">
                <div class="product-info">
                    <p class="product-name">Long Gown</p>
                    <p class="price">₱ 3 500</p>
                </div>
            </div>
            
            <div class="product-card" onclick="openPanel('/assets/images/h2.png', 'Grey Tuxedo', '₱ 3,500', 'Medium', 'A sharp and classy tuxedo perfect for formal occasions.')">
                <img src="/assets/images/h2.png" alt="Product Image">
                <div class="product-info">
                    <p class="product-name">Grey Tuxedo</p>
                    <p class="price">₱ 3 500</p>
                </div>
            </div>
            
            <div class="product-card" onclick="openPanel('/assets/images/h3.png', 'Ruffle Dress', '₱ 3,500', 'Medium', 'A playful ruffle dress that adds flair to any event.')">
                <img src="/assets/images/h3.png" alt="Product Image">
                <div class="product-info">
                    <p class="product-name">Ruffle Dress</p>
                    <p class="price">₱ 3 500</p>
                </div>
            </div>
            
            <div class="product-card" onclick="openPanel('/assets/images/h4.png', 'Barong', '₱ 3,500', 'Large', 'A traditional Filipino Barong Tagalog for cultural and formal events.')">
                <img src="/assets/images/h4.png" alt="Product Image">
                <div class="product-info">
                    <p class="product-name">Barong</p>
                    <p class="price">₱ 3 500</p>
                </div>
            </div>
            
            <div class="product-card" onclick="openPanel('/assets/images/h1.png', 'Dress', '₱ 3,500', 'Small', 'A simple and elegant dress for any occasion.')">
                <img src="/assets/images/h1.png" alt="Product Image">
                <div class="product-info">
                    <p class="product-name">Dress</p>
                    <p class="price">₱ 3 500</p>
                </div>
            </div>
            
            <div class="product-card" onclick="openPanel('/assets/images/h2.png', 'Cocktail', '₱ 3,500', 'Small', 'A stylish cocktail dress perfect for parties.')">
                <img src="/assets/images/h2.png" alt="Product Image">
                <div class="product-info">
                    <p class="product-name">Cocktail</p>
                    <p class="price">₱ 3 500</p>
                </div>
            </div>
            


        </div>



        <div id="sidePanel" class="side-panel">
            <button class="close-btn" onclick="closePanel()">&larr;</button>
            <div class="side-panel-content">
                <div class="panel-layout">
                    <div class="panel-image-section">
                        <img id="panelImage" src="" alt="Selected Product">
                    </div>
        
                    <div class="panel-info-section">
                        <h2 id="panelTitle">Product Name</h2>
                        <br>
                        <p class="panel-price" id="panelPrice">₱ 0.00</p>
        
                        
                        <div class="panel-section"> 
                            <h4>Size</h4>
                            <p id="panelSize" >Small</p>
                        </div>
                        
                        <br>
                        
                        <div class="panel-section">
                            <h4>Details</h4>
                            <br>
                            <p class="panel-description" id="panelDescription">Product details will be shown here.</p>
                        </div>  
                        
                        <br>
        
                        <form action="" method="GET">
                            <button type="submit" class="rent-button"><a href="{{ route('cashier.checkout')}}">rent</a></button>
                            
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
        
        <div id="overlay" class="overlay" onclick="closePanel()"></div>
        
        

    </div>

    <script>
        function updateSubType() {
            const type = document.getElementById('type-select').value;
            const subTypeSelect = document.getElementById('subtype-select');
            let options = [];

            subTypeSelect.innerHTML = '';

            const subTypes = {
                gown: ['Evening Gown', 'Cocktail Gown', 'Ball Gown', 'Wedding Gown', 'Bridesmaid Gown', 'Prom Gown'],
                tuxedo: ['Black Tuxedo', 'White Tuxedo', 'Velvet Tuxedo', 'Slim Fit Tuxedo', 'Classic Tuxedo', 'Modern Tuxedo'],
                barong: ['Barong Tagalog (traditional)', 'Modern Barong', 'Embroidered Barong', 'Formal Barong', 'Casual Barong'],
                filipiniana: ['Balintawak', 'Terno', 'Mestiza Dress', 'Maria Clara Gown', "Baro't Saya", 'Modern Filipiniana']
            };

            if (subTypes[type]) {
                options = subTypes[type];
            }

            options.forEach(option => {
                const opt = document.createElement('option');
                opt.value = option.toLowerCase().replace(/\s+/g, '-');
                opt.textContent = option;
                subTypeSelect.appendChild(opt);
            });
        }

        function openPanel(imgSrc, title, price, size, description) {
    document.getElementById('panelImage').src = imgSrc;
    document.getElementById('panelTitle').innerText = title;
    document.getElementById('panelPrice').innerText = price;
    document.getElementById('panelSize').innerText = size;
    document.getElementById('panelDescription').innerText = description;

    document.getElementById('sidePanel').classList.add('open');
    document.getElementById('overlay').classList.add('active');
}

function closePanel() {
    document.getElementById('sidePanel').classList.remove('open');
    document.getElementById('overlay').classList.remove('active');
}

    </script>
</x-layouts.cashlayout>