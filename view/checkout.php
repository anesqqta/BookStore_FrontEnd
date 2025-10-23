<?php
@include '../../BookStore_BackEnd/config/Database.php';
session_start();
$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
   header('location:login.php');
}
if (isset($_POST['order'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $address = mysqli_real_escape_string($conn, 'квартира no. '. $_POST['flat'].', '. $_POST['street']);
    $placed_on = date('d-M-Y');
    $cart_total = 0;
    $cart_products[] = '';
    $cart_query = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'") or die('Помилка запиту');
    if (mysqli_num_rows($cart_query) > 0) {
        while ($cart_item = mysqli_fetch_assoc($cart_query)) {
            $cart_products[] = $cart_item['name'].' ('.$cart_item['quantity'].') ';
            $sub_total = ($cart_item['price'] * $cart_item['quantity']);
            $cart_total += $sub_total;
        }
    }
    $total_products = implode(', ', $cart_products);
    $order_query = mysqli_query($conn, "SELECT * FROM orders WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('Помилка запиту');
    if ($cart_total == 0) {
        $message[] = 'Ваш кошик порожній!';
    } elseif (mysqli_num_rows($order_query) > 0) {
        $message[] = 'Замовлення вже зроблене!';
    } else {
        mysqli_query($conn, "INSERT INTO orders(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')") or die('Помилка запиту');
        mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id'") or die('Помилка запиту');
        $message[] = 'Замовлення успішно оформлено!';
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Оформлення замовлення</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<section class="heading">
    <h3>ОФОРМЛЕННЯ ЗАМОВЛЕННЯ</h3>
    <p> <a href="home.php">головна</a> / оформлення замовлення </p>
</section>
<section class="display-order">
    <?php
        $grand_total = 0;
        $select_cart = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'") or die('Помилка запиту');
        if (mysqli_num_rows($select_cart) > 0) {
            while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
    ?>    
    <p> <?php echo $fetch_cart['name'] ?> <span>(<?php echo '$'.$fetch_cart['price'].'/-'.' x '.$fetch_cart['quantity']  ?>)</span> </p>
    <?php
        }
        } else {
            echo '<p class="empty">Ваш кошик порожній</p>';
        }
    ?>
    <div class="grand-total">Загальна сума : <span>₴<?php echo $grand_total; ?>/-</span></div>
</section>
<section class="checkout">
    <form action="" method="POST">
        <h3>ОФОРМІТЬ ВАШЕ ЗАМОВЛЕННЯ</h3>
        <div class="flex">
            <div class="inputBox">
                <span>Ваше ім’я :</span>
                <input type="text" name="name" placeholder="Введіть ваше ім’я">
            </div>
            <div class="inputBox">
                <span>Ваш номер :</span>
                <input type="number" name="number" min="0" placeholder="Введіть ваш номер">
            </div>
            <div class="inputBox">
            <span>Ваша електронна пошта :</span>
                <input type="email" name="email" placeholder="Введіть вашу електронну пошту">
            </div>
            <div class="inputBox">
                <span>Метод оплати :</span>
                <select name="method">
                    <option value="оплата при отриманні">Оплата при отриманні</option>
                    <option value="кредитна картка">Кредитна картка</option>
                    <option value="paypal">PayPal</option>
                </select>
            </div>
            <div class="inputBox">
                <span>Адреса, рядок 1 :</span>
                <input type="text" name="flat" placeholder="Наприклад, квартира №">
            </div>
            <div class="inputBox">
                <span>Адреса, рядок 2 :</span>
                <input type="text" name="street" placeholder="Наприклад, вулиця">
            </div>
            <div class="inputBox">
                <span>Місто :</span>
                <input type="text" name="city" placeholder="Наприклад, Коломия">
            </div>
        </div>
        <input type="submit" name="order" value="Оформити замовлення" class="option-btn">
    </form>
</section>
<?php include 'includes/footer.php'; ?>
<script src="../assets/js/script.js"></script>
</body>
</html>