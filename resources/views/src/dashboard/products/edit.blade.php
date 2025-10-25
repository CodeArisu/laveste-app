<x-layouts.app>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/productadd.css') }}">
    @endpush

    <div class="container">

        @if (Session('success') && !str_contains(Session('success'), 'logged in'))
            <x-fragments.alert-response message="{{ Session('success') }}" type='success' />
        @endif

        <a href="{{ route('dashboard.product.index') }}" class="back-btn">← Back</a>
        <h1>Update Product</h1>
        <form action="{{ route('dashboard.product.update', ['product' => $products]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-sections">
                <!-- Product Information -->
                <div class="form-section">
                    <h2>Product Information</h2>
                    <label for="product-name">Product Name</label>
                    <input type="text" id="product-name" name='product_name' value='{{ $products->product_name }}'
                        required>
                    @error('product_name')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                    <div class="row">
                        <div>
                            <label for="type">Type<span class='importance'>*</span></label>
                            <select id="type" name="type" required>
                                @foreach ($types as $type)
                                    @if($type->type_name === $products->types->type_name)
                                        <option value="{{ $type->type_name }}" selected>{{ Str::ucfirst($products->types->type_name) }}</option>
                                    @else
                                    <option value="{{ $type->type_name }}"
                                        {{ old('type') == $type->type_name ? 'selected' : '' }}>
                                        {{ Str::ucfirst($type->type_name) }}
                                    </option>
                                    @endif
                                @endforeach
                                <option value="new_type" {{ old('type') == 'new_type' ? 'selected' : '' }}>Add New Type
                                </option>
                            </select>
                            @error('type')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror

                            <div id="new-type-container"
                                style="{{ old('type') == 'new_type' ? '' : 'display: none;' }}">
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

                                </option>
                                @foreach ($subtypes as $subtype)
                                    @if ($subtype->subtype_name === $products->subtypes[0]->subtype_name)
                                        <option value="{{ $subtype->subtype_name }}" selected>{{ Str::ucfirst($subtype->subtype_name) }}
                                    @else
                                        <option value="{{ $subtype->subtype_name }}"
                                            {{ old('subtype') == $subtype->subtype_name ? 'selected' : '' }}>
                                            {{ Str::ucfirst($subtype->subtype_name) }}
                                        </option>
                                    @endif
                                @endforeach
                                <option value="new_subtype" {{ old('subtype') == 'new_subtype' ? 'selected' : '' }}>Add
                                    New Sub-type</option>
                            </select>
                            @error('subtype')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror

                            <div id="new-subtype-container"
                                style="{{ old('subtype') == 'new_subtype' ? '' : 'display: none;' }}">
                                <input type="text" name="new_subtype"
                                    placeholder="Enter new sub-type (comma separated)" value="{{ old('new_subtype') }}"
                                    class="mb-0">
                                <small>Separate using commas for multiple inputs.</small>
                                @error('new_subtype')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <label for="description">Description</label>
                    <textarea name="description" id="description" rows="4">
                    {{ $products->description }}
                    </textarea>
                    @error('description')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror

                    <label for="original-price">Original Price</label>
                    <input name="original_price" type="number" value='{{ $products->original_price }}'
                        id="original-price">
                    @error('original_price')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Supplier Information -->
                <div class="form-section">
                    <h2>Supplier Information</h2>
                    <label for="supplier-name">Supplier Name</label>
                    <input name="supplier_name" type="text" value='{{ $products->supplier->supplier_name }}'
                        id="supplier-name">
                    @error('supplier_name')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror

                    <label for="company-name">Company Name</label>
                    <input name="company_name" type="text" value='{{ $products->supplier->company_name }}'
                        id="company-name">
                    @error('company_name')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror

                    <label for="address">Address</label>
                    <input name="address" type="text" value='{{ $products->supplier->address }}' id="address">
                    @error('address')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror

                    <label for="contact">Contact</label>

                    <input name="contact" type="text" value='{{ $products->supplier->contact }}' id="contact">
                    @error('contact')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror


                    <div class="button-group">
                        <button type='button' id='clear-btn' class="clear-btn">Clear all</button>
                        <button type='submit' class="add-btn">Update product</button>
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
