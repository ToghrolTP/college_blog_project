<?php
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
    
    <link href="style.css" rel="stylesheet">
    
    <?php if (get_direction() == 'rtl'): ?>
    <style>
        body {
            font-family: 'Tahoma', 'Arial', sans-serif;
        }
        .badge {
            margin-left: 0;
            margin-right: 10px;
        }
    </style>
    <?php endif; ?>
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
                        <a class="nav-link active" href="index.php"><?php echo t('home'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="create.php"><?php echo t('new_post'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?lang=<?php echo get_other_lang(); ?>&page=<?php echo $page; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">
                            🌐 <?php echo get_other_lang_name(); ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4"><?php echo t('all_posts'); ?></h1>
        
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="index.php" class="row g-3">
                    <div class="col-md-10">
                        <input type="text" 
                               class="form-control" 
                               name="search" 
                               placeholder="<?php echo t('search_placeholder'); ?>"
                               value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100"><?php echo t('search'); ?></button>
                    </div>
                    <input type="hidden" name="lang" value="<?php echo $current_lang; ?>">
                </form>
            </div>
        </div>
        
        <?php if (!empty($search)): ?>
            <div class="alert alert-info">
                <?php echo t('search_results'); ?>: <strong><?php echo htmlspecialchars($search); ?></strong> 
                (<?php echo $total_posts; ?> <?php echo $total_posts == 1 ? t('post_found') : t('posts_found'); ?>)
                <a href="index.php?lang=<?php echo $current_lang; ?>" class="btn btn-sm btn-secondary"><?php echo t('clear_search'); ?></a>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success'])): ?>
            <?php if ($_GET['success'] == 'created'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo t('success_created'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php elseif ($_GET['success'] == 'updated'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo t('success_updated'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php elseif ($_GET['success'] == 'deleted'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo t('success_deleted'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <?php
        if ($result->num_rows > 0) {
            while($post = $result->fetch_assoc()) {
                ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h2 class="card-title mb-0"><?php echo htmlspecialchars($post['title']); ?></h2>
                            <span class="badge bg-primary"><?php echo get_category_name($post['category_key']); ?></span>
                        </div>
                        <p class="card-text mt-3"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                        <p class="text-muted">
                            <?php echo t('by'); ?> <?php echo htmlspecialchars($post['author']); ?> | 
                            <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                        </p>
                        <a href="edit.php?id=<?php echo $post['id']; ?>" class="btn btn-warning btn-sm"><?php echo t('edit'); ?></a>
                        <a href="delete.php?id=<?php echo $post['id']; ?>" class="btn btn-danger btn-sm" 
                           onclick="return confirm('<?php echo t('delete_confirm'); ?>')"><?php echo t('delete'); ?></a>
                    </div>
                </div>
                <?php
            }
            
            if ($total_pages > 1) {
                ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        
                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&lang=<?php echo $current_lang; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>"><?php echo t('previous'); ?></a>
                        </li>
                        
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&lang=<?php echo $current_lang; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&lang=<?php echo $current_lang; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>"><?php echo t('next'); ?></a>
                        </li>
                        
                    </ul>
                </nav>
                <?php
            }
            
        } else {
            if (!empty($search)) {
                echo '<div class="alert alert-warning">' . t('no_search_results') . '</div>';
            } else {
                echo '<div class="alert alert-info">' . t('no_posts') . '</div>';
            }
        }
        
        $stmt->close();
        $conn->close();
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
