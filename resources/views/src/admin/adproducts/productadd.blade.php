<x-layouts.app>
    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/productadd.css') }}">
    @endpush
    <div class="container">
        <a href="{{ route('dashboard.product.index') }}" class="back-btn">← Back</a>
        <h1>Add Product</h1>

        {{-- shows message after success API --}}
        @if (session('success'))
            <x-fragments.alert-response message="{{ Session('success') }}" type='success' />
        @endif

        @if($errors->has('internal_error'))
             <div class="alert alert-danger">
                <strong>Error:</strong> {{ $errors->first('internal_error') }}
                <p>{{ $errors->first('internal_error_description') }}</p>
            </div>
        @endif

        <form action="{{ route('dashboard.product.store') }}" method="POST">
            @csrf
            <div class="form-sections">
                <!-- Product Information -->
                <div class="form-section">
                    <h2>Product Information</h2>
                    <label for="product-name">Product Name<span class='importance'>*</span></label>
                    <input type="text" id="product-name" name='product_name' value='{{ old('product_name') }}'>
                    {{-- product name error message --}}
                    @error('product_name')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                    <div class="row">
                        <div>
                            <label for="type">Type<span class='importance'>*</span></label>
                            <select id="type" name="type" required>
                                <option value="" disabled {{ old('type') ? '' : 'selected' }}>Select Type</option>
                                @foreach ($types as $type)
                                <option value="{{ $type->type_name }}" {{ old('type') == $type->type_name ? 'selected' : '' }}>
                                    {{ Str::ucfirst($type->type_name) }}
                                </option>
                                @endforeach
                                <option value="new_type" {{ old('type') == 'new_type' ? 'selected' : '' }}>Add New Type</option>
                            </select>
                            @error('type')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        
                            <div id="new-type-container" style="{{ old('type') == 'new_type' ? '' : 'display: none;' }}">
                                <input type="text" name="new_type" placeholder="Enter new type" 
                                    value="{{ old('new_type') }}">
                                @error('new_type')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                    </div>
                    <div>
                        <label for="sub-type">Sub-type<span class='importance'>*</span></label>
                        <select id="sub-type" name="subtype" required>
                            <option value="" disabled {{ old('subtype') ? '' : 'selected' }}>Select Sub-type</option>
                            @foreach ($subtypes as $subtype)
                            <option value="{{ $subtype->subtype_name }}" {{ old('subtype') == $subtype->subtype_name ? 'selected' : '' }}>
                                {{ Str::ucfirst($subtype->subtype_name) }}
                            </option>
                            @endforeach
                            <option value="new_subtype" {{ old('subtype') == 'new_subtype' ? 'selected' : '' }}>Add New Sub-type</option>
                        </select>
                        @error('subtype')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                        
                        <div id="new-subtype-container" style="{{ old('subtype') == 'new_subtype' ? '' : 'display: none;' }}">
                            <input type="text" name="new_subtype" placeholder="Enter new sub-type (comma separated)" 
                                value="{{ old('new_subtype') }}" class="mb-0">
                            <small>Separate using commas for multiple inputs.</small>
                            @error('new_subtype')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="4">{{ old('description') }}</textarea>
                @error('description')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
                
                <label for="original-price">Original Price<span class='importance'>*</span></label>
                <input name="original_price" type="number" id="original-price" value="{{ old('original_price') }}" required>
                @error('original_price')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

                <!-- Supplier Information -->
                <div class="form-section">
                    <h2>Supplier Information</h2>
                    <label for="supplier-name">Supplier Name</label>
                    <input name="supplier_name" type="text" id="supplier-name" value={{ old('supplier_name') }}>
                 
                    <label for="company-name">Company Name<span class='importance'>*</span></label>
                    <input name="company_name" type="text" id="company-name" value={{ old('company_name') }}>

                    {{-- company name error message --}}
                    @error('company_name')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror

                    <label for="address">Address<span class='importance'>*</span></label>
                    <input name="address" type="text" id="address" value={{ old('address') }}>
                    {{-- address name error message --}}
                    @error('address')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror

                    <label for="contact">Contact<span class='importance'>*</span></label>
                    <input name="contact" type="text" id="contact" value={{ old('contact') }}>

                    {{-- contact error message --}}
                    @error('contact')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                <div class="button-group">
                    <button type='button' id="clear-btn" class="clear-btn">Clear all</button>
                    <button type='submit' class="add-btn" >Add product</button>
                </div>
                </div>
            </div>
        </form>
    </div>

    @section('scripts')
        <script src={{ asset('scripts/clearInputsButton.js') }}></script>
        <script src={{ asset('scripts/addProductSelectTypesHandler.js') }}></script>
        <script src={{ asset('scripts/categoriesHandler.js') }}></script>
    @endsection
    
</x-layouts.app>
