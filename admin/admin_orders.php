<?php
@include '../../BookStore_BackEnd/config/Database.php';
session_start();
$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
   header('location:login.php');
};
if(isset($_POST['update_order'])){
   $order_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   mysqli_query($conn, "UPDATE orders SET payment_status = '$update_payment' WHERE id = '$order_id'") or die('Помилка запиту');
   $message[] = 'Статус оплати оновлено!';
}
if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM orders WHERE id = '$delete_id'") or die('Помилка запиту');
   header('location:admin_orders.php');
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
   <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
<?php @include 'admin_header.php'; ?>
<section class="placed-orders">
   <h1 class="title">ОФОРМЛЕНІ ЗАМОВЛЕННЯ</h1>
   <div class="box-container">
      <?php
      $select_orders = mysqli_query($conn, "SELECT * FROM orders") or die('Помилка запиту');
      if(mysqli_num_rows($select_orders) > 0){
         while($fetch_orders = mysqli_fetch_assoc($select_orders)){
      ?>
      <div class="box">
         <p> ID користувача: <span><?php echo $fetch_orders['user_id']; ?></span> </p>
         <p> Дата замовлення: <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
         <p> Ім'я: <span><?php echo $fetch_orders['name']; ?></span> </p>
         <p> Номер телефону: <span><?php echo $fetch_orders['number']; ?></span> </p>
         <p> Email: <span><?php echo $fetch_orders['email']; ?></span> </p>
         <p> Адреса: <span><?php echo $fetch_orders['address']; ?></span> </p>
         <p> Загальна кількість товарів: <span><?php echo $fetch_orders['total_products']; ?></span> </p>
         <p> Загальна сума: <span>₴<?php echo $fetch_orders['total_price']; ?>/-</span> </p>
         <p> Метод оплати: <span><?php echo $fetch_orders['method']; ?></span> </p>
         <form action="" method="post">
            <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
            <select name="update_payment">
               <option disabled selected><?php echo $fetch_orders['payment_status']; ?></option>
               <option value="pending">Очікується</option>
               <option value="completed">Завершено</option>
            </select>
            <input type="submit" name="update_order" value="Оновити" class="option-btn">
            <a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('Видалити це замовлення?');">Видалити</a>
         </form>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">Замовлень поки немає!</p>';
      }
      ?>
   </div>
</section>
<script src="../assets/js/admin_script.js"></script>
</body>
</html>