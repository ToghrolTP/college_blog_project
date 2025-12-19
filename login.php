<?php
include 'lang.php';
include 'config.php';

// If you later add session-based authentication, you can check here
// and redirect logged-in users away from this page.
// For now, it's just the login UI.

?>

<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>" dir="<?php echo get_direction(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('login'); ?> - <?php echo t('site_title'); ?></title>
    
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
                        <a class="nav-link" href="index.php"><?php echo t('home'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="create.php"><?php echo t('new_post'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">🔐 <?php echo t('login'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="register.php">✨ <?php echo t('register'); ?></a>
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
            <div class="col-md-6 col-lg-5">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <h2 class="text-center mb-4">🔐 <?php echo t('login'); ?></h2>
                        
                        <!-- Login Form -->
                        <form method="POST" action="login_process.php">
                            <div class="mb-3">
                                <label for="username" class="form-label"><?php echo t('username'); ?></label>
                                <input type="text" class="form-control" id="username" name="username" required autofocus>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label"><?php echo t('password'); ?></label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg"><?php echo t('login'); ?></button>
                            </div>
                        </form>
                        
                        <!-- Optional links (you can add registration later) -->
                        <div class="text-center mt-4">
                            <small class="text-muted">
                                <?php echo t('no_account_yet'); ?> 
                                <a href="#"><?php echo t('register'); ?></a>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>