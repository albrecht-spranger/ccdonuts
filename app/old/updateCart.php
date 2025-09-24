<?php
require_once __DIR__."/commonFunctions.php";
require_once __DIR__."/sessionManager.php";
verifyCsrfToken();

$quantities = $_POST["quantities"] ?? [];
foreach($quantities as $pid=>$qty){
    $pid = (int)$pid; $qty = (int)$qty;
    if($pid <= 0) continue;
    if($qty <= 0){
        unset($_SESSION["cart"][$pid]);
    }else{
        $_SESSION["cart"][$pid] = $qty;
    }
}
redirect("../cart.php");
