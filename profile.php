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
<?php require('partials/footer.php') ?>
</body>
</html>
