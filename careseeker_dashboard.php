<?php
session_start();
include('config.php');
// Check if the user is logged in as a care seeker
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "care_seeker") {
    header("Location: careseeker_login.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Care Seeker Dashboard</title>
</head>
<body>
<?php include('navbar.php'); ?>

<h3>Welcome to Your Dashboard, Care Seeker!</h3>



<a href="logout.php">Logout</a>
</body>
</html>
