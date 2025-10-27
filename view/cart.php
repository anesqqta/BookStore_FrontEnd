<?php
require_once '../../BookStore_BackEnd/controllers/CartController.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: login.php');
    exit;
}
$cartController = new CartController();
$database = new Database();
$conn = $database->getConnection();

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $delete_id, $user_id);
    $stmt->execute();
    header('Location: cart.php');
    exit;
}

if (isset($_GET['delete_all'])) {
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    header('Location: cart.php');
    exit;
}

if (isset($_POST['update_quantity'])) {
    $cart_id = $_POST['cart_id'];
    $cart_quantity = $_POST['cart_quantity'];

    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("iii", $cart_quantity, $cart_id, $user_id);
    $stmt->execute();
    $message[] = 'Кількість товару оновлено!';
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кошик покупок</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<section class="heading">
    <h3>КОШИК ПОКУПОК</h3>
    <p><a href="home.php">головна</a> / кошик</p>
</section>
<section class="shopping-cart">
    <h1 class="title">Додані товари</h1>
    <div class="box-container">
        <?php
        $grand_total = 0;
        $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0):
            while ($fetch_cart = $result->fetch_assoc()):
        ?>
        <div class="box">
            <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('Видалити цей товар з кошика?');"></a>
            <a href="view_book.php?book_id=<?php echo $fetch_cart['book_id']; ?>" class="fas fa-eye"></a>
            <img src="../uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="" class="image">
            <div class="name"><?php echo $fetch_cart['name']; ?></div>
            <div class="price">₴<?php echo $fetch_cart['price']; ?></div>
            <form action="" method="post">
                <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>" class="qty">
                <input type="submit" value="Оновити" name="update_quantity" class="option-btn">
            </form>
            <div class="sub-total">Підсумок: <span>₴<?php echo $sub_total = $fetch_cart['price'] * $fetch_cart['quantity']; ?></span></div>
        </div>
        <?php
            $grand_total += $sub_total;
            endwhile;
        else:
            echo '<p class="empty">Ваш кошик порожній</p>';
        endif;
        ?>
    </div>
    <div class="more-btn">
        <a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 0) ? '' : 'disabled'; ?>" onclick="return confirm('Видалити всі товари з кошика?');">Видалити все</a>
    </div>
    <div class="cart-total">
        <p>Загальна сума: <span>₴<?php echo $grand_total; ?></span></p>
        <a href="shop.php" class="option-btn">Продовжити покупку</a>
        <a href="checkout.php" class="cart-btn <?php echo ($grand_total > 0) ? '' : 'disabled'; ?>">Перейти до оформлення</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
<script src="../assets/js/script.js"></script>
</body>
</html>