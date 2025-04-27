<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Information</title>
    <link rel="stylesheet" href="{{ asset('css/admin/infoprod.css') }}">
</head>

<body>
    @php
        use App\Enum\Measurement;
        use App\Enum\ConditionStatus;
    @endphp
    <div class="container">
        <div class="product-section">
            <a href="{{ url()->previous() }}" class="back-btn">← Back</a>
            <h3>Product Information</h3>
            <div class="product-content">
                <img src="/assets/images/h1.png" alt="Gown Image" class="product-image">
                <div class="product-details">
                    <h2>{{ $products->product_name }}</h2>
                    <br>
                    <p><strong>Type</strong>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Sub-type</strong></p>
                    <p>{{ $products->types->type_name }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $products->subtypes[0]->subtype_name }}</p>
                    <p><strong>Original Price</strong><br>{{ $products->original_price }}</p>
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
            <div class="supplier-details">
                <p><strong>Supplier Name</strong><span class="indented">{{ $products->supplier->supplier_name }}</span>
                </p>
                <p><strong>Company Name</strong><span class="indented">{{ $products->supplier->company_name }}</span>
                </p>
                <p><strong>Address</strong><span class="indented">{{ $products->supplier->address }}</span></p>
                <p><strong>Contact</strong><span class="indented">{{ $products->supplier->contact }}</span></p>
                <p><strong>Original Price</strong><span class="indented">{{ $products->original_price }}</span></p>
                <p><strong>Date</strong><span class="indented">{{ $products->created_at }}</span></p>
            </div>

        </div>


        <div id="addGarmentPanel" class="side-panel">
            <br>
            <a href="{{ url()->previous() }}" class="back-btn">←</a>
            <br><br>
            <h2>Add to Garment</h2>
            <form class="garment-form" method='POST' action="{{ route('dashboard.garment.store', [$products->id]) }}">
                @csrf
                {{-- gets product id as input --}}
                <input hidden name='product_id' value='{{ $products->id }}'></input>
                <div class="form-row">
                    <div>
                        {{-- sets image upload --}}
                        <label>Upload Image</label>
                        <input name='poster' type="file">
                    </div>
                    <div>
                        {{-- add new condition to the garment data --}}
                        <label for="condition">Condition</label>
                        <select id="condition" name='condition_id' class="green-input">
                            <option value="">Select Condition</option>
                            @foreach (ConditionStatus::cases() as $condition)
                                <option value="{{ $condition->value }}">{{ ucfirst($condition->label()) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="tight-group">
                    <label>Product Name</label>
                    <input type="text" value="{{ $products->product_name }}">
                    <label>Description</label>
                    <textarea name='additional_description' rows="4">{{ $products->description }}</textarea>
                </div>

                <div class="form-row">
                    <div>
                        <label for="type">Type</label>
                        <select id="type">
                            @if (empty($products->types->type_name))
                                <option value="">Select Type</option>
                                <option value="gown">Gown</option>
                                <option value="tuxedo">Tuxedo</option>
                                <option value="barong">Barong</option>
                                <option value="filipiniana">Filipiniana</option>
                            @else
                                <option value="">Select Type</option>
                                <option selected value="{{ $products->types->type_name }}">{{ ucfirst($products->types->type_name) }}</option>
                            @endif
                        </select>
                    </div>
                    <div>
                        <label for="sub-type">Sub-type</label>
                        <select id="sub-type">
                            @if (empty($products->subtypes[0]->subtype_name))
                                <option value="">Select Type</option>
                                <option value="gown">Gown</option>
                                <option value="tuxedo">Tuxedo</option>
                                <option value="barong">Barong</option>
                                <option value="filipiniana">Filipiniana</option>
                            @else
                                <option value="">Select Type</option>
                                <option selected value="{{ $products->subtypes[0]->subtype_name }}">{{ ucfirst($products->subtypes[0]->subtype_name) }}</option>
                            @endif
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <label>Rental Price</label>
                        <input type="number" name='rent_price'>
                    </div>
                    <div>
                        <label for="size">Size</label>
                        <select id="size" name='measurement'>
                            <option value="">Select Size</option>
                            <option value="{{ Measurement::XS->value }}">Extra Small</option>
                            <option value="{{ Measurement::S->value }}">Small</option>
                            <option value="{{ Measurement::M->value }}">Medium</option>
                            <option value="{{ Measurement::L->value }}">Large</option>
                            <option value="{{ Measurement::XL->value }}">Extra Large</option>
                            <option value="{{ Measurement::XXL->value }}">XXL</option>
                        </select>
                    </div>
                </div>

                <div class='form-row'>
                    <div>
                        <label>Width</label>
                        <input type="number" name='width' >
                    </div>
                    <div>
                        <label>Height</label>
                        <input type="number" name='length' >
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


        // const subTypes = {
        //     gown: ['Evening Gown', 'Cocktail Gown', 'Ball Gown', 'Wedding Gown', 'Bridesmaid Gown', 'Prom Gown'],
        //     tuxedo: ['Black Tuxedo', 'White Tuxedo', 'Velvet Tuxedo', 'Slim Fit Tuxedo', 'Classic Tuxedo',
        //         'Modern Tuxedo'
        //     ],
        //     barong: ['Barong Tagalog (traditional)', 'Modern Barong', 'Embroidered Barong', 'Formal Barong',
        //         'Casual Barong'
        //     ],
        //     filipiniana: ['Balintawak', 'Terno', 'Mestiza Dress', 'Maria Clara Gown', "Baro't Saya",
        //         'Modern Filipiniana'
        //     ]
        // };

        // function updateSubTypes() {
        //     const typeSelect = document.getElementById('type');
        //     const subTypeSelect = document.getElementById('sub-type');
        //     const selectedType = typeSelect.value;

        //     // Enable the sub-type dropdown if a valid type is selected
        //     subTypeSelect.disabled = !selectedType;

        //     // Clear existing options in the sub-type dropdown
        //     subTypeSelect.innerHTML = '<option value="">Select Sub-type</option>';

        //     // If a valid type is selected, populate the sub-type dropdown
        //     if (selectedType && subTypes[selectedType]) {
        //         subTypes[selectedType].forEach(subType => {
        //             const option = document.createElement('option');
        //             option.value = subType.toLowerCase().replace(/\s+/g, '-'); // Make value suitable for HTML
        //             option.textContent = subType;
        //             subTypeSelect.appendChild(option);
        //         });
        //     }
        // }

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
