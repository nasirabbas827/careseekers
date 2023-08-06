<?php
session_start();
include('config.php');

// Check if the user is logged in as a support worker
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "support_worker") {
    header("Location: worker_login.php");
    exit;
}

$worker_id = $_SESSION["user_id"];

if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];

    // Fetch job details and care seeker's information
    $select_job_query = "SELECT * FROM jobs WHERE id = $job_id";
    $job_result = $conn->query($select_job_query);

    if ($job_result->num_rows > 0) {
        $job = $job_result->fetch_assoc();
        
        $care_seeker_id = $job['care_seeker_id'];

        // Fetch care seeker's information
        $select_care_seeker_query = "SELECT * FROM care_seekers WHERE id = $care_seeker_id";
        $care_seeker_result = $conn->query($select_care_seeker_query);

        if ($care_seeker_result->num_rows > 0) {
            $care_seeker = $care_seeker_result->fetch_assoc();
        } else {
            $care_seeker = false; // Mark as care seeker not found
        }
    } else {
        $care_seeker = false; // Mark as care seeker not found
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
    $message = $_POST['message'];

    // Insert message into database
    $insert_message_query = "INSERT INTO chat_messages (job_id, worker_id, message) VALUES ($job_id, $worker_id, '$message')";

    if ($conn->query($insert_message_query) === TRUE) {
        // Message inserted successfully
    } else {
        echo "Error inserting message: " . $conn->error;
    }
}

// Fetch chat messages
$select_messages_query = "SELECT chat_messages.*, workers.full_name AS worker_name
                         FROM chat_messages
                         INNER JOIN workers ON chat_messages.worker_id = workers.id
                         WHERE job_id = $job_id
                         ORDER BY id ASC";

$messages_result = $conn->query($select_messages_query);
$messages = [];



$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Continue Chat</title>
</head>
<body>
<?php include('navbar.php'); ?>

<h3>Continue Chat with Care Seeker</h3>

<?php if ($care_seeker && is_array($messages)) : ?>
    <h4>Care Seeker: <?php echo $care_seeker['full_name']; ?></h4>
    <p>Email: <?php echo $care_seeker['email']; ?></p>
    <p>Contact Number: <?php echo $care_seeker['contact_number']; ?></p>

    <div id="chat-box">
        <?php foreach ($messages as $message) : ?>
            <p><?php echo $message['worker_name'] . ': ' . $message['message']; ?></p>
        <?php endforeach; ?>
    </div>

    <form action="<?php echo $_SERVER['PHP_SELF'] . '?job_id=' . $job_id; ?>" method="POST">
        <input type="text" name="message" placeholder="Type your message" required>
        <button type="submit">Send</button>
    </form>
    
    <?php if (isset($_POST['message'])) : ?>
        <p style="color: green;">Message sent successfully!</p>
    <?php endif; ?>

<?php else : ?>
    <p>Invalid Job ID or Care Seeker not found.</p>
<?php endif; ?>

</body>
</html>