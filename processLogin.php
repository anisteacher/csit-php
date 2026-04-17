<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $redirect = trim($_POST['redirect'] ?? '');
    $isSafeRedirect = preg_match('/^[a-zA-Z0-9_\/.-]+(\?[a-zA-Z0-9_=&-]+)?$/', $redirect) === 1;

    $stmt = $conn->prepare("SELECT id, name, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['name'] = $user['name'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];

            if ($redirect !== '' && $isSafeRedirect) {
                header('Location: ' . $redirect);
                exit();
            }

            header('Location: index.php');
            exit();
        }

        echo "Invalid password.";
        exit();
    }

    echo "User not found.";
}
?>
