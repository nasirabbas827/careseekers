<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Handle category deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $category_id = $_GET['delete'];

    // Delete category from the database
    $delete_query = "DELETE FROM categories WHERE id = $category_id";

    if ($conn->query($delete_query) === TRUE) {
        $category_deleted = true;
    } else {
        $category_delete_error = "Error deleting category: " . $conn->error;
    }
}

// Fetch all categories from the database
$select_query = "SELECT id, name FROM categories";
$result = $conn->query($select_query);
$categories = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Categories</title>
</head>
<body>
<?php include('admin_navbar.php'); ?>

<h3>Manage Categories</h3>
<?php
if (isset($category_deleted)) {
    echo "<p style='color: green;'>Category deleted successfully!</p>";
} elseif (isset($category_delete_error)) {
    echo "<p style='color: red;'>$category_delete_error</p>";
}
?>

<table>
    <tr>
        <th>Category ID</th>
        <th>Category Name</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    <?php foreach ($categories as $category) : ?>
        <tr>
            <td><?php echo $category['id']; ?></td>
            <td><?php echo $category['name']; ?></td>
            <td><a href="edit_category.php?id=<?php echo $category['id']; ?>">Edit</a></td>
            <td><a href="?delete=<?php echo $category['id']; ?>" onclick="return confirm('Are you sure you want to delete this category?')">Delete</a></td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
