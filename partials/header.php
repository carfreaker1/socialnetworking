<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Social Network</title>
<?php $activePage = basename($_SERVER['REQUEST_URI']); ?> 
<?php if($activePage == 'signup.php' || $activePage == 'login.php'){

?>
<link rel="stylesheet" href="assets/css/style.css">
<?php }?>
<?php if($activePage == 'profile.php'){

?>
<link rel="stylesheet" href="assets/css/post.css">
<?php }?>
</head>