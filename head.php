<?php
declare(strict_types=1);

// PHPの共通関数群
require_once __DIR__ . '/app/sessionManager.php';
require_once __DIR__ . '/app/commonFunctions.php';
require_once __DIR__ . '/app/auth.php';
?>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= (!isset($pageTitle) || $pageTitle === "") ? "CCドーナツ" : $pageTitle ?></title>
	<link rel="stylesheet" href="styles/reset.css">
	<link rel="stylesheet" href="styles/style.css">
	<link rel="stylesheet" href="styles/index.css">
	<link rel="stylesheet" href="styles/products.css">
	<link rel="stylesheet" href="styles/productDetail.css">
	<link rel="stylesheet" href="styles/cart.css">
	<link rel="stylesheet" href="styles/login.css">
	<link rel="stylesheet" href="styles/registerStyles.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
</head>