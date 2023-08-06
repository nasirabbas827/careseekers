<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Handle form submission to add a category
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['category_name'])) {

    $category_name = $_POST['category_name'];

    // Insert category into database
    $insert_query = "INSERT INTO categories (name) VALUES ('$category_name')";
    
    if ($conn->query($insert_query) === TRUE) {
        $category_added = true;
    } else {
        $category_error = "Error adding category: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Home</title>
</head>
<body>
<?php include('admin_navbar.php'); ?>

  
  <h3>Add Category</h3>
  <?php
  if (isset($category_added)) {
      echo "<p style='color: green;'>Category added successfully!</p>";
  } elseif (isset($category_error)) {
      echo "<p style='color: red;'>$category_error</p>";
  }
  ?>
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <label for="category_name">Category Name:</label>
    <input type="text" id="category_name" name="category_name" required><br><br>
    
    <input type="submit" value="Add Category">
  </form>
  
  <br>
  <a href="view_categories.php">View Categories</a>
</body>
</html>
