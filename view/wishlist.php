<?php
@include '../../BookStore_BackEnd/config/Database.php';
session_start();
$user_id = $_SESSION['user_id'];
if(!isset($user_id)){
   header('location:login.php');
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
if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM wishlist WHERE id = '$delete_id'") or die('Не вдалося виконати запит');
    header('location:wishlist.php');
}
if(isset($_GET['delete_all'])){
    mysqli_query($conn, "DELETE FROM wishlist WHERE user_id = '$user_id'") or die('Не вдалося виконати запит');
    header('location:wishlist.php');
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Список бажаного</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<section class="heading">
    <h3>ВАШ СПИСОК БАЖАНОГО</h3>
    <p> <a href="home.php">головна</a> / список бажаного </p>
</section>
<section class="wishlist">
    <h1 class="title">Додані продукти</h1>
    <div class="box-container">
    <?php
        $grand_total = 0;
        $select_wishlist = mysqli_query($conn, "SELECT * FROM wishlist WHERE user_id = '$user_id'") or die('Не вдалося виконати запит');
        if(mysqli_num_rows($select_wishlist) > 0){
            while($fetch_wishlist = mysqli_fetch_assoc($select_wishlist)){
    ?>
    <form action="" method="POST" class="box">
        <a href="wishlist.php?delete=<?php echo $fetch_wishlist['id']; ?>" class="fas fa-times" onclick="return confirm('Видалити це з списку бажаного?');"></a>
        <a href="view_page.php?book_id=<?php echo $fetch_wishlist['book_id']; ?>" class="fas fa-eye"></a>
        <img src="../uploaded_img/<?php echo $fetch_wishlist['image']; ?>" alt="" class="image">
        <div class="name"><?php echo $fetch_wishlist['name']; ?></div>
        <div class="price">₴<?php echo $fetch_wishlist['price']; ?>/-</div>
        <input type="hidden" name="product_id" value="<?php echo $fetch_wishlist['book_id']; ?>">
        <input type="hidden" name="product_name" value="<?php echo $fetch_wishlist['name']; ?>">
        <input type="hidden" name="product_price" value="<?php echo $fetch_wishlist['price']; ?>">
        <input type="hidden" name="product_image" value="<?php echo $fetch_wishlist['image']; ?>">
        <input type="submit" value="Додати до кошика" name="add_to_cart" class="option-btn"> 
    </form>
    <?php
    $grand_total += $fetch_wishlist['price'];
        }
    }else{
        echo '<p class="empty">Ваш список бажаного порожній</p>';
    }
    ?>
    </div>
<div class="wishlist-total">
<p>Загальна сума : <span>₴<?php echo $grand_total; ?>/-</span></p>
        <a href="shop.php" class="option-btn">Продовжити покупки</a>
        <a href="wishlist.php?delete_all" class="delete-btn <?php echo ($grand_total > 1)?'':'disabled' ?>" onclick="return confirm('Видалити все з списку бажаного?');">Видалити все</a>
    </div>
</section>
<?php include 'includes/footer.php'; ?>
<script src="../assets/js/script.js"></script>
</body>
</html>