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

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="mb-3">
        <div class="form-group">
            <label for="category">Filter by Category:</label>
            <select id="category" name="category" class="form-control">
                <option value="">All Categories</option>
                <?php foreach ($categories as $category) : ?>
                    <option value="<?php echo $category['name']; ?>" <?php if ($selected_category == $category['name']) echo 'selected'; ?>>
                        <?php echo $category['name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Apply</button>
    </form>

    <div class="row">
        <?php foreach ($jobs as $job) : ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $job['required_service']; ?></h5>
                        <p class="card-text"><?php echo $job['detail']; ?></p>
                        <p class="card-text">Address: <?php echo $job['address']; ?></p>
                        <p class="card-text">Budget: $<?php echo $job['estimated_hourly_budget']; ?></p>
                        <p class="card-text">Time: <?php echo $job['time_of_service']; ?></p>
                        <p class="card-text">Status: <?php echo $job['status']; ?></p>
                        <a href="chat.php?job_id=<?php echo $job['id']; ?>" class="btn btn-primary">Chat</a>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" style="display: inline-block;">
                            <input type="hidden" name="accept_job" value="<?php echo $job['id']; ?>">
                            <button type="submit" class="btn btn-success">Accept</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
