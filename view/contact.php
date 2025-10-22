<?php
@include '../../BookStore_BackEnd/config/Database.php';
session_start();
$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
   header('location:login.php');
}
if (isset($_POST['send'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $msg = mysqli_real_escape_string($conn, $_POST['message']);
    $select_message = mysqli_query($conn, "SELECT * FROM message WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'") or die('Помилка запиту');
    if (mysqli_num_rows($select_message) > 0) {
        $message[] = 'Повідомлення вже надіслано!';
    } else {
        mysqli_query($conn, "INSERT INTO message(user_id, name, email, number, message) VALUES('$user_id', '$name', '$email', '$number', '$msg')") or die('Помилка запиту');
        $message[] = 'Повідомлення успішно надіслано!';
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>КОНТАКТИ</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="../assets/css/style.css">
</head> 
<body>
<?php include 'includes/header.php'; ?>
<section class="heading">
    <h3>Зв'язатись з нами</h3>
    <p> <a href="home.php">головна</a> / контакти </p>
</section>
<section class="contact">
    <form action="" method="POST">
        <h3>Надіслати нам повідомлення!</h3>
        <input type="text" name="name" placeholder="Введіть ваше ім’я" class="box" required> 
        <input type="email" name="email" placeholder="Введіть вашу електронну пошту" class="box" required>
        <input type="number" name="number" placeholder="Введіть ваш номер" class="box" required>
        <textarea name="message" class="box" placeholder="Введіть ваше повідомлення" required cols="30" rows="10"></textarea>
        <input type="submit" value="Надіслати повідомлення" name="send" class="btn-contact">
    </form>
</section>
<?php include 'includes/footer.php'; ?>
<script src="../assets/js/script.js"></script>
</body>
</html>