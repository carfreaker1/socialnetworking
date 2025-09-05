<?php
session_start();
require_once 'Classes/User.php';
require_once 'Classes/Post.php';
function timeAgo($datetime) {
  $timestamp = strtotime($datetime);
  $diff = time() - $timestamp;

  if ($diff < 60) {
      return $diff . " seconds ago";
  } elseif ($diff < 3600) {
      return floor($diff / 60) . " minutes ago";
  } elseif ($diff < 86400) {
      return floor($diff / 3600) . " hours ago";
  } else {
      return date('Y-m-d', $timestamp);
  }
}
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$id =$_SESSION['user_id'];
$user = new User();
$userData = $user->getuserById($id);
// print_r($userData);
// die();
$postObj = new Post();
$postsData = $postObj->getAllPosts();
$UserPostsData = $postObj->getIndivididualUserPosts($id);
// print_r($UserPostsData);
// die();
?>
<?php include('partials\header.php') ?>

<header>
  <div class="brand"><span class="dot"></span> Social Network</div>
  <div class="header-right">
    <!-- <span class="hint">Demo UI • Minimal JS</span> -->
    <button type="button" id="logoutBtn" class="btn small">Logout</button>
  </div>
</header>
<body>
<div id="notification" class="hidden"></div>

<div class="wrap">
  <div class="grid">
    <!-- PROFILE SIDEBAR -->
    <aside class="profile">
      <div class="card">
      <div class="avatar-wrap">
        <img id="profilePhoto" class="avatar" src="<?= isset($userData['image']) ? $userData['image'] : 'assets/images/default.jpg'; ?>" />
        <label class="avatar-upload">
          <input id="profilePhotoInput" hidden>
          Change
        </label>
      </div>

        <h3 id="displayName"><?php echo $userData['full_name'] ?></h3>
        <div class="field editable">
          <small>Name</small>
          <div class="value"><span id="nameValue"><?php echo $userData['full_name'] ?></span><span class="edit-icon">✎</span></div>
        </div>
        <div class="field">
          <small>Email</small>
          <div class="value" id="emailValue"><?php echo $userData['email'] ?></div>
        </div>
        <div class="field editable">
          <small>Age</small>
          <div class="value"><span id="ageValue"><?php echo $userData['dob'] ?></span><span class="edit-icon">✎</span></div>
        </div>
        <div class="profile-actions">
          <button class="btn" id="openEdit">Edit Profile</button>
        </div>
      </div>
    </aside>

    <!-- FEED AREA -->
    <main>
      <!-- COMPOSER -->
      <section class="card composer" style="margin-bottom: 16px">
        <h4>Add Post</h4>
        <form id="postSubmit" enctype="multipart/form-data">
          <textarea id="postText" name="content" class="textarea" placeholder="What's happening?"></textarea>
          <div id="postDrop" class="dropzone">Click or drag an image here</div>
          <input id="postImage" name="image" type="file" accept="image/*" hidden>
          <div id="postPreview" class="preview"></div>
          <div class="actions">
            <button id="postBtn" type="button" class="btn">Post</button>
          </div>
          </form>
      </section>

      <!-- FEED (Static) -->
      <section id="feed">
        <?php if(isset($UserPostsData) && count($UserPostsData) > 0){ ?>
          <?php foreach ($UserPostsData as $post): ?>
              <article class="card post" data-id="<?php echo $post['id'] ?>">
                  <img class="avatar-sm user-avatar" data-user-id="<?= $post['user_id'] ?>"  src="<?php echo $post['userImage']?>" />
                  <div>
                      <strong><?php $post['full_name'] ?></strong>
                      <div class="meta">Posted on • <?= date('Y-m-d H:i', strtotime($post['created_at'])) ?></div>
                      <p><?php echo $post['content'] ?></p>
                      <?php if (!empty($post['image'])): ?>
                          <div class="img"><img src="uploads/post/<?php echo $post['image'] ?>"></div>
                      <?php endif; ?>
                      <div class="controls">
                          <button class="icon-btn like <?php echo $post['user_action'] === 'like' ? 'liked' : '' ?>">👍 <span class="like-count"><?= $post['likes'] ?></span></button>
                          <button class="icon-btn dislike <?php echo $post['user_action'] === 'dislike' ? 'disliked' : '' ?>">👎 <span class="dislike-count"><?= $post['dislikes'] ?></span></button>
                          <?php if ($post['user_id'] == $_SESSION['user_id']): ?>
                              <button class="icon-btn danger delete">🗑 Delete</button>
                          <?php endif; ?>
                      </div>
                  </div>
              </article>
          <?php endforeach; ?>
        <?php }else{  ?>
          <h2 style="text-align: center;">No Post Available</h4>
        <?php } ?>
      </section>
    </main>
  </div>
</div>

<!-- EDIT MODAL -->
<div id="editModal" class="modal">
  <div class="dialog">
    <h3>Edit Profile</h3>
    <input id="userId" type="hidden" style="margin-bottom: 4px;" class="input" value="<?php echo $_SESSION['user_id'] ?>" placeholder="Your name" />
    <input id="editName" style="margin-bottom: 4px;" class="input" placeholder="Your name" />
    <input id="editAge" type="date" class="input" placeholder="Age" />
    <div class="actions">
      <button class="btn secondary" id="cancelEdit">Cancel</button>
      <button class="btn" type="button" id="saveEditUser">Save</button>
    </div>
  </div>
</div>
<script>
  async function checkInternet() {
    try {
        const response = await fetch('https://images.unsplash.com/photo-1607746882042-944635dfe10e?q=80&w=640', { method: 'HEAD', cache: 'no-store' });
        return response.ok;
    } catch (error) {
        return false;
    }
}

async function updateDotColor() {
    const dots = document.querySelectorAll('.dot');
    const isOnline = await checkInternet();
    
    if (isOnline) {
        dots.forEach(dot => dot.style.background = 'green');
        console.log("You are online!");
    } else {
        dots.forEach(dot => dot.style.background = 'red');
        console.log("You are offline!");
    }
}

updateDotColor();
window.addEventListener('online', updateDotColor);
window.addEventListener('offline', updateDotColor);
</script>
<?php require('partials/footer.php') ?>
<script>

document.addEventListener("DOMContentLoaded", () => {
  const avatarUpload = document.querySelector(".avatar-upload");

  avatarUpload.addEventListener("click", () => {
    // Only append if modal doesn't already exist
    if (!document.getElementById("imageModal")) {
      const modalHtml = `
        <div id="imageModal" class="modal">
          <div class="dialog">
            <h3>Change Profile Picture</h3>
            <input type="file" id="newProfileImage" accept="image/*" class="input" />
            <div id="imagePreviewContainer" style="margin-top: 10px;">
              <img id="imagePreview" src="" style="max-width: 100%; max-height: 200px; display: none; border-radius: 6px;" />
            </div>
            <div class="actions">
              <button class="btn secondary" id="cancelImageChange">Cancel</button>
              <button class="btn" id="saveProfileImageChange">Save</button>
            </div>
          </div>
        </div>
      `;
      document.body.insertAdjacentHTML('beforeend', modalHtml);
    }

    // Show modal
    const modal = document.getElementById("imageModal");
    modal.classList.add("show");

    const fileInput = document.getElementById("newProfileImage");
    const previewImg = document.getElementById("imagePreview");

    // Clear previous preview
    fileInput.value = '';
    previewImg.style.display = 'none';
    previewImg.src = '';

    // Show preview on file select
    fileInput.onchange = () => {
      const file = fileInput.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          previewImg.src = e.target.result;
          previewImg.style.display = 'block';
        };
        reader.readAsDataURL(file);
      }
    };

    // Cancel button
    document.getElementById("cancelImageChange").onclick = () => {
      modal.classList.remove("show");
    };

    // Save button — apply preview to profile image
    document.getElementById("saveImageChange").onclick = () => {
      const file = fileInput.files[0];
      if (file && previewImg.src) {
        document.getElementById("profilePhoto").src = previewImg.src; // Update avatar image
        modal.classList.remove("show");
      }
    };
  });
});




document.addEventListener('DOMContentLoaded', () => {

    // Profile Edit Modal
    const editModal = document.querySelector('#editModal');
    const openEditBtn = document.querySelector('#openEdit');
    const cancelEditBtn = document.querySelector('#cancelEdit');
    const saveEditBtn = document.querySelector('#saveEditUser');

    const nameValue = document.querySelector('#nameValue');
    const ageValue = document.querySelector('#ageValue');
    const displayName = document.querySelector('#displayName');

    openEditBtn.addEventListener('click', () => {
        editModal.style.display = 'flex';
        document.querySelector('#editName').value = nameValue.textContent;
        document.querySelector('#editAge').value = ageValue.textContent;
    });

    cancelEditBtn.addEventListener('click', () => {
        editModal.style.display = 'none';
    });

    saveEditBtn.addEventListener('click', () => {
        const name = document.querySelector('#editName').value.trim();
        const age = document.querySelector('#editAge').value;

        if (name) {
            nameValue.textContent = name;
            displayName.textContent = name;
        }
        if (age) {
            ageValue.textContent = age;
        }
        editModal.style.display = 'none';
    });

    // Avatar upload
    // document.querySelector('#profilePhotoInput').addEventListener('change', (e) => {
    //     const file = e.target.files[0];
    //     if (file) {
    //         const imgURL = URL.createObjectURL(file);
    //         document.querySelector('#profilePhoto').src = imgURL;
    //     }
    // });

    // Image preview for post
    const postInput = document.querySelector('#postImage');
    const preview = document.querySelector('#postPreview');
    const postDrop = document.querySelector('#postDrop');

    postDrop.addEventListener('click', () => postInput.click());

    postInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const img = new Image();
            img.src = URL.createObjectURL(file);
            img.style.maxWidth = "100%";
            img.style.borderRadius = "8px";
            preview.innerHTML = '';
            preview.append(img);
        }
    });

    // Add Post
    // feed.addEventListener('click', (e) => {
    //     if (e.target.closest('.like')) {
    //         const likeCount = e.target.closest('.like').querySelector('.like-count');
    //         likeCount.textContent = parseInt(likeCount.textContent) + 1;
    //     }

    //     if (e.target.closest('.dislike')) {
    //         const dislikeCount = e.target.closest('.dislike').querySelector('.dislike-count');
    //         dislikeCount.textContent = parseInt(dislikeCount.textContent) + 1;
    //     }

    //     if (e.target.closest('.delete')) {
    //         e.target.closest('.post').remove();
    //     }
    // });

});
function showNotification(message, type) {
    const notification = document.getElementById("notification");
    notification.textContent = message;
    notification.className = type === "success" ? "show" : "show error";
    notification.classList.add("show");
    setTimeout(() => {
        notification.classList.remove("show");
    }, 3000);
}
</script>

</body>
</html>
