<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    
    $filePath = __DIR__ . '/data/users.json';
    if (!file_exists($filePath)) {
        header("Location: connexion.html?error=1");
        exit();
    }
    
    $json = file_get_contents($filePath);
    $users = json_decode($json, true);
    
    $found = false;
    foreach ($users as $user) {
        if ($user['email'] === $email && password_verify($password, $user['password'])) {
            $found = true;
            $_SESSION['user_email'] = $email;
            break;
        }
    }
    
    if ($found) {
        header("Location: dashboard.php");
        exit();
    } else {
        header("Location: connexion.html?error=1");
        exit();
    }
}
?>
