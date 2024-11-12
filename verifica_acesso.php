<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['nivel_acesso'] !== 'admin') {
    header("Location: home.html"); // Redireciona para home se não for admin
    exit();
}
?>
