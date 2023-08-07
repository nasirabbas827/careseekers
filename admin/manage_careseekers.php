<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $care_seeker_id = $_POST['care_seeker_id'];
    $new_status = $_POST['new_status'];

    // Update care seeker's status in the database
    $update_query = "UPDATE care_seekers SET status = '$new_status' WHERE id = $care_seeker_id";

    if ($conn->query($update_query) === TRUE) {
        $status_updated = true;
    } else {
        $status_update_error = "Error updating status: " . $conn->error;
    }
}

// Fetch care seekers from the database
$select_care_seekers_query = "SELECT * FROM care_seekers";
$care_seekers_result = $conn->query($select_care_seekers_query);
$care_seekers = [];

if ($care_seekers_result->num_rows > 0) {
    while ($row = $care_seekers_result->fetch_assoc()) {
        $care_seekers[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Care Seekers</title>
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
<?php include('admin_navbar.php'); ?>

<div class="container mt-4">
    <h3>Manage Care Seekers</h3>
    <?php
    if (isset($status_updated)) {
        echo "<p class='text-success'>Status updated successfully!</p>";
    } elseif (isset($status_update_error)) {
        echo "<p class='text-danger'>$status_update_error</p>";
    }
    ?>

    <div class="table-responsive mt-4">
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Seeker ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($care_seekers as $care_seeker) : ?>
                    <tr>
                        <td><?php echo $care_seeker['id']; ?></td>
                        <td><?php echo $care_seeker['full_name']; ?></td>
                        <td><?php echo $care_seeker['email']; ?></td>
                        <td><?php echo $care_seeker['contact_number']; ?></td>
                        <td><?php echo $care_seeker['address']; ?></td>
                        <td><?php echo $care_seeker['status']; ?></td>
                        <td>
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                <input type="hidden" name="care_seeker_id" value="<?php echo $care_seeker['id']; ?>">
                                <select name="new_status" class="form-control">
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                                <input type="submit" name="update_status" value="Update" class="btn btn-primary mt-2">
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
