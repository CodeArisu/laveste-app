<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/css/login.css">
</head>
<body>

    <div class="container">

        
        <div class="form-container">
            <h1>La Veste Rentals</h1>
            <h2>Welcome Back!</h2>
            


            <form action="" method="POST">
                @csrf
                <label for="email"></label> 
                <input type="email" id="email" name="email" placeholder="Email" required><br>
            
                <label for="password"></label> 
                <input type="password" id="password" name="password" placeholder="Password" required><br>
            
                <input type="submit" value="Login" >
            </form>
            
        </div>

        
        <div class="image-container">
            <img src="/assets/images/img1.png" alt="login image">
        </div>

    </div>

</body>
</html>
