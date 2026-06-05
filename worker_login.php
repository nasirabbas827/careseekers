<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the provided email and password match a registered support worker
    $login_query = "SELECT * FROM support_workers WHERE email = '$email' AND password = "YOUR_OWN_API_KEY" AND status = 'approved'";
    $login_result = $conn->query($login_query);

    if ($login_result->num_rows > 0) {
        $user = $login_result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['usertype'] = 'support_worker';

        header("Location: ./worker/worker_dashboard.php");
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
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    body{
        background-color: aquamarine;
    }
    h1, h2, h3, h4{
        text-align: center;
        margin-top: 30px;
        margin-bottom: 30px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .table th,
    .table td {
        padding: 10px;
        border: 1px solid #dee2e6;
    }

    .table thead th {
        background-color: #f8f9fa;
        font-weight: bold;
        text-align: center;
    }

    .table tbody td {
        vertical-align: middle;
    }

    .table img {
        max-width: 100px;
        max-height: 100px;
    }

    .btn-primary {
        padding: 5px 10px;
    }

</style>

</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h3 class="mb-3">Support Worker Login</h3>

    <?php
    if (isset($login_error)) {
        echo "<p style='color: red;'>$login_error</p>";
    }
    ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>

<!-- Add Bootstrap JS and Popper.js (for dropdowns, tooltips, and popovers) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
