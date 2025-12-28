<?php
session_start();
include 'auth.php';
require_admin();

include 'lang.php';
include 'config.php';

$id = (int)($_GET['id'] ?? 0);
if ($id === 0) {
    header("Location: index.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    header("Location: index.php");
    exit();
}
$post = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);

    if (!empty($title) && !empty($content) && !empty($author) && $category_id > 0) {
        $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ?, author = ?, category_id = ? WHERE id = ?");
        $stmt->bind_param("sssii", $title, $content, $author, $category_id, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: index.php?success=updated");
        exit();
    }
}

$categories = $conn->query("SELECT id, name FROM categories ORDER BY name");
?>

<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>" dir="<?php echo get_direction(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('edit'); ?> - <?php echo t('site_title'); ?></title>
    
    <!-- Bootstrap CSS -->
    <?php if (get_direction() == 'rtl'): ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <?php else: ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php endif; ?>
    
<<<<<<< HEAD
    <!-- Gruvbox Minimal Theme -->
    <style>
        :root {
            /* Gruvbox Dark Palette */
            --gruv-bg: #282828;
            --gruv-bg-soft: #3c3836;
            --gruv-fg: #ebdbb2;
            --gruv-red: #cc241d;
            --gruv-green: #98971a;
            --gruv-yellow: #d79921;
            --gruv-blue: #458588;
            --gruv-purple: #b16286;
            --gruv-aqua: #689d6a;
            --gruv-gray: #a89984;
        }

        body {
            background-color: var(--gruv-bg);
            color: var(--gruv-fg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Typography & Links */
        h1, h2, h3, h4, h5, h6 { color: var(--gruv-yellow); font-weight: 600; }
        a { color: var(--gruv-blue); text-decoration: none; }
        a:hover { color: var(--gruv-aqua); }
        label { color: var(--gruv-fg); font-weight: 500; }

        /* Navbar */
        .navbar {
            background-color: var(--gruv-bg-soft) !important;
            border-bottom: 1px solid var(--gruv-bg);
        }
        .navbar-brand { color: var(--gruv-fg) !important; font-weight: bold; }
        .nav-link { color: var(--gruv-gray) !important; }
        .nav-link.active, .nav-link:hover { color: var(--gruv-fg) !important; }

        /* Cards */
        .card {
            background-color: var(--gruv-bg-soft);
            color: var(--gruv-fg);
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        }

        /* Forms & Inputs */
        .form-control, .form-select {
            background-color: var(--gruv-bg);
            border: 1px solid var(--gruv-gray);
            color: var(--gruv-fg);
        }
        .form-control:focus, .form-select:focus {
            background-color: var(--gruv-bg);
            color: var(--gruv-fg);
            border-color: var(--gruv-blue);
            box-shadow: 0 0 0 0.25rem rgba(69, 133, 136, 0.25);
        }
        ::placeholder { color: var(--gruv-gray) !important; opacity: 0.7; }
        
        /* Select dropdown arrow fix for dark mode */
        .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23ebdbb2' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        }

        /* Buttons */
        .btn-primary { background-color: var(--gruv-blue); border-color: var(--gruv-blue); color: var(--gruv-bg); }
        .btn-primary:hover { background-color: var(--gruv-aqua); border-color: var(--gruv-aqua); color: var(--gruv-bg); }
        
        .btn-secondary { background-color: var(--gruv-bg); border-color: var(--gruv-gray); color: var(--gruv-gray); }
        .btn-secondary:hover { background-color: var(--gruv-gray); border-color: var(--gruv-gray); color: var(--gruv-bg); }

        /* Alerts */
        .alert-danger { background-color: var(--gruv-bg-soft); border-color: var(--gruv-red); color: var(--gruv-red); }

        /* RTL Specifics */
        <?php if (get_direction() == 'rtl'): ?>
            .badge { margin-left: 0; margin-right: 10px; }
=======
    <style>
        :root {
            --gruv-bg: #282828;
            --gruv-bg-soft: #3c3836;
            --gruv-fg: #ebdbb2;
            --gruv-yellow: #d79921;
            --gruv-blue: #458588;
            --gruv-aqua: #689d6a;
            --gruv-gray: #a89984;
            --gruv-green: #98971a;
        }
        body { background-color: var(--gruv-bg); color: var(--gruv-fg); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        h1, h2 { color: var(--gruv-yellow); }
        a { color: var(--gruv-blue); }
        a:hover { color: var(--gruv-aqua); }
        .card { background-color: var(--gruv-bg-soft); color: var(--gruv-fg); border: none; }
        .form-control { background-color: var(--gruv-bg); border: 1px solid var(--gruv-gray); color: var(--gruv-fg); }
        .form-control:focus { background-color: var(--gruv-bg); border-color: var(--gruv-blue); box-shadow: none; }
        .btn-primary { background-color: var(--gruv-blue); border-color: var(--gruv-blue); }
        .btn-primary:hover { background-color: var(--gruv-aqua); border-color: var(--gruv-aqua); }
        <?php if (get_direction() == 'rtl'): ?>
            body { font-family: 'Tahoma', 'Arial', sans-serif; }
>>>>>>> complited
        <?php endif; ?>
    </style>
</head>
<body>
<<<<<<< HEAD
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php">📝 <?php echo t('site_title'); ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><?php echo t('home'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="create.php"><?php echo t('new_post'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?id=<?php echo $id; ?>&lang=<?php echo get_other_lang(); ?>">
                            🌐 <?php echo get_other_lang_name(); ?>
                        </a>
                    </li>
                </ul>
=======
    <nav class="navbar navbar-expand-lg" style="background-color: var(--gruv-bg-soft);">
        <div class="container">
            <a class="navbar-brand" href="index.php">📝 <?php echo t('site_title'); ?></a>
            <div class="ms-auto">
                <a href="index.php" class="btn btn-outline-light btn-sm">← <?php echo t('back_to_home'); ?></a>
>>>>>>> complited
            </div>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
<<<<<<< HEAD
                <h1 class="mb-4 text-center"><?php echo t('edit_post'); ?></h1>
                
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger shadow-sm">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <div class="card p-2">
                    <div class="card-body">
                        <form method="POST" action="edit.php">
                            
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            
                            <div class="mb-4">
                                <label for="title" class="form-label"><?php echo t('post_title'); ?></label>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       id="title" 
                                       name="title" 
                                       value="<?php echo htmlspecialchars($title); ?>">
                            </div>
                            
                            <div class="mb-4">
                                <label for="category_id" class="form-label"><?php echo t('category'); ?></label>
                                <select class="form-select" id="category_id" name="category_id">
                                    <option value="" class="text-muted"><?php echo t('select_category'); ?></option>
                                    <?php
                                    $categories_result->data_seek(0);
                                    while($category = $categories_result->fetch_assoc()): 
                                    ?>
                                        <option value="<?php echo $category['id']; ?>"
                                                <?php echo ($category_id == $category['id']) ? 'selected' : ''; ?>>
                                            <?php echo get_category_name($category['name']); ?>
=======
                <div class="card shadow">
                    <div class="card-body p-5">
                        <h2 class="text-center mb-4"><?php echo t('edit_post'); ?></h2>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label"><?php echo t('title'); ?></label>
                                <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><?php echo t('content'); ?></label>
                                <textarea class="form-control" name="content" rows="10" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><?php echo t('author'); ?></label>
                                <input type="text" class="form-control" name="author" value="<?php echo htmlspecialchars($post['author']); ?>" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label"><?php echo t('category'); ?></label>
                                <select class="form-select" name="category_id" required>
                                    <option value="">-- <?php echo t('select_category'); ?> --</option>
                                    <?php while ($cat = $categories->fetch_assoc()): ?>
                                        <option value="<?php echo $cat['id']; ?>" <?php echo $cat['id'] == $post['category_id'] ? 'selected' : ''; ?>>
                                            <?php echo get_category_name($cat['name']); ?>
>>>>>>> complited
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
<<<<<<< HEAD
                            
                            <div class="mb-4">
                                <label for="content" class="form-label"><?php echo t('content'); ?></label>
                                <textarea class="form-control" 
                                          id="content" 
                                          name="content" 
                                          rows="10"><?php echo htmlspecialchars($content); ?></textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label for="author" class="form-label"><?php echo t('author'); ?></label>
                                <input type="text" 
                                       class="form-control" 
                                       id="author" 
                                       name="author" 
                                       value="<?php echo htmlspecialchars($author); ?>">
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
                                <a href="index.php" class="btn btn-secondary px-4"><?php echo t('cancel'); ?></a>
                                <button type="submit" class="btn btn-primary px-4"><?php echo t('update'); ?></button>
                            </div>
                            
=======
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg"><?php echo t('update_post'); ?></button>
                            </div>
>>>>>>> complited
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>