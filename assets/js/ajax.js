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





// For post and save
$(document).ready(function () {
    
    // Upload image when user clicks on dropzone
    // $("#postDrop").click(function () {
    //     $("#postImage").click();
    // });

    // // Show preview
    // $("#postImage").change(function () {
    //     let file = this.files[0];
    //     if (file) {
    //         let reader = new FileReader();
    //         reader.onload = function (e) {
    //             $("#postPreview").html(`<img src="${e.target.result}" style="width:100px">`);
    //         };
    //         reader.readAsDataURL(file);
    //     }
    // });

    // Post data via AJAX
    $("#postBtn").click(function (e) {
        e.preventDefault();
        let content = $("#postText").val().trim();
        if (content === "") {
            alert("Please enter some content before posting.");
            return;
        }
        let formData = new FormData($("#postSubmit")[0]);
        formData.append("action", "createPost"); 
        $.ajax({
            url: "ajax.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                console.log(response);
                let res = JSON.parse(response);
                if (res.status === "success") {
                    const postBtn = document.querySelector('#postBtn');
                    const preview = document.querySelector('#postPreview');
                    postText.value = '';
                    preview.innerHTML = '';
                    renderPost({
                        text: res.post.text,
                        image: res.post.image ? `uploads/post/${res.post.image}` : null,
                        userName: res.post.user_name,
                        postId: res.post.post_id,
                        userPhoto: res.post.user_photo
                    });
                    showNotification("✅ Post Added Successfully!", "success");
                } else if (res.status === "error") {
                    if (res.errors) {
                        if (res.errors.image) {
                            showNotification("❌ Error: " + res.errors.image, "error");
                        }
                    } else {
                        showNotification(res.message);
                    }
                }
            }
        });;
    });
    
    function renderPost(postData) {
          const { text, image, userName, userPhoto, userId, postId } = postData;
          const newPost = `
              <article class="card post" data-id="${postId}">
                  <img class="avatar-sm" src="${userPhoto}" />
                  <div>
                      <strong>${userName}</strong>
                      <div class="meta">Posted just now</div>
                      <p>${text}</p>
                      ${image ? `<div class="img"><img src="${image}" /></div>` : ''}
                      <div class="controls">
                          <button class="icon-btn like">👍 <span class="like-count">0</span></button>
                          <button class="icon-btn dislike">👎 <span class="dislike-count">0</span></button>
                          <button class="icon-btn danger delete">🗑 Delete</button>
                      </div>
                  </div>
              </article>
          `;
          document.querySelector('#feed').insertAdjacentHTML('afterbegin', newPost);
      }

    // Like/Dislike
    $(document).on("click", ".like, .dislike", function () {
        let postId = $(this).closest(".post").data("id");
        let act = $(this).hasClass("like") ? "like" : "dislike";

        $.post("ajax.php", { action: "likeDislike", postId: postId, act: act }, function (data) {
            let res = JSON.parse(data);
            $(`[data-id='${postId}'] .like-count`).text(res.likes);
            $(`[data-id='${postId}'] .dislike-count`).text(res.dislikes);
        });
        if (act === "like") {
            // When user likes the post
            $post.find(".like").addClass("liked").removeClass("disliked");
            $post.find(".dislike").removeClass("disliked");
        } else {
            // When user dislikes the post
            $post.find(".dislike").addClass("disliked").removeClass("liked");
            $post.find(".like").removeClass("liked");
        }
    });

    // Delete Post
    $(document).on("click", ".delete", function () {
        let postId = $(this).closest(".post").data("id");
        if (confirm("Are you sure?")) {
            $.post("ajax.php", { action: "deletePost", postId: postId }, function (data) {
                let res = JSON.parse(data);
                if (res.status === "deleted") {
                    showNotification("✅ Post Deleted Successfully!", "success");
                    $(`[data-id='${postId}']`).remove();
                }else if(res.status === "error"){
                    showNotification("Some Error Occured", "error");
                }
            });
        }
    });
});




$(document).on("click", "#saveProfileImageChange", function () {
    const fileInput = document.getElementById("newProfileImage");
    const file = fileInput.files[0];

    if (!file) {
        alert("Please select an image.");
        return;
    }

    const formData = new FormData();
    formData.append("action", "update_profile_image");
    formData.append("user_id", $("#userId").val());
    formData.append("profile_image", file);

    $.ajax({
        url: "ajax.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (response) {
            if (response.status === "success") {
                // Update the avatar on page
                $("#profilePhoto").attr("src", response.image_url);
                const userId = $("#userId").val();
                $(`img.user-avatar[data-user-id='${userId}']`).attr("src", response.image_url);
                $("#imageModal").removeClass("show"); 
                showNotification(response.message, 'success');
            } else if(response.status === 'error'){
                showNotification(response.errors.profile_image, 'error');
            }else{
                showNotification(response.message || "Failed to update image.", 'error');
            }
        },
        error: function () {
            alert("AJAX error occurred.");
        }
    });
});
