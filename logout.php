<?
session_start();

unset($_SESSION['user_key']);
unset($_SESSION['user_ac']);
unset($_SESSION['user_pw']);
unset($_SESSION['user_id']);

header('Location: index.php?page=home');
?>