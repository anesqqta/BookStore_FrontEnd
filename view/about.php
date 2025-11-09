<?php
require_once '../../BookStore_BackEnd/controllers/UserController.php';
session_start();
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="uk">
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
    <h3>Про нас</h3>
    <p><a href="home.php">головна</a> / про</p>
</section>
<section class="about">
    <div class="flex">
        <div class="image">
            <img src="../assets/images/about1.jpg" alt="">
        </div>
        <div class="content">
            <h3>Чому обирають нас?</h3>
            <p>Наш магазин створено для тих, хто цінує справжні історії, натхнення та запах нових сторінок. Ми пропонуємо широкий вибір книжок — від світових бестселерів до української сучасної літератури. Наші читачі обирають нас за якість, чесність і любов до книг, яку ми вкладаємо у кожне замовлення.</p>
            <a href="shop.php" class="btn-about">робити покупки зараз</a>
        </div>
    </div>
    <div class="flex">
        <div class="content">
            <h3>Що ми надаємо?</h3>
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
            <h3>Хто ми?</h3>
            <p>Ми — команда книголюбів, які вірять, що книга здатна змінювати життя. Наш “Book Store” — це не просто інтернет-магазин, а місце, де зустрічаються історії та серця. Ми об’єднуємо авторів, видавців і читачів, створюючи спільноту, де книга залишається головною цінністю.</p>
            <a href="#reviews" class="btn-about">відгуки клієнтів</a>
        </div>
    </div>
</section>
<section class="reviews" id="reviews">
    <h1 class="title">Відгуки клієнтів</h1>
    <div class="box-container">
        <?php
        $reviews = [
            ["pic-1.png", "Замовлення прийшло швидко, книги запаковані з турботою. Приємно отримувати не просто покупку, а шматочок натхнення.", "Анастасія Семенюк"],
            ["pic-2.png", "Дуже приємний сервіс! Знайшов тут книжку, яку давно шукав і не міг знайти ніде! Рекомендую!", "Владислав Бордун"],
            ["pic-3.png", "Люблю цей магазин за атмосферу. Все продумано — від обкладинок до маленьких листівок у посилці.", "Денис Самофал"],
            ["pic-4.png", "Швидка доставка, гарна комунікація, зручна оплата. А ще — чудові знижки! Тепер це мій улюблений книжковий сайт.", "Вікторія Семенюк"],
            ["pic-5.png", "Великий вибір книжок, приємні ціни та швидка доставка. Усе прийшло в чудовому стані.", "Ярослав Семенюк"],
            ["pic-6.png", "Затишний куточок для всіх, хто любить читати. Книги завжди приходять у чудовому стані.", "Оксана Матишейко"]
        ];
        foreach ($reviews as $review) {
            echo '
            <div class="box">
                <img src="../assets/images/'.$review[0].'" alt="">
                <p>'.$review[1].'</p>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <h3>'.$review[2].'</h3>
            </div>';
        }
        ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
<script src="../assets/js/script.js"></script>
</body>
</html>