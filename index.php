<?php
session_start();
include 'auth.php';
include 'lang.php';
include 'config.php';

$posts_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $posts_per_page;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if (!empty($search)) {
    $search_param = "%" . $search . "%";

    $count_sql = "SELECT COUNT(*) as total FROM posts 
                  WHERE title LIKE ? OR content LIKE ? OR author LIKE ?";
    $count_stmt = $conn->prepare($count_sql);
    $count_stmt->bind_param("sss", $search_param, $search_param, $search_param);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    $total_posts = $count_result->fetch_assoc()['total'];
    $count_stmt->close();

    $sql = "SELECT posts.*, categories.name as category_key 
            FROM posts 
            LEFT JOIN categories ON posts.category_id = categories.id 
            WHERE posts.title LIKE ? OR posts.content LIKE ? OR posts.author LIKE ?
            ORDER BY posts.created_at DESC 
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $search_param, $search_param, $search_param, $posts_per_page, $offset);
} else {
    $count_sql = "SELECT COUNT(*) as total FROM posts";
    $count_result = $conn->query($count_sql);
    $total_posts = $count_result->fetch_assoc()['total'];

    $sql = "SELECT posts.*, categories.name as category_key 
            FROM posts 
            LEFT JOIN categories ON posts.category_id = categories.id 
            ORDER BY posts.created_at DESC 
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $posts_per_page, $offset);
}

$total_pages = ceil($total_posts / $posts_per_page);

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>" dir="<?php echo get_direction(); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('site_title'); ?></title>

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
            margin-bottom: 2rem;
        }

        /* Inputs */
        .form-control {
            background-color: var(--gruv-bg);
            border: 1px solid var(--gruv-gray);
            color: var(--gruv-fg);
        }
        .form-control:focus {
            background-color: var(--gruv-bg);
            color: var(--gruv-fg);
            border-color: var(--gruv-blue);
            box-shadow: none;
        }
        ::placeholder { color: var(--gruv-gray) !important; }

        /* Buttons - Mapping Bootstrap classes to Gruvbox */
        .btn-primary { background-color: var(--gruv-blue); border-color: var(--gruv-blue); color: var(--gruv-bg); }
        .btn-primary:hover { background-color: var(--gruv-aqua); border-color: var(--gruv-aqua); color: var(--gruv-bg); }
        
        .btn-warning { background-color: var(--gruv-yellow); border-color: var(--gruv-yellow); color: var(--gruv-bg); }
        
        .btn-danger { background-color: var(--gruv-red); border-color: var(--gruv-red); }
        
        .btn-outline-warning { color: var(--gruv-yellow); border-color: var(--gruv-yellow); }
        .btn-outline-warning:hover { background-color: var(--gruv-yellow); color: var(--gruv-bg); }

        .btn-secondary { background-color: var(--gruv-gray); border-color: var(--gruv-gray); color: var(--gruv-bg); }

        /* Badges */
        .badge { font-weight: normal; font-size: 0.8em; padding: 0.5em 0.8em; }
        .bg-primary { background-color: var(--gruv-blue) !important; color: var(--gruv-bg); }

        /* Pagination */
        .page-link { background-color: var(--gruv-bg-soft); border-color: var(--gruv-bg); color: var(--gruv-fg); }
        .page-link:hover { background-color: var(--gruv-bg); border-color: var(--gruv-bg); color: var(--gruv-yellow); }
        .page-item.active .page-link { background-color: var(--gruv-yellow); border-color: var(--gruv-yellow); color: var(--gruv-bg); }
        .page-item.disabled .page-link { background-color: var(--gruv-bg-soft); border-color: var(--gruv-bg); color: var(--gruv-gray); }

        /* Alerts */
        .alert-info { background-color: var(--gruv-bg-soft); border-color: var(--gruv-blue); color: var(--gruv-blue); }
        .alert-warning { background-color: var(--gruv-bg-soft); border-color: var(--gruv-yellow); color: var(--gruv-yellow); }
        .alert-success { background-color: var(--gruv-bg-soft); border-color: var(--gruv-green); color: var(--gruv-green); }

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
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>" href="index.php"><?php echo t('home'); ?></a>
                    </li>

                    <?php if (is_admin()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="create.php"><?php echo t('new_post'); ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if (is_logged_in()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <?php echo htmlspecialchars($_SESSION['user_username']); ?>
                                <?php if (is_admin()): ?>
                                    <span class="badge bg-danger ms-1">Admin</span>
                                <?php endif; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="logout.php">🚪 <?php echo t('logout'); ?></a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php"><?php echo t('login'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php"><?php echo t('register'); ?></a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link" href="?lang=<?php echo get_other_lang(); ?>&page=<?php echo $page; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">
                            <?php echo get_other_lang_name(); ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        
        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                 <h1 class="mb-0"><?php echo t('all_posts'); ?></h1>
            </div>
            <div class="col-md-6">
                <form method="GET" action="index.php" class="d-flex gap-2">
                    <input type="text" 
                        class="form-control" 
                        name="search" 
                        placeholder="<?php echo t('search_placeholder'); ?>" 
                        value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn btn-primary"><?php echo t('search'); ?></button>
                    <input type="hidden" name="lang" value="<?php echo $current_lang; ?>">
                </form>
            </div>
        </div>

        <?php if (!empty($search)): ?>
            <div class="alert alert-info d-flex justify-content-between align-items-center">
                <span>
                    <?php echo t('search_results'); ?>: <strong class="text-white"><?php echo htmlspecialchars($search); ?></strong>
                    (<?php echo $total_posts; ?> <?php echo $total_posts == 1 ? t('post_found') : t('posts_found'); ?>)
                </span>
                <a href="index.php?lang=<?php echo $current_lang; ?>" class="btn btn-sm btn-secondary"><?php echo t('clear_search'); ?></a>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <?php 
                $msg = '';
                if ($_GET['success'] == 'created') $msg = t('success_created');
                elseif ($_GET['success'] == 'updated') $msg = t('success_updated');
                elseif ($_GET['success'] == 'deleted') $msg = t('success_deleted');
            ?>
            <?php if($msg): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $msg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php
        if ($result->num_rows > 0) {
            while ($post = $result->fetch_assoc()) {
        ?>
                <div class="card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <a href="post.php?id=<?php echo $post['id']; ?>" class="text-decoration-none">
                                <h2 class="card-title h4 mb-0"><?php echo htmlspecialchars($post['title']); ?></h2>
                            </a>
                            <span class="badge bg-primary rounded-pill"><?php echo get_category_name($post['category_key']); ?></span>
                        </div>
                        
                        <p class="text-muted small mb-3">
                            <span><?php echo t('by'); ?> <?php echo htmlspecialchars($post['author']); ?></span>
                            <span class="mx-1">•</span>
                            <span><?php echo date('F j, Y', strtotime($post['created_at'])); ?></span>
                        </p>

                        <p class="card-text">
                            <?php 
                                // Clean minimal excerpt
                                $content = htmlspecialchars($post['content']);
                                echo nl2br(substr($content, 0, 250)) . (strlen($content) > 250 ? '...' : ''); 
                            ?>
                        </p>
                        
                        <div class="mt-4 pt-3 border-top border-secondary d-flex justify-content-between align-items-center" style="border-color: #504945 !important;">
                            <a href="post.php?id=<?php echo $post['id']; ?>" class="btn btn-outline-warning btn-sm">
                                <?php echo t('read_more'); ?>
                            </a>
                            
                            <div>
                                <?php if (is_admin()): ?>
                                    <a href="edit.php?id=<?php echo $post['id']; ?>" class="btn btn-link text-muted btn-sm p-0 me-2 text-decoration-none" style="font-size: 0.9em;">
                                        <?php echo t('edit'); ?>
                                    </a>
                                <?php endif; ?>

                                <?php if (is_admin()): ?>
                                    <a href="delete.php?id=<?php echo $post['id']; ?>" 
                                       class="btn btn-link text-danger btn-sm p-0 text-decoration-none" 
                                       style="font-size: 0.9em;"
                                       onclick="return confirm('<?php echo t('delete_confirm'); ?>')">
                                        <?php echo t('delete'); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }

            // Pagination
            if ($total_pages > 1) {
            ?>
                <nav aria-label="Page navigation" class="mt-5">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&lang=<?php echo $current_lang; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">&laquo; <?php echo t('previous'); ?></a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&lang=<?php echo $current_lang; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&lang=<?php echo $current_lang; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>"><?php echo t('next'); ?> &raquo;</a>
                        </li>
                    </ul>
                </nav>
        <?php
            }
        } else {
            echo '<div class="alert alert-warning text-center">' . (!empty($search) ? t('no_search_results') : t('no_posts')) . '</div>';
        }

        $stmt->close();
        $conn->close();
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
