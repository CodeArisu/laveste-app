<x-layouts.app>
        <link rel="stylesheet" href="/css/login.css">
        <link rel="stylesheet" href="/css/login.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

        <div class="container">
            <div class="form-container">
                <h1>La Veste Rentals</h1>
                <h2>Welcome Back!</h2>
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <label for="email"></label> 
                    <input type="email" id="email" name="email" placeholder="Email" required><br>
                  
                    <div class="password-container">
                        <label for="password"></label>
                        <input type="password" id="password" name="password" placeholder="Password" required>
                        <i class="fa-solid fa-eye toggle-password" id="togglePassword"></i>
                    </div>
                    
                    
                
                    <input type="submit" value='login' />
                </form>
            </div>
            <div class="image-container">
                <img src="/assets/images/img1.png" alt="login image">
            </div>
        </div>



        <script>
          document.addEventListener("DOMContentLoaded", function() {
    const togglePassword = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("password");

    togglePassword.addEventListener("click", function() {
        const isPassword = passwordInput.getAttribute("type") === "password";
        passwordInput.setAttribute("type", isPassword ? "text" : "password");
        // Swap icon class
        this.classList.toggle("fa-eye");
        this.classList.toggle("fa-eye-slash");
    });
});

        </script>
        
    </x-layouts.app>
