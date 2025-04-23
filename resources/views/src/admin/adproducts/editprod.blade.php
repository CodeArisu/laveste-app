<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="{{ asset('css/admin/productadd.css') }}">
</head>

<body>
    <div class="container">
        <a href="{{ url()->previous() }}" class="back-btn">← Back</a>
        <h1>Update Product</h1>
        <form action="{{ route('dashboard.product.store') }}" method="POST">
            @csrf
            <div class="form-sections">
                <!-- Product Information -->
                <div class="form-section">
                    <h2>Product Information</h2>
                    <label for="product-name">Product Name</label>
                    <input type="text" id="product-name" name='product_name' value='{{ $products[0]->product_name }}' required>
                    <div class="row">
                        <div>
                            <label for="type">Type</label>
                            <select id="type" name="type" onchange="updateTypeField()">
                                <option selected>Select Type</option>
                                    {{-- @foreach ($types as $type)
                                        <option value="{{ $type->type_name }}">
                                            {{ Str::ucfirst($type->type_name) }}
                                        </option>
                                    @endforeach --}}
                                <option value="new_type">New Type</option>
                            </select>

                            {{-- <div id="newTypeContainer" style="display: none;">
                                <input type="text" name="type" id="newTypeInput" placeholder="Enter new type name">
                            </div> --}}
                        </div>
                        <div>
                            <label for="sub-type">Sub-type</label>
                            <select id="sub-type" name="subtype" onchange="updateSubtypeField()">
                                <option selected>Select Sub-type</option>
                                    {{-- @foreach ($subtypes as $subtype)
                                        <option value="{{ $subtype->subtype_name }}">
                                            {{ Str::ucfirst($subtype->subtype_name) }}
                                        </option>
                                    @endforeach --}}
                                <option value="new_subtype">New subtype</option>
                            </select>

                            {{-- <div id="newSubtypeContainer" style="display: none;">
                                <input type="text" name="subtype" id="newSubtypeInput" placeholder="Enter new subtype name">
                            </div> --}}
                        </div>
                    </div>

                    <label for="description">Description</label>
                    <textarea name="description" id="description" rows="4">
                        {{ $products[0]->description }}
                    </textarea>

                    <label for="original-price">Original Price</label>
                    <input name="original_price" type="number" value='{{ $products[0]->supplier->original_price }}' id="original-price">
                </div>

                <!-- Supplier Information -->
                <div class="form-section">
                    <h2>Supplier Information</h2>
                    <label for="supplier-name">Supplier Name</label>
                    <input name="supplier_name" type="text" value='{{ $products[0]->supplier->supplier_name }}' id="supplier-name">

                    <label for="company-name">Company Name</label>
                    <input name="company_name" type="text" value='{{ $products[0]->supplier->company_name }}' id="company-name">

                    <label for="address">Address</label>
                    <input name="address" type="text" value='{{ $products[0]->supplier->address }}' id="address">

                    <label for="contact">Contact</label>
                    <input name="contact" type="text" value='{{ $products[0]->supplier->contact }}' id="contact">
                </div>

                <!-- Buttons -->
                <div class="button-group">
                    <button class="clear-btn">Clear all</button>
                    <button class="add-btn">✔ Add product</button>
                </div>
            </div>

        </form>
    </div>

    {{-- <script>
        function updateTypeField() {
            const typeSelect = document.getElementById('type');
            const newTypeContainer = document.getElementById('newTypeContainer');

            if (typeSelect.value === 'new_type') {
                typeSelect.style.display = 'none';
                newTypeContainer.style.display = 'block';
                document.getElementById('newTypeInput').focus();
            }
        }

        function updateSubtypeField() {
            const subtypeSelect = document.getElementById('subtype');
            const newSubtypeContainer = document.getElementById('newSubtypeContainer');

            if (subtypeSelect.value === 'new_subtype') {
                typeSelect.style.display = 'none';
                newSubtypeContainer.style.display = 'block';
                document.getElementById('newSubtypeInput').focus();
            }
        }

        // Optional: Add function to revert back to select if needed
        function cancelNewType() {
            document.getElementById('type').style.display = 'block';
            document.getElementById('newTypeContainer').style.display = 'none';
            document.getElementById('type').value = '';
        }
    </script> --}}
</body>

</html>
