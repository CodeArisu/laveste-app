<x-layouts.app>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/productadd.css') }}">
    @endpush
    <div class="container">
        <a href="{{ url()->previous() }}" class="back-btn">← Back</a>
        <h1>Add Product</h1>

        {{-- shows message after success API --}}
        @if(session('success'))
            <div class="alert alert-success fade show" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('dashboard.product.store') }}" method="POST">
            @csrf
            <div class="form-sections">
                <!-- Product Information -->
                <div class="form-section">
                    <h2>Product Information</h2>
                    <label for="product-name">Product Name</label>
                   
                    <input type="text" id="product-name" name='product_name' value='{{ old('product_name') }}'>
                    {{-- product name error message --}}
                    @error('product_name')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                    <div class="row">
                        <div>
                            <label for="type">Type</label>
                            <select id="type" name="type" onchange="updateTypeField()">
                                <option selected>Select Type</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->type_name }}">
                                            {{ Str::ucfirst($type->type_name) }}
                                        </option>
                                    @endforeach
                                <option value="new_type">New Type</option>
                            </select>
                            {{-- type error message --}}
                            @error('type')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror

                            {{-- new input for new type --}}
                            <div id="newTypeContainer" style="display: none;">
                                <input type="text" name="type" id="newTypeInput" placeholder="Enter new type name">
                                <button type="button" class="cancel-btn" onclick="cancelNewType()">Cancel</button>
                            </div>
                        </div>
                        <div>
                            <label for="sub-type">Sub-type</label>
                            <select id="sub-type" name="subtype" onchange="updateSubtypeField()">
                                <option selected>Select Sub-type</option>
                                    @foreach ($subtypes as $subtype)
                                        <option value="{{ $subtype->subtype_name }}">
                                            {{ Str::ucfirst($subtype->subtype_name) }}
                                        </option>
                                    @endforeach
                                <option value="new_subtype">New subtype</option>
                            </select>
                            {{-- subtype error message --}}
                            @error('type')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                            <div id="newSubtypeContainer" style="display: none;">
                                <input type="text" name="subtype" id="newSubtypeInput" placeholder="Enter new subtype name">
                                <button type="button" class="cancel-btn" onclick="cancelNewSubtype()">Cancel</button>
                            </div>
                        </div>
                    </div>

                    <label for="description">Description</label>
                    <textarea name="description" id="description" rows="4"></textarea>
                    {{-- description error message --}}
                    @error('description')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror

                    <label for="original-price">Original Price</label>
                    <input name="original_price" type="number" id="original-price">
                    {{-- original price error message --}}
                    @error('original_price')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Supplier Information -->
                <div class="form-section">
                    <h2>Supplier Information</h2>
                    <label for="supplier-name">Supplier Name</label>
                    <input name="supplier_name" type="text" id="supplier-name">
                    {{-- supplier name error message --}}
                    @error('supplier_name')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror

                    <label for="company-name">Company Name</label>
                    <input name="company_name" type="text" id="company-name">
                    {{-- company name error message --}}
                    @error('company_name')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror

                    <label for="address">Address</label>
                    <input name="address" type="text" id="address">
                    {{-- address name error message --}}
                    @error('address')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror

                    <label for="contact">Contact</label>
                    <input name="contact" type="text" id="contact">
                    {{-- contact error message --}}
                    @error('contact')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="button-group">
                    <button class="clear-btn">Clear all</button>
                    <button class="add-btn">✔ Add product</button>
                </div>
            </div>

        </form>
    </div>

    <script>
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
            const subtypeSelect = document.getElementById('sub-type');
            const newSubtypeContainer = document.getElementById('newSubtypeContainer');

            if (subtypeSelect.value === 'new_subtype') {
                subtypeSelect.style.display = 'none';
                newSubtypeContainer.style.display = 'block';
                document.getElementById('newSubtypeInput').focus();
            }
        }

        // Optional: Add function to revert back to select if needed
        function cancelNewType() {
            const typeSelect = document.getElementById('type');
            typeSelect.style.display = 'block';
            typeSelect.value = 'Select Type'; // Reset to default option
            document.getElementById('newTypeContainer').style.display = 'none';
        }

        function cancelNewSubtype() {
            const subtypeSelect = document.getElementById('sub-type');
            subtypeSelect.style.display = 'block';
            subtypeSelect.value = 'Select Sub-type'; // Reset to default option
            document.getElementById('newSubtypeContainer').style.display = 'none';
        }
    </script>
</x-layouts.app>