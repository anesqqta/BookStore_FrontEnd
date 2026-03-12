<?php
require_once '../../BookStore_BackEnd/controllers/AdminProductController.php';
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('location:login.php');
    exit;
}
$controller = new AdminProductController();
$message = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $response = $controller->addProduct($_POST, $_FILES);
    $message[] = $response;
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Додати новий товар</title>
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
<section class="heading">
    <h3>Додати товар</h3>
    <p><a href="admin_products.php">Всі товари</a> / Додати</p>
</section>
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
      <a href="admin_products.php" class="option-btn">Повернутись</a>
   </form>
</section>

<script src="../assets/js/admin_script.js"></script>
</body>
</html>