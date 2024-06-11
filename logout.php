<?php
session_start();
setcookie("remember_me", "", time() - 3600, "/");
session_unset();
session_destroy();
header("Location: login.php");
exit();
?>
