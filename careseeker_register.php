<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];

    // Check if email already exists
    $existing_query = "SELECT * FROM care_seekers WHERE email = '$email'";
    $existing_result = $conn->query($existing_query);

    if ($existing_result->num_rows > 0) {
        $existing_error = "An account with the same email already exists.";
    } else {
        // Insert care seeker into database with pending status
        $insert_query = "INSERT INTO care_seekers (full_name, email, password, contact_number, address, status) 
                         VALUES ('$full_name', '$email', '$password', '$contact_number', '$address', 'pending')";

        if ($conn->query($insert_query) === TRUE) {
            $registration_success = true;
        } else {
            $registration_error = "Error submitting registration: " . $conn->error;
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Care Seeker Registration</title>
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
    <h3 class="mb-3">Care Seeker Registration</h3>

    <?php
    if (isset($registration_success)) {
        echo "<p style='color: green;'>Registration submitted successfully! Awaiting approval.</p>";
    } elseif (isset($registration_error)) {
        echo "<p style='color: red;'>$registration_error</p>";
    } elseif (isset($existing_error)) {
        echo "<p style='color: red;'>$existing_error</p>";
    }
    ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="form-group">
            <label for="full_name">Full Name:</label>
            <input type="text" class="form-control" id="full_name" name="full_name" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        
        <div class="form-group">
            <label for="contact_number">Contact Number:</label>
            <input type="tel" class="form-control" id="contact_number" name="contact_number" required>
        </div>
        
        <div class="form-group">
            <label for="address">Address:</label>
            <textarea class="form-control" id="address" name="address" required></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>

<!-- Add Bootstrap JS and Popper.js (for dropdowns, tooltips, and popovers) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
