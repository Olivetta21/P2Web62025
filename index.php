<?php
session_start();

if (isset($_SESSION["usuario"])) {
    header("Location: pages/home.php");
} else {
    header("Location: pages/login.php");
}

?>