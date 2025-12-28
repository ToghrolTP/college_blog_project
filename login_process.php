<?php
session_start();
include 'config.php';
include 'lang.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username)) {
        $errors[] = t('username_required');
    }
    if (empty($password)) {
        $errors[] = t('password_required');
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id, username, password_hash, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_username'] = $user['username'];
                $_SESSION['user_role'] = $user['role'];

                session_regenerate_id(true);

                header("Location: index.php");
                exit();
            } else {
                $errors[] = t('invalid_credentials');
            }
        } else {
            $errors[] = t('invalid_credentials');
        }

        $stmt->close();
    }

    if (!empty($errors)) {
        $_SESSION['login_errors'] = $errors;
        $_SESSION['old_username'] = $username;
        header("Location: login.php");
        exit();
    }
}

$conn->close();
?>