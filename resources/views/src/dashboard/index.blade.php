<x-layouts.admin>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
    @endpush
    <div class="dashboard">

        @if (Session('success'))
            <x-fragments.alert-response message="{{ Session('success') }}" type="success" />
            @php
                // Clear the session message after displaying it
                session()->forget('success');
            @endphp
        @endif

        <h2 class="dashboard-title">Dashboard</h2>
        <div class="dashboard-cards">
            <div class="card">
                <div class="card-left">
                    <img src="{{ asset('assets/icons/d1.png') }}" alt="Sales Icon" class="card-icon">
                    <p class="card-label">Total Sales</p>
                </div>
                <span class="card-value">₱0.00</span>
            </div>
            <div class="card">
                <div class="card-left">
                    <img src="{{ asset('assets/icons/d2.png') }}" alt="Products Icon" class="card-icon">
                    <p class="card-label">Total Products</p>
                </div>
                <span class="card-value">{{ $productCount }}</span>
            </div>
            <div class="card">
                <div class="card-left">
                    <img src="{{ asset('assets/icons/d3.png') }}" alt="Displayed Products Icon" class="card-icon">
                    <p class="card-label">Displayed Products</p>
                </div>
                <span class="card-value">{{ $catalogCount }}</span>
            </div>
        </div>

        {{-- code --}}
        <div class='pt-5 d-flex flex-row justify-content-between gap-4 h-100'>
            <div class='d-flex flex-column w-50'>
                <form action="{{ route('dashboard.code.register') }}" method='POST'>
                    @csrf
                    <div class='d-flex flex-row'>
                        <input type="text" placeholder="code" name='code'
                            value="{{ old('code', session('generatedCode') ?? '') }}"
                            class='form-control border border-primary'>

                        <select name='coupon_type'>
                            <option value='' disabled selected>Select</option>
                            @foreach ($discounts as $discount)
                                <option value={{ $discount->type() }}>{{ ucfirst($discount->type()) }}</option>
                            @endforeach
                        </select>
                        <div class='btn-group ms-2'>

                            {{-- generates code --}}
                            <button formaction="{{ route('dashboard.code') }}" formmethod="POST"
                                class='btn btn-secondary p-1 text-nowrap' type="submit">Generate Code</button>

                            {{-- registers code --}}
                            <button class='btn btn-success p-1 text-nowrap' type="submit">Register Code</button>

                        </div>
                    </div>
                </form>
                <h3 class='my-3'>Generated Codes</h3>
                <div class='border border-primary h-100'>
                    <table class="table table-striped ">
                        <thead>
                            <tr>
                                <td>Code</td>
                                <td>Description</td>
                                <td>Type</td>
                                <td>Expiry</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($codes as $code)
                                <tr>
                                    <td>{{ $code->code }}</td>
                                    <td>{{ $code->description ?? 'no description' }}</td>
                                    <td>{{ ucfirst($code->coupon_type) }}</td>
                                    <td>{{ $code->expiry_date }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

            <div class='w-100'>
                <h3 class='p-0 m-0 text-start'>
                    Recent Transactions
                </h3>
                <div class='border border-primary mt-3 h-100'>
                    <table class="table table-striped ">
                        <thead>
                            <tr>
                                <td>Product ID</td>
                                <td>Transaction ID</td>
                                <td>Status</td>
                                <td>Date</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>row col1</td>
                                <td>row col2</td>
                                <td>row col3</td>
                                <td>row col4</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-layouts.admin>
