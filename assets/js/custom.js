document.getElementById('uploadBtn').addEventListener('click', function() {
    document.getElementById('upload').click();
});

document.getElementById('upload').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});
function refreshImage(){
    document.getElementById('preview').value = "";
    const defaultImage = "https://cdn-icons-png.flaticon.com/512/847/847969.png";
    document.getElementById('preview').src = defaultImage;
}

// document.getElementById('signupForm').addEventListener('submit', function(e) {
//     e.preventDefault();
//     const password = document.getElementById('password').value;
//     const repassword = document.getElementById('repassword').value;

//     if (password !== repassword) {
//         alert('Passwords do not match!');
//     } else {
//         alert('Sign Up Successful!');
//     }
// });

function validateName(input) {
    input.value = input.value.replace(/[^a-zA-Z\s]/g, '');
}
function validateEmail(input) {
    input.value = input.value.replace(/[^a-zA-Z0-9@._-]/g, '');
}

function validatePassword(input) {
    const errorElement = document.getElementById('password');
    
    if (input.value.length < 6) {
        errorElement.textContent = "Password must be at least 6 characters.";
    } else {
        errorElement.textContent = "";
    }
}

function validatePassword() {
    const password = document.getElementById('password').value;
    const passwordError = document.getElementById('passwordError');

    if (password.length < 6) {
        passwordError.textContent = "Password must be at least 6 characters.";
    } else {
        passwordError.textContent = "";
    }
    validateConfirmPassword();
}

function validateConfirmPassword() {
    const password = document.getElementById('password').value;
    const repassword = document.getElementById('repassword').value;
    const repasswordError = document.getElementById('repasswordError');

    if (repassword.length === 0) {
        repasswordError.textContent = "";
        return;
    }

    if (repassword !== password) {
        repasswordError.textContent = "Passwords do not match.";
    } else {
        repasswordError.textContent = "";
    }
}