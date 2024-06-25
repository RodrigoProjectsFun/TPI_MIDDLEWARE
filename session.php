<?php
session_start();

function checkLogin()
{
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit();
    }
}
?>
