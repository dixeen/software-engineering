<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $username;
            header('Location: index.php');
            exit;
        }
    }
    $error = "Невірний логін або пароль!";
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Вхід - Game Diary</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <h2>З поверненням!</h2>
            <?php if (isset($error)) echo "<p style='color: #e74c3c; font-weight: bold;'>$error</p>"; ?>
            <form method="POST">
                <input type="text" name="username" class="auth-input" placeholder="Ваш логін" required>
                <input type="password" name="password" class="auth-input" placeholder="Ваш пароль" required>
                <button type="submit" class="btn btn-full">Увійти в щоденник</button>
            </form>
            <div class="auth-links">
                Не маєте акаунта? <a href="register.php">Створіть його тут</a><br><br>
                <a href="index.php">← На головну сторінку</a>
            </div>
        </div>
    </div>
</body>
</html>