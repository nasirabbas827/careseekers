<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if care seeker exists and password matches
    $login_query = "SELECT * FROM care_seekers WHERE email = '$email' AND password = '$password'";
    $login_result = $conn->query($login_query);

    if ($login_result->num_rows == 1) {
        $care_seeker = $login_result->fetch_assoc();
        $_SESSION["user_id"] = $care_seeker["id"];
        $_SESSION["usertype"] = "care_seeker";
        header("Location: careseeker_dashboard.php");
        exit;
    } else {
        $login_error = "Invalid email or password.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Care Seeker Login</title>
</head>
<body>
<?php include('navbar.php'); ?>

<h3>Care Seeker Login</h3>

<?php
if (isset($login_error)) {
    echo "<p style='color: red;'>$login_error</p>";
}
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>
    
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>
    
    <input type="submit" value="Login">
</form>
</body>
</html>
