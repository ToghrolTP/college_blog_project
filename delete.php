<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "DELETE FROM posts WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: index.php?success=deleted");
        exit();
    } else {
        die("Error deleting post: " . $conn->error);
    }
    
    $stmt->close();
    $conn->close();
    
} else {
    die("No post ID provided!");
}
?>
