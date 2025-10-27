<?php
require_once '../../BookStore_BackEnd/controllers/WishlistController.php';
require_once '../../BookStore_BackEnd/controllers/CartController.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header('location:login.php');
    exit;
}
$wishlistController = new WishlistController();
$cartController = new CartController();

if (isset($_POST['add_to_cart'])) {
    $book = [
        'product_id' => $_POST['product_id'],
        'product_name' => $_POST['product_name'],
        'product_price' => $_POST['product_price'],
        'product_image' => $_POST['product_image'],
        'product_quantity' => $_POST['product_quantity'] ?? 1
    ];
    $cartController->addToCart($user_id, $book);

    $wishlist_id = $_POST['wishlist_id'] ?? null;
    if ($wishlist_id) {
        require_once '../../BookStore_BackEnd/config/Database.php';
        $database = new Database();
        $conn = $database->getConnection();
        $stmt = $conn->prepare("DELETE FROM wishlist WHERE id = ?");
        $stmt->bind_param("i", $wishlist_id);
        $stmt->execute();
    }
    $message[] = 'Товар додано до кошика!';
}
if (isset($_GET['delete'])) {
    require_once '../../BookStore_BackEnd/config/Database.php';
    $database = new Database();
    $conn = $database->getConnection();
    $stmt = $conn->prepare("DELETE FROM wishlist WHERE id = ?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();

    header('location:wishlist.php');
    exit;
}
if (isset($_GET['delete_all'])) {
    require_once '../../BookStore_BackEnd/config/Database.php';
    $database = new Database();
    $conn = $database->getConnection();
    $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    header('location:wishlist.php');
    exit;
}
require_once '../../BookStore_BackEnd/config/Database.php';
    $database = new Database();
    $conn = $database->getConnection();

    $result = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ?");
    $result->bind_param("i", $user_id);
    $result->execute();
    $wishlist = $result->get_result();
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Список бажаного</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<section class="heading">
    <h3>ВАШ СПИСОК БАЖАНОГО</h3>
    <p><a href="home.php">головна</a> / список бажаного</p>
</section>
<section class="wishlist">
    <h1 class="title">Додані продукти</h1>
    <div class="box-container">
        <?php
        $grand_total = 0;
        if ($wishlist && $wishlist->num_rows > 0) {
            while ($item = $wishlist->fetch_assoc()) {
                $grand_total += $item['price'];
        ?>
        <form action="" method="POST" class="box">
            <a href="wishlist.php?delete=<?php echo $item['id']; ?>" class="fas fa-times" onclick="return confirm('Видалити цей товар зі списку бажаного?');"></a>
            <a href="view_book.php?book_id=<?php echo $item['book_id']; ?>" class="fas fa-eye"></a>
            <img src="../uploaded_img/<?php echo $item['image']; ?>" alt="" class="image">
            <div class="name"><?php echo $item['name']; ?></div>
            <div class="price">₴<?php echo $item['price']; ?></div>
            <input type="hidden" name="wishlist_id" value="<?php echo $item['id']; ?>">
            <input type="hidden" name="product_id" value="<?php echo $item['book_id']; ?>">
            <input type="hidden" name="product_name" value="<?php echo $item['name']; ?>">
            <input type="hidden" name="product_price" value="<?php echo $item['price']; ?>">
            <input type="hidden" name="product_image" value="<?php echo $item['image']; ?>">
            <input type="submit" value="Додати до кошика" name="add_to_cart" class="option-btn">
        </form>
        <?php
            }
        } else {
            echo '<p class="empty">Ваш список бажаного порожній</p>';
        }
        ?>
    </div>
    <div class="wishlist-total">
        <p>Загальна сума: <span>₴<?php echo $grand_total; ?></span></p>
        <a href="shop.php" class="option-btn">Продовжити покупки</a>
        <a href="wishlist.php?delete_all" class="delete-btn <?php echo ($grand_total > 0)?'':'disabled'; ?>" onclick="return confirm('Видалити все зі списку бажаного?');">Видалити все</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
<script src="../assets/js/script.js"></script>
</body>
</html>