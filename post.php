<?php
session_start();
include 'auth.php';
include 'lang.php';
include 'config.php';

// 1. Check if an ID exists in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// 2. Fetch the specific post
$sql = "SELECT posts.*, categories.name as category_key 
        FROM posts 
        LEFT JOIN categories ON posts.category_id = categories.id 
        WHERE posts.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// 3. Check if post exists
if ($result->num_rows == 0) {
    die("Post not found!");
}

$post = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>" dir="<?php echo get_direction(); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - <?php echo t('site_title'); ?></title>

    <?php if (get_direction() == 'rtl'): ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <?php else: ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php endif; ?>

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
            line-height: 1.6;
        }

        /* Typography & Links */
        h1, h2, h3, h4, h5, h6 { color: var(--gruv-yellow); font-weight: 600; }
        a { color: var(--gruv-blue); text-decoration: none; }
        a:hover { color: var(--gruv-aqua); }
        .text-muted { color: var(--gruv-gray) !important; }

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
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        /* Buttons */
        .btn-primary { background-color: var(--gruv-blue); border-color: var(--gruv-blue); color: var(--gruv-bg); }
        .btn-primary:hover { background-color: var(--gruv-aqua); border-color: var(--gruv-aqua); color: var(--gruv-bg); }
        
        .btn-warning { background-color: var(--gruv-yellow); border-color: var(--gruv-yellow); color: var(--gruv-bg); }
        .btn-warning:hover { filter: brightness(1.1); }

        .btn-secondary { background-color: var(--gruv-bg); border-color: var(--gruv-gray); color: var(--gruv-gray); }
        .btn-secondary:hover { background-color: var(--gruv-gray); border-color: var(--gruv-gray); color: var(--gruv-bg); }

        /* Badges & Elements */
        .badge { font-weight: normal; font-size: 0.9rem; padding: 0.5em 0.8em; }
        .bg-primary { background-color: var(--gruv-blue) !important; color: var(--gruv-bg); }
        hr { border-color: var(--gruv-gray); opacity: 0.3; }

        /* Post Content Specifics */
        .post-content {
            font-size: 1.1rem;
            color: #fbf1c7; /* slightly brighter for main text */
        }

        /* RTL Specifics */
        <?php if (get_direction() == 'rtl'): ?>
            .badge { margin-left: 0; margin-right: 10px; }
        <?php endif; ?>
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php"><?php echo t('site_title'); ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><?php echo t('home'); ?></a>
                    </li>
                    
                    <?php if (is_admin()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="create.php"><?php echo t('new_post'); ?></a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link" href="?id=<?php echo $id; ?>&lang=<?php echo get_other_lang(); ?>">
                            <?php echo get_other_lang_name(); ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <article class="card p-3">
                    <div class="card-body">

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h1 class="display-5 mb-0" style="color: var(--gruv-yellow);"><?php echo htmlspecialchars($post['title']); ?></h1>
                            </div>

                            <div class="d-flex align-items-center gap-3 mt-3">
                                <span class="badge rounded-pill bg-primary"><?php echo get_category_name($post['category_key']); ?></span>
                                <span class="text-muted small">|</span>
                                <span class="text-muted small">
                                    <?php echo t('by'); ?> <strong style="color: var(--gruv-fg);"><?php echo htmlspecialchars($post['author']); ?></strong>
                                </span>
                                <span class="text-muted small">
                                    <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                                </span>
                            </div>
                        </div>

                        <hr class="mb-4">

                        <div class="post-content mb-5">
                            <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                        </div>

                        <div class="mt-5 pt-4 border-top d-flex justify-content-between align-items-center" style="border-color: #504945 !important;">
                            <a href="index.php" class="btn btn-secondary px-4">
                                &larr; <?php echo t('back_to_home'); ?>
                            </a>

                            <?php if (is_admin()): ?>
                                <a href="edit.php?id=<?php echo $post['id']; ?>" class="btn btn-link text-muted text-decoration-none">
                                    <?php echo t('edit'); ?>
                                </a>
                            <?php endif; ?>
                        </div>

                    </div>
                </article>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
