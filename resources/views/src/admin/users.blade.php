<x-layouts.admin>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/tables.css') }}">
        <link rel="stylesheet" href="{{ asset('css/admin/users.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @endpush

    <div class="product-page">
        <div class="header-section">
            <h2 class="section-title1">User Management</h2>
            <a href="{{ route('form.register') }}" class="add-product-btn">
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
            <tbody>
                @foreach ($users as $user)
                    <tr class="product-row" onclick="openPanel()">
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role->role_name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>


        <div id="overlay" class="overlay" onclick="closePanel()"></div>
       
        <div id="overlay" class="overlay" onclick="closePanel()"></div>

        <div id="profilePanel" class="profile-panel">
            <div class="profile-header">
                <img src="/assets/images/catty.jpg" alt="User Photo">
            </div>
        
            <div class="section">
                <div class="section-title">
                    <h2>User Information</h2>
                    <a href="/admin/user_blades/edituser" class="edit-btn">
                        <i class="fas fa-edit"></i> Edit
                    </a>                    
                </div>
                <div class="info-row"><span>First Name</span><span class="value">Anna</span></div>
                <div class="info-row"><span>Last Name</span><span class="value">Cruz</span></div>
                <div class="info-row"><span>Contact</span><span class="value">09123456789</span></div>
                <div class="info-row"><span>Address</span><span class="value">Davao City</span></div>
            </div>
        
            <div class="section">
                <h2>Account Details</h2>
                <div class="info-row"><span>Username</span><span class="value">Anna</span></div>
                <div class="info-row"><span>Email</span><span class="value">anna@gmail.com</span></div>
            </div>
        
            <div class="btn-wrapper">
                <button class="disable-btn">Disable</button>
            </div>
        </div>
        


        <script>
            function openPanel() {
    const panel = document.getElementById('profilePanel');
    const overlay = document.getElementById('overlay');
    panel.classList.add('show');
    overlay.classList.add('show');
}

function closePanel() {
    const panel = document.getElementById('profilePanel');
    const overlay = document.getElementById('overlay');
    panel.classList.remove('show');
    overlay.classList.remove('show');
}

        </script>

</x-layouts.admin>