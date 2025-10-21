<x-layouts.admin>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/tables.css') }}">
        <link rel="stylesheet" href="{{ asset('css/admin/users.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @endpush

    @if (session('success'))
        <x-fragments.alert-response message="{{ Session('success') }}" type="success" />
    @endif

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
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="product-row"
                        onclick="openPanel(
                        '{{ $user->id }}',
                        '{{ $user->name }}',
                        '{{ $user->email }}',
                        '{{ $user->role->role_name }}',
                        '/assets/images/catty.jpg',
                        '{{ $user->userDetail?->contact ?? 'N/A' }}',
                        '{{ $user->userDetail?->address ?? 'N/A' }}',
                        '{{ $user->isDisabled() ? 'true' : 'false' }}'
                    )">
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role->role_name }}</td>
                        <td>
                            @if ($user->isDisabled())
                                <div class='bg-danger rounded p-1 text-white text-center w-50'>Disabled</div>
                            @else
                                <div class='bg-success rounded p-2 text-white text-center w-50'>Active</div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div id="overlay" class="overlay" onclick="closePanel()"></div>

        <div id="profilePanel" class="profile-panel">
            <div class="profile-header">
                <img id="userPhoto" src="" alt="User Photo">
            </div>
            <div class='user-contents'>
                <div class="section">
                    <div class="section-title">
                        <h2>User Information</h2>
                        <a href="{{ route('user.form.edit', ['user' => $user->id]) }}" class="edit-btn" id="editUserBtn">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                    <div class="info-row"><span>Name</span><span id="userName" class="value"></span></div>
                    <div class="info-row"><span>Contact</span><span id="userContact" class="value"></span></div>
                    <div class="info-row"><span>Address</span><span id="userAddress" class="value"></span></div>
                </div>

                <div class="section">
                    <h2>Account Details</h2>
                    <div class="info-row"><span>Username</span><span id="userUsername" class="value"></span></div>
                    <div class="info-row"><span>Email</span><span id="userEmail" class="value"></span></div>
                    <div class="info-row"><span>Role</span><span id="userRole" class="value"></span></div>
                </div>
            </div>
            <div class="btn-wrapper" id='userStatusWrapper'>
                {{-- <form action="{{ route('disable', ['user' => $user->id]) }}" method="POST">
                    @csrf
                    <button type="submit" class="disable-btn">Disable</button>
                </form> --}}
            </div>
        </div>

        <script>
            function openPanel(id, name, email, role, photo, contact, address, isDisabled) {
                const panel = document.getElementById('profilePanel');
                const overlay = document.getElementById('overlay');

                // Update panel content
                document.getElementById('userPhoto').src = photo;
                document.getElementById('userName').textContent = name;
                document.getElementById('userContact').textContent = contact;
                document.getElementById('userAddress').textContent = address;
                document.getElementById('userUsername').textContent = name;
                document.getElementById('userEmail').textContent = email;
                document.getElementById('userRole').textContent = role;

                // Update edit link with current user ID
                document.getElementById('editUserBtn').href = `/edit/${id}`;

                // Update disable button/form
                const statusWrapper = document.getElementById('userStatusWrapper');
                if (isDisabled === 'true') {
                    statusWrapper.innerHTML = '<div class="bg-danger rounded p-2 text-white">User is Disabled</div>';
                } else {
                    statusWrapper.innerHTML = `
                        <form action="/disable/${id}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="disable-btn">Disable</button>
                        </form>
                    `;
                }

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
