<?php
session_start();
include 'config.php';

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $worker_id = $_POST['worker_id'];
    $new_status = $_POST['new_status'];

    // Update worker's status in the database
    $update_query = "UPDATE support_workers SET status = '$new_status' WHERE id = $worker_id";

    if ($conn->query($update_query) === true) {
        $status_updated = true;
    } else {
        $status_update_error = "Error updating status: " . $conn->error;
    }
}

// Fetch support workers from the database
$select_workers_query = "SELECT * FROM support_workers";
$workers_result = $conn->query($select_workers_query);
$workers = [];

if ($workers_result->num_rows > 0) {
    while ($row = $workers_result->fetch_assoc()) {
        $workers[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Support Workers</title>
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
<?php include 'admin_navbar.php'; ?>

<div class="container">
    <h3 class="mt-4">Manage Support Workers</h3>
    <?php
    if (isset($status_updated)) {
        echo "<p style='color: green;'>Status updated successfully!</p>";
    } elseif (isset($status_update_error)) {
        echo "<p style='color: red;'>$status_update_error</p>";
    }
    ?>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Worker ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Hourly Rate</th>
                    <th>Experience</th>
                    <th>Reference 1</th>
                    <th>Reference 2</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Picture</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($workers as $worker): ?>
                    <tr>
                        <td><?php echo $worker['id']; ?></td>
                        <td><?php echo $worker['full_name']; ?></td>
                        <td><?php echo $worker['email']; ?></td>
                        <td><?php echo $worker['contact_number']; ?></td>
                        <td><?php echo $worker['hourly_rate']; ?></td>
                        <td><?php echo $worker['experience']; ?></td>
                        <td><?php echo $worker['reference1']; ?></td>
                        <td><?php echo $worker['reference2']; ?></td>
                        <td>
                            <?php
                            include 'config.php';
                            $categoryId = $worker['category'];
                            $query = "SELECT name FROM categories WHERE id = $categoryId";
                            $result = $conn->query($query);

                            if ($result && $result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                echo $row['name'];
                            } else {
                                echo "N/A";
                            }
                            ?>
                        </td>
                        <td><?php echo $worker['status']; ?></td>
                        <td><img src="../<?php echo $worker['picture']; ?>" width="100" height="100" alt="Worker Picture"></td>
                        <td>
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                <input type="hidden" name="worker_id" value="<?php echo $worker['id']; ?>">
                                <select name="new_status">
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                                <input type="submit" name="update_status" value="Update" class="mt-3 btn btn-primary">
                            </form>
                        </td>
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
