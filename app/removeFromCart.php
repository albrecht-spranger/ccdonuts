<?php
require_once __DIR__."/commonFunctions.php";
require_once __DIR__."/sessionManager.php";

if($_SERVER["REQUEST_METHOD"]==="POST"){
    $pid = (int)($_POST["productId"] ?? 0);
    if($pid>0 && isset($_SESSION["cart"][$pid])){
        unset($_SESSION["cart"][$pid]);
    }
}
redirect("../cart.php");
