<?php
require_once '../../BookStore_BackEnd/controllers/BookController.php';
require_once '../../BookStore_BackEnd/controllers/CartController.php';
require_once '../../BookStore_BackEnd/controllers/WishlistController.php';

session_start();
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('location:login.php');
    exit;
}

$bookController = new BookController();
$cartController = new CartController();
$wishlistController = new WishlistController();

$message = [];

// --- Додавання до списку бажаного ---
if (isset($_POST['add_to_wishlist'])) {
    $wishlistController->addToWishlist($user_id, $_POST);
    $message[] = 'Товар додано до списку бажаного';
}

// --- Додавання до кошика ---
if (isset($_POST['add_to_cart'])) {
    $cartController->addToCart($user_id, $_POST);
    $message[] = 'Товар додано до кошика';
}

// --- Отримання даних книги ---
$book = null;
if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];
    $book = $bookController->getBookById($book_id);
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Детальна сторінка книги</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<section class="heading">
    <h3>ДЕТАЛЬНА СТОРІНКА КНИГИ</h3>
    <p><a href="home.php">головна</a> / книга</p>
</section>

<section class="book-view">
<?php if ($book): ?>
    <div class="book-wrapper">
        <div class="book-image">
            <img src="../uploaded_img/<?= htmlspecialchars($book['image']); ?>" alt="">
        </div>

        <div class="book-info">
            <h2><?= htmlspecialchars($book['name']); ?></h2>
            <p><span class="label">Жанр:</span> <span class="value"><?= htmlspecialchars($book['genre']); ?></span></p>
            <p><span class="label">Автор:</span> <span class="value"><?= htmlspecialchars($book['author']); ?></span></p>
            <p><span class="label">Рік видання:</span> <span class="value"><?= htmlspecialchars($book['year_published']); ?></span></p>
            <p><span class="label">Мова:</span> <span class="value"><?= htmlspecialchars($book['language']); ?></span></p>
            <p><span class="label">Кількість сторінок:</span> <span class="value"><?= htmlspecialchars($book['number_pages']); ?></span></p>
        </div>

        <div class="book-price-box">
            <p class="price">₴<?= htmlspecialchars($book['price']); ?></p>
            <form action="" method="POST">
                <input type="hidden" name="product_id" value="<?= $book['id']; ?>">
                <input type="hidden" name="product_name" value="<?= htmlspecialchars($book['name']); ?>">
                <input type="hidden" name="product_price" value="<?= $book['price']; ?>">
                <input type="hidden" name="product_image" value="<?= htmlspecialchars($book['image']); ?>">
                <input type="number" name="product_quantity" value="1" min="1" class="qty">
                <input type="submit" value="Додати до списку бажаного" name="add_to_wishlist" class="option-btn">
                <input type="submit" value="Додати до кошика" name="add_to_cart" class="btn">
            </form>
        </div>
    </div>

    <div class="book-description">
        <p><?= nl2br(htmlspecialchars($book['primary_description'])); ?></p>
        <p><?= nl2br(htmlspecialchars($book['secondary_description'])); ?></p>
    </div>
<?php else: ?>
    <p class="empty">Книга не знайдена</p>
<?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>

<script src="../assets/js/script.js"></script>
</body>
</html>