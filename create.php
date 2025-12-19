<?php
include 'lang.php';
include 'config.php';

$categories_sql = "SELECT * FROM categories ORDER BY name ASC";
$categories_result = $conn->query($categories_sql);

$title = $content = $author = $category_id = "";
$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $author = trim($_POST['author']);
    $category_id = $_POST['category_id'];
    
    if (empty($title)) {
        $errors[] = t('title_required');
    }
    
    if (empty($content)) {
        $errors[] = t('content_required');
    }
    
    if (empty($author)) {
        $errors[] = t('author_required');
    }
    
    if (empty($category_id)) {
        $errors[] = t('category_required');
    }
    
    if (empty($errors)) {
        $sql = "INSERT INTO posts (title, content, author, category_id) VALUES (?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $title, $content, $author, $category_id);
        
        if ($stmt->execute()) {
            header("Location: index.php?success=created&lang=" . $current_lang);
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
<html lang="<?php echo $current_lang; ?>" dir="<?php echo get_direction(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('create_post'); ?> - <?php echo t('site_title'); ?></title>
    
    <?php if (get_direction() == 'rtl'): ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <?php else: ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php endif; ?>
    
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">📝 <?php echo t('site_title'); ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><?php echo t('home'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="create.php"><?php echo t('new_post'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?lang=<?php echo get_other_lang(); ?>">
                            🌐 <?php echo get_other_lang_name(); ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1 class="mb-4"><?php echo t('create_post'); ?></h1>
                
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
                                <label for="title" class="form-label"><?php echo t('post_title'); ?></label>
                                <input type="text" 
                                       class="form-control" 
                                       id="title" 
                                       name="title" 
                                       value="<?php echo htmlspecialchars($title); ?>"
                                       placeholder="<?php echo t('enter_title'); ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="category_id" class="form-label"><?php echo t('category'); ?></label>
                                <select class="form-select" id="category_id" name="category_id">
                                    <option value=""><?php echo t('select_category'); ?></option>
                                    <?php
                                    $categories_result->data_seek(0);
                                    while($category = $categories_result->fetch_assoc()): 
                                    ?>
                                        <option value="<?php echo $category['id']; ?>"
                                                <?php echo ($category_id == $category['id']) ? 'selected' : ''; ?>>
                                            <?php echo get_category_name($category['name']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="content" class="form-label"><?php echo t('content'); ?></label>
                                <textarea class="form-control" 
                                          id="content" 
                                          name="content" 
                                          rows="8"
                                          placeholder="<?php echo t('enter_content'); ?>"><?php echo htmlspecialchars($content); ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="author" class="form-label"><?php echo t('author'); ?></label>
                                <input type="text" 
                                       class="form-control" 
                                       id="author" 
                                       name="author" 
                                       value="<?php echo htmlspecialchars($author); ?>"
                                       placeholder="<?php echo t('enter_author'); ?>">
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php" class="btn btn-secondary"><?php echo t('cancel'); ?></a>
                                <button type="submit" class="btn btn-primary"><?php echo t('publish'); ?></button>
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
