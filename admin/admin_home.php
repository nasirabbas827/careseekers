<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Retrieve summary data
$total_categories = 0;
$total_support_workers = 0;
$total_care_seekers = 0;
$total_jobs = 0;

// Replace with your actual data retrieval queries
$category_query = "SELECT COUNT(*) AS total_categories FROM categories";
$worker_query = "SELECT COUNT(*) AS total_support_workers FROM support_workers";
$seeker_query = "SELECT COUNT(*) AS total_care_seekers FROM care_seekers";
$job_query = "SELECT COUNT(*) AS total_jobs FROM jobs";

$category_result = $conn->query($category_query);
$worker_result = $conn->query($worker_query);
$seeker_result = $conn->query($seeker_query);
$job_result = $conn->query($job_query);

if ($category_result && $category_result->num_rows > 0) {
    $total_categories = $category_result->fetch_assoc()['total_categories'];
}

if ($worker_result && $worker_result->num_rows > 0) {
    $total_support_workers = $worker_result->fetch_assoc()['total_support_workers'];
}

if ($seeker_result && $seeker_result->num_rows > 0) {
    $total_care_seekers = $seeker_result->fetch_assoc()['total_care_seekers'];
}

if ($job_result && $job_result->num_rows > 0) {
    $total_jobs = $job_result->fetch_assoc()['total_jobs'];
}

// Retrieve pending categories
$pending_categories = array();

$pending_category_query = "SELECT id, name FROM categories WHERE status = 'pending'";
$pending_category_result = $conn->query($pending_category_query);

if ($pending_category_result && $pending_category_result->num_rows > 0) {
    while ($row = $pending_category_result->fetch_assoc()) {
        $pending_categories[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
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
<?php include('admin_navbar.php'); ?>

<div class="container mt-4">
    <h3>Admin Dashboard</h3>
    
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Categories</h5>
                    <p class="card-text"><?php echo $total_categories; ?></p>
                    <a href="view_categories.php" class="btn btn-primary">View Details</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Support Workers</h5>
                    <p class="card-text"><?php echo $total_support_workers; ?></p>
                    <a href="manage_workers.php" class="btn btn-primary">View Details</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Care Seekers</h5>
                    <p class="card-text"><?php echo $total_care_seekers; ?></p>
                    <a href="manage_careseekers.php" class="btn btn-primary">View Details</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Jobs</h5>
                    <p class="card-text"><?php echo $total_jobs; ?></p>
                    <a href="manage_jobs.php" class="btn btn-primary">View Details</a>
                </div>
            </div>
        </div>
    </div>
    
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

