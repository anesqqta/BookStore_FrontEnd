<?php
require_once '../../BookStore_BackEnd/controllers/SearchController.php';
require_once '../../BookStore_BackEnd/controllers/CartController.php';
require_once '../../BookStore_BackEnd/controllers/WishlistController.php';

session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('location:login.php');
    exit;
}

$searchController = new SearchController();
$cartController = new CartController();
$wishlistController = new WishlistController();
$message = [];

// Додавання до списку бажаного
if (isset($_POST['add_to_wishlist'])) {
    $response = $wishlistController->addToWishlist($user_id, $_POST);
    $message[] = $response;
}

// Додавання до кошика
if (isset($_POST['add_to_cart'])) {
    $response = $cartController->addToCart($user_id, $_POST);
    $message[] = $response;
}

// Пошук товарів
$results = [];
if (isset($_POST['search_btn'])) {
    $search_box = $_POST['search_box'];
    $results = $searchController->searchProducts($search_box);
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Сторінка пошуку</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<section class="heading">
    <h3>СТОРІНКА ПОШУКУ</h3>
    <p><a href="home.php">головна</a> / пошук</p>
</section>

<section class="search-form">
    <form action="" method="POST">
        <input type="text" class="box" placeholder="Пошук товарів..." name="search_box" required>
        <input type="submit" class="option-btn" value="Пошук" name="search_btn">
    </form>
</section>

<section class="products" style="padding-top: 0;">
   <div class="box-container">
   <?php
   if (isset($_POST['search_btn'])) {
       if ($results->num_rows > 0) {
           while ($product = $results->fetch_assoc()) {
   ?>
   <form action="" method="POST" class="box">
       <a href="view_book.php?book_id=<?= $product['id']; ?>" class="fas fa-eye"></a>
       <div class="price">₴<?= $product['price']; ?></div>
       <img src="../uploaded_img/<?= $product['image']; ?>" alt="<?= htmlspecialchars($product['name']); ?>" class="image">
       <div class="name"><?= htmlspecialchars($product['name']); ?></div>
       <input type="number" name="product_quantity" value="1" min="1" class="qty">
       <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
       <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['name']); ?>">
       <input type="hidden" name="product_price" value="<?= $product['price']; ?>">
       <input type="hidden" name="product_image" value="<?= $product['image']; ?>">
       <input type="submit" value="Додати до списку бажаного" name="add_to_wishlist" class="option-btn">
       <input type="submit" value="Додати до кошика" name="add_to_cart" class="btn">
   </form>
   <?php
           }
       } else {
           echo '<p class="empty">Нічого не знайдено!</p>';
       }
   }
   ?>
   </div>
</section>

<?php include 'includes/footer.php'; ?>
<script src="../assets/js/script.js"></script>
</body>
</html>