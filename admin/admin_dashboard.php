<?php
require_once '../../BookStore_BackEnd/controllers/AdminDashboardController.php';
session_start();

$admin_id = $_SESSION['admin_id'] ?? null;
if (!$admin_id) {
    header('location:../view/login.php');
    exit;
}

$controller = new AdminDashboardController();
$data = $controller->getDashboardData();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Адмін-панель</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
<?php include 'admin_header.php'; ?>

<section class="dashboard">
   <h1 class="title">ПАНЕЛЬ АДМІНІСТРАТОРА</h1>

   <div class="box-container">
      <div class="box">
         <h3>₴<?= $data['pendingTotal']; ?>/-</h3>
         <p>Загальна сума очікуваних платежів</p>
      </div>

      <div class="box">
         <h3>₴<?= $data['completedTotal']; ?>/-</h3>
         <p>Завершені платежі</p>
      </div>

      <div class="box">
         <h3><?= $data['orders']; ?></h3>
         <p>Кількість замовлень</p>
      </div>

      <div class="box">
         <h3><?= $data['products']; ?></h3>
         <p>Додані товари</p>
      </div>

      <div class="box">
         <h3><?= $data['users']; ?></h3>
         <p>Користувачі</p>
      </div>

      <div class="box">
         <h3><?= $data['admins']; ?></h3>
         <p>Адміністратори</p>
      </div>

      <div class="box">
         <h3><?= $data['accounts']; ?></h3>
         <p>Усього акаунтів</p>
      </div>

      <div class="box">
         <h3><?= $data['messages']; ?></h3>
         <p>Нові повідомлення</p>
      </div>
   </div>
</section>

<script src="../assets/js/admin_script.js"></script>
</body>
</html>