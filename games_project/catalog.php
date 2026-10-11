<?php
require 'db.php';

// Перевірка, чи користувач увійшов у систему
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Обробка форми
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Захист від порожнього відправлення (якщо статус не вибрано)
    if (!isset($_POST['status']) || empty($_POST['status'])) {
        header('Location: catalog.php');
        exit;
    }

    $game_id = (int)$_POST['game_id'];
    $status = $_POST['status'];
    
    // 2. Безпечна перевірка: чи передано 'tier', тільки якщо гра пройдена
    $tier = ($status === 'completed' && isset($_POST['tier'])) ? $_POST['tier'] : NULL;

    // 3. Оновлений SQL-запит: використовуємо VALUES(), щоб уникнути помилок PHP
    $stmt = $conn->prepare("
        INSERT INTO user_games (user_id, game_id, status, tier) 
        VALUES (?, ?, ?, ?) 
        ON DUPLICATE KEY UPDATE status = VALUES(status), tier = VALUES(tier)
    ");
    
    // Тепер передаємо лише 4 змінні, як і очікує база
    $stmt->bind_param("iiss", $user_id, $game_id, $status, $tier);
    $stmt->execute();
    
    header('Location: catalog.php');
    exit;
}

$result = $conn->query("SELECT * FROM games");
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Додати гру</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="app-container">
        <aside class="sidebar">
            <div class="logo">Game Diary</div>
            <nav class="nav-menu">
                <a href="index.php" class="nav-link">📂 Мої папки</a>
                <a href="catalog.php" class="nav-link" style="background:#ebf5fb; color:#2980b9;">➕ Додати гру</a>
            </nav>
            <div class="user-panel">
                <p>Привіт, <b><?= htmlspecialchars($_SESSION['username']) ?></b>!</p>
                <a href="logout.php" class="btn" style="width:100%; box-sizing:border-box;">Вийти</a>
            </div>
        </aside>

        <main class="main-content">
            <h1 class="page-title">Список ігор</h1>
            <div class="games-grid">
                <?php while ($game = $result->fetch_assoc()): ?>
                    <div class="game-card">
                        <h2 style="margin-top:0; color:#34495e;"><?= htmlspecialchars($game['title']) ?></h2>
                        <p style="flex-grow: 1; color:#7f8c8d;"><?= htmlspecialchars($game['description']) ?></p>
                        
                        <form method="POST" class="add-game-form">
                            <input type="hidden" name="game_id" value="<?= $game['id'] ?>">
                            
                            <select name="status" required onchange="toggleTier(this)">
                                <option value="" disabled selected>Вибрати папку...</option>
                                <option value="completed">✅ Пройшов</option>
                                <option value="plan">🎯 Хочу пограти</option>
                                <option value="waiting">⏳ Чекаю релізу</option>
                                <option value="dropped">🗑️ Закинув (не зайшло)</option>
                            </select>

                            <select name="tier" class="tier-select" style="display: none;">
                                <option value="top">💚 Дуже сподобалась</option>
                                <option value="mid">💛 Не особо (нормально)</option>
                                <option value="bad">💔 Взагалі не сподобалась</option>
                            </select>

                            <button type="submit" class="btn">Додати до списку</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            </div>
        </main>
    </div>

    <script>
        function toggleTier(selectElement) {
            const tierSelect = selectElement.nextElementSibling;
            if (selectElement.value === 'completed') {
                tierSelect.style.display = 'block';
                tierSelect.required = true;
            } else {
                tierSelect.style.display = 'none';
                tierSelect.required = false;
            }
        }
    </script>
</body>
</html>