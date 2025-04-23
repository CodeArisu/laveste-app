<x-layouts.admin>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/tables.css') }}">
    @endpush

    <div class="product-page">
        <div class="header-section">
            <h2 class="section-title">Products</h2>
            <a href="{{ url('/admin/adproduct_blades/productadd') }}" class="add-product-btn">
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
                    <th>Size</th>
                </tr>
            </thead>
            <tbody> <!-- Only one tbody here -->
                <tr class="product-row" onclick="window.location='{{ url('/admin/adproduct_blades/infoprod') }}'">
                    <td>0001</td>
                    <td>Very nice gown</td>
                    <td>3,600.00</td>
                    <td>Gown</td>
                    <td>Evening</td>
                    <td>M</td>
                </tr>
                <tr class="product-row" onclick="window.location='{{ url('/admin/adproduct_blades/infoprod') }}'">
                    <td>0002</td>
                    <td>Stylish dress</td>
                    <td>4,500.00</td>
                    <td>Dress</td>
                    <td>Casual</td>
                    <td>L</td>
                </tr>
            </tbody> <!-- Only one tbody here -->
        </table>
    </div>
</x-layouts.admin>
