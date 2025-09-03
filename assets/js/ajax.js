$('#signupForm').submit(function(e) {
    e.preventDefault();

    let password = $('#password').val();
    let repassword = $('#repassword').val();
    if (password !== repassword) {
        alert("Passwords do not match!");
        return;
    }
    $('.error').text('');
    $('#email').text('');
    let formData = new FormData(this);
    formData.append('action', 'create');
    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                alert('User added successfully!');
                refreshImage();
                $('#signupForm')[0].reset();
                loadUsers();
                $('#email').text('');
            } else if (response.status === 'error') {
                if (response.errors) {
                    
                    if (response.errors.full_name) {
                        $('#full_name').text(response.errors.full_name);
                    }
                    if (response.errors.dob) {
                        $('#dob').text(response.errors.dob);
                    }
                    if (response.errors.email) {
                        $('#email').text(response.errors.email);
                    }
                    if (response.errors.password) {
                        $('#password').text(response.errors.password);
                    }
                    if (response.errors.image) {
                        $('#image').text(response.errors.image);
                    }
                } else {
                    alert(response.message);
                }
            }
        }
    });
});



$('#loginForm').submit(function(e) {

    e.preventDefault();

 
    $('#login_error').text('');
    let formData = new FormData(this);
    formData.append('action', 'login');
    
    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                alert('User Logged in successfully!');
                window.location.href = 'profile.php';
            } else if (response.status === 'error') {
                if (response.errors) {
                    if (response.errors.full_name) {
                        $('#login_error').text(response.errors.login_error);
                    }
                } else {
                    alert(response.message);
                }
            }
        }
    });
});
console.log(typeof $); // Should print "function"
console.log($("#logoutBtn").length); // Should print 1
$(document).ready(function(){
    console.log("jQuery Loaded");
    $("#logoutBtn").click(function(){
        alert('fa');
        $.ajax({
            url: "ajax.php",
            type: "POST",
            dataType: "json",
            data: {action: "logout"},
            success: function(response){
                if(response.status === "success"){
                    $("#logoutMsg").text(response.message);
                    setTimeout(function(){
                        window.location.href = "login.php";
                    }, 1000);
                }
            }
        });
    });
});


$(document).ready(function () {
    console.log("jQuery Loaded");

    $("#saveEditUser").click(function () {
        alert('asdfa');
        let id = $("#userId").val(); 
        let name = $("#editName").val().trim();
        let dob = $("#editAge").val().trim();

        if (name === "" || dob === "") {
            alert("Please fill all fields");
            return;
        }

        $.ajax({
            url: "ajax.php",
            type: "POST",
            dataType: "json",
            data: {
                action: "update",
                id: id,
                name: name,
                dob: dob
            },
            success: function (response) {
                console.log(response);
                if (response.status === "success") {
                    $("#nameValue").text(name);
                    $("#ageValue").text(dob);
                    $("#editModal").hide();
                    alert(response.message); // Or use a nice modal
                } else if (response.status === 'error') {
                    if (response.errors) {
                        if (response.errors.full_name) {
                            $('#login_error').text(response.errors.login_error);
                        }
                    } else {
                        alert(response.message);
                    }
                }
            }
        });
    });
});
