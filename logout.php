<?php

session_start();
session_unset();
session_destroy();

if (isset($_COOKIE['remember_me'])) {
    setcookie('remember_me', '', time() - (86400 * 30), "/");
}

header("Location: index.php");
exit();
