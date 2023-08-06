<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['category_name'])) {
    $category_id = $_POST['category_id'];
    $new_category_name = $_POST['category_name'];

    // Update category in the database
    $update_query = "UPDATE categories SET name = '$new_category_name' WHERE id = $category_id";

    if ($conn->query($update_query) === TRUE) {
        $category_updated = true;
    } else {
        $category_update_error = "Error updating category: " . $conn->error;
    }
}

// Fetch category details based on ID
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $category_id = $_GET['id'];
    
    $select_query = "SELECT id, name FROM categories WHERE id = $category_id";
    $result = $conn->query($select_query);

    if ($result->num_rows > 0) {
        $category = $result->fetch_assoc();
    } else {
        $category_not_found = true;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Category</title>
</head>
<body>
<?php include('admin_navbar.php'); ?>

<h3>Edit Category</h3>
<?php
if (isset($category_updated)) {
    echo "<p style='color: green;'>Category updated successfully!</p>";
} elseif (isset($category_update_error)) {
    echo "<p style='color: red;'>$category_update_error</p>";
} elseif (isset($category_not_found)) {
    echo "<p style='color: red;'>Category not found.</p>";
}
?>

<?php if (isset($category)) : ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
        <label for="category_name">Category Name:</label>
        <input type="text" id="category_name" name="category_name" value="<?php echo $category['name']; ?>" required><br><br>

        <input type="submit" value="Update Category">
    </form>
<?php endif; ?>

<br>
<a href="view_categories.php">Back to Manage Categories</a><br>
</body>
</html>
