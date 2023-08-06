<?php
session_start();
include('config.php');

// Check if the user is logged in as a support worker
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "support_worker") {
    header("Location: worker_login.php");
    exit;
}

$worker_id = $_SESSION["user_id"];

// Fetch worker's categories from the database
$select_worker_categories_query = "SELECT category FROM support_workers WHERE id = $worker_id";
$worker_categories_result = $conn->query($select_worker_categories_query);
$worker_categories = [];

if ($worker_categories_result->num_rows > 0) {
    $row = $worker_categories_result->fetch_assoc();
    $worker_categories = explode(',', $row['category']);
}

// Fetch all categories from the database
$select_categories_query = "SELECT * FROM categories";
$categories_result = $conn->query($select_categories_query);
$categories = [];

if ($categories_result->num_rows > 0) {
    while ($row = $categories_result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Filter jobs based on selected category, if any
$selected_category = isset($_GET['category']) ? $_GET['category'] : '';
$jobs = [];

if ($selected_category) {
    $select_jobs_query = "SELECT * FROM jobs WHERE required_service = '$selected_category' ORDER BY id DESC";
} else {
    $select_jobs_query = "SELECT * FROM jobs ORDER BY FIELD(required_service, '" . implode("','", $worker_categories) . "') DESC, id DESC";
}

$jobs_result = $conn->query($select_jobs_query);

if ($jobs_result->num_rows > 0) {
    while ($row = $jobs_result->fetch_assoc()) {
        $jobs[] = $row;
    }
}

// Worker accepts a job
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accept_job'])) {
    $accepted_job_id = $_POST['accept_job'];
    
    // Get job details
    $select_job_query = "SELECT * FROM jobs WHERE id = $accepted_job_id";
    $job_result = $conn->query($select_job_query);
    
    if ($job_result->num_rows > 0) {
        $job = $job_result->fetch_assoc();
        
        // Insert accepted job details into job_accepted table
        $insert_accepted_job_query = "INSERT INTO job_accepted (worker_id, job_id, careseeker_id, category_id, required_service) 
                                     VALUES ($worker_id, $accepted_job_id, " . $job['care_seeker_id'] . ", " . $job['category_id'] . ", '" . $job['required_service'] . "')";
        
        if ($conn->query($insert_accepted_job_query) === TRUE) {
            // Update the job status to "accepted"
            $update_status_query = "UPDATE jobs SET status = 'accepted' WHERE id = $accepted_job_id";
            if ($conn->query($update_status_query) === TRUE) {
                // You can add further logic or notifications here if needed
            } else {
                $accept_error = "Error updating job status: " . $conn->error;
            }
        } else {
            $accept_error = "Error accepting job: " . $conn->error;
        }
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

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
    <label for="category">Filter by Category:</label>
    <select id="category" name="category">
        <option value="">All Categories</option>
        <?php foreach ($categories as $category) : ?>
            <option value="<?php echo $category['name']; ?>" <?php if ($selected_category == $category['name']) echo 'selected'; ?>>
                <?php echo $category['name']; ?>
            </option>
        <?php endforeach; ?>
    </select>
    <input type="submit" value="Apply">
</form>

<table>
    <tr>
        <th>Job ID</th>
        <th>Required Service</th>
        <th>Detail</th>
        <th>Address</th>
        <th>Estimated Hourly Budget</th>
        <th>Time of Service</th>
        <th>Status</th>
        <th>Action</th>
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
            <td>
                <a href="chat.php?job_id=<?php echo $job['id']; ?>">Chat</a>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" style="display: inline-block;">
                        <input type="hidden" name="accept_job" value="<?php echo $job['id']; ?>">
                        <input type="submit" value="Accept">
                    </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
