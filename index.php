<!DOCTYPE html>
<html>
<head>
    <title>Careseeker - Find Reliable Caregivers</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            padding: 50px 0;
        }

        .jumbotron {
            background-color: #17a2b8;
            color: #ffffff;
        }

        .card {
            margin-bottom: 20px;
        }

        .service-images {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
        }

        .service-image {
            max-width: 150px;
        }

        .meet-workers {
            text-align: center;
            margin-top: 50px;
        }

        .worker-image {
            max-width: 150px;
            margin: 20px;
        }
    </style>
</head>
<body>
<?php
include('navbar.php');

?>
    
    <!-- Jumbotron -->
    <div class="jumbotron text-center">
        <h1 class="display-4">Welcome to Careseeker</h1>
        <p class="lead">Find Reliable Caregivers for Your Loved Ones</p>
    </div>

    <!-- Service Images -->
    <div class="container">
        <div class="service-images">
            <img class="service-image" src="https://plus.unsplash.com/premium_photo-1661573644696-d670a189244b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8Y3VzdG9tZXIlMjBzZXJ2aWNlfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60" alt="Service 1">
            <img class="service-image" src="https://plus.unsplash.com/premium_photo-1661573644696-d670a189244b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8Y3VzdG9tZXIlMjBzZXJ2aWNlfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60" alt="Service 2">
            <img class="service-image" src="https://plus.unsplash.com/premium_photo-1661573644696-d670a189244b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8Y3VzdG9tZXIlMjBzZXJ2aWNlfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60" alt="Service 3">
        </div>
    </div>

    <!-- Meet our Care and Support Workers -->
    <div class="container meet-workers">
        <h2>Meet our care and support workers</h2>
        <div class="row">
            <div class="col-md-4">
                <img class="worker-image" src="https://plus.unsplash.com/premium_photo-1661573644696-d670a189244b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8Y3VzdG9tZXIlMjBzZXJ2aWNlfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60" alt="Worker 1">
                <p>Experienced caregiver with a passion for providing exceptional support.</p>
            </div>
            <div class="col-md-4">
                <img class="worker-image" src="https://plus.unsplash.com/premium_photo-1661573644696-d670a189244b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8Y3VzdG9tZXIlMjBzZXJ2aWNlfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60" alt="Worker 2">
                <p>Compassionate caregiver skilled in assisting individuals with special needs.</p>
            </div>
            <div class="col-md-4">
                <img class="worker-image" src="https://plus.unsplash.com/premium_photo-1661573644696-d670a189244b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8Y3VzdG9tZXIlMjBzZXJ2aWNlfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60" alt="Worker 3">
                <p>Dedicated support worker committed to improving the quality of life for seniors.</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2023 Careseeker. All rights reserved.</p>
    </footer>

    <!-- Add Bootstrap JS scripts at the end of the body -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
