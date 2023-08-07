<?php
session_start();
include('config.php');

// Check if the user is logged in as a care seeker
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "care_seeker") {
    header("Location: careseeker_login.php");
    exit;
}

$care_seeker_id = $_SESSION["user_id"];

// Fetch care seeker's messages and job details from the database
$select_messages_query = "SELECT chat_messages.*, jobs.required_service, support_workers.full_name
                         FROM chat_messages
                         INNER JOIN jobs ON chat_messages.job_id = jobs.id
                         INNER JOIN support_workers ON chat_messages.worker_id = support_workers.id
                         WHERE jobs.care_seeker_id = $care_seeker_id
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
    <title>Care Seeker Messages</title>
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
    <h3>Your Messages and Replies</h3>

    <?php if (empty($messages)) : ?>
        <p>No messages to display.</p>
    <?php else : ?>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>Job Name</th>
                        <th>Support Worker</th>
                        <th>Sent Message</th>
                        <th>Reply</th>
                        <th>Reply Form</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $message) : ?>
                        <tr>
                            <td><?php echo $message['required_service']; ?></td>
                            <td><?php echo $message['full_name']; ?></td>
                            <td><?php echo $message['message']; ?></td>
                            <td><?php echo $message['reply'] ?: 'No reply'; ?></td>
                            <td>
                                <?php if (empty($message['reply'])) : ?>
                                    <form action="careseeker_reply.php" method="POST">
                                        <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                        <div class="form-group">
                                            <textarea name="reply_message" placeholder="Reply to the support worker" class="form-control"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Send Reply</button>
                                    </form>
                                <?php else : ?>
                                    <p>Already replied</p>
                                <?php endif; ?>
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
