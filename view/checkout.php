<?php
require_once '../../BookStore_BackEnd/controllers/CartController.php';
require_once '../../BookStore_BackEnd/controllers/OrderController.php';
require_once '../../BookStore_BackEnd/config/Database.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('location:login.php');
    exit;
}
$cartController = new CartController();
$orderController = new OrderController();

$database = new Database();
$conn = $database->getConnection();

if (isset($_POST['order'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $address = mysqli_real_escape_string($conn, 'квартира № ' . $_POST['flat'] . ', ' . $_POST['street']);
    $placed_on = date('d-M-Y');

    $cart_items = $cartController->getUserCart($user_id);
    $cart_total = 0;
    $cart_products = [];

    if ($cart_items && $cart_items->num_rows > 0) {
        while ($item = $cart_items->fetch_assoc()) {
            $cart_products[] = $item['name'] . ' (' . $item['quantity'] . ')';
            $cart_total += ($item['price'] * $item['quantity']);
        }
    }
    if ($cart_total == 0) {
        $message[] = 'Ваш кошик порожній!';
    } else {
        $orderData = [
            'user_id' => $user_id,
            'name' => $name,
            'number' => $number,
            'email' => $email,
            'method' => $method,
            'address' => $address,
            'total_products' => implode(', ', $cart_products),
            'total_price' => $cart_total,
            'placed_on' => $placed_on
        ];
        $response = $orderController->placeOrder($orderData);

        if ($response === true) {
            $cartController->clearCart($user_id);
            $message[] = 'Замовлення успішно оформлено!';
        } else {
            $message[] = $response;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Оформлення замовлення</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<section class="heading">
    <h3>ОФОРМЛЕННЯ ЗАМОВЛЕННЯ</h3>
    <p><a href="home.php">головна</a> / оформлення замовлення</p>
</section>
<section class="display-order">
    <?php
    $grand_total = 0;
    $cart_items = $cartController->getUserCart($user_id);
    if ($cart_items && $cart_items->num_rows > 0) {
        while ($item = $cart_items->fetch_assoc()) {
            $total_price = $item['price'] * $item['quantity'];
            $grand_total += $total_price;
            echo "<p>{$item['name']} <span>(₴{$item['price']} x {$item['quantity']})</span></p>";
        }
    } else {
        echo '<p class="empty">Ваш кошик порожній</p>';
    }
    ?>
    <div class="grand-total">Загальна сума: <span>₴<?php echo $grand_total; ?></span></div>
</section>
<section class="checkout">
    <form action="" method="POST">
        <h3>ОФОРМІТЬ ВАШЕ ЗАМОВЛЕННЯ</h3>
        <div class="flex">
            <div class="inputBox">
                <span>Ваше ім’я :</span>
                <input type="text" name="name" required placeholder="Введіть ваше ім’я">
            </div>
            <div class="inputBox">
                <span>Ваш номер :</span>
                <input type="number" name="number" required min="0" placeholder="Введіть ваш номер">
            </div>
            <div class="inputBox">
                <span>Ваша електронна пошта :</span>
                <input type="email" name="email" required placeholder="Введіть вашу електронну пошту">
            </div>
            <div class="inputBox">
                <span>Метод оплати :</span>
                <select name="method"><option value="оплата при отриманні">Оплата при отриманні</option>
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