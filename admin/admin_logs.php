<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('location:../login.php');
    exit;
}

require_once __DIR__ . '/../../BookStore_BackEnd/controllers/AdminLogsController.php';

$controller = new AdminLogsController();
$data = $controller->index();

$users = $data['users'];
$logs = $data['logs'];
$search = $data['search'];
$user = $data['user'];
$table = $data['table'];
$start_date = $data['start_date'];
$end_date = $data['end_date'];

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Логи системи | Admin</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
    <script src="../assets/js/admin_script.js" defer></script>
</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="heading">
    <h3>Логи системи</h3>
    <p><a href="admin_page.php">адмін панель</a> / логи</p>
</section>

<section class="logs-page">
    <div class="stats-filter">
        <form method="GET">
            <div class="filter-row">
                <div class="filter-box">
                    <label>Пошук дії:</label>
                    <input type="text" name="search" value="<?= h($search) ?>" placeholder="Наприклад: update">
                </div>

                <div class="filter-box">
                    <label>Користувач:</label>
                    <select name="user">
                        <option value="">Всі</option>
                        <?php foreach ($users as $u): ?>
                            <option value="<?= (int)$u['id'] ?>" <?= ($user == $u['id'] ? 'selected' : '') ?>>
                                <?= h($u['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-box">
                    <label>Таблиця:</label>
                    <input type="text" name="table" value="<?= h($table) ?>" placeholder="products, orders, users...">
                </div>

                <div class="filter-box">
                    <label>Від дати:</label>
                    <input type="date" name="start_date" value="<?= h($start_date) ?>">
                </div>

                <div class="filter-box">
                    <label>До дати:</label>
                    <input type="date" name="end_date" value="<?= h($end_date) ?>">
                </div>
            </div>

            <div class="filter-buttons">
                <button type="submit" class="option-btn">Застосувати</button>
                <a href="admin_logs.php" class="delete-btn">Скинути</a>
            </div>
        </form>
    </div>

    <table class="db-table">
        <tr>
            <th>Дата</th>
            <th>Користувач</th>
            <th>Дія</th>
            <th>Таблиця</th>
            <th>Опис</th>
        </tr>

        <?php if (!empty($logs)): ?>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= h($log['created_at']) ?></td>
                    <td><?= h($log['user_name'] ?: 'Система') ?></td>
                    <td><?= h($log['action']) ?></td>
                    <td><?= h($log['table_name']) ?></td>
                    <td><?= h($log['description']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="empty">Немає записів</td>
            </tr>
        <?php endif; ?>
    </table>
</section>

</body>
</html>