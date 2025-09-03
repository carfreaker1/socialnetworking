<?php
require_once 'User.php';
require_once 'LoginLogout.php';
require_once 'Post.php';
require_once 'LikeDislike.php';


$user = new User();
$loginLogout = new LoginLogout();
$postObj = new Post();
$likeObj = new LikeDislike();

if ($_POST['action'] == 'create') {
    $name = $_POST['full_name'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $errors = [];
    if (empty($name)) {
        $errors['full_name'] = "Full name is required.";
    }

    if (empty($dob)) {
        $errors['dob'] = "Date of birth is required.";
    }

    if (empty($email)) {
        $errors['email'] = "Email is required.";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required.";
    }

    $imageName = $_FILES['image']['name'];
    $tmpName = $_FILES['image']['tmp_name'];
    $imageSize = $_FILES['image']['size'];

    if ($imageSize > 2 * 1024 * 1024) {
        $errors['image'] = "Image size must be less than 2 MB.";
    }
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $imageExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
    if (!in_array($imageExtension, $allowedExtensions)) {
        $errors['image'] = "Only JPG, JPEG, PNG, GIF allowed.";
    }

    if (!empty($errors)) {
        echo json_encode(['status' => 'error', 'errors' => $errors]);
        exit;
    }


    $folder = "uploads/";
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }
    $imagePath = $folder . time() . "_" . basename($imageName);

    if (move_uploaded_file($tmpName, $imagePath)) {
        if ($user->create($name, $dob, $email, $password, $imagePath)) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database insert failed']);
        }
    } else {
        echo json_encode(['status' => 'error', 'errors' => ['image' => 'Image upload failed']]);
    }

}

 


if ($_POST['action'] == 'update') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $dob = $_POST['dob'];

     if($user->update($id, $name, $dob)){
        echo json_encode(['status' => 'success']);
     } else{
        echo json_encode(['status' => 'error', 'message' => 'Database insert failed']);
     }
}

if ($_POST['action'] == 'delete') {
    echo $user->delete($_POST['id']) ? "success" : "error";
}


if ($_POST['action'] == 'login') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($loginLogout->login($email, $password)) {
        echo json_encode(['status' => 'success']);
        exit;
    } else {
        echo json_encode(['status' => 'error', 'errors' => ['login_error' => 'Invalid email or password!']]);;
    }
}


if (isset($_POST['action']) && $_POST['action'] === 'logout') {
    $loginLogout->logout();
    echo json_encode(['status' => 'success', 'message' => 'Logged out successfully']);
    exit;
}
if ($_POST['action'] === 'createPost') {
    $content = $_POST['content'];
    $userId = $_SESSION['user_id']; // Assume user is logged in
    $image = null;

    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        $image = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $image);
    }

    if ($postObj->createPost($userId, $content, $image)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}