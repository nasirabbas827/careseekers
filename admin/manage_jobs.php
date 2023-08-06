<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $job_id = $_POST['job_id'];
    $new_status = $_POST['new_status'];

    // Update job status in the database
    $update_query = "UPDATE jobs SET status = '$new_status' WHERE id = $job_id";

    if ($conn->query($update_query) === TRUE) {
        $status_updated = true;
    } else {
        $status_update_error = "Error updating status: " . $conn->error;
    }
}

// Fetch jobs and related care seeker names from the database
$select_jobs_query = "SELECT jobs.id, jobs.required_service, jobs.detail, jobs.address, jobs.estimated_hourly_budget, jobs.time_of_service, jobs.status, care_seekers.full_name
                      FROM jobs
                      JOIN care_seekers ON jobs.care_seeker_id = care_seekers.id";
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
    <title>Manage Jobs</title>
</head>
<body>
<?php include('admin_navbar.php'); ?>

<h3>Manage Jobs</h3>
<?php
if (isset($status_updated)) {
    echo "<p style='color: green;'>Status updated successfully!</p>";
} elseif (isset($status_update_error)) {
    echo "<p style='color: red;'>$status_update_error</p>";
}
?>

<table>
    <tr>
        <th>Job ID</th>
        <th>Care Seeker</th>
        <th>Required Service</th>
        <th>Detail</th>
        <th>Address</th>
        <th>Estimated Hourly Budget</th>
        <th>Time of Service</th>
        <th>Status</th>
        <th>Update Status</th>
    </tr>
    <?php foreach ($jobs as $job) : ?>
        <tr>
            <td><?php echo $job['id']; ?></td>
            <td><?php echo $job['full_name']; ?></td>
            <td><?php echo $job['required_service']; ?></td>
            <td><?php echo $job['detail']; ?></td>
            <td><?php echo $job['address']; ?></td>
            <td><?php echo $job['estimated_hourly_budget']; ?></td>
            <td><?php echo $job['time_of_service']; ?></td>
            <td><?php echo $job['status']; ?></td>
            <td>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                    <select name="new_status">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                    <input type="submit" name="update_status" value="Update">
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<br>
<a href="logout.php">Logout</a>
</body>
</html>
