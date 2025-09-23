<?php
require_once __DIR__."/commonFunctions.php";
require_once __DIR__."/sessionManager.php";
$_SESSION["cart"] = [];
redirect("../cart.php");
