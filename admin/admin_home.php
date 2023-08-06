<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{
            background-color: aquamarine;
        }
h1, h2, h3, h4{
    margin-top: 30px;
    margin-bottom: 30px;
    text-align: center;
}
    </style>
</head>
<body>
    <?php include('admin_navbar.php'); ?>
    <div class="container mt-5">
        <h2>Admin Dashboard</h2>
        <div class="row mt-3">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <?php
                        $query = "SELECT COUNT(*) AS total_users FROM users";
                        $result = mysqli_query($conn, $query);
                        $data = mysqli_fetch_assoc($result);
                        echo '<p class="card-text">' . $data['total_users'] . '</p>';
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Pending Users</h5>
                        <?php
                        $query = "SELECT COUNT(*) AS pending_users FROM users WHERE status = 'pending'";
                        $result = mysqli_query($conn, $query);
                        $data = mysqli_fetch_assoc($result);
                        echo '<p class="card-text">' . $data['pending_users'] . '</p>';
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Events</h5>
                        <?php
                        $query = "SELECT COUNT(*) AS total_events FROM events";
                        $result = mysqli_query($conn, $query);
                        $data = mysqli_fetch_assoc($result);
                        echo '<p class="card-text">' . $data['total_events'] . '</p>';
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Attendance</h5>
                        <?php
                        $query = "SELECT COUNT(*) AS total_attendance FROM attendance";
                        $result = mysqli_query($conn, $query);
                        $data = mysqli_fetch_assoc($result);
                        echo '<p class="card-text">' . $data['total_attendance'] . '</p>';
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Positions</h5>
                        <?php
                        $query = "SELECT COUNT(*) AS total_positions FROM votingpositions";
                        $result = mysqli_query($conn, $query);
                        $data = mysqli_fetch_assoc($result);
                        echo '<p class="card-text">' . $data['total_positions'] . '</p>';
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Candidates</h5>
                        <?php
                        $query = "SELECT COUNT(*) AS total_candidates FROM candidates";
                        $result = mysqli_query($conn, $query);
                        $data = mysqli_fetch_assoc($result);
                        echo '<p class="card-text">' . $data['total_candidates'] . '</p>';
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
