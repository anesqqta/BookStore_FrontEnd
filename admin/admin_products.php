<?php
require_once '../../BookStore_BackEnd/controllers/AdminProductController.php';
session_start();
$admin_id = $_SESSION['admin_id'] ?? null;
if (!$admin_id) {
    header('location:../view/login.php');
    exit;
}

$controller = new AdminProductController();
$message = [];

// Видалення товару
if (isset($_GET['delete'])) {
    $controller->deleteProduct($_GET['delete']);
    header('location:admin_products.php');
    exit;
}

// Отримання товарів
$products = $controller->getProducts();

// Пошук та фільтр
$search = $_GET['search'] ?? "";
$genre_filter = $_GET['genre'] ?? "";

if ($search) {
    $products = array_filter($products, function($p) use ($search){
        return stripos($p['name'], $search) !== false;
    });
}

if ($genre_filter) {
    $products = array_filter($products, function($p) use ($genre_filter){
        return $p['genre'] === $genre_filter;
    });
}

// Унікальні жанри для фільтру
$genres = array_unique(array_column($controller->getProducts(), 'genre'));
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Товари</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
<?php include 'admin_header.php'; ?>

<?php if (!empty($message)): ?>
    <?php foreach ($message as $msg): ?>
        <div class="message">
            <span><?= htmlspecialchars($msg) ?></span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<section class="show-products">
    <h1 class="title">Товари</h1>

    <!-- Панель пошуку та кнопка додати товар -->
    <form method="GET" class="prod-toolbar">
        <input
            type="text"
            name="search"
            class="toolbar-input"
            placeholder="Пошук товару..."
            value="<?= htmlspecialchars($search) ?>"
        >

        <select name="genre" class="toolbar-select">
            <option value="">Жанр: всі</option>
            <?php foreach ($genres as $g): ?>
                <option value="<?= $g ?>" <?= $genre_filter==$g ? "selected":"" ?>>
                    <?= htmlspecialchars($g) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="btn">Застосувати</button>

        <!-- Перехід на сторінку додавання товару -->
        <a href="admin_add_book.php" class="btn blue">+ Додати товар</a>
    </form>

    <?php if (!empty($products)): ?>
    <table class="products-table">
        <tr>
            <th>ID</th>
            <th>Зображення</th>
            <th>Назва</th>
            <th>Ціна</th>
            <th>Дії</th>
        </tr>
        <?php foreach ($products as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p['id']) ?></td>
            <td>
                <?php if ($p['image']): ?>
                    <img src="../uploaded_img/<?= htmlspecialchars($p['image']) ?>" class="product-image" alt="">
                <?php else: ?>
                    <span class="no-img">—</span>
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($p['name']) ?></td>
            <td>₴<?= htmlspecialchars($p['price']) ?></td>
            <td>
                <a href="admin_edit_book.php?edit_id=<?= $p['id'] ?>" class="option-btn">Оновити</a>
                <a href="admin_products.php?delete=<?= $p['id'] ?>" class="delete-btn" onclick="return confirm('Видалити цей товар?');">Видалити</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <p class="empty">Поки що немає доданих товарів!</p>
    <?php endif; ?>
</section>

<script src="../assets/js/admin_script.js"></script>
</body>
</html>