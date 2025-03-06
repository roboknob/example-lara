<?php
session_start();
if(isset($_SESSION['user']) && $_SESSION['user_agent'] == $_SERVER['HTTP_USER_AGENT']){
    header('location: ./profile.php');
}
?>
<html>
<form action="login.php" method="post">
    <input type="text" name="login" placeholder="Login">
    <input type="password" name="passowrd" placeholder="Password">
    <button type="submit">Войти</button>
</form>
</html>
