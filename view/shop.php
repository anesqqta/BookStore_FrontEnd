<?php
require_once '../../BookStore_BackEnd/controllers/BookController.php';
require_once '../../BookStore_BackEnd/controllers/CartController.php';
require_once '../../BookStore_BackEnd/controllers/WishlistController.php';
$bookController = new BookController();
$cartController = new CartController();
$wishlistController = new WishlistController();
session_start();
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
   header('location:login.php');
   exit;
}
if (isset($_POST['add_to_cart'])) {
   $message[] = $cartController->addToCart($user_id, $_POST);
}
if (isset($_POST['add_to_wishlist'])) {
   $message[] = $wishlistController->addToWishlist($user_id, $_POST);
}
$filters = [
    'genre' => $_GET['genre'] ?? '',
    'year' => $_GET['year'] ?? '',
    'price_min' => $_GET['price_min'] ?? '',
    'price_max' => $_GET['price_max'] ?? ''
];
$books = $bookController->getBooks($filters);
?>
<!DOCTYPE html>
<html lang="uk">
<head>
   <meta charset="UTF-8">
   <title>Магазин</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<section class="heading">
    <h3>НАШ МАГАЗИН</h3>
    <p><a href="home.php">головна</a> / магазин</p>
</section>
<section class="products">
   <div class="products-header">
      <h1 class="title">ТОВАРИ</h1>
      <div class="filter-wrapper">
         <button type="button" class="filter-toggle">Фільтри <i class="fas fa-chevron-down"></i></button>
         <form action="" method="GET" class="filter-dropdown">
            <div class="filter-group">
               <label for="genre">Жанр:</label>
               <select name="genre" id="genre">
                   <option value="">Всі</option>
                   <option value="Фентезі">Фентезі</option>
                   <option value="Роман">Роман</option>
                   <option value="Детектив">Детектив</option>
                   <option value="Біографія">Біографія</option>
                   <option value="Наукова фантастика">Наукова фантастика</option>
                   <option value="Жахи">Жахи</option>
               </select>
            </div>
            <div class="filter-group">
               <label for="year">Рік:</label>
               <input type="number" name="year" id="year" placeholder="2025">
            </div>
            <div class="filter-group">
               <label>Ціна:</label>
               <div class="price-range">
                   <input type="number" name="price_min" placeholder="Від">
                   <input type="number" name="price_max" placeholder="До">
               </div>
            </div>
            <div class="filter-buttons">
               <button type="submit" class="btn">Застосувати</button>
               <a href="shop.php" class="option-btn">Очистити</a>
            </div>
         </form>
      </div>
   </div>
   <div class="box-container">
      <?php
      if ($books && mysqli_num_rows($books) > 0) {
         while ($book = mysqli_fetch_assoc($books)) {
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
         <input type="submit" value="Додати в бажаний список" name="add_to_wishlist" class="option-btn">
         <input type="submit" value="Додати в кошик" name="add_to_cart" class="btn">
      </form>
      <?php
         }
      } else {
         echo '<p class="empty">Продукти ще не додано!</p>';
      }
      ?>
   </div>
</section>

<?php include 'includes/footer.php'; ?>
<script src="../assets/js/script.js"></script>
</body>
</html>