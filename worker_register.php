<?php
session_start();
include('config.php');

// Fetch categories from the database
$select_categories = "SELECT * FROM categories";
$categories_result = $conn->query($select_categories);
$categories = [];

if ($categories_result->num_rows > 0) {
    while ($row = $categories_result->fetch_assoc()) {
        $categories[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $contact_number = $_POST['contact_number'];
    $hourly_rate = $_POST['hourly_rate'];
    $experience = $_POST['experience'];
    $reference1 = $_POST['reference1'];
    $reference2 = $_POST['reference2'];
    $category = $_POST['category'];

    // Check if email or contact number already exist
    $existing_query = "SELECT * FROM support_workers WHERE email = '$email' OR contact_number = '$contact_number'";
    $existing_result = $conn->query($existing_query);

    if ($existing_result->num_rows > 0) {
        $existing_error = "An account with the same email or contact number already exists.";
    } else {
        $picture = $_FILES['picture'];
        $picture_name = $picture['name'];
        $picture_tmp_name = $picture['tmp_name'];

        // Move uploaded picture to a folder (e.g., 'uploads')
        $upload_path = 'uploads/';
        $uploaded_picture = $upload_path . $picture_name;
        move_uploaded_file($picture_tmp_name, $uploaded_picture);

        // Insert support worker into database with pending status
        $insert_query = "INSERT INTO support_workers (full_name, email, password, contact_number, picture, hourly_rate, experience, reference1, reference2, category, status) 
                         VALUES ('$full_name', '$email', '$password', '$contact_number', '$uploaded_picture', '$hourly_rate', '$experience', '$reference1', '$reference2', '$category', 'pending')";

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
    <title>Support Worker Registration</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

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

<div class="container mt-4">
    <h3 class="mb-4">Support Worker Registration</h3>

    <?php
    if (isset($registration_success)) {
        echo "<p style='color: green;'>Registration submitted successfully! Awaiting admin approval.</p>";
    } elseif (isset($registration_error)) {
        echo "<p style='color: red;'>$registration_error</p>";
    } elseif (isset($existing_error)) {
        echo "<p style='color: red;'>$existing_error</p>";
    }
    ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="contact_number">Contact Number:</label>
            <input type="tel" id="contact_number" name="contact_number" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="picture">Picture:</label>
            <input type="file" id="picture" name="picture" accept="image/*" class="form-control-file" required>
        </div>
        
        <div class="form-group">
            <label for="hourly_rate">Hourly Rate:</label>
            <input type="number" id="hourly_rate" name="hourly_rate" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="experience">Experience:</label>
            <textarea id="experience" name="experience" class="form-control" required></textarea>
        </div>
        
        <div class="form-group">
            <label for="reference1">Reference 1:</label>
            <input type="text" id="reference1" name="reference1" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="reference2">Reference 2:</label>
            <input type="text" id="reference2" name="reference2" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="category">Category:</label>
            <select id="category" name="category" class="form-control">
                <?php foreach ($categories as $category) : ?>
                    <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Submit Registration</button>
    </form>
</div>

<!-- Add Bootstrap JS scripts at the end of the body -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
