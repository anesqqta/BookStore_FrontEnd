<?php
require_once '../../BookStore_BackEnd/controllers/AdminProductController.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('location:login.php');
    exit;
}
$controller = new AdminProductController();
$message = [];

if (isset($_POST['update_product'])) {
    $response = $controller->updateProduct($_POST, $_FILES);
    $message[] = $response;
}
$update_id = $_GET['edit_id'] ?? null;
$product = $update_id ? $controller->getProductById($update_id) : null;
?>
<!DOCTYPE html>
<html lang="uk">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Оновлення товару</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
<?php include 'admin_header.php'; ?>

<section class="update-product">
<?php if ($product): ?>
   <form action="" method="post" enctype="multipart/form-data">
      <img src="../uploaded_img/<?php echo htmlspecialchars($product['image']); ?>" class="image" alt="">
      <input type="hidden" name="update_p_id" value="<?php echo $product['id']; ?>">
      <input type="hidden" name="update_p_image" value="<?php echo $product['image']; ?>">
      <input type="text" class="box" name="name" required value="<?php echo htmlspecialchars($product['name']); ?>">
      <input type="number" class="box" name="price" min="0" required value="<?php echo $product['price']; ?>">
      <input type="text" class="box" name="genre" required value="<?php echo htmlspecialchars($product['genre']); ?>">
      <input type="text" class="box" name="author" required value="<?php echo htmlspecialchars($product['author']); ?>">
      <input type="number" class="box" name="year_published" required value="<?php echo $product['year_published']; ?>">
      <input type="text" class="box" name="language" required value="<?php echo htmlspecialchars($product['language']); ?>">
      <input type="number" class="box" name="number_pages" required value="<?php echo $product['number_pages']; ?>">
      <textarea name="primary_description" class="box" required><?php echo htmlspecialchars($product['primary_description']); ?></textarea>
      <textarea name="secondary_description" class="box" required><?php echo htmlspecialchars($product['secondary_description']); ?></textarea>
      <input type="file" accept="image/*" class="box" name="image">
      <input type="submit" name="update_product" value="Оновити товар" class="btn">
      <a href="admin_products.php" class="option-btn">Повернутись</a>
   </form>
<?php else: ?>
   <p class="empty">Не вибрано товар для оновлення</p>
<?php endif; ?>
</section>

<script src="../assets/js/admin_script.js"></script>
</body>
</html>