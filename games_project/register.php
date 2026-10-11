<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);
    
    if ($stmt->execute()) {
        header('Location: login.php');
        exit;
    } else {
        $error = "Цей логін вже зайнятий!";
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Реєстрація - Game Diary</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <h2>Новий акаунт</h2>
            <?php if (isset($error)) echo "<p style='color: #e74c3c; font-weight: bold;'>$error</p>"; ?>
            <form method="POST">
                <input type="text" name="username" class="auth-input" placeholder="Придумайте логін" required>
                <input type="password" name="password" class="auth-input" placeholder="Придумайте пароль" required>
                <button type="submit" class="btn btn-full">Зареєструватися</button>
            </form>
            <div class="auth-links">
                Вже є акаунт? <a href="login.php">Увійдіть</a><br><br>
                <a href="index.php">← На головну сторінку</a>
            </div>
        </div>
    </div>
</body>
</html>