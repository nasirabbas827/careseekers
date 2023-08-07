<?php
session_start();
include('config.php');

// Check if the user is logged in as a care seeker
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "care_seeker") {
    header("Location: care_seeker_login.php");
    exit;
}

$care_seeker_id = $_SESSION["user_id"];

// Fetch accepted job details for the care seeker
$select_accepted_jobs_query = "SELECT ja.*, j.required_service, sw.full_name AS worker_name, sw.picture AS worker_picture, sw.hourly_rate AS worker_hourly_rate, sw.email AS worker_email, sw.contact_number AS worker_contact
                               FROM job_accepted ja
                               INNER JOIN jobs j ON ja.job_id = j.id
                               INNER JOIN support_workers sw ON ja.worker_id = sw.id
                               WHERE ja.careseeker_id = $care_seeker_id
                               ORDER BY ja.accepted_date DESC";
$accepted_jobs_result = $conn->query($select_accepted_jobs_query);
$accepted_jobs = [];

if ($accepted_jobs_result->num_rows > 0) {
    while ($row = $accepted_jobs_result->fetch_assoc()) {
        $accepted_jobs[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Accepted Jobs</title>
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
    <h3>Accepted Jobs</h3>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Job ID</th>
                    <th>Required Service</th>
                    <th>Assigned Support Worker</th>
                    <th>Worker Picture</th>
                    <th>Hourly Rate</th>
                    <th>Worker Email</th>
                    <th>Worker Contact</th>
                    <th>Accepted Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($accepted_jobs as $job) : ?>
                    <tr>
                        <td><?php echo $job['job_id']; ?></td>
                        <td><?php echo $job['required_service']; ?></td>
                        <td><?php echo $job['worker_name']; ?></td>
                        <td><img src="../<?php echo $job['worker_picture']; ?>" alt="Worker Picture" width="50"></td>
                        <td>$<?php echo $job['worker_hourly_rate']; ?></td>
                        <td><?php echo $job['worker_email']; ?></td>
                        <td><?php echo $job['worker_contact']; ?></td>
                        <td><?php echo $job['accepted_date']; ?></td>
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
