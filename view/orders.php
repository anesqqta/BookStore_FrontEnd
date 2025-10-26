<?php
require_once '../../BookStore_BackEnd/controllers/OrderController.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('location:login.php');
    exit;
}

// ініціалізуємо контролер замовлень
$orderController = new OrderController();
$orders = $orderController->getUserOrders($user_id);
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Замовлення</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<section class="heading">
    <h3>ВАШІ ЗАМОВЛЕННЯ</h3>
    <p><a href="home.php">головна</a> / замовлення</p>
</section>

<section class="placed-orders">
    <h1 class="title">Розміщені замовлення</h1>
    <div class="box-container">
        <?php
        if ($orders && $orders->num_rows > 0) {
            while ($order = $orders->fetch_assoc()) {
        ?>
        <div class="box">
            <p>Розміщено: <span><?php echo $order['placed_on']; ?></span></p>
            <p>Ім’я: <span><?php echo $order['name']; ?></span></p>
            <p>Номер: <span><?php echo $order['number']; ?></span></p>
            <p>Email: <span><?php echo $order['email']; ?></span></p>
            <p>Адреса: <span><?php echo $order['address']; ?></span></p>
            <p>Спосіб оплати: <span><?php echo $order['method']; ?></span></p>
            <p>Ваші замовлення: <span><?php echo $order['total_products']; ?></span></p>
            <p>Загальна сума: <span>₴<?php echo $order['total_price']; ?>/-</span></p>
            <p>Статус оплати: 
                <span style="color:<?php echo ($order['payment_status'] == 'pending') ? 'tomato' : 'green'; ?>">
                    <?php echo $order['payment_status']; ?>
                </span>
            </p>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">Ви ще не розмістили жодного замовлення!</p>';
        }
        ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
<script src="../assets/js/script.js"></script>
</body>
</html>