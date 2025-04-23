<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="{{ asset('css/admin/productadd.css') }}">
</head>
<body>
    <div class="container">

        <a href="{{ url('/admin/adproduct_blades/infoprod') }}" class="back-btn">← Back</a>
        <h1>Edit Product</h1>

        <div class="form-sections">
            <!-- Product Information -->
            <div class="form-section">
                <h2>Product Information</h2>
                <form>
                    <label for="product-name">Product Name</label>
                    <input type="text" id="product-name">

                    <div class="row">
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
                                <!-- Sub-type options will be dynamically populated based on Type selection -->
                            </select>
                        </div>
                    </div>
                    

                    <label for="description">Description</label>
                    <textarea id="description" rows="4"></textarea>

                    <label for="original-price">Original Price</label>
                    <input type="text" id="original-price">
                </form>
            </div>

            <!-- Supplier Information -->
            <div class="form-section">
                <h2>Supplier Information</h2>
                <form>
                    <label for="supplier-name">Supplier Name</label>
                    <input type="text" id="supplier-name">

                    <label for="company-name">Company Name</label>
                    <input type="text" id="company-name">

                    <label for="address">Address</label>
                    <input type="text" id="address">

                    <label for="contact">Contact</label>
                    <input type="text" id="contact">
                </form>
            </div>
        </div>

        <!-- Buttons -->
        <div class="button-group">
            <button class="clear-btn">Clear all</button>
            <button class="add-btn">✔ Update</button>
        </div>
    </div>

    <script>
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

    </script>
</body>
</html>
