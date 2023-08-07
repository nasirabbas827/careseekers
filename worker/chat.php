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
        
        // Fetch care seeker's information
        $care_seeker_id = $job['care_seeker_id'];
        $select_care_seeker_query = "SELECT * FROM care_seekers WHERE id = $care_seeker_id";
        $care_seeker_result = $conn->query($select_care_seeker_query);
        
        if ($care_seeker_result->num_rows > 0) {
            $care_seeker = $care_seeker_result->fetch_assoc();
        }
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
$select_messages_query = "SELECT * FROM chat_messages WHERE job_id = $job_id ORDER BY id ASC";
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
    <title>Chat with Care Seeker</title>
</head>
<body>
<?php include('navbar.php'); ?>

<h3>Chat with Care Seeker</h3>

<?php if (isset($care_seeker)) : ?>
    <h4>Care Seeker: <?php echo $care_seeker['full_name']; ?></h4>
    <p>Email: <?php echo $care_seeker['email']; ?></p>
    <p>Contact Number: <?php echo $care_seeker['contact_number']; ?></p>
    
    <div id="chat-box">
        <?php foreach ($messages as $message) : ?>
            <?php if ($message['worker_id'] == $worker_id) : ?>
                <p style="text-align: right;"><?php echo $message['message']; ?></p>
            <?php else : ?>
                <p><?php echo $message['message']; ?></p>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    
    <form action="<?php echo $_SERVER['PHP_SELF'] . '?job_id=' . $job_id; ?>" method="POST">
        <input type="text" name="message" placeholder="Type your message" required>
        <button type="submit">Send</button>
    </form>
    
    <!-- Add JavaScript for real-time updates here -->
    
<?php else : ?>
    <p>Invalid Job ID.</p>
<?php endif; ?>

</body>
</html>
