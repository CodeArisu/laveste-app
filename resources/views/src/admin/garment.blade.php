<x-layouts.admin>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/tables.css') }}">
        <link rel="stylesheet" href="{{ asset('css/admin/garment.css') }}">
    @endpush

    @if (Session('success'))
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
                    <tr class="product-row" data-garment-id="{{ $garment->id }}">
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

        <div class="sidepanel" id="garmentSidePanel">
            {{-- side panel content --}}
        </div>

        <div class="side-panel" id="editGarmentPanel">
            {{-- garment edit panel --}}
        </div>  
    
    </div>

    @push('scripts')
        <script src='{{ asset('scripts/garmentPanelsHandler.js') }}'></script>
    @endpush

</x-layouts.admin>