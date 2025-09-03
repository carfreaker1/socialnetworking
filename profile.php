<?php
session_start();
require_once 'User.php';
require_once 'Post.php';
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$id =$_SESSION['user_id'];
$user = new User();
$userData = $user->getuserById($id);
// print_r($userData);
// die();
// $postObj = new Post();
// $posts = $postObj->getAllPosts();
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
<div class="wrap">
  <div class="grid">
    <!-- PROFILE SIDEBAR -->
    <aside class="profile">
      <div class="card">
        <div class="avatar-wrap">
          <img id="profilePhoto" class="avatar" src="<?php echo isset($userData['image']) ? $userData['image'] : 'assets/images/photo-1607746882042-944635dfe10e.jpg'; ?>" />
          <label class="avatar-upload">
            <input id="profilePhotoInput" type="file" accept="image/*" hidden>
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
        <textarea id="postText" class="textarea" placeholder="What's happening?"></textarea>
        <div id="postDrop" class="dropzone">Click or drag an image here</div>
        <input id="postImage" type="file" accept="image/*" hidden>
        <div id="postPreview" class="preview"></div>
        <div class="actions">
          <button id="postBtn" class="btn">Post</button>
        </div>
      </section>

      <!-- FEED (Static) -->
      <section id="feed">
        <article class="card post">
          <img class="avatar-sm" src="https://images.unsplash.com/photo-1607746882042-944635dfe10e?q=80&w=640" />
          <div>
            <strong>John Doe</strong>
            <div class="meta">Posted on • 2024-11-20</div>
            <p>Innovation, teamwork, and growth—what we stand for!</p>
            <div class="img"><img src="https://images.unsplash.com/photo-1529336953121-ad5a0d43d0d2?q=80&w=1200" /></div>
            <div class="controls">
              <button class="icon-btn like">👍 <span class="like-count">25</span></button>
              <button class="icon-btn dislike">👎 <span class="dislike-count">10</span></button>
              <button class="icon-btn danger delete">🗑 Delete</button>
            </div>
          </div>
        </article>
        <article class="card post">
          <img class="avatar-sm" src="https://images.unsplash.com/photo-1607746882042-944635dfe10e?q=80&w=640" />
          <div>
            <strong>John Doe</strong>
            <div class="meta">Posted on • 2024-11-20</div>
            <p>Innovation, teamwork, and growth—what we stand for!</p>
            <div class="img"><img src="https://images.unsplash.com/photo-1529336953121-ad5a0d43d0d2?q=80&w=1200" /></div>
            <div class="controls">
              <button class="icon-btn like">👍 <span class="like-count">25</span></button>
              <button class="icon-btn dislike">👎 <span class="dislike-count">10</span></button>
              <button class="icon-btn danger delete">🗑 Delete</button>
            </div>
          </div>
        </article>
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

<?php require('partials/footer.php') ?>
<script>
    // Profile Edit Modal
document.querySelector('#openEdit').addEventListener('click', () => {
    document.querySelector('#editModal').style.display = 'flex';
    document.querySelector('#editName').value = document.querySelector('#nameValue').textContent;
    document.querySelector('#editAge').value = document.querySelector('#ageValue').textContent;
});

document.querySelector('#cancelEdit').addEventListener('click', () => {
    document.querySelector('#editModal').style.display = 'none';
});

document.querySelector('#saveEdit').addEventListener('click', () => {
    const name = document.querySelector('#editName').value;
    const age = document.querySelector('#editAge').value;

    document.querySelector('#nameValue').textContent = name;
    document.querySelector('#ageValue').textContent = age;
    document.querySelector('#displayName').textContent = name;
    document.querySelector('#editModal').style.display = 'none';
});

// Avatar upload
document.querySelector('#profilePhotoInput').addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
        const imgURL = URL.createObjectURL(file);
        document.querySelector('#profilePhoto').src = imgURL;
    }
});

// Image preview for post
const postInput = document.querySelector('#postImage');
const preview = document.querySelector('#postPreview');

document.querySelector('#postDrop').addEventListener('click', () => postInput.click());

postInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
        const img = new Image();
        img.src = URL.createObjectURL(file);
        preview.innerHTML = '';
        preview.append(img);
    }
});

// Add Post (Append to feed)
document.querySelector('#postBtn').addEventListener('click', () => {
    const text = document.querySelector('#postText').value.trim();
    if (!text && !preview.innerHTML) return alert('Please add text or image');

    const newPost = `
        <article class="card post">
            <img class="avatar-sm" src="${document.querySelector('#profilePhoto').src}" />
            <div>
                <strong>${document.querySelector('#nameValue').textContent}</strong>
                <div class="meta">Posted just now</div>
                <p>${text}</p>
                ${preview.innerHTML ? `<div class="img">${preview.innerHTML}</div>` : ''}
                <div class="controls">
                    <button class="icon-btn like">👍 <span class="like-count">0</span></button>
                    <button class="icon-btn dislike">👎 <span class="dislike-count">0</span></button>
                    <button class="icon-btn danger delete">🗑 Delete</button>
                </div>
            </div>
        </article>
    `;

    document.querySelector('#feed').insertAdjacentHTML('afterbegin', newPost);
    document.querySelector('#postText').value = '';
    preview.innerHTML = '';
});


</script>
  
</body>
</html>
