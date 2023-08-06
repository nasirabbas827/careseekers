<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Fetch accepted job details
$select_accepted_jobs_query = "SELECT ja.*, j.required_service, cs.full_name AS careseeker_name, cs.email AS careseeker_email, cs.contact_number AS careseeker_contact, 
                               sw.full_name AS worker_name, sw.email AS worker_email, sw.contact_number AS worker_contact
                               FROM job_accepted ja
                               INNER JOIN jobs j ON ja.job_id = j.id
                               INNER JOIN care_seekers cs ON ja.careseeker_id = cs.id
                               INNER JOIN support_workers sw ON ja.worker_id = sw.id
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
<?php include('admin_navbar.php'); ?>

<h3>View Accepted Jobs Information</h3>

<table>
    <tr>
        <th>Job ID</th>
        <th>Required Service</th>
        <th>Care Seeker Name</th>
        <th>Care Seeker Email</th>
        <th>Care Seeker Contact</th>
        <th>Assigned Support Worker</th>
        <th>Worker Email</th>
        <th>Worker Contact</th>
        <th>Accepted Date</th>
    </tr>
    <?php foreach ($accepted_jobs as $job) : ?>
        <tr>
            <td><?php echo $job['job_id']; ?></td>
            <td><?php echo $job['required_service']; ?></td>
            <td><?php echo $job['careseeker_name']; ?></td>
            <td><?php echo $job['careseeker_email']; ?></td>
            <td><?php echo $job['careseeker_contact']; ?></td>
            <td><?php echo $job['worker_name']; ?></td>
            <td><?php echo $job['worker_email']; ?></td>
            <td><?php echo $job['worker_contact']; ?></td>
            <td><?php echo $job['accepted_date']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
