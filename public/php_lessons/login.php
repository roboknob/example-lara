<?php
session_start();

$login = $_POST['login'];
$password = $_POST['passowrd'];

if($login == "admin" && $password == "admin"){
    session_regenerate_id(true);
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    $_SESSION['user'] = $login;
    header("Location: profile.php");
} else {
    echo "Не верный логин или пароль";
}
