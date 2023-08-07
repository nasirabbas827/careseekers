<?php
session_start();
include('config.php');

// Check if the user is logged in as a care seeker
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "care_seeker") {
    header("Location: careseeker_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message_id']) && isset($_POST['reply_message'])) {
    $message_id = $_POST['message_id'];
    $reply_message = $_POST['reply_message'];

    // Update the message with the care seeker's reply
    $update_message_query = "UPDATE chat_messages SET reply = '$reply_message' WHERE id = $message_id";

    if ($conn->query($update_message_query) === TRUE) {
        header("Location: careseeker_message.php"); // Redirect back to messages page
    } else {
        echo "Error updating message: " . $conn->error;
    }
}

$conn->close();
?>
