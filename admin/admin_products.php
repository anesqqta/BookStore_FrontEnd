<?php
@include '../../BookStore_BackEnd/config/Database.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
    header('location:login.php');
}
if(isset($_POST['add_product'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $genre = mysqli_real_escape_string($conn, $_POST['genre']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $year_published = mysqli_real_escape_string($conn, $_POST['year_published']);
    $language = mysqli_real_escape_string($conn, $_POST['language']);
    $number_pages = mysqli_real_escape_string($conn, $_POST['number_pages']);
    $primary_description = mysqli_real_escape_string($conn, $_POST['primary_description']);
    $secondary_description = mysqli_real_escape_string($conn, $_POST['secondary_description']);
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_img/' . $image;
    $select_product_name = mysqli_query($conn, "SELECT name FROM products WHERE name = '$name'") or die('Помилка запиту');
    if(mysqli_num_rows($select_product_name) > 0){
        $message[] = 'Назва товару вже існує!';
    } else {
        $insert_product = mysqli_query($conn, "INSERT INTO products(name, price, genre, author, year_published, language, number_pages, primary_description, secondary_description, image) VALUES('$name', '$price', '$genre', '$author', '$year_published', '$language', '$number_pages', '$primary_description', '$secondary_description', '$image')") or die('Помилка запиту');

        if($insert_product){
            if($image_size > 2000000){
                $message[] = 'Розмір зображення занадто великий!';
            } else {
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = 'Товар успішно додано!';
            }
        }
    }
}

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    $select_delete_image = mysqli_query($conn, "SELECT image FROM products WHERE id = '$delete_id'") or die('Помилка запиту');
    $fetch_delete_image = mysqli_fetch_assoc($select_delete_image);
    if(file_exists('../uploaded_img/'.$fetch_delete_image['image'])){
        unlink('../uploaded_img/'.$fetch_delete_image['image']);
    }
    mysqli_query($conn, "DELETE FROM products WHERE id = '$delete_id'") or die('Помилка запиту');
    mysqli_query($conn, "DELETE FROM wishlist WHERE book_id = '$delete_id'") or die('Помилка запиту');
    mysqli_query($conn, "DELETE FROM cart WHERE book_id = '$delete_id'") or die('Помилка запиту');
    header('location:admin_products.php');
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Товари</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
<?php include 'admin_header.php'; ?>

<section class="add-products">
    <form action="" method="POST" enctype="multipart/form-data">
        <h3>ДОДАТИ НОВИЙ ТОВАР</h3>
        <input type="text" class="box" required placeholder="Введіть назву товару" name="name">
        <input type="number" min="0" class="box" required placeholder="Введіть ціну товару" name="price">
        <input type="text" class="box" required placeholder="Введіть жанр товару" name="genre">
        <input type="text" class="box" required placeholder="Введіть автора товару" name="author">
        <input type="number" min="0" class="box" required placeholder="Введіть рік видання товару" name="year_published">
        <input type="text" class="box" required placeholder="Введіть мову написання товару" name="language">
        <input type="number" min="1" class="box" required placeholder="Введіть кількість сторінок в товарі" name="number_pages">
        <textarea name="primary_description" class="box" required placeholder="Введіть опис 1" cols="30" rows="5"></textarea>
        <textarea name="secondary_description" class="box" required placeholder="Введіть опис 2" cols="30" rows="5"></textarea>
        <input type="file" accept="image/jpg, image/jpeg, image/png" required class="box" name="image">
        <input type="submit" value="Додати товар" name="add_product" class="btn">
    </form>
</section>
<section class="show-products">
    <div class="box-container">
        <?php
        $select_products = mysqli_query($conn, "SELECT * FROM products") or die('Помилка запиту');
        if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
        ?>
        <div class="box">
            <div class="price">₴<?php echo $fetch_products['price']; ?>/-</div>
            <img class="image" src="../uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
            <div class="name"><?php echo $fetch_products['name']; ?></div>
            <a href="admin_edit_book.php?edit_id=<?php echo $fetch_products['id']; ?>" class="option-btn">Оновити</a>
            <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Видалити цей товар?');">Видалити</a>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">Поки що немає доданих товарів!</p>';
        }
        ?>
    </div>
</section>

<script src="../assets/js/admin_script.js"></script>
</body>
</html>
