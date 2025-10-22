<?php
@include '../../BookStore_BackEnd/config/Database.php';
session_start();
$user_id = $_SESSION['user_id'];
if(!isset($user_id)){
   header('location:login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Про нас</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<section class="heading">
    <h3>про нас</h3>
    <p> <a href="home.php">головна</a> / про </p>
</section>
<section class="about">
    <div class="flex">
        <div class="image">
            <img src="../assets/images/about1.jpg" alt="">
        </div>
        <div class="content">
            <h3>чому обирають нас?</h3>
            <p>Наш магазин створено для тих, хто цінує справжні історії, натхнення та запах нових сторінок. Ми пропонуємо широкий вибір книжок — від світових бестселерів до української сучасної літератури. Наші читачі обирають нас за якість, чесність і любов до книг, яку ми вкладаємо у кожне замовлення.</p>
            <a href="shop.php" class="btn-about">робити покупки зараз</a>
        </div>
    </div>
    <div class="flex">
        <div class="content">
            <h3>що ми надаємо?</h3>
            <p>• Тисячі книжок у різних жанрах — від романів до професійної літератури; <br>
                • Швидку доставку по всій Україні;<br>
                • Зручні способи оплати;<br>
                • Підтримку та рекомендації для вибору книги саме під твій настрій.<br>
                Ми прагнемо зробити процес купівлі книги простим, приємним і надихаючим, щоб кожен читач міг знайти свою історію.</p>
            <a href="contact.php" class="btn-about">зв'яжіться з нами</a>
        </div>
        
        <div class="image">
            <img src="../assets/images/about2.jpg" alt="">
        </div>
    </div>
    <div class="flex">
        <div class="image">
            <img src="../assets/images/about3.jpg" alt="">
        </div>
        <div class="content">
            <h3>хто ми?</h3>
            <p>Ми — команда книголюбів, які вірять, що книга здатна змінювати життя. Наш “Book Store” — це не просто інтернет-магазин, а місце, де зустрічаються історії та серця. Ми об’єднуємо авторів, видавців і читачів, створюючи спільноту, де книга залишається головною цінністю.</p>
            <a href="#reviews" class="btn-about">відгуки клієнтів</a>
        </div>
    </div>
</section>
<section class="reviews" id="reviews">
    <h1 class="title">відгуки клієнтів</h1>
    <div class="box-container">
        <div class="box">
            <img src="../assets/images/pic-1.png" alt="">
            <p>Замовлення прийшло швидко, книги запаковані з турботою. Приємно отримувати не просто покупку, а шматочок натхнення. Замовлятиму ще.</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
            </div>
            <h3>Анастасія Семенюк</h3>
        </div>
        <div class="box">
            <img src="../assets/images/pic-2.png" alt="">
            <p>Дуже приємний сервіс! Знайшов тут книжку, яку давно шукав і не міг знайти ніде! Великий вибір і чудова навігація по сайту. Рекомендую всім книголюбам!</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
            </div>
            <h3>Владислав Бордун</h3>
        </div>
        <div class="box">
            <img src="../assets/images/pic-3.png" alt="">
            <p>Люблю цей магазин за атмосферу. Все продумано — від обкладинок до маленьких листівок у посилці. Відчувається любов до читання.</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
            </div>
            <h3>Денис Самофал</h3>
        </div>
        <div class="box">
            <img src="../assets/images/pic-4.png" alt="">
            <p>Швидка доставка, гарна комунікація, зручна оплата. А ще — чудові знижки! Тепер це мій улюблений книжковий сайт.</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
            </div>
            <h3>Вікторія Семенюк</h3>
        </div>
        <div class="box">
            <img src="../assets/images/pic-5.png" alt="">
            <p>Великий вибір книжок, приємні ціни та швидка доставка. Усе прийшло в чудовому стані, сервіс на високому рівні.</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Ярослав Семенюк</h3>
        </div>
        <div class="box">
            <img src="../assets/images/pic-6.png" alt="">
            <p>Затишний куточок для всіх, хто любить читати. Книги завжди приходять у чудовому стані, а персонал — неймовірно уважний.</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Оксана Матишейко</h3>
        </div>
    </div>
</section>
<?php include 'includes/footer.php'; ?>
<script src="../assets/js/script.js"></script>
</body>
</html>