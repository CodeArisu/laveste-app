<x-layouts.app>
    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/productadd.css') }}">
    @endpush
    <div class="container">
        <a href="{{ route('dashboard.product.index') }}" class="back-btn">← Back</a>
        <h1>Add Product</h1>

        {{-- shows message after success API --}}
        @if (session('success'))
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


                            <input type="text" name="new_type" placeholder="Enter new type (optional)">
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

                            <input type="text" name="new_subtype" placeholder="Enter new sub-type (optional)">
                        </div>
                    </div>

                    <label for="description">Description</label>
                    <textarea name="description" id="description" rows="4" value={{ old('description') }}></textarea>
                    {{-- description error message --}}
                    @error('description')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror

                    <label for="original-price">Original Price</label>
                    <input name="original_price" type="number" id="original-price" value={{ old('original_price') }}>
                    {{-- original price error message --}}
                    @error('original_price')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Supplier Information -->
                <div class="form-section">
                    <h2>Supplier Information</h2>
                    <label for="supplier-name">Supplier Name</label>
                    <input name="supplier_name" type="text" id="supplier-name" value={{ old('supplier_name') }}>
                    {{-- supplier name error message --}}
                    @error('supplier_name')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror

                    <label for="company-name">Company Name</label>
                    <input name="company_name" type="text" id="company-name" value={{ old('company_name') }}>
                    {{-- company name error message --}}
                    @error('company_name')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror

                    <label for="address">Address</label>
                    <input name="address" type="text" id="address" value={{ old('address') }}>
                    {{-- address name error message --}}
                    @error('address')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror

                    <label for="contact">Contact</label>
                    <input name="contact" type="text" id="contact" value={{ old('contact') }}>

                    {{-- contact error message --}}
                    @error('contact')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror


                       <!-- Buttons -->
                <div class="button-group">
                    <button class="clear-btn">Clear all</button>
                    <button class="add-btn" >Add product</button>
                </div>
                </div>

             

              
            </div>
        </form>
    </div>

    @push('scripts')
        {{-- <script src={{ asset('scripts/toastHandler.js') }}></script> --}}

        <script src={{ asset('scripts/addProductSelectTypesHandler.js') }}></script>
    @endpush
    
</x-layouts.app>
