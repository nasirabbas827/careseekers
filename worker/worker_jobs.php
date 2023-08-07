<?php
session_start();
include('config.php');

// Check if the user is logged in as a support worker
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "support_worker") {
    header("Location: worker_login.php");
    exit;
}

$worker_id = $_SESSION["user_id"];

// Fetch accepted job details for the support worker
$select_accepted_jobs_query = "SELECT ja.*, j.required_service, cs.full_name AS careseeker_name, cs.email AS careseeker_email, cs.contact_number AS careseeker_contact
                               FROM job_accepted ja
                               INNER JOIN jobs j ON ja.job_id = j.id
                               INNER JOIN care_seekers cs ON ja.careseeker_id = cs.id
                               WHERE ja.worker_id = $worker_id
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
                    <th>Care Seeker Name</th>
                    <th>Care Seeker Email</th>
                    <th>Care Seeker Contact</th>
                    <th>Accepted Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($accepted_jobs as $job) : ?>
                    <tr>
                        <td><?php echo $job['job_id']; ?></td>
                        <td><?php echo $job['required_service']; ?></td>
                        <td><?php echo $job['careseeker_name']; ?></td>
                        <td><?php echo $job['careseeker_email']; ?></td>
                        <td><?php echo $job['careseeker_contact']; ?></td>
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
