<?php
require_once '../../BookStore_BackEnd/controllers/BookController.php';
session_start();

$bookController = new BookController();
$books = $bookController->getLatestBooks();

$user_id = $_SESSION['user_id'] ?? null;
if(!$user_id){
   header('location:login.php');
   exit;
}
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
         while ($fetch_products = $books->fetch_assoc()) {
      ?>
      <form action="" method="POST" class="box">
         <a href="view_book.php?book_id=<?php echo $fetch_products['id']; ?>" class="fas fa-eye"></a>
         <div class="price">₴<?php echo $fetch_products['price']; ?>/-</div>
         <img src="../uploaded_img/<?php echo $fetch_products['image']; ?>" alt="" class="image">
         <div class="name"><?php echo $fetch_products['name']; ?></div>
         <input type="number" name="product_quantity" value="1" min="0" class="qty">
         <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
         <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
         <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
         <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
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
      <a href="contact.php" class="btn">Зв'яжіться з нами</a>
   </div>
</section>

<?php include 'includes/footer.php'; ?>
</body>
</html>