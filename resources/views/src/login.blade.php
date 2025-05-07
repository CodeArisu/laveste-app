<x-layouts.app>
    <link rel="stylesheet" href="/css/login.css">
    <link rel="stylesheet" href="/css/login.css">
    <div class="container">
        <div class="form-container">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <p>{{ $errors->first() }}</p>
                    @if ($errors->has('description'))
                        <p class="small">{{ $errors->first('description') }}</p>
                    @endif
                </div>
            @endif
            <h1>La Veste Rentals</h1>
            <h2>Welcome Back!</h2>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <label for="email"></label>
                <input type="email" id="email" name="email" placeholder="Email" value='{{ old('email') }}'
                    required><br>
                @error('email')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
                <label for="password"></label>
                <input type="password" id="password" name="password" placeholder="Password" required><br>
                @error('password')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
                <input type="submit" value='login' />
            </form>
        </div>
        <div class="image-container">
            <img src="/assets/images/img1.png" alt="login image">
        </div>
    </div>
</x-layouts.app>
