<?php
session_start();
include 'auth.php';
require_admin();

include 'config.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: index.php?success=deleted");
    exit();
}

header("Location: index.php");
exit();
?>