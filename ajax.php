<?php
require_once 'Classes/User.php';
require_once 'Classes/LoginLogout.php';
require_once 'Classes/Post.php';
require_once 'Classes/LikeDislike.php';


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


    $folder = "uploads/profile/";
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }
    $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $imageName = time() . '.' . $fileExtension;
    $imagePath = $folder . $imageName;
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


// for user image update

if ($_POST['action'] === 'update_profile_image') {
    $userId = $_POST['user_id'];

    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
        $image = $_FILES['profile_image'];

        $folder = "uploads/profile/";
        $tmpName = $_FILES['profile_image']['tmp_name'];
        $imageName = $_FILES['profile_image']['name'];
        $imageSize = $_FILES['profile_image']['size'];

        if ($imageSize > 2 * 1024 * 1024) {
            $errors['profile_image'] = "Image size must be less than 2 MB.";
        }
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $imageExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
        if (!in_array($imageExtension, $allowedExtensions)) {
            $errors['profile_image'] = "Only JPG, JPEG, PNG, GIF allowed.";
        }
    
        if (!empty($errors)) {
            echo json_encode(['status' => 'error', 'errors' => $errors]);
            exit;
        }
        $fileExtension = pathinfo($imageName, PATHINFO_EXTENSION);
        $image = $imageName = time() . '.' . $fileExtension;
        $imagePath = $folder . $imageName;
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        if (move_uploaded_file($tmpName, $imagePath)) {
            // Update DB using your User class
            if ($user->updateProfileImage($userId, $imagePath)) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Profile image updated successfully.',
                    'image_url' => $imagePath
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Database update failed.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to move uploaded file.'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid image upload.'
        ]);
    }
    exit;
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
    $userId = $_SESSION['user_id'];
    $image = null;
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
    $Postfolder = "uploads/post/";
    
    if (!is_dir($Postfolder)) {
        mkdir($Postfolder, 0777, true);
    }
    if (!empty($_FILES['image']['name'])) {
        $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image = $imageName = time() . '.' . $fileExtension;
        $imagePath = $Postfolder . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    }

    $latestPostId = $postObj->createPost($userId, $content, $image);
    if ($latestPostId) {
        $userData = $user->getuserById($userId);
        // $userName = $$user['full_name']
        echo json_encode([
            'status' => 'success',
            'post' => [
                'text' => $content,
                'image' => $image, // filename
                'user_name' => $userData['full_name'],
                'post_id' => $latestPostId,
                'user_photo' => $userData['image']
            ]
        ]);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

if ($_POST['action'] === 'deletePost') {
    $postId = $_POST['postId'];
    $userId = $_SESSION['user_id'];

    if ($postObj->deletePost($postId, $userId)) {
        echo json_encode(['status' => 'deleted']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

if ($_POST['action'] === 'likeDislike') {
    $userId = $_SESSION['user_id'];
    $postId = $_POST['postId'];
    $act = $_POST['act'];

    $likeObj->toggleAction($userId, $postId, $act);
    $counts = $likeObj->countActions($postId);
    $userAction = $likeObj->getUserAction($userId, $postId);

    echo json_encode(['likes' => $counts['likes'], 'dislikes' => $counts['dislikes'], 'userAction' => $userAction]);
}