<?php
@include '../../BookStore_BackEnd/config/Database.php';
session_start();
$user_id = $_SESSION['user_id'];
if(!isset($user_id)){
   header('location:login.php');
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Замовлення</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<section class="heading">
    <h3>ВАШІ ЗАМОВЛЕННЯ</h3>
    <p> <a href="home.php">головна</a> / замовлення </p>
</section>
<section class="placed-orders">
    <h1 class="title">розміщені замовлення</h1>
    <div class="box-container">
    <?php
        $select_orders = mysqli_query($conn, "SELECT * FROM orders WHERE user_id = '$user_id'") or die('query failed');
        if(mysqli_num_rows($select_orders) > 0){
            while($fetch_orders = mysqli_fetch_assoc($select_orders)){
    ?>
    <div class="box">
        <p> Розміщено: <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
        <p> Ім'я: <span><?php echo $fetch_orders['name']; ?></span> </p>
        <p> Номер: <span><?php echo $fetch_orders['number']; ?></span> </p>
        <p> Email: <span><?php echo $fetch_orders['email']; ?></span> </p>
        <p> Адреса: <span><?php echo $fetch_orders['address']; ?></span> </p>
        <p> Спосіб оплати: <span><?php echo $fetch_orders['method']; ?></span> </p>
        <p> Ваші замовлення: <span><?php echo $fetch_orders['total_products']; ?></span> </p>
        <p> Загальна сума: <span>₴<?php echo $fetch_orders['total_price']; ?>/-</span> </p>
        <p> Статус оплати: <span style="color:<?php if($fetch_orders['payment_status'] == 'pending'){echo 'tomato'; }else{echo 'green';} ?>"><?php echo $fetch_orders['payment_status']; ?></span> </p>
    </div>
    <?php 
        }
    }else{
        echo '<p class="empty">Ви ще не розмістили жодного замовлення!</p>';
    }
    ?>
    </div>
</section>
<?php include 'includes/footer.php'; ?>
<script src="../assets/js/script.js"></script>
</body>
</html>