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
</head>
<body>
<?php include('navbar.php'); ?>

<h3>Accepted Jobs</h3>

<table>
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
    <?php foreach ($accepted_jobs as $job) : ?>
        <tr>
            <td><?php echo $job['job_id']; ?></td>
            <td><?php echo $job['required_service']; ?></td>
            <td><?php echo $job['worker_name']; ?></td>
            <td><img src="<?php echo $job['worker_picture']; ?>" alt="Worker Picture" width="50"></td>
            <td>$<?php echo $job['worker_hourly_rate']; ?></td>
            <td><?php echo $job['worker_email']; ?></td>
            <td><?php echo $job['worker_contact']; ?></td>
            <td><?php echo $job['accepted_date']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
