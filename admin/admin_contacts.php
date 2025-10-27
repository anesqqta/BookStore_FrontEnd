<?php
require_once '../../BookStore_BackEnd/controllers/AdminContactController.php';
session_start();

$admin_id = $_SESSION['admin_id'] ?? null;
if (!$admin_id) {
   header('location:login.php');
   exit;
}

$controller = new AdminContactController();

// 🔹 Видалення повідомлення
if (isset($_GET['delete'])) {
   $controller->deleteMessage($_GET['delete']);
   header('location:admin_contacts.php');
   exit;
}

// 🔹 Отримання всіх повідомлень
$messages = $controller->getMessages();
?>
<!DOCTYPE html>
<html lang="uk">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Повідомлення користувачів</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="messages">
   <h1 class="title">ПОВІДОМЛЕННЯ КОРИСТУВАЧІВ</h1>
   <div class="box-container">

      <?php if (mysqli_num_rows($messages) > 0): ?>
         <?php while ($fetch_message = mysqli_fetch_assoc($messages)): ?>
            <div class="box">
               <p>ID користувача: <span><?= $fetch_message['user_id']; ?></span></p>
               <p>Ім’я: <span><?= htmlspecialchars($fetch_message['name']); ?></span></p>
               <p>Номер телефону: <span><?= htmlspecialchars($fetch_message['number']); ?></span></p>
               <p>Email: <span><?= htmlspecialchars($fetch_message['email']); ?></span></p>
               <p>Повідомлення: <span><?= nl2br(htmlspecialchars($fetch_message['message'])); ?></span></p>
               <a href="?delete=<?= $fetch_message['id']; ?>" 
                  onclick="return confirm('Видалити це повідомлення?');" 
                  class="delete-btn">Видалити</a>
            </div>
         <?php endwhile; ?>
      <?php else: ?>
         <p class="empty">У вас немає нових повідомлень!</p>
      <?php endif; ?>

   </div>
</section>

<script src="../assets/js/admin_script.js"></script>
</body>
</html>