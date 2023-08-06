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
</head>
<body>
<?php include('navbar.php'); ?>

<h3>Care Seeker Registration</h3>

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
    <label for="full_name">Full Name:</label>
    <input type="text" id="full_name" name="full_name" required><br><br>
    
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>
    
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>
    
    <label for="contact_number">Contact Number:</label>
    <input type="tel" id="contact_number" name="contact_number" required><br><br>
    
    <label for="address">Address:</label>
    <textarea id="address" name="address" required></textarea><br><br>
    
    <input type="submit" value="Register">
</form>
</body>
</html>
