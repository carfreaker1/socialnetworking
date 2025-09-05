<?php include('partials\header.php') ?>
<body>
<div class="container">
    <div class="form-wrapper">
        <h2>Join Social Network</h2>
        <form id="signupForm">

        <div class="profile-pic">
            <img id="preview" src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="Profile">
            <input type="file" name="image" id="upload" accept="image/*" hidden>
            <button type="button" id="uploadBtn">Upload Profile Pic</button>
            <small style="margin-top: 2px" class="error" id="image"></small>
        </div>
            <label>Full Name</label>
            <input name="full_name" oninput="validateName(this)" type="text" placeholder="John Doe" required>
            <small class="error" id="full_name"></small>

            <label>Date of Birth</label>
            <input name="dob" type="date" required>
            <small class="error" id="dob"></small>

            <label>Email Address</label>
            <input name="email" oninput="validateEmail(this)" type="email" required>
            <small class="error" id="email"></small>


            <div class="password-group">
                <div>
                    <label>Password</label>
                    <input name="password" type="password" id="password" oninput="validatePassword()" required>
                    <small class="error" id="passwordError" style="color:red;"></small>
                </div>
                <div>
                    <label>Re - Password</label>
                    <input name="repassword" type="text" id="repassword" oninput="validateConfirmPassword()" required>
                    <small class="error" id="repasswordError" style="color:red;"></small>
                </div>
            </div>
            <small style="margin-bottom: 6px;">Use A-Z, a-z, 0-9, !@#$%^&* in password</small>

            <button type="submit" class="signup-btn">Sign Up</button>
            <a href="login.php">Already Signed Login</a>
        </form>
    </div>
</div>
<?php require('partials/footer.php') ?>
</body>
</html>
