<x-layouts.app>
    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/infoprod.css') }}">
    @endpush

    <div class="info-container">
        <a href="{{ route('dashboard.product.index') }}" class="back-btn">← Back</a>
        <div class="info-sections">
            <div class="product-section">
                @if(session('success'))
                    <div class="alert alert-success fade show" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                <br>
                <h3>Product Information</h3>
                <div class="product-content">
                    <div class="product-details">
                        <h2>{{ $products->product_name }}</h2>
                        <p><strong>Type</strong>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Sub-type</strong>
                        </p>
                        <p>{{ $products->types->type_name }}
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{
                            $products->subtypes[0]->subtype_name }}
                        </p>
                        <p><strong>Original Price</strong><br>{{ $products->getFormattedOriginalPrice() }}</p>
                    </div>
                </div>
                <br><br>
                <div class="description">
                    <h4>Description</h4>
                    <p>
                        {{ $products->description }}
                    </p>
                </div>
            </div>
            <div class="supplier-section">
                <br>
                <h3>Supplier Information</h3>
                <br>
                <div class="supplier-details">
                    <div class="supplier-row">
                        <span class="label">Supplier Name</span>
                        <span class="value">{{ $products->supplier->supplier_name }}</span>
                    </div>
                    <div class="supplier-row">
                        <span class="label">Company Name</span>
                        <span class="value">{{ $products->supplier->company_name }}</span>
                    </div>
                    <div class="supplier-row">
                        <span class="label">Address</span>
                        <span class="value">{{ $products->supplier->address }}</span>
                    </div>
                    <div class="supplier-row">
                        <span class="label">Contact</span>
                        <span class="value">{{ $products->supplier->contact }}</span>
                    </div>
                    <div class="supplier-row">
                        <span class="label">Original Price</span>
                        <span class="value">{{ $products->getFormattedOriginalPrice() }}</span>
                    </div>
                    <div class="supplier-row">
                        <span class="label">Date</span>
                        <span class="value">{{ $products->getFormattedDate() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div id="addGarmentPanel" class="side-panel">
            <br>
            <a href="{{ route('dashboard.product.index') }}" class="back-btn2">←</a>
            <br><br>
            <h2>Add to Garment</h2>
            <form class="garment-form" method='POST' action="{{ route('dashboard.garment.store', [$products->id]) }}"
                enctype="multipart/form-data">
                @csrf
                {{-- gets product id as input --}}
                <input hidden name='product_id' value='{{ $products->id }}'></input>
                <div class="form-row">
                    <div>
                        {{-- sets image upload --}}
                        <label>Product Image<span class='importance'>*</span></label>
                        <input name="poster" type="file">
                    </div>
                    <div>
                        {{-- add new condition to the garment data --}}
                        <label for="condition">Condition</label>
                        <select id="condition" name='condition_id' class="green-input">
                            @foreach ($conditions as $condition)
                            <option value="{{ $condition->value }}">{{ ucfirst($condition->label()) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="tight-group">
                    <label>Product Name</label>
                    <input type="text" value="{{ $products->product_name }}" readonly>
                    <label>Description</label>
                    <textarea name='additional_description' rows="4">{{ $products->description }}</textarea>
                </div>

                <div class="form-row">
                    <div>
                        <label for="type">Type<span class='importance'>*</span></label>
                        <select id="type">
                            @if (empty($products->types->type_name))
                            <option value="">Select Type</option>
                            <option value="gown">Gown</option>
                            <option value="tuxedo">Tuxedo</option>
                            <option value="barong">Barong</option>
                            <option value="filipiniana">Filipiniana</option>
                            @else
                            <option value="">Select Type</option>
                            <option selected value="{{ $products->types->type_name }}">
                                {{ ucfirst($products->types->type_name) }}</option>
                            @endif
                        </select>
                    </div>
                    <div>
                        <label for="sub-type">Sub-type<span class='importance'>*</span></label>
                        <select id="sub-type">
                            @if (empty($products->subtypes[0]->subtype_name))
                            <option value="">Select Type</option>
                            <option value="gown">Gown</option>
                            <option value="tuxedo">Tuxedo</option>
                            <option value="barong">Barong</option>
                            <option value="filipiniana">Filipiniana</option>
                            @else
                            <option value="">Select Type</option>
                            <option selected value="{{ $products->subtypes[0]->subtype_name }}">
                                {{ ucfirst($products->subtypes[0]->subtype_name) }}</option>
                            @endif
                        </select>
                    </div>
                </div>

                <div class="form-row">  
                    <div>
                        <label>Rental Price<span class='importance'>*</span></label>
                        <input type="number" name='rent_price' value={{ $products->original_price }}>
                    </div>
                    <div>
                        <label for="size">Size<span class='importance'>*</span></label>
                        <select id="size" name='measurement'>
                            <option value="">Select Size</option>
                            @foreach ($measurements as $measurement)
                            <option value="{{ $measurement->value }}">
                                {{ ucfirst($measurement->label()) }}
                            </option>
                            @endforeach>
                        </select>
                    </div>
                </div>

                <div class='form-row'>
                    <div>
                        <label>Width (optional)</label>
                        <input type="number" name='width'>
                    </div>
                    <div>
                        <label>Height (optional)</label>
                        <input type="number" name='length'>
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
            <form action="{{ route('dashboard.product.delete', [$products->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type='submit' class="delete">Delete</button>
            </form>

            <button class="update"
                onclick="window.location.href='{{ route('dashboard.product.edit', [$products->id]) }}'">Update</button>
            <button class="add">Add to Garment</button>
        </div>
    </div>


    <script>
        document.querySelector('.add').addEventListener('click', () => {
            document.getElementById('addGarmentPanel').style.display = 'block';
        });

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
</x-layouts.app>