<?php
session_start();
if (isset($_SESSION['email']) || !empty($_SESSION['email'])) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();
}
    header('Location: index.php');
    exit();
?> 