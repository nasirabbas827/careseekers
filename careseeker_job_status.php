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
</head>
<body>
<?php include('navbar.php'); ?>

<h3>View Posted Jobs</h3>

<table>
    <tr>
        <th>Job ID</th>
        <th>Required Service</th>
        <th>Detail</th>
        <th>Address</th>
        <th>Estimated Hourly Budget</th>
        <th>Time of Service</th>
        <th>Status</th>
    </tr>
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
</table>

</body>
</html>
