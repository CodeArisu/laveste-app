<x-layouts.admin>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/tables.css') }}">
    @endpush
    <div class="product-page">

        @if (Session('deleted'))
            <x-fragments.alert-response message="{{ Session('deleted') }}" type='danger' />
        @elseif (Session('success'))
            <x-fragments.alert-response message="{{ Session('success') }}" type='success' />
        @endif

        <div class="header-section">
            <h2 class="section-title">Products</h2>
            <a href="{{ route('dashboard.product.form') }}" class="add-product-btn">
                <span>+</span> Add Product
            </a>
        </div>
        <table class="product-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Type</th>
                    <th>Sub-type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody> <!-- Only one tbody here -->
                @foreach ($products as $product)
                    <tr class="product-row">
                        <td>{{ $product->id }}</td>
                        <td>{{ Str::ucfirst($product->product_name) }}</td>
                        <td>{{ $product->original_price }}</td>
                        <td>{{ Str::upper($product->types->type_name) }}</td>
                        <td>{{ Str::upper($product->subtypes[0]->subtype_name) }}</td>
                        <td>
                            <div class='dropdown'>
                                <button class="action-btn dropdown-toggle no-caret" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><button class="dropdown-item" type="button">Action</button></li>
                                    <li><button class="dropdown-item" type="button">Another action</button></li>
                                    <li><button class="dropdown-item" type="button">Something else here</button></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.admin>
