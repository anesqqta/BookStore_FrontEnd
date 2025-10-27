<?php
require_once '../../BookStore_BackEnd/controllers/BookController.php';
require_once '../../BookStore_BackEnd/controllers/CartController.php';
require_once '../../BookStore_BackEnd/controllers/WishlistController.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('location:login.php');
    exit;
}
$bookController = new BookController();
$cartController = new CartController();
$wishlistController = new WishlistController();

if (isset($_POST['add_to_cart'])) {
    $message[] = $cartController->addToCart($user_id, $_POST);
}
if (isset($_POST['add_to_wishlist'])) {
    $message[] = $wishlistController->addToWishlist($user_id, $_POST);
}
$books = $bookController->getLatestBooks(6);
?>
<!DOCTYPE html>
<html lang="uk">
<head>
   <meta charset="UTF-8">
   <title>Головна</title>
   <link rel="stylesheet" href="../assets/css/style.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<section class="home">
   <div class="content">
      <h3>Book Store</h3>
      <p>Відкрий сторінку свого натхнення. Завітай до нас, щоб відчути справжню магію книг!</p>
      <a href="about.php" class="btn">Дізнатися більше</a>
   </div>
</section>
<section class="products">
   <h1 class="title">НОВИНКИ</h1>
   <div class="box-container">
      <?php
      if ($books && $books->num_rows > 0) {
         while ($book = $books->fetch_assoc()) {
      ?>
      <form action="" method="POST" class="box">
         <a href="view_book.php?book_id=<?= $book['id']; ?>" class="fas fa-eye"></a>
         <div class="price">₴<?= $book['price']; ?></div>
         <img src="../uploaded_img/<?= $book['image']; ?>" alt="" class="image">
         <div class="name"><?= $book['name']; ?></div>
         <input type="number" name="product_quantity" value="1" min="1" class="qty">
         <input type="hidden" name="product_id" value="<?= $book['id']; ?>">
         <input type="hidden" name="product_name" value="<?= $book['name']; ?>">
         <input type="hidden" name="product_price" value="<?= $book['price']; ?>">
         <input type="hidden" name="product_image" value="<?= $book['image']; ?>">
         <input type="submit" value="Додати до списку бажаного" name="add_to_wishlist" class="option-btn">
         <input type="submit" value="Додати до кошика" name="add_to_cart" class="btn">
      </form>
      <?php
         }
      } else {
         echo '<p class="empty">Поки що немає товарів!</p>';
      }
      ?>
   </div>
   <div class="more-btn">
      <a href="shop.php" class="option-btn">Завантажити ще</a>
   </div>
</section>
<section class="home-contact">
   <div class="content">
      <h3>Є питання?</h3>
      <p>Перейдіть за посиланням нижче</p>
      <a href="contact.php" class="btn">Звʼяжіться з нами</a>
   </div>
</section>

<?php include 'includes/footer.php'; ?>
</body>
</html>