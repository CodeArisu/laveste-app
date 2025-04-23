<x-layouts.admin>

    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/tables.css') }}">
    @endpush

    <div class="product-page">
        <div class="header-section">
            <h2 class="section-title">User Management</h2>
            <a href="{{ url('/register') }}" class="add-product-btn">
                <span>+</span> Add User
            </a>            
        </div>

        <table class="product-table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody> <!-- Only one tbody here -->
                <tr class="product-row" onclick="window.location='{{ url('/admin/adproduct_blades/infoprod') }}'">
                    <td>0001</td>
                    <td>Anna</td>
                    <td>anna@gmail.com</td>
                    <td>Cashier</td>
                </tr>
                <tr class="product-row" onclick="window.location='{{ url('/admin/adproduct_blades/infoprod') }}'">
                    <td>0001</td>
                    <td>Lara</td>
                    <td>lara@gmail.com</td>
                    <td>Admin</td>
                </tr>
            </tbody> <!-- Only one tbody here -->
        </table>
    </div>

</x-layouts.admin>