<x-layouts.app>    
    @push('styles')
        <link rel="stylesheet" href="/css/products/product.css">
        <link rel="stylesheet" href="/css/cashier/cashierhead.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
        <div class="product-grid">

            {{-- @foreach ($items as $item)
                <div class="product-card" onclick="openPanel('/assets/images/h1.png', '{{ $item->garment->product->product_name }}', '{{ $item->getFormattedRentPrice() }}', '{{ $item->garment->size->measurement }}', '{{ $item->garment->additional_description }}')">
                    <img src="{{ asset('/assets/images/h1.png') }}" alt="Product Image">
                    <div class="product-info">
                        <p class="product-name">{{ $item->garment->product->product_name }}</p>
                        <p class="price">{{ $item->getFormattedRentPrice() }}</p>
                    </div>  --}}
           
        <div class='row row-cols-1 row-cols-md-4 g-1'>
            @foreach ($catalogs as $catalog)
                <div class="col">
                    <x-fragments.catalog-card-component 
                        :image="$catalog->getImageUrl()"
                        :title="$catalog->garment->product->product_name"
                        :url="route('cashier.details', ['catalogs' => $catalog->id])"
                    />
                </div>
            @endforeach
        </div>


</x-layouts.app>
