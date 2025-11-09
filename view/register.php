<?php
require_once '../../BookStore_BackEnd/controllers/UserController.php';

$userController = new UserController();
$message = [];
if (isset($_POST['submit'])) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $pass = $_POST['pass'] ?? '';
    $cpass = $_POST['cpass'] ?? '';

    $registerResult = $userController->registerUser($name, $email, $pass, $cpass);

    if ($registerResult['status'] === 'success') {
        header('location:login.php');
        exit;
    } else {
        $message[] = $registerResult['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
   <meta charset="UTF-8">
   <title>Реєстрація</title>
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
      <h3>ЗАРЕЄСТРУВАТИСЯ ЗАРАЗ</h3>
      <input type="text" name="name" class="box" placeholder="Введіть ваше ім’я" required>
      <input type="email" name="email" class="box" placeholder="Введіть ваш email" required>
      <input type="password" name="pass" class="box" placeholder="Введіть ваш пароль" required>
      <input type="password" name="cpass" class="box" placeholder="Підтвердіть ваш пароль" required>
      <input type="submit" class="option-btn" name="submit" value="Зареєструватися">
      <p>Вже маєте акаунт? <a href="login.php">Увійти</a></p>
   </form>
</section>

</body>
</html>