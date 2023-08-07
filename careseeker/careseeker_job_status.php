<?php
session_start();
include('config.php');

// Check if the user is logged in as a care seeker
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "care_seeker") {
    header("Location: care_seeker_login.php");
    exit;
}

$care_seeker_id = $_SESSION["user_id"];

// Fetch posted jobs for the care seeker from the database
$select_jobs_query = "SELECT * FROM jobs WHERE care_seeker_id = $care_seeker_id";
$jobs_result = $conn->query($select_jobs_query);
$jobs = [];

if ($jobs_result->num_rows > 0) {
    while ($row = $jobs_result->fetch_assoc()) {
        $jobs[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Jobs</title>
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
    <h3>View Posted Jobs</h3>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Job ID</th>
                    <th>Required Service</th>
                    <th>Detail</th>
                    <th>Address</th>
                    <th>Estimated Hourly Budget</th>
                    <th>Time of Service</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jobs as $job) : ?>
                    <tr>
                        <td><?php echo $job['id']; ?></td>
                        <td><?php echo $job['required_service']; ?></td>
                        <td><?php echo $job['detail']; ?></td>
                        <td><?php echo $job['address']; ?></td>
                        <td><?php echo $job['estimated_hourly_budget']; ?></td>
                        <td><?php echo $job['time_of_service']; ?></td>
                        <td><?php echo $job['status']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
