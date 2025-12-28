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
    
    <?php if (get_direction() == 'rtl'): ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <?php else: ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php endif; ?>
    
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
        <?php endif; ?>
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg" style="background-color: var(--gruv-bg-soft);">
        <div class="container">
            <a class="navbar-brand" href="index.php">📝 <?php echo t('site_title'); ?></a>
            <div class="ms-auto">
                <a href="index.php" class="btn btn-outline-light btn-sm">← <?php echo t('back_to_home'); ?></a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
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
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg"><?php echo t('update_post'); ?></button>
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