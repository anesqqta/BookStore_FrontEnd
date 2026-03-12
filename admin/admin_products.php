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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $response = $controller->addProduct($_POST, $_FILES);
    $message[] = $response;
}
if (isset($_GET['delete'])) {
    $controller->deleteProduct($_GET['delete']);
    header('location:admin_products.php');
    exit;
}
$products = $controller->getProducts();
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
<section class="add-products">
   <form action="" method="POST" enctype="multipart/form-data">
      <h3>ДОДАТИ НОВИЙ ТОВАР</h3>
      <input type="text" class="box" name="name" placeholder="Назва товару" required>
      <input type="number" class="box" name="price" min="0" placeholder="Ціна" required>
      <input type="text" class="box" name="genre" placeholder="Жанр" required>
      <input type="text" class="box" name="author" placeholder="Автор" required>
      <input type="number" class="box" name="year_published" min="0" placeholder="Рік видання" required>
      <input type="text" class="box" name="language" placeholder="Мова" required>
      <input type="number" class="box" name="number_pages" min="1" placeholder="Кількість сторінок" required>
      <textarea name="primary_description" class="box" placeholder="Опис 1" required></textarea>
      <textarea name="secondary_description" class="box" placeholder="Опис 2" required></textarea>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png" required>
      <input type="submit" name="add_product" value="Додати товар" class="btn">
   </form>
</section>
<section class="show-products">
    <h1 class="title">Товари</h1>

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
            <td><img src="../uploaded_img/<?= htmlspecialchars($p['image']) ?>" class="product-image" alt=""></td>
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