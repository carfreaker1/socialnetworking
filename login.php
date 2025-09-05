<?php include('partials\header.php') ?>
<?php session_start(); ?>
<body>
<div class="container">
    <div class="form-wrapper">
        <h2>Login Social Network</h2>
        <form id="loginForm">

        

            <small class="error" id="login_error"></small>
            <label>Email Address</label>
            <input name="email" oninput="validateEmail(this)" type="email" required>


   
                    <label>Password</label>
                    <input name="password" type="password" id="password" oninput="validatePassword()" required>

     
            <small>Use A-Z, a-z, 0-9, !@#$%^&* in password</small>

            <button type="submit" class="signup-btn" style="margin-top: 4px;">Login</button>
            <a href="signup.php">New User? Register</a>

        </form>
    </div>
</div>
<?php require('partials/footer.php') ?>
</body>
</html>
