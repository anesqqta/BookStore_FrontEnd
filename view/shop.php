<?php
@include '../../BookStore_BackEnd/config/Database.php';
session_start();
$user_id = $_SESSION['user_id'];
if(!isset($user_id)){
   header('location:login.php');
};
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
   <title>Магазин</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<section class="heading">
    <h3>НАШ МАГАЗИН</h3>
    <p> <a href="home.php">головна</a> / магазин </p>
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
                <input type="number" name="year" id="year" placeholder="Наприклад: 2025" value="<?= isset($_GET['year']) ? htmlspecialchars($_GET['year']) : '' ?>">
            </div>
            <div class="filter-group">
                <label>Ціна:</label>
                <div class="price-range">
                    <input type="number" name="price_min" placeholder="Від" value="<?= isset($_GET['price_min']) ? htmlspecialchars($_GET['price_min']) : '' ?>">
                    <input type="number" name="price_max" placeholder="До" value="<?= isset($_GET['price_max']) ? htmlspecialchars($_GET['price_max']) : '' ?>">
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
         $where = [];
if (!empty($_GET['genre'])) {
    $genre = mysqli_real_escape_string($conn, $_GET['genre']);
    $where[] = "genre = '$genre'";
}
if (!empty($_GET['year'])) {
    $year = (int)$_GET['year'];
    $where[] = "year_published = $year";
}
if (!empty($_GET['price_min'])) {
    $min = (float)$_GET['price_min'];
    $where[] = "price >= $min";
}
if (!empty($_GET['price_max'])) {
    $max = (float)$_GET['price_max'];
    $where[] = "price <= $max";
}
$query = "SELECT * FROM products";
if (!empty($where)) {
    $query .= " WHERE " . implode(" AND ", $where);
}
$select_products = mysqli_query($conn, $query) or die('Запит не вдався');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
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
         <input type="submit" value="Додати в бажаний список" name="add_to_wishlist" class="option-btn">
         <input type="submit" value="Додати в кошик" name="add_to_cart" class="btn">
      </form>
      <?php
         }
      }else{
         echo '<p class="empty">продукти ще не додано!</p>';
      }
      ?>
   </div>
</section>
<?php include 'includes/footer.php'; ?>
<script src="../assets/js/script.js"></script>
</body>
</html>