<?php
require_once '../../BookStore_BackEnd/controllers/UserController.php';
session_start();

$userController = new UserController();

if (isset($_POST['submit'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['pass'] ?? '';

    $loginResult = $userController->loginUser($email, $password);

    if ($loginResult['status'] === 'success') {
        if ($loginResult['user_type'] === 'admin') {
            $_SESSION['admin_name'] = $loginResult['name'];
            $_SESSION['admin_email'] = $loginResult['email'];
            $_SESSION['admin_id'] = $loginResult['id'];
            header('location:../admin/admin_dashboard.php');
            exit;
        } else {
            $_SESSION['user_name'] = $loginResult['name'];
            $_SESSION['user_email'] = $loginResult['email'];
            $_SESSION['user_id'] = $loginResult['id'];
            header('location:home.php');
            exit;
        }
    } else {
        $message[] = $loginResult['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
   <meta charset="UTF-8">
   <title>Увійти</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php
if (!empty($message)) {
   foreach ($message as $msg) {
      echo '
      <div class="message">
         <span>'.$msg.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>';
   }
}
?>

<section class="form-container">
   <form action="" method="post">
      <h3>УВІЙТИ</h3>
      <input type="email" name="email" class="box" placeholder="Введіть ваш email" required>
      <input type="password" name="pass" class="box" placeholder="Введіть ваш пароль" required>
      <input type="submit" class="option-btn" name="submit" value="Увійти">
      <p>Ще не маєте облікового запису? <a href="register.php">Зареєструватися</a></p>
   </form>
</section>
</body>
</html>