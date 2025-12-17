<?php
include 'config.php';

$id = $title = $content = $author = "";
$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id = $_POST['id'];
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $author = trim($_POST['author']);
    
    if (empty($title)) {
        $errors[] = "Title is required";
    }
    
    if (empty($content)) {
        $errors[] = "Content is required";
    }
    
    if (empty($author)) {
        $errors[] = "Author name is required";
    }
    
    if (empty($errors)) {
        $sql = "UPDATE posts SET title = ?, content = ?, author = ? WHERE id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $title, $content, $author, $id);
        
        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Error updating post: " . $conn->error;
        }
        
        $stmt->close();
    }
    
} elseif (isset($_GET['id'])) {
    
    $id = $_GET['id'];
    
    // Fetch existing post data
    $sql = "SELECT * FROM posts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $post = $result->fetch_assoc();
        $title = $post['title'];
        $content = $post['content'];
        $author = $post['author'];
    } else {
        die("Post not found!");
    }
    
    $stmt->close();
    
} else {
    die("No post ID provided!");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post - Simple Blog</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">📝 Simple Blog</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="create.php">New Post</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1 class="mb-4">Edit Post</h1>
                
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="edit.php">
                            
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            
                            <div class="mb-3">
                                <label for="title" class="form-label">Post Title</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="title" 
                                       name="title" 
                                       value="<?php echo htmlspecialchars($title); ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="content" class="form-label">Post Content</label>
                                <textarea class="form-control" 
                                          id="content" 
                                          name="content" 
                                          rows="8"><?php echo htmlspecialchars($content); ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="author" class="form-label">Author Name</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="author" 
                                       name="author" 
                                       value="<?php echo htmlspecialchars($author); ?>">
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update Post</button>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
