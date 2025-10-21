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
                        </tr>
                    </thead>
                    <tbody> <!-- Only one tbody here -->
                        @foreach ($products as $product)
                            <tr class="product-row"
                                onclick="window.location='{{ route('dashboard.product.show', $product->id) }}'">
                                <td>{{ $product->id }}</td>
                                <td>{{ Str::ucfirst($product->product_name) }}</td>
                                <td>{{ $product->original_price }}</td>
                                <td>{{ Str::upper($product->types->type_name) }}</td>
                                <td>{{ Str::upper($product->subtypes[0]->subtype_name) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
</x-layouts.admin>
