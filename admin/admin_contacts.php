<?php
@include '../../BookStore_BackEnd/config/Database.php';
session_start();
$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
   header('location:login.php');
};
if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM message WHERE id = '$delete_id'") or die('Помилка запиту');
   header('location:admin_contacts.php');
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Контакти</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body> 
<?php include 'admin_header.php'; ?>
<section class="messages">
   <h1 class="title">ПОВІДОМЛЕННЯ</h1>
   <div class="box-container">
      <?php
       $select_message = mysqli_query($conn, "SELECT * FROM message") or die('Помилка запиту');
       if(mysqli_num_rows($select_message) > 0){
          while($fetch_message = mysqli_fetch_assoc($select_message)){
      ?>
      <div class="box">
         <p>ID користувача: <span><?php echo $fetch_message['user_id']; ?></span> </p>
         <p>Ім'я: <span><?php echo $fetch_message['name']; ?></span> </p>
         <p>Номер телефону: <span><?php echo $fetch_message['number']; ?></span> </p>
         <p>Email: <span><?php echo $fetch_message['email']; ?></span> </p>
         <p>Повідомлення: <span><?php echo $fetch_message['message']; ?></span> </p>
         <a href="admin_contacts.php?delete=<?php echo $fetch_message['id']; ?>" onclick="return confirm('Видалити це повідомлення?');" class="delete-btn">Видалити</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">У вас немає повідомлень!</p>';
      }
      ?>
   </div>
</section>
<script src="../assets/js/admin_script.js"></script>
</body>
</html>