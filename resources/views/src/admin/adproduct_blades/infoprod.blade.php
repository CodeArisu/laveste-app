<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Information</title>
    <link rel="stylesheet" href="{{ asset('css/admin/infoprod.css') }}">
</head>

<body>

    <div class="container">



        <div class="product-section">
            <a href="{{ url('/admin/adproduct') }}" class="back-btn">← Back</a>
            <h3>Product Information</h3>
            <div class="product-content">
                <img src="/assets/images/h1.png" alt="Gown Image" class="product-image">
                <div class="product-details">
                    <h2>Very pretty cute gown</h2>
                    <br>
                    <p><strong>Type</strong>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Sub-type</strong></p>
                    <p>Gown &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Evening Gown</p>
                    <p><strong>Size</strong><br>Medium</p>
                    <p><strong>Rental Price</strong><br>3,500.00</p>
                </div>
            </div>
            <br><br>
            <div class="description">
                <h4>Description</h4>
                <p>
                    This elegant gown features a chic one-shoulder design with cascading ruffles, adding a touch of
                    sophistication and flair. Perfect for formal events, it combines style and grace, ensuring a
                    standout look for any special occasion.
                </p>
            </div>
        </div>


        <div class="supplier-section">
            <br>
            <h3>Supplier Information</h3>
            <div class="supplier-details">
                <p><strong>Supplier Name</strong><br><span class="indented">Jhonny Bravo</span></p>
                <p><strong>Company Name</strong><br><span class="indented">Lokal Dabaw</span></p>
                <p><strong>Address</strong><br><span class="indented">Davao City</span></p>
                <p><strong>Contact</strong><br><span class="indented">09123456789</span></p>
                <p><strong>Original Price</strong><br><span class="indented">2,000.00</span></p>
                <p><strong>Date</strong><br><span class="indented">March 1, 2025</span></p>
            </div>

        </div>


        <div id="addGarmentPanel" class="side-panel">
            <br>
            <a href="{{ url('/admin/adproduct_blades/infoprod') }}" class="back-btn">←</a>
            <br><br>
            <h2>Add to Garment</h2>
            <form class="garment-form">
                <div class="form-row">
                    <div>
                        <label>Upload Image</label>
                        <input type="file">
                    </div>
                    <div>
                        <label for="condition">Condition</label>
                        <select id="condition" class="green-input">
                            <option value="">Select Condition</option>
                            <option value="new">New</option>
                            <option value="good">Good</option>
                            <option value="fair">Fair</option>
                            <option value="worn">Worn</option>
                            <option value="damaged">Damaged</option>
                        </select>
                    </div>
                </div>

                <div class="tight-group">
                    <label>Product Name</label>
                    <input type="text">
                    <label>Description</label>
                    <textarea rows="4"></textarea>
                </div>

                <div class="form-row">
                    <div>
                        <label for="type">Type</label>
                        <select id="type" onchange="updateSubTypes()">
                            <option value="">Select Type</option>
                            <option value="gown">Gown</option>
                            <option value="tuxedo">Tuxedo</option>
                            <option value="barong">Barong</option>
                            <option value="filipiniana">Filipiniana</option>
                        </select>
                    </div>
                    <div>
                        <label for="sub-type">Sub-type</label>
                        <select id="sub-type" disabled>
                            <option value="">Select Sub-type</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <label>Rental Price</label>
                        <input type="text">
                    </div>
                    <div>
                        <label for="size">Size</label>
                        <select id="size">
                            <option value="">Select Size</option>
                            <option value="xs">Extra Small</option>
                            <option value="s">Small</option>
                            <option value="m">Medium</option>
                            <option value="l">Large</option>
                            <option value="xl">Extra Large</option>
                            <option value="2xl">2XL</option>
                            <option value="3xl">3XL</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="green-button">Add to Garment</button>
            </form>
        </div>




        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" class="modal-overlay">
            <div class="modal-content">
                <div class="modal-icon">
                    <img src="/assets/icons/delete.png" alt="Delete Icon">
                </div>
                <p class="modal-text">Are you sure you want to<br><strong>delete this?</strong></p>
                <div class="modal-buttons">
                    <button class="modal-cancel">Cancel</button>
                    <button class="modal-confirm">Archive</button>
                </div>
            </div>
        </div>




        <div class="buttons">
            <button class="delete">Delete</button>
            <button class="update"
                onclick="window.location.href='{{ url('/admin/adproduct_blades/editprod') }}'">Update</button>
            <button class="add">Add to Garment</button>
        </div>
    </div>


    <script>
        document.querySelector('.add').addEventListener('click', () => {
        document.getElementById('addGarmentPanel').style.display = 'block';
    });


    const subTypes = {
    gown: ['Evening Gown', 'Cocktail Gown', 'Ball Gown', 'Wedding Gown', 'Bridesmaid Gown', 'Prom Gown'],
    tuxedo: ['Black Tuxedo', 'White Tuxedo', 'Velvet Tuxedo', 'Slim Fit Tuxedo', 'Classic Tuxedo', 'Modern Tuxedo'],
    barong: ['Barong Tagalog (traditional)', 'Modern Barong', 'Embroidered Barong', 'Formal Barong', 'Casual Barong'],
    filipiniana: ['Balintawak', 'Terno', 'Mestiza Dress', 'Maria Clara Gown', "Baro't Saya", 'Modern Filipiniana']
};

function updateSubTypes() {
    const typeSelect = document.getElementById('type');
    const subTypeSelect = document.getElementById('sub-type');
    const selectedType = typeSelect.value;

    // Enable the sub-type dropdown if a valid type is selected
    subTypeSelect.disabled = !selectedType;

    // Clear existing options in the sub-type dropdown
    subTypeSelect.innerHTML = '<option value="">Select Sub-type</option>';

    // If a valid type is selected, populate the sub-type dropdown
    if (selectedType && subTypes[selectedType]) {
        subTypes[selectedType].forEach(subType => {
            const option = document.createElement('option');
            option.value = subType.toLowerCase().replace(/\s+/g, '-');  // Make value suitable for HTML
            option.textContent = subType;
            subTypeSelect.appendChild(option);
        });
    }
}




   // Open modal when delete is clicked
   document.querySelector('.delete').addEventListener('click', () => {
        document.getElementById('deleteModal').style.display = 'flex';
    });

    // Close modal when cancel is clicked
    document.querySelector('.modal-cancel').addEventListener('click', () => {
        document.getElementById('deleteModal').style.display = 'none';
    });

    // Archive button logic (can replace this later with form submission or AJAX)
    document.querySelector('.modal-confirm').addEventListener('click', () => {
        alert('Item archived!');
        document.getElementById('deleteModal').style.display = 'none';
    });

    </script>



</body>

</html>