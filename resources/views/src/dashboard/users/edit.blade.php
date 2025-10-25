<x-layouts.app>
    @push('styles')
        <link rel="stylesheet" href="/css/register.css">
    @endpush

    @if (Session('success') && !str_contains(Session('success'), 'logged in'))
        <x-fragments.alert-response message="{{ Session('success') }}" type='success' />
    @endif

    <div class="container">
        <div class="form-container">
            <a href="{{ route('dashboard.home') }}" class="back-btn">←</a>
            <h1>Edit User</h1>

            <form action="{{ route('update', ['user' => $user]) }}" method="POST">
                @csrf
                @method('PUT')
                <h3>Account Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" id="firstname" name="first_name"
                            value="{{ $user->userDetail->first_name }}" placeholder="First Name" required>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" id="lastname" name="last_name" value="{{ $user->userDetail->last_name }}"
                            placeholder="Last Name" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="contact">Contact</label>
                        <input type="text" id="contact" name="contact" value="{{ $user->userDetail->contact }}"
                            placeholder="Contact Number" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" value="{{ $user->userDetail->address }}"
                            placeholder="Address" required>
                    </div>
                </div>

                <h3>Account Details</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="name" value="{{ $user->name }}"
                            placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ $user->email }}"
                            placeholder="Email" required>
                    </div>
                </div>

                <input type="submit" value="Update">
            </form>
        </div>

        <div class="image-container">
            <img src="/assets/images/img1.png" alt="login image">
        </div>

    </div>
</x-layouts.app>
