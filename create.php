<?php
include 'config.php';

$title = $content = $author = "";
$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
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
        $sql = "INSERT INTO posts (title, content, author) VALUES (?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $title, $content, $author);
        
        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Error creating post: " . $conn->error;
        }
        
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Post - Simple Blog</title>
    
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
                        <a class="nav-link active" href="create.php">New Post</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1 class="mb-4">Create New Post</h1>
                
                <!-- Display Errors -->
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
                        <form method="POST" action="create.php">
                            
                            <div class="mb-3">
                                <label for="title" class="form-label">Post Title</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="title" 
                                       name="title" 
                                       value="<?php echo htmlspecialchars($title); ?>"
                                       placeholder="Enter post title">
                            </div>
                            
                            <div class="mb-3">
                                <label for="content" class="form-label">Post Content</label>
                                <textarea class="form-control" 
                                          id="content" 
                                          name="content" 
                                          rows="8"
                                          placeholder="Write your post content here..."><?php echo htmlspecialchars($content); ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="author" class="form-label">Author Name</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="author" 
                                       name="author" 
                                       value="<?php echo htmlspecialchars($author); ?>"
                                       placeholder="Enter your name">
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Publish Post</button>
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
