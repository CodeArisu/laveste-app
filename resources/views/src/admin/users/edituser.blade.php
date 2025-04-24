<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="/css/register.css">
</head>

<body>
    <div class="container">


        <div class="form-container">
            <a href="{{ url('/admin/users') }}" class="back-btn">←</a>
            <h1>Edit User</h1>



            <form action="" method="POST">
                @csrf

                <h3>Account Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" id="firstname" name="firstname" placeholder="First Name" required>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" id="lastname" name="lastname" placeholder="Last Name" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="contact">Contact</label>
                        <input type="text" id="contact" name="contact" placeholder="Contact Number" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" placeholder="Address" required>
                    </div>
                </div>

                <h3>Add Image</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="profileImage">Upload Image</label>
                        <input type="file" id="profileImage" name="profileImage" accept="image/*" required>
                    </div>
                </div>


                <h3>Account Details</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Email" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirmpassword">Confirm Password</label>
                        <input type="password" id="confirmpassword" name="confirmpassword"
                            placeholder="Confirm Password" required>
                    </div>
                </div>

                <input type="submit" value="Update">
            </form>


        </div>


        <div class="image-container">
            <img src="/assets/images/img1.png" alt="login image">
        </div>

    </div>
</body>

</html>