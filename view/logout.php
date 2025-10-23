<?php
@include '../../BookStore_BackEnd/config/Database.php';
session_start();
session_unset();
session_destroy();
header('location:login.php');
?>