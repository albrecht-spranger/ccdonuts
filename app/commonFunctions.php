<?php
function h($str){return htmlspecialchars($str,ENT_QUOTES,"UTF-8");}
function redirect($path){header("Location: ".$path);exit;}
function postParam($key){return $_POST[$key] ?? null;}
function verifyCsrfToken(){
    if(!isset($_POST["csrfToken"]) || !isset($_SESSION["csrfToken"]) || $_POST["csrfToken"] !== $_SESSION["csrfToken"]){
        http_response_code(400);
        exit("Invalid CSRF token.");
    }
}
?>