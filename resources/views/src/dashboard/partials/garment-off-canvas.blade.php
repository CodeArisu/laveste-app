<div class="container-fluid garment-details">
    <div class="row g-4">
        <div class="col-md-6">
            <div class="h-100 rounded">
                <img src="{{ $image }}" class="img-fluid rounded shadow" alt="{{ $name }}">
            </div>
        </div>
        <div class="col-md-6">
            <h3 class="mb-3">{{ $name }}</h3>
            
            <div class="mb-3">
                <h5>Details</h5>
                <ul class="list-unstyled">
                    <li><strong>Price:</strong> ${{ number_format($price, 2) }}</li>
                    <li><strong>Type:</strong> {{ $type }}</li>
                    <li><strong>Subtypes:</strong> {{ $subtypes }}</li>
                    <li>
                        <strong>Condition:</strong> 
                        <span class="status {{ $condition == 'ok' ? 'good' : 'damaged' }}">
                            {{ ucfirst($condition) }}
                        </span>
                    </li>
                </ul>
            </div>
            
            <div class="mb-3">
                <h5>Description</h5>
                <p>{{ $description }}</p>
            </div>
            
            <div class="d-grid gap-2">
                <a href="/rent/{{ $id }}" class="btn btn-primary">Rent This Item</a>
                <button class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Close</button>
            </div>
        </div>
    </div>
</div>