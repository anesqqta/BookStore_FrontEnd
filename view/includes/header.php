<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../BookStore_BackEnd/controllers/WishlistController.php';
require_once __DIR__ . '/../../../BookStore_BackEnd/controllers/CartController.php';

$user_id = $_SESSION['user_id'] ?? null;

$wishlistCount = 0;
$cartCount = 0;

if ($user_id) {
    $wishlistController = new WishlistController();
    $cartController = new CartController();

    $wishlistCount = $wishlistController->getWishlistCount($user_id);
    $cartCount = $cartController->getCartCount($user_id);
}

if(isset($message)){
   foreach($message as $msg){
      echo '
      <div class="message">
         <span>'.$msg.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">
    <div class="flex">
        <a href="home.php" class="logo">BookStore</a>
        <nav class="navbar">
            <ul>
                <li><a href="home.php">Головна</a></li>
                <li><a href="#">Сторінки +</a>
                    <ul>
                        <li><a href="about.php">Про нас</a></li>
                        <li><a href="contact.php">Контакти</a></li>
                    </ul>
                </li>
                <li><a href="shop.php">Магазин</a></li>
                <li><a href="orders.php">Замовлення</a></li>
                <li><a href="#">Обліковий запис +</a>
                    <ul>
                        <li><a href="profile.php">Профіль</a></li>
                        <li><a href="logout.php">Вийти</a></li>
                    </ul>
                </li>
            </ul>
        </nav>

        <div class="icons"> 
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="search_page.php" class="fas fa-search"></a>
            <a href="profile.php" id="user-btn" class="fas fa-user"></a>
            <a href="wishlist.php"><i class="fas fa-heart"></i><span>(<?php echo $wishlistCount; ?>)</span></a>
            <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?php echo $cartCount; ?>)</span></a>
        </div>
    </div>
</header>