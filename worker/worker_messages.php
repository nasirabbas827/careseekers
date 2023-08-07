<?php
session_start();
include('config.php');

// Check if the user is logged in as a support worker
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "support_worker") {
    header("Location: worker_login.php");
    exit;
}

$worker_id = $_SESSION["user_id"];

// Fetch worker's messages and job details from the database
$select_messages_query = "SELECT chat_messages.*, jobs.required_service, care_seekers.full_name AS care_seeker_name
                         FROM chat_messages
                         INNER JOIN jobs ON chat_messages.job_id = jobs.id
                         INNER JOIN care_seekers ON jobs.care_seeker_id = care_seekers.id
                         WHERE chat_messages.worker_id = $worker_id
                         ORDER BY chat_messages.id DESC";

$messages_result = $conn->query($select_messages_query);
$messages = [];

if ($messages_result->num_rows > 0) {
    while ($row = $messages_result->fetch_assoc()) {
        $messages[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Worker Messages</title>
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
    <h3>Your Sent Messages and Replies</h3>

    <?php if (empty($messages)) : ?>
        <p>No messages to display.</p>
    <?php else : ?>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>Job Name</th>
                        <th>Care Seeker</th>
                        <th>Sent Message</th>
                        <th>Time</th>
                        <th>Reply</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $message) : ?>
                        <tr>
                            <td><?php echo $message['required_service']; ?></td>
                            <td><?php echo $message['care_seeker_name']; ?></td>
                            <td><?php echo $message['message']; ?></td>
                            <td><?php echo $message['timestamp']; ?></td>
                            <td><?php echo $message['reply'] ?: 'No reply'; ?></td>
                            <td>
                                <a href="worker_continue_chat.php?job_id=<?php echo $message['job_id']; ?>" class="btn btn-primary">Continue Chat</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
