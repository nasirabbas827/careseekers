<?php
session_start();
include('config.php');

// Check if the user is already logged in
if (isset($_SESSION['email'])) {
  // User is logged in, redirect to another page
  header("Location: home.php"); 
  exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Event Management System</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
 body{
            background-color: aquamarine;
        }

        .container {
            padding: 50px 0;
        }
        .jumbotron {
            background-color: #343a40;
            color: #ffffff;
        }
        .card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>
    
    <div class="jumbotron text-center">
        <h1 class="display-4">Welcome to the Event Management System</h1>
        <p class="lead">Efficiently manage events and voting positions with ease.</p>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">View Events</h5>
                        <p class="card-text">Browse through upcoming events and mark attendance.</p>
                        <a href="login.php" class="btn btn-primary">View Events</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Winners</h5>
                        <p class="card-text">See the winners of different voting positions.</p>
                        <a href="login.php" class="btn btn-primary">View Winners</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Your Attendance Events</h5>
                        <p class="card-text">View events for which you marked attendance.</p>
                        <a href="user_attendance_events.php" class="btn btn-primary">View Attendance Events</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Manage Voting Positions</h5>
                        <p class="card-text">Admin: Create and manage voting positions.</p>
                        <a href="login.php" class="btn btn-primary">Manage Positions</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Bootstrap JS scripts at the end of the body -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>

