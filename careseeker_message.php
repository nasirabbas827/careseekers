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
</head>
<body>
<?php include('navbar.php'); ?>

<h3>Your Messages and Replies</h3>

<?php if (empty($messages)) : ?>
    <p>No messages to display.</p>
<?php else : ?>
    <table>
        <tr>
            <th>Job Name</th>
            <th>Support Worker</th>
            <th>Sent Message</th>
            <th>Reply</th>
            <th>Reply Form</th>
        </tr>
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
                            <textarea name="reply_message" placeholder="Reply to the support worker"></textarea><br>
                            <button type="submit">Send Reply</button>
                        </form>
                    <?php else : ?>
                        <p>Already replied</p>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

</body>
</html>
