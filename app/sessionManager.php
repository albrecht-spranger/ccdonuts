<?php
session_start();
if(!isset($_SESSION["csrfToken"])){
    $_SESSION["csrfToken"] = bin2hex(random_bytes(32));
}
if(!isset($_SESSION["cart"])){
    $_SESSION["cart"] = []; // [ productId => quantity ]
}
?>