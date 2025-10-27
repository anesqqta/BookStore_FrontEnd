<?php
require_once '../../BookStore_BackEnd/controllers/AdminUserController.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:login.php');
    exit;
}

$controller = new AdminUserController();
$message = [];

// 🔹 Видалення користувача
if (isset($_GET['delete'])) {
    $controller->deleteUser($_GET['delete']);
    header('location:admin_users.php');
    exit;
}

// 🔹 Отримання всіх користувачів
$users = $controller->getUsers();
?>
<!DOCTYPE html>
<html lang="uk">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Користувачі</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="users">
   <h1 class="title">Облікові записи користувачів</h1>

   <div class="box-container">
      <?php if (mysqli_num_rows($users) > 0): ?>
         <?php while ($fetch_users = mysqli_fetch_assoc($users)): ?>
            <div class="box">
               <p>ID користувача: <span><?= $fetch_users['id']; ?></span></p>
               <p>Ім’я користувача: <span><?= htmlspecialchars($fetch_users['name']); ?></span></p>
               <p>Електронна пошта: <span><?= htmlspecialchars($fetch_users['email']); ?></span></p>
               <p>Тип користувача: 
                  <span style="color:<?= $fetch_users['user_type'] == 'admin' ? 'var(--orange)' : 'var(--black)'; ?>">
                     <?= htmlspecialchars($fetch_users['user_type']); ?>
                  </span>
               </p>
               <a href="?delete=<?= $fetch_users['id']; ?>" 
                  onclick="return confirm('Видалити цього користувача?');" 
                  class="delete-btn">Видалити</a>
            </div>
         <?php endwhile; ?>
      <?php else: ?>
         <p class="empty">Користувачів поки немає!</p>
      <?php endif; ?>
   </div>
</section>

<script src="../assets/js/admin_script.js"></script>
</body>
</html>