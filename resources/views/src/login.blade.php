<x-layouts.app>
        <link rel="stylesheet" href="/css/login.css">
        <div class="container">
            <div class="form-container">
                <h1>La Veste Rentals</h1>
                <h2>Welcome Back!</h2>
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <label for="email"></label> 
                    <input type="email" id="email" name="email" placeholder="Email" required><br>
                
                    <label for="password"></label> 
                    <input type="password" id="password" name="password" placeholder="Password" required><br>
                
                    <button type="submit">Login</button>
                </form>    
            </div>
        </div>
</x-layouts.app>
