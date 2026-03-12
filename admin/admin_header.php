<?php
if (!empty($message)) {
    foreach ($message as $msg) {
        echo '
        <div class="message">
            <span>' . htmlspecialchars($msg) . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>';
    }
}
?>

<header class="header">
    <div class="flex">
        <a href="admin_dashboard.php" class="logo">
            <span class="logo-top">BookStore</span>
            <span class="logo-bottom">AdminPanel</span>
        </a>
        <nav class="navbar">
            <a href="admin_dashboard.php">Головна</a>
            <a href="admin_products.php">Товари</a>
            <a href="admin_orders.php">Замовлення</a>
            <a href="admin_users.php">Користувачі</a>
            <a href="admin_contacts.php">Повідомлення</a>
            <a href="admin_stats.php">Статистика</a>
        </nav>
        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
        </div>
        <div class="account-box">
            <p>Ім’я користувача: <span><?php echo htmlspecialchars($_SESSION['admin_name'] ?? ''); ?></span></p>
            <p>Email: <span><?php echo htmlspecialchars($_SESSION['admin_email'] ?? ''); ?></span></p>
            <a href="../view/logout.php" class="delete-btn">Вийти</a>
            <div>Новий <a href="../view/login.php">вхід</a> | <a href="../view/register.php">реєстрація</a></div>
        </div>
    </div>
</header>