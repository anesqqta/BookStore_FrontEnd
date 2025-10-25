<?php
@include '../../BookStore_BackEnd/config/Database.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
   header('location:login.php');
}

// Оновлення товару
if(isset($_POST['update_product'])){
   $update_p_id = $_POST['update_p_id'];
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $price = mysqli_real_escape_string($conn, $_POST['price']);
   $genre = mysqli_real_escape_string($conn, $_POST['genre']);
   $author = mysqli_real_escape_string($conn, $_POST['author']);
   $year_published = mysqli_real_escape_string($conn, $_POST['year_published']);
   $language = mysqli_real_escape_string($conn, $_POST['language']);
   $number_pages = mysqli_real_escape_string($conn, $_POST['number_pages']);
   $primary_description = mysqli_real_escape_string($conn, $_POST['primary_description']);
   $secondary_description = mysqli_real_escape_string($conn, $_POST['secondary_description']);

   mysqli_query($conn, "UPDATE products SET 
      name = '$name', 
      price = '$price', 
      genre = '$genre', 
      author = '$author', 
      year_published = '$year_published',
      language = '$language',
      number_pages = '$number_pages',
      primary_description = '$primary_description',
      secondary_description = '$secondary_description'
      WHERE id = '$update_p_id'") or die('Помилка запиту');

   // Зміна зображення
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/' . $image;
   $old_image = $_POST['update_p_image'];

   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'Розмір зображення занадто великий!';
      } else {
         mysqli_query($conn, "UPDATE products SET image = '$image' WHERE id = '$update_p_id'") or die('Помилка запиту');
         move_uploaded_file($image_tmp_name, $image_folder);
         if(file_exists('../uploaded_img/'.$old_image)){
            unlink('../uploaded_img/'.$old_image);
         }
         $message[] = 'Зображення оновлено!';
      }
   }

   $message[] = 'Товар успішно оновлено!';
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Оновлення товару</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>

<?php @include 'admin_header.php'; ?>

<section class="update-product">
<?php
   $update_id = $_GET['edit_id'];
   $select_products = mysqli_query($conn, "SELECT * FROM products WHERE id = '$update_id'") or die('Помилка запиту');
   if(mysqli_num_rows($select_products) > 0){
      while($fetch_products = mysqli_fetch_assoc($select_products)){
?>
<form action="" method="post" enctype="multipart/form-data">
   <img src="../uploaded_img/<?php echo $fetch_products['image']; ?>" class="image" alt="">

   <input type="hidden" value="<?php echo $fetch_products['id']; ?>" name="update_p_id">
   <input type="hidden" value="<?php echo $fetch_products['image']; ?>" name="update_p_image">

   <input type="text" class="box" name="name" required value="<?php echo $fetch_products['name']; ?>" placeholder="Назва книги">
   <input type="number" class="box" min="0" name="price" required value="<?php echo $fetch_products['price']; ?>" placeholder="Ціна">
   <input type="text" class="box" name="genre" required value="<?php echo $fetch_products['genre']; ?>" placeholder="Жанр">
   <input type="text" class="box" name="author" required value="<?php echo $fetch_products['author']; ?>" placeholder="Автор">
   <input type="number" class="box" min="0" name="year_published" required value="<?php echo $fetch_products['year_published']; ?>" placeholder="Рік публікації">
   <input type="text" class="box" name="language" required value="<?php echo $fetch_products['language']; ?>" placeholder="Мова">
   <input type="number" class="box" min="1" name="number_pages" required value="<?php echo $fetch_products['number_pages']; ?>" placeholder="Кількість сторінок">

   <textarea name="primary_description" class="box" required placeholder="Основний опис" cols="30" rows="5"><?php echo $fetch_products['primary_description']; ?></textarea>
   <textarea name="secondary_description" class="box" required placeholder="Додатковий опис" cols="30" rows="5"><?php echo $fetch_products['secondary_description']; ?></textarea>

   <input type="file" accept="image/jpg, image/jpeg, image/png" class="box" name="image">
   <input type="submit" value="Оновити товар" name="update_product" class="btn">
   <a href="admin_products.php" class="option-btn">Повернутись</a>
</form>
<?php
      }
   } else {
      echo '<p class="empty">Не вибрано товар для оновлення</p>';
   }
?>
</section>

<script src="../assets/js/admin_script.js"></script>
</body>
</html>
