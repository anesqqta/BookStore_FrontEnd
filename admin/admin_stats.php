<?php
require_once __DIR__ . '/../../BookStore_BackEnd/config/Database.php';

session_start();

$db = new Database();
$conn = $db->getConnection();

if (!$conn) {
    die("Помилка підключення до бази даних");
}

if (!isset($_SESSION['admin_id'])) {
    header('location:../login.php');
    exit;
}

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function parseOrderItems($totalProducts, $productsMap) {
    $result = [];

    foreach ($productsMap as $productName => $productData) {
        $escaped = preg_quote($productName, '/');

        $patterns = [
            '/'.$escaped.'\s*\((\d+)\)/ui',
            '/'.$escaped.'\s*x\s*(\d+)/ui',
            '/'.$escaped.'\s*-\s*(\d+)/ui',
            '/'.$escaped.'.*?qty[: ](\d+)/ui',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $totalProducts, $matches)) {
                $qty = (int)$matches[1];
                if ($qty > 0) {
                    $result[] = [
                        'name' => $productName,
                        'genre' => $productData['genre'],
                        'qty' => $qty,
                        'price' => (float)$productData['price']
                    ];
                }
                break;
            }
        }
    }

    return $result;
}

$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';
$genre_filter = $_GET['genre'] ?? '';
$search = trim($_GET['search'] ?? '');
$payment_status = $_GET['payment_status'] ?? '';

$products = [];
$productQuery = mysqli_query($conn, "SELECT id, name, genre, price FROM products ORDER BY name ASC");
while ($row = mysqli_fetch_assoc($productQuery)) {
    $products[$row['name']] = $row;
}

$genres = [];
$genreQuery = mysqli_query($conn, "SELECT DISTINCT genre FROM products WHERE genre <> '' ORDER BY genre ASC");
while ($g = mysqli_fetch_assoc($genreQuery)) {
    $genres[] = $g['genre'];
}

$orderSql = "SELECT * FROM orders WHERE 1";
if ($payment_status !== '') {
    $safe_status = mysqli_real_escape_string($conn, $payment_status);
    $orderSql .= " AND payment_status = '{$safe_status}'";
}
$orderSql .= " ORDER BY id DESC";

$orderRes = mysqli_query($conn, $orderSql);

$latestOrders = [];

$totalRevenue = 0;
$totalOrders = 0;
$totalUnitsSold = 0;

$dailyRevenue = [];
$dailyOrders = [];

$bookSales = [];
$genreSales = [];

while ($order = mysqli_fetch_assoc($orderRes)) {
    $orderDateRaw = $order['placed_on'] ?? '';
    $orderTimestamp = strtotime($orderDateRaw);

    if (!$orderTimestamp) {
        continue;
    }

    $orderDate = date('Y-m-d', $orderTimestamp);

    if ($start_date && $orderDate < $start_date) {
        continue;
    }

    if ($end_date && $orderDate > $end_date) {
        continue;
    }

    $matchedItems = parseOrderItems($order['total_products'], $products);

    $orderMatchesFilters = true;

    if ($genre_filter !== '' || $search !== '') {
        $orderMatchesFilters = false;

        foreach ($matchedItems as $item) {
            $okGenre = ($genre_filter === '' || mb_strtolower($item['genre']) === mb_strtolower($genre_filter));
            $okSearch = ($search === '' || mb_stripos($item['name'], $search) !== false);

            if ($okGenre && $okSearch) {
                $orderMatchesFilters = true;
                break;
            }
        }
    }

    if (!$orderMatchesFilters) {
        continue;
    }

    $latestOrders[] = $order;

    $totalOrders++;
    $orderRevenue = (float)$order['total_price'];
    $totalRevenue += $orderRevenue;

    if (!isset($dailyRevenue[$orderDate])) {
        $dailyRevenue[$orderDate] = 0;
    }

    if (!isset($dailyOrders[$orderDate])) {
        $dailyOrders[$orderDate] = 0;
    }

    $dailyRevenue[$orderDate] += $orderRevenue;
    $dailyOrders[$orderDate]++;

    foreach ($matchedItems as $item) {
        $okGenre = ($genre_filter === '' || mb_strtolower($item['genre']) === mb_strtolower($genre_filter));
        $okSearch = ($search === '' || mb_stripos($item['name'], $search) !== false);

        if (!$okGenre || !$okSearch) {
            continue;
        }

        $bookName = $item['name'];
        $genreName = $item['genre'];
        $qty = (int)$item['qty'];

        $totalUnitsSold += $qty;

        if (!isset($bookSales[$bookName])) {
            $bookSales[$bookName] = 0;
        }
        $bookSales[$bookName] += $qty;

        if (!isset($genreSales[$genreName])) {
            $genreSales[$genreName] = 0;
        }
        $genreSales[$genreName] += $qty;
    }
}

arsort($bookSales);
$topBooks = array_slice($bookSales, 0, 5, true);

$leastBooks = $bookSales;
asort($leastBooks);
$leastBooks = array_slice($leastBooks, 0, 5, true);

arsort($genreSales);
$topGenres = array_slice($genreSales, 0, 5, true);

$leastGenres = $genreSales;
asort($leastGenres);
$leastGenres = array_slice($leastGenres, 0, 5, true);

ksort($dailyRevenue);
ksort($dailyOrders);

$latestOrders = array_slice($latestOrders, 0, 15);
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Статистика | Admin</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../assets/js/admin_script.js" defer></script>
</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="heading">
    <h3>Статистика</h3>
    <p><a href="admin_page.php">адмін панель</a> / статистика</p>
</section>

<section class="stats-page">

    <div class="stats-filter">
        <form method="GET">
            <div class="filter-row">
                <div class="filter-box">
                    <label>Дата від:</label>
                    <input type="date" name="start_date" value="<?= h($start_date) ?>">
                </div>

                <div class="filter-box">
                    <label>Дата до:</label>
                    <input type="date" name="end_date" value="<?= h($end_date) ?>">
                </div>

                <div class="filter-box">
                    <label>Жанр:</label>
                    <select name="genre">
                        <option value="">Всі</option>
                        <?php foreach ($genres as $genre): ?>
                            <option value="<?= h($genre) ?>" <?= ($genre_filter === $genre ? 'selected' : '') ?>>
                                <?= h($genre) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-box">
                    <label>Пошук книги:</label>
                    <input type="text" name="search" placeholder="Назва..." value="<?= h($search) ?>">
                </div>

                <div class="filter-box">
                    <label>Статус оплати:</label>
                    <select name="payment_status">
                        <option value="">Всі</option>
                        <option value="pending" <?= ($payment_status === 'pending' ? 'selected' : '') ?>>pending</option>
                        <option value="completed" <?= ($payment_status === 'completed' ? 'selected' : '') ?>>completed</option>
                    </select>
                </div>
            </div>

            <div class="filter-buttons">
                <button type="submit" class="option-btn">Застосувати</button>
                <a href="admin_stats.php" class="delete-btn">Скинути</a>
            </div>
        </form>
    </div>

    <div class="stats-cards">
        <div class="stats-card">
            <h3>Загальний заробіток</h3>
            <p><?= number_format($totalRevenue, 2, '.', ' ') ?> грн</p>
        </div>

        <div class="stats-card">
            <h3>Кількість замовлень</h3>
            <p><?= (int)$totalOrders ?></p>
        </div>

        <div class="stats-card">
            <h3>Продано одиниць</h3>
            <p><?= (int)$totalUnitsSold ?></p>
        </div>

        <div class="stats-card">
            <h3>Середній чек</h3>
            <p><?= $totalOrders > 0 ? number_format($totalRevenue / $totalOrders, 2, '.', ' ') : '0.00' ?> грн</p>
        </div>
    </div>

    <div class="charts-grid">
        <div class="chart-box">
            <h3 class="chart-title">Заробіток по датах</h3>
            <canvas id="revenueChart"></canvas>
        </div>

        <div class="chart-box">
            <h3 class="chart-title">Кількість замовлень по датах</h3>
            <canvas id="ordersChart"></canvas>
        </div>

        <div class="chart-box">
            <h3 class="chart-title">Топ-5 книг по продажах</h3>
            <canvas id="topBooksChart"></canvas>
        </div>

        <div class="chart-box">
            <h3 class="chart-title">Найменше продавані книги</h3>
            <canvas id="leastBooksChart"></canvas>
        </div>

        <div class="chart-box">
            <h3 class="chart-title">Топ-5 жанрів по продажах</h3>
            <canvas id="topGenresChart"></canvas>
        </div>

        <div class="chart-box">
            <h3 class="chart-title">Найменше продавані жанри</h3>
            <canvas id="leastGenresChart"></canvas>
        </div>
    </div>

    <h2 class="subtitle">Останні замовлення</h2>

    <table class="db-table">
        <tr>
            <th>ID</th>
            <th>Дата</th>
            <th>Клієнт</th>
            <th>Email</th>
            <th>Товари</th>
            <th>Сума</th>
            <th>Статус</th>
        </tr>

        <?php if (!empty($latestOrders)): ?>
            <?php foreach ($latestOrders as $order): ?>
                <tr>
                    <td><?= (int)$order['id'] ?></td>
                    <td><?= h($order['placed_on']) ?></td>
                    <td><?= h($order['name']) ?></td>
                    <td><?= h($order['email']) ?></td>
                    <td><?= h($order['total_products']) ?></td>
                    <td><?= number_format((float)$order['total_price'], 2, '.', ' ') ?> грн</td>
                    <td><?= h($order['payment_status']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="empty">Немає даних</td>
            </tr>
        <?php endif; ?>
    </table>
</section>

<script>
const chartColors = {
    primary: '#4A3F35',
    secondary: '#E0CFB1',
    soft: '#F4EDE4',
    accent: '#6B6B6B',
    light: '#FAF7F2'
};

const revenueLabels = <?= json_encode(array_keys($dailyRevenue), JSON_UNESCAPED_UNICODE) ?>;
const revenueData = <?= json_encode(array_values($dailyRevenue), JSON_UNESCAPED_UNICODE) ?>;

const ordersLabels = <?= json_encode(array_keys($dailyOrders), JSON_UNESCAPED_UNICODE) ?>;
const ordersData = <?= json_encode(array_values($dailyOrders), JSON_UNESCAPED_UNICODE) ?>;

const topBooksLabels = <?= json_encode(array_keys($topBooks), JSON_UNESCAPED_UNICODE) ?>;
const topBooksData = <?= json_encode(array_values($topBooks), JSON_UNESCAPED_UNICODE) ?>;

const leastBooksLabels = <?= json_encode(array_keys($leastBooks), JSON_UNESCAPED_UNICODE) ?>;
const leastBooksData = <?= json_encode(array_values($leastBooks), JSON_UNESCAPED_UNICODE) ?>;

const topGenresLabels = <?= json_encode(array_keys($topGenres), JSON_UNESCAPED_UNICODE) ?>;
const topGenresData = <?= json_encode(array_values($topGenres), JSON_UNESCAPED_UNICODE) ?>;

const leastGenresLabels = <?= json_encode(array_keys($leastGenres), JSON_UNESCAPED_UNICODE) ?>;
const leastGenresData = <?= json_encode(array_values($leastGenres), JSON_UNESCAPED_UNICODE) ?>;

new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: revenueLabels,
        datasets: [{
            label: 'Заробіток',
            data: revenueData,
            borderColor: chartColors.primary,
            backgroundColor: 'rgba(224, 207, 177, 0.35)',
            borderWidth: 3,
            tension: 0.3,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

new Chart(document.getElementById('ordersChart'), {
    type: 'bar',
    data: {
        labels: ordersLabels,
        datasets: [{
            label: 'Замовлення',
            data: ordersData,
            backgroundColor: chartColors.secondary,
            borderColor: chartColors.primary,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

new Chart(document.getElementById('topBooksChart'), {
    type: 'bar',
    data: {
        labels: topBooksLabels,
        datasets: [{
            label: 'Продано',
            data: topBooksData,
            backgroundColor: chartColors.primary
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y'
    }
});

new Chart(document.getElementById('leastBooksChart'), {
    type: 'bar',
    data: {
        labels: leastBooksLabels,
        datasets: [{
            label: 'Продано',
            data: leastBooksData,
            backgroundColor: chartColors.accent
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y'
    }
});

new Chart(document.getElementById('topGenresChart'), {
    type: 'pie',
    data: {
        labels: topGenresLabels,
        datasets: [{
            data: topGenresData,
            backgroundColor: [
                '#4A3F35',
                '#E0CFB1',
                '#6B6B6B',
                '#F4EDE4',
                '#AAAAAA'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

new Chart(document.getElementById('leastGenresChart'), {
    type: 'doughnut',
    data: {
        labels: leastGenresLabels,
        datasets: [{
            data: leastGenresData,
            backgroundColor: [
                '#E0CFB1',
                '#4A3F35',
                '#6B6B6B',
                '#F4EDE4',
                '#AAAAAA'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>

</body>
</html>