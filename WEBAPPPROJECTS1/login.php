<?php
session_start();
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $stmt->execute();

    if ($user = $result->fetchArray(SQLITE3_ASSOC)) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            header('Location: dashboard.php');
            exit;
        }
    }

    // If login fails, redirect back to the login page with an error
    header('Location: index.php?error=1');
    exit;
}
?>