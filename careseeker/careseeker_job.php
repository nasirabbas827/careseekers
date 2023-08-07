<?php
session_start();
include('config.php');

// Check if the user is logged in as a care seeker
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "care_seeker") {
    header("Location: careseeker_login.php");
    exit;
}

// Fetch categories from the database
$select_categories_query = "SELECT * FROM categories";
$categories_result = $conn->query($select_categories_query);
$categories = [];

if ($categories_result->num_rows > 0) {
    while ($row = $categories_result->fetch_assoc()) {
        $categories[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $care_seeker_id = $_SESSION["user_id"];
    $required_service = $_POST['required_service'];
    $detail = $_POST['detail'];
    $address = $_POST['address'];
    $estimated_hourly_budget = $_POST['estimated_hourly_budget'];
    $time_of_service = $_POST['time_of_service'];

    // Get the category ID based on the selected category name
    $category_name = $_POST['required_service'];
    $category_query = "SELECT id FROM categories WHERE name = '$category_name'";
    $category_result = $conn->query($category_query);
    $category_id = ($category_result->num_rows > 0) ? $category_result->fetch_assoc()['id'] : null;

    // Insert job into database with pending status
    $insert_query = "INSERT INTO jobs (care_seeker_id, category_id, required_service, detail, address, estimated_hourly_budget, time_of_service, status) 
                     VALUES ($care_seeker_id, $category_id, '$required_service', '$detail', '$address', $estimated_hourly_budget, '$time_of_service', 'pending')";

    if ($conn->query($insert_query) === TRUE) {
        $job_posted = true;
    } else {
        $job_post_error = "Error posting job: " . $conn->error;
    }

    $conn->close();
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Post Job</title>
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
    <h3>Welcome to Your Dashboard, Care Seeker!</h3>
    <h3>Post Job</h3>
    <?php
    if (isset($job_posted)) {
        echo "<p style='color: green;'>Job posted successfully! Awaiting approval.</p>";
    } elseif (isset($job_post_error)) {
        echo "<p style='color: red;'>$job_post_error</p>";
    }
    ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="form-group">
            <label for="required_service">Required Service:</label>
            <select id="required_service" name="required_service" required class="form-control">
                <?php foreach ($categories as $category) : ?>
                    <option value="<?php echo $category['name']; ?>"><?php echo $category['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="detail">Detail of Required Service:</label>
            <textarea id="detail" name="detail" required class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="address">Address:</label>
            <textarea id="address" name="address" required class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="estimated_hourly_budget">Estimated Hourly Budget:</label>
            <input type="number" id="estimated_hourly_budget" name="estimated_hourly_budget" required class="form-control">
        </div>

        <div class="form-group">
            <label for="time_of_service">Time of Service:</label>
            <input type="datetime-local" id="time_of_service" name="time_of_service" required class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Post Job</button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
