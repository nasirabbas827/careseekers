<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the provided email and password match a registered support worker
    $login_query = "SELECT * FROM support_workers WHERE email = '$email' AND password = '$password' AND status = 'approved'";
    $login_result = $conn->query($login_query);

    if ($login_result->num_rows > 0) {
        $user = $login_result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['usertype'] = 'support_worker';

        header("Location: worker_dashboard.php");
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
    <title>Support Worker Login</title>
</head>
<body>
<?php include('navbar.php'); ?>

<h3>Support Worker Login</h3>

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
