<?php
require_once '../../BookStore_BackEnd/controllers/UserController.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header('location:login.php');
    exit();
}
$userController = new UserController();
$user = $userController->getUserById($user_id);

if (!$user) {
    echo '<p class="empty">Користувача не знайдено!</p>';
    exit();
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Профіль користувача</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="heading">
    <h3>ПРОФІЛЬ КОРИСТУВАЧА</h3>
    <p><a href="home.php">головна</a> / профіль</p>
</div>
<section class="profile-section">
    <div class="profile-container">
        <div class="profile-left">
            <img src="../assets/images/profile.jpg" alt="user">
            <h2><?php echo htmlspecialchars($user['name']); ?></h2>
        </div>
        <div class="profile-info">
            <p><span>Ім’я:</span> <?php echo htmlspecialchars($user['name']); ?></p>
            <p><span>Email:</span> <?php echo htmlspecialchars($user['email']); ?></p>
            <button onclick="window.location.href='logout.php'" class="logout-btn">Вийти</button>
        </div>
        <div class="profile-right">
            <ul>
                <li><i class="fas fa-user-edit"></i> <a href="edit_profile.php">Редагувати профіль</a></li>
                <li><i class="fas fa-box"></i> <a href="orders.php">Історія замовлень</a></li>
                <li><i class="fas fa-heart"></i> <a href="wishlist.php">Список бажань</a></li>
            </ul>
        </div>
   </div>
</section>

<?php include 'includes/footer.php'; ?>
<script src="../assets/js/profile.js"></script>
</body>
</html>