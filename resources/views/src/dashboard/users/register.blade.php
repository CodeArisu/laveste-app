<x-layouts.app>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    @endpush

    <div class="container">
        <div class="form-container">
            <a href="{{ route('dashboard.users') }}" class="back-btn">←</a>
            <h1>Add User</h1>
            @if ($errors->has('internal_error'))
                <div class="alert alert-danger text-wrap mt-1">
                    <strong>Error:</strong> {{ $errors->first('internal_error') }}
                    <p class='p-0 m-0'>{{ $errors->first('internal_error_description') }}</p>
                </div>
            @endif
            <form action="{{ route('register') }}" method="POST">
                @csrf
                @method('POST')
                <h3>Account Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" id="firstname" value='{{ old('first_name') }}' name="first_name" placeholder="First Name" required>
                        @error('first_name')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" id="lastname" value='{{ old('last_name') }}' name="last_name" placeholder="Last Name" required>
                        @error('last_name')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="contact">Contact</label>
                        <input type="text" id="contact" value='{{ old('contact') }}' name="contact" placeholder="Contact Number" required>
                        @error('contact')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" value='{{ old('address') }}' name="address" placeholder="Address" required>
                        @error('address')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- <h3>Add Image</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="profileImage">Upload Image</label>
                        <input type="file" id="profileImage" name="profileImage" accept="image/*" required>
                    </div>
                </div> --}}


                <h3>Account Details</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" value='{{ old('name') }}' name="name" placeholder="Username" required>
                        @error('name')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" value='{{ old('email') }}' name="email" placeholder="Email" required>
                        @error('email')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Password" required>
                        @error('password')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="confirmpassword">Confirm Password</label>
                        <input type="password" id="confirmpassword" name="password_confirmation"
                            placeholder="Confirm Password" required>
                        @error('confirmpassword')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <input type="submit" value='Register'>
            </form>
        </div>
        <div class="image-container">
            <img src="/assets/images/img1.png" alt="login image">
        </div>
    </div>
</x-layouts.app>