    <div class="card color-default shadow shadow-sm rounded-4">
        
        <div class='display-relative'>
            <img src="{{ $catalog->getImageUrl() }}" class="card-img-top rounded-4" alt="..." style='height: 30em; object-fit: cover;'>
            @if ($catalog->productStatus->status_name !== 'available')
                <span class='position-absolute top-0 end-0 bg-danger text-white px-2 py-1 m-2 rounded'>{{ $catalog->productStatus->status_name }}</span>
            @else
                <span class='position-absolute top-0 end-0 bg-success text-white px-2 py-1 m-2 rounded'>{{ $catalog->productStatus->status_name }}</span>
            @endif
        </div>

        <div class="card-body display-flex">
            <div class='display-flex row flex-row align-items-center'>  
                <h5 class="card-title col text-nowrap">{{ ucwords($catalog->garment->product->product_name) }}</h5>
                <h5 class="card-detail col text-end">{{ $catalog->getFormattedRentPrice() }}</h5>
            </div>

            <button class="btn btn-success w-100 rent-btn" 
                type="button" 
                data-bs-toggle="offcanvas" 
                data-bs-target="#catalogOffCanvas"
                data-title="{{ ucwords($catalog->garment->product->product_name) }}"
                data-price="{{ $catalog->getFormattedRentPrice() }}"
                data-status="{{ $catalog->productStatus->status_name }}"
                data-image="{{ $catalog->getImageUrl() }}"
                data-description="{{ $catalog->garment->additional_description }}"
                data-size="{{ strtoupper($catalog->garment->size->measurement) }}"
                data-type="{{ strtoupper($catalog->garment->product->types->type_name) }}"
                data-subtype="{{ strtoupper($catalog->garment->product->subtypes[0]->subtype_name) }}"
                route="{{ Route('cashier.details', ['catalogs' => $catalog->id]) }}">
                Rent Product
            </button>

        </div>
    </div>
