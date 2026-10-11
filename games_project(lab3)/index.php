<?php
require 'db.php';

if (!isset($_SESSION['user_id'])) {
?>
    <!DOCTYPE html>
    <html lang="uk">
    <head>
        <meta charset="UTF-8">
        <title>Вітаємо у Game Diary</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="landing-container">
            <h1 class="landing-title">Твій ідеальний <span>Game Diary</span></h1>
            <p class="landing-subtitle">Створи свій особистий щоденник відеоігор. Зберігай пройдені шедеври, плануй майбутні пригоди та складай власні тір-листи у зручному інтерфейсі.</p>
            <div class="landing-buttons">
                <a href="login.php" class="btn btn-full">Увійти</a>
                <a href="register.php" class="btn btn-outline btn-full">Створити акаунт</a>
            </div>
        </div>
    </body>
    </html>
<?php
    exit; 
}

$user_id = $_SESSION['user_id'];

$query = "
    SELECT games.title, user_games.status, user_games.tier 
    FROM user_games 
    JOIN games ON user_games.game_id = games.id 
    WHERE user_games.user_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$games = [
    'plan' => [], 'waiting' => [], 'dropped' => [], 
    'completed' => ['top' => [], 'mid' => [], 'bad' => []]
];

while ($row = $result->fetch_assoc()) {
    if ($row['status'] == 'completed') {
        $games['completed'][$row['tier']][] = $row['title'];
    } else {
        $games[$row['status']][] = $row['title'];
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Мій Щоденник Ігор</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="app-container">
        <aside class="sidebar">
            <div class="logo">Game Diary</div>
            <nav class="nav-menu">
                <a href="index.php" class="nav-link" style="background:#ebf5fb; color:#2980b9;">📂 Мої папки</a>
                <a href="catalog.php" class="nav-link">➕ Додати гру</a>
            </nav>
            <div class="user-panel">
                <p>Привіт, <b><?= htmlspecialchars($_SESSION['username']) ?></b>!</p>
                <a href="logout.php" class="btn" style="width:100%; box-sizing:border-box;">Вийти</a>
            </div>
        </aside>

        <main class="main-content">
            <h1 class="page-title">Мої списки</h1>
            <div class="folder-section">
                <h2 class="folder-title">✅ Пройдені ігри</h2>
                <div class="tier-group tier-top">
                    <div class="tier-title">💚 Дуже сподобались:</div>
                    <?php foreach ($games['completed']['top'] as $title): ?>
                        <div class="game-item"><?= htmlspecialchars($title) ?></div>
                    <?php endforeach; ?>
                </div>
                <div class="tier-group tier-mid">
                    <div class="tier-title">💛 Не особо (нормально):</div>
                    <?php foreach ($games['completed']['mid'] as $title): ?>
                        <div class="game-item"><?= htmlspecialchars($title) ?></div>
                    <?php endforeach; ?>
                </div>
                <div class="tier-group tier-bad">
                    <div class="tier-title">💔 Взагалі не сподобались:</div>
                    <?php foreach ($games['completed']['bad'] as $title): ?>
                        <div class="game-item"><?= htmlspecialchars($title) ?></div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="folder-section">
                <h2 class="folder-title">🎯 Хочу пограти</h2>
                <?php if (empty($games['plan'])) echo '<p style="color: #95a5a6;">Папка порожня.</p>'; ?>
                <?php foreach ($games['plan'] as $title): ?>
                    <div class="game-item"><?= htmlspecialchars($title) ?></div>
                <?php endforeach; ?>
            </div>

            <div class="folder-section">
                <h2 class="folder-title">⏳ Чекаю (ще не вийшли)</h2>
                <?php if (empty($games['waiting'])) echo '<p style="color: #95a5a6;">Папка порожня.</p>'; ?>
                <?php foreach ($games['waiting'] as $title): ?>
                    <div class="game-item"><?= htmlspecialchars($title) ?></div>
                <?php endforeach; ?>
            </div>

            <div class="folder-section">
                <h2 class="folder-title">🗑️ Закинув (пробував, але не зайшло)</h2>
                <?php if (empty($games['dropped'])) echo '<p style="color: #95a5a6;">Папка порожня.</p>'; ?>
                <?php foreach ($games['dropped'] as $title): ?>
                    <div class="game-item" style="color: #7f8c8d; border-color: #f5b7b1; background-color: #fdf5f5; text-decoration: line-through;">
                        <?= htmlspecialchars($title) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</body>
</html>