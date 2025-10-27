<?php
require_once '../../BookStore_BackEnd/controllers/AdminOrderController.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:login.php');
    exit;
}

$controller = new AdminOrderController();
$message = [];

//  Оновлення статусу
if (isset($_POST['update_order'])) {
    $controller->updateOrderStatus($_POST['order_id'], $_POST['update_payment']);
    $message[] = 'Статус оплати оновлено!';
}

//  Видалення замовлення
if (isset($_GET['delete'])) {
    $controller->deleteOrder($_GET['delete']);
    header('location:admin_orders.php');
    exit;
}

//  Отримання списку замовлень
$orders = $controller->getOrders();
?>
<!DOCTYPE html>
<html lang="uk">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Замовлення</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="placed-orders">
   <h1 class="title">ОФОРМЛЕНІ ЗАМОВЛЕННЯ</h1>



   <div class="box-container">
      <?php if (mysqli_num_rows($orders) > 0): ?>
         <?php while ($fetch_orders = mysqli_fetch_assoc($orders)): ?>
            <div class="box">
               <p>ID користувача: <span><?= $fetch_orders['user_id']; ?></span></p>
               <p>Дата: <span><?= $fetch_orders['placed_on']; ?></span></p>
               <p>Ім’я: <span><?= $fetch_orders['name']; ?></span></p>
               <p>Номер телефону: <span><?= $fetch_orders['number']; ?></span></p>
               <p>Email: <span><?= $fetch_orders['email']; ?></span></p>
               <p>Адреса: <span><?= $fetch_orders['address']; ?></span></p>
               <p>Товари: <span><?= $fetch_orders['total_products']; ?></span></p>
               <p>Сума: <span>₴<?= $fetch_orders['total_price']; ?>/-</span></p>
               <p>Метод: <span><?= $fetch_orders['method']; ?></span></p>

               <form action="" method="POST">
                  <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                  <select name="update_payment">
                     <option disabled selected><?= $fetch_orders['payment_status']; ?></option>
                     <option value="pending">Очікується</option>
                     <option value="completed">Завершено</option>
                  </select>
                  <input type="submit" name="update_order" value="Оновити" class="option-btn">
                  <a href="?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('Видалити це замовлення?');">Видалити</a>
               </form>
            </div>
         <?php endwhile; ?>
      <?php else: ?>
         <p class="empty">Замовлень поки немає!</p>
      <?php endif; ?>
   </div>
</section>

<script src="../assets/js/admin_script.js"></script>
</body>
</html>