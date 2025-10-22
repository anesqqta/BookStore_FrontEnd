<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
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
                        <li><a href="login.php">Увійти</a></li>
                        <li><a href="register.php">Зареєстр.</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div class="icons"> 
        <div class="icons"> 
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="search_page.php" class="fas fa-search"></a>
            <a href="profile.php" id="user-btn" class="fas fa-user"></a>
        <?php
            $select_wishlist_count = mysqli_query($conn, "SELECT * FROM wishlist WHERE user_id = '$user_id'") or die('query failed');
            $wishlist_num_rows = mysqli_num_rows($select_wishlist_count);
        ?>
        <a href="wishlist.php"><i class="fas fa-heart"></i><span>(<?php echo $wishlist_num_rows; ?>)</span></a>
        <?php
            $select_cart_count = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'") or die('query failed');
            $cart_num_rows = mysqli_num_rows($select_cart_count);
        ?>
        <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?php echo $cart_num_rows; ?>)</span></a>
        </div>
    </div>
</header>