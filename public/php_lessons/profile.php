<?php
session_start();
if($_SESSION['user_agent'] != $_SERVER['HTTP_USER_AGENT']) {
    session_unset();
    session_destroy();
    header("location: index.php");
} else {
    if(!isset($_SESSION['user'])){
        header("location: index.php");
        exit;
    }
    echo "привет " . $_SESSION['user'];
}
?>
<html>
<form action="logout.php" method="post">
    <button type="submit">Exit</button>
</form>
</html>


