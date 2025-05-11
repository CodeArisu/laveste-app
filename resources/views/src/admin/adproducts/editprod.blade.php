<x-layouts.app>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/productadd.css') }}">
    @endpush
    <link rel="stylesheet" href="{{ asset('css/admin/productadd.css') }}">
    <div class="container">
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
                            <label for="type">Type</label>
                            <select id="type" name="type" onchange="updateTypeField()">
                                @foreach ($types as $type)
                                    @if ($types === $products->type)
                                        <option value="{{ $type->type_name }}" selected>
                                            {{ Str::ucfirst($products->type->type_name) }}
                                        </option>
                                    @endif
                                    <option value="{{ $type->type_name }}">
                                        {{ Str::ucfirst($type->type_name) }}
                                    </option>
                                @endforeach
                                <option value="new_type">New Type</option>
                                @error('type')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </select>

                            {{-- <div id="newTypeContainer" style="display: none;">
                                <input type="text" name="type" id="newTypeInput" placeholder="Enter new type name">
                            </div> --}}
                        </div>
                        <div>
                            <label for="sub-type">Sub-type</label>
                            <select id="sub-type" name="subtype" onchange="updateSubtypeField()">
                                @foreach ($subtypes as $subtype)
                                    @if ($subtypes === $products->subtypes)
                                        <option value="{{ $subtype->subtype_name }}" selected>
                                            {{ Str::ucfirst($products->subtype->subtype_name) }}
                                        </option>
                                    @endif
                                    <option value="{{ $subtype->subtype_name }}">
                                        {{ Str::ucfirst($subtype->subtype_name) }}
                                    </option>
                                @endforeach
                                <option value="new_subtype">New subtype</option>
                                @error('sub-type')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </select>
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
                </div>

                    <div class="button-group">
                    <button class="clear-btn">Clear all</button>
                    <button class="add-btn">✔ Update product</button>
                </div>
                </div>

                <!-- Buttons -->
                
            </div>
        </form>
    </div>
</x-layouts.app>
