<?php
session_start();
include 'auth.php';
include 'lang.php';
include 'config.php';

$errors = $_SESSION['login_errors'] ?? [];
$old_username = $_SESSION['old_username'] ?? '';

unset($_SESSION['login_errors'], $_SESSION['old_username']);
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

    <style>
        :root {
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

        h1, h2, h3, h4, h5, h6 { color: var(--gruv-yellow); font-weight: 600; }
        a { color: var(--gruv-blue); text-decoration: none; }
        a:hover { color: var(--gruv-aqua); }
        .text-muted { color: var(--gruv-gray) !important; }

        .navbar {
            background-color: var(--gruv-bg-soft) !important;
            border-bottom: 1px solid var(--gruv-bg);
        }
        .navbar-brand { color: var(--gruv-fg) !important; font-weight: bold; }
        .nav-link { color: var(--gruv-gray) !important; }
        .nav-link.active, .nav-link:hover { color: var(--gruv-fg) !important; }

        .card {
            background-color: var(--gruv-bg-soft);
            color: var(--gruv-fg);
            border: none;
            border-radius: 8px;
        }

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

        .btn-primary {
            background-color: var(--gruv-blue);
            border-color: var(--gruv-blue);
            color: var(--gruv-bg);
        }
        .btn-primary:hover {
            background-color: var(--gruv-aqua);
            border-color: var(--gruv-aqua);
            color: var(--gruv-bg);
        }

        .alert-danger {
            background-color: var(--gruv-bg-soft);
            border-color: var(--gruv-red);
            color: var(--gruv-red);
        }

        <?php if (get_direction() == 'rtl'): ?>
            body { font-family: 'Tahoma', 'Arial', sans-serif; }
            .badge { margin-left: 0; margin-right: 10px; }
        <?php endif; ?>
    </style>
</head>
<body>
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
                        <a class="nav-link active" href="login.php">🔐 <?php echo t('login'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">✨ <?php echo t('register'); ?></a>
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

                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="login_process.php">
                            <div class="mb-3">
                                <label for="username" class="form-label"><?php echo t('username'); ?></label>
                                <input type="text"
                                       class="form-control <?php echo !empty($errors) ? 'is-invalid' : ''; ?>"
                                       id="username"
                                       name="username"
                                       value="<?php echo htmlspecialchars($old_username); ?>"
                                       required autofocus>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label"><?php echo t('password'); ?></label>
                                <input type="password"
                                       class="form-control <?php echo !empty($errors) ? 'is-invalid' : ''; ?>"
                                       id="password"
                                       name="password"
                                       required>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg"><?php echo t('login'); ?></button>
                            </div>
                        </form>
                        
                        <div class="text-center mt-4">
                            <small class="text-muted">
                                <?php echo t('no_account_yet'); ?> 
                                <a href="register.php"><?php echo t('register'); ?></a>
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