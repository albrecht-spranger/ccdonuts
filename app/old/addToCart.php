<?php
require_once __DIR__."/commonFunctions.php";
require_once __DIR__."/sessionManager.php";
verifyCsrfToken();

$productId = (int)(postParam("productId") ?? 0);
$quantity = max(1, (int)(postParam("quantity") ?? 1));

if($productId > 0){
    $_SESSION["cart"][$productId] = ($_SESSION["cart"][$productId] ?? 0) + $quantity;
}
redirect("../cart.php");
