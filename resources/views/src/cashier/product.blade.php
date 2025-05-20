<x-layouts.cashlayout>
    @push('styles')
        <link rel="stylesheet" href="/css/products/product.css">
        <link rel="stylesheet" href="/css/cashier/cashierhead.css">
        <link rel="stylesheet" href="/css/products/custom-filter.css">
    @endpush

    <div class="container">
        <div class="filters">
            <div class="styled-select">
                <select id="type-select" onchange="updateSubType()">
                    <option disabled selected>Select type</option>
                    <option value="gown">Gown</option>
                    <option value="tuxedo">Tuxedo</option>
                    <option value="barong">Barong</option>
                    <option value="filipiniana">Filipiniana</option>
                </select>
            </div>

            <div class="styled-select">
                <select id="subtype-select">
                    <option disabled selected>Select sub-type</option>
                </select>
            </div>

            <div class="search-container">
                <input type="text" placeholder="Search products..." />
                <span class="search-icon"><i class="fas fa-search"></i></span>
            </div>
        </div>
        
        <div class="container">
            <div class='row gy-4'>
                @foreach ($catalogs as $catalog)
                    <div class="col-md-4">
                        <x-fragments.catalog-card-component 
                            :catalog="$catalog" 
                            :url="route('cashier.details', ['catalogs' => $catalog->id])"/>
                        </div>
                @endforeach 
            </div>
        </div>

        <x-fragments.catalog-off-canvas canvasId="catalogOffCanvas" />

        @push('scripts')
            <script src={{ asset('scripts/catalogOffCanvasHandler.js') }}></script>
            <script src={{ asset('scripts/navbarHandler.js') }}></script>
        @endpush
</x-layouts.cashlayout>
