<?php
session_start();
include 'config.php';
include 'lang.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    $errors = [];

    if (empty($username)) {
        $errors[] = t('username_required');
    } elseif (strlen($username) < 3 || strlen($username) > 50) {
        $errors[] = t('username_length');
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = t('valid_email_required');
    }

    if (empty($password)) {
        $errors[] = t('password_required');
    } elseif (strlen($password) < 6) {
        $errors[] = t('password_min_length');
    }

    if ($password !== $password_confirm) {
        $errors[] = t('passwords_do_not_match');
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors[] = t('username_taken');
        }
        $stmt->close();

        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors[] = t('email_taken');
        }
        $stmt->close();
    }

    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password_hash);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = t('register_success');
            header("Location: login.php");
            exit();
        } else {
            $errors[] = t('register_failed');
        }
        $stmt->close();
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old_input'] = [
            'username' => $username,
            'email' => $email
        ];
        header("Location: register.php");
        exit();
    }
}

$conn->close();
?>
