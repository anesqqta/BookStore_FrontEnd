<?php
@include '../../BookStore_BackEnd/config/Database.php';
session_start();

$user_id = $_SESSION['user_id'];
if(!isset($user_id)){
   header('location:login.php');
   exit();
}

if(isset($_POST['add_to_wishlist'])){
   $message = [];
   $product_id = $_POST['product_id'];
   $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM wishlist WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
   if(mysqli_num_rows($check_wishlist_numbers) > 0){
       $message[] = 'Товар вже додано до списку бажаного';
   } else {
       mysqli_query($conn, "INSERT INTO wishlist(user_id, book_id, name, price, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_image')") or die('query failed');
       $message[] = 'Товар додано до списку бажаного';
   }
}
if(isset($_POST['add_to_cart'])){
   $message = [];
   $product_id = $_POST['product_id'];
   $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];
   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM cart WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
   if(mysqli_num_rows($check_cart_numbers) > 0){
       $message[] = 'Товар вже додано до кошика';
   }else{
       $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM wishlist WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
       if(mysqli_num_rows($check_wishlist_numbers) > 0){
           mysqli_query($conn, "DELETE FROM wishlist WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
       }
       mysqli_query($conn, "INSERT INTO cart(user_id, book_id, name, price, quantity, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
       $message[] = 'Товар додано до кошика';
   }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Детальна сторінка книги</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<section class="heading">
   <h3>ДЕТАЛЬНА СТОРІНКА КНИГИ</h3>
   <p><a href="home.php">головна</a> / книга</p>
</section>

<section class="book-view">
<?php
if(isset($_GET['book_id'])){
   $book_id = $_GET['book_id'];
   $select_book = mysqli_query($conn, "SELECT * FROM products WHERE id = '$book_id'") or die('Помилка запиту');
   if(mysqli_num_rows($select_book) > 0){
      $book = mysqli_fetch_assoc($select_book);
?>
   <div class="book-wrapper">
      <div class="book-image">
         <img src="../uploaded_img/<?php echo $book['image']; ?>" alt="">
      </div>

      <div class="book-info">
         <h2><?php echo $book['name']; ?></h2>
         <p><span class="label">Жанр:</span> <span class="value"><?php echo $book['genre']; ?></span></p>
         <p><span class="label">Автор:</span> <span class="value"><?php echo $book['author']; ?></span></p>
         <p><span class="label">Рік видання:</span> <span class="value"><?php echo $book['year_published']; ?></span></p>
         <p><span class="label">Мова:</span> <span class="value"><?php echo $book['language']; ?></span></p>
         <p><span class="label">Кількість сторінок:</span> <span class="value"><?php echo $book['number_pages']; ?></span></p>
      </div>

      <div class="book-price-box">
         <p class="price">₴<?php echo $book['price']; ?></p>
         <form action="" method="POST">
            <input type="hidden" name="product_id" value="<?php echo $book['id']; ?>">
            <input type="hidden" name="product_name" value="<?php echo $book['name']; ?>">
            <input type="hidden" name="product_price" value="<?php echo $book['price']; ?>">
            <input type="hidden" name="product_image" value="<?php echo $book['image']; ?>">
            <input type="number" name="product_quantity" value="1" min="1" class="qty">
            <input type="submit" value="Додати до списку бажаного" name="add_to_wishlist" class="option-btn">
            <input type="submit" value="Додати до кошика" name="add_to_cart" class="btn">
         </form>
      </div>
   </div>
   <div class="book-description">
      <p><?php echo $book['primary_description']; ?></p>
      <p><?php echo $book['secondary_description']; ?></p>
   </div>
<?php
   } else {
      echo '<p class="empty">Книга не знайдена</p>';
   }
}
?>
</section>

<?php include 'includes/footer.php'; ?>

<script src="../assets/js/script.js"></script>
</body>
</html>