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
</head>
<body>
<?php include('admin_navbar.php'); ?>

<h3>Manage Care Seekers</h3>
<?php
if (isset($status_updated)) {
    echo "<p style='color: green;'>Status updated successfully!</p>";
} elseif (isset($status_update_error)) {
    echo "<p style='color: red;'>$status_update_error</p>";
}
?>

<table>
    <tr>
        <th>Seeker ID</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Contact Number</th>
        <th>Address</th>
        <th>Status</th>
        <th>Update Status</th>
    </tr>
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
