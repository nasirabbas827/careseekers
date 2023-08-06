<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="">Careseekers</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <?php if ($_SESSION["usertype"] === "support_worker") { // Check if the user is a worker ?>
        <li class="nav-item active">
          <a class="nav-link" href="worker_dashboard.php">Support Worker Dashboard</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="worker_messages.php">Chat Data</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="worker_jobs.php">My Jobs</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout</a>
        </li>
      <?php } elseif ($_SESSION["usertype"] === "care_seeker") { // Check if the user is a care seeker ?>
        <li class="nav-item active">
          <a class="nav-link" href="">Careseekers Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="careseeker_job.php">Post Job</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="careseeker_job_status.php">Job Status</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="careseeker_message.php">Chat</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="careseeker_accepted_jobs.php">Job Accepted</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout</a>
        </li>
      <?php } else { // Default for visitors ?>
        <li class="nav-item">
          <a class="nav-link" href="worker_register.php">Worker Register</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="worker_login.php">Worker Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="careseeker_register.php">Careseeker Register</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="careseeker_login.php">Careseeker Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./admin/admin_login.php">Login as Admin</a>
        </li>
      <?php } ?>
    </ul>
  </div>
</nav>
