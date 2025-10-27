<?php
require_once '../../BookStore_BackEnd/controllers/UserController.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('location:login.php');
    exit;
}
$controller = new UserController();
$user = $controller->getUserById($user_id);
$message = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $message[] = $controller->updateProfile($user_id, $_POST);
    $user = $controller->getUserById($user_id); // оновлення після збереження
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагування профілю</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<section class="heading">
    <h3>РЕДАГУВАННЯ ПРОФІЛЮ</h3>
    <p><a href="home.php">головна</a> / профіль</p>
</section>
<section class="edit-profile">
    <div class="profile-container">
        <div class="profile-left">
            <img src="../assets/images/profile.jpg" alt="user">
            <h2><?= htmlspecialchars($user['name']); ?></h2>
        </div>
        <form action="" method="POST" class="profile-form">
            <div class="flex">
                <div class="profile-info">
                    <span>Ваше ім’я:</span>
                    <input type="text" name="name" value="<?= htmlspecialchars($user['name']); ?>" required>
                    <span>Ваша електронна пошта:</span>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
                </div>
            </div>
            <input type="submit" name="update_profile" value="Зберегти" class="btn-contact">
        </form>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
<script src="../assets/js/script.js"></script>
</body>
</html>