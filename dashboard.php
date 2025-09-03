<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}
?>
<?php include('partials\header.php') ?>

<body>
<small class="error" id="logoutMsg"></small>

    <h2>Welcome, <?php echo $_SESSION['user_name']; ?>!</h2>
    <p>This is a protected page. Only logged-in users can see this.</p>
</body>
<?php require('partials/footer.php') ?>

</html>
