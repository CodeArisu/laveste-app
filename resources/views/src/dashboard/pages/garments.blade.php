<x-layouts.admin>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/tables.css') }}">
        <link rel="stylesheet" href="{{ asset('css/admin/garment.css') }}">
    @endpush

    @if (Session('success') && !str_contains(Session('success'), 'logged in'))
        <x-fragments.alert-response message="{{ Session('success') }}" type="success" />
    @endif

    <div class="product-page">
        <div class="header-section">
            <h2 class="section-title">Garments</h2>
        </div>
        <table class="product-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Type</th>
                    <th>Sub-type</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <!-- Only one tbody here -->
                @foreach ($garments as $garment)
                    <tr class="product-row" 
                    data-bs-toggle="offcanvas" 
                    data-bs-target="#garmentCanvas"
                    data-name="{{ $garment->product->product_name }}"
                    data-rented-price="{{ $garment->getFormattedRentedPrice() }}"
                    data-image="{{ $garment->getImageUrl() }}"
                    data-description="{{ $garment->additional_description }}"
                    data-condition="{{ $garment->condition->condition_name }}"
                    data-size="{{ $garment->size->measurement }}"
                    >
                        <td>{{ $garment->id }}</td>
                        <td>{{ $garment->product->product_name }}</td>
                        <td>{{ $garment->rent_price }}</td>
                        <td>{{ $garment->product->types->type_name }}</td>
                        <td>
                            {{-- loop through array of subtypes --}}
                            @foreach ($garment->product->subtypes as $subtypes)
                                {{ $subtypes->subtype_name }}
                            @endforeach
                        </td>
                        <td>
                            {{-- for condition status design conditions --}}
                            @if ($garment->condition->condition_name == 'ok')
                                <span class="status good">{{ $garment->condition->condition_name }}</span>
                            @else
                                <span class="status damaged">{{ $garment->condition->condition_name }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody> <!-- Only one tbody here -->
        </table>

    </div>

    <x-fragments.dynamic-off-canvas offCanvasId='garmentCanvas' />

    @push('scripts')
        <script src='{{ asset('scripts/garmentPanelsHandler.js') }}'></script>
        <script src='{{ asset('scripts/dynamicOffCanvas.js') }}'></script>
    @endpush

</x-layouts.admin>
