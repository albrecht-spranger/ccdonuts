<?php if(!isset($pageTitle)){$pageTitle="CC Donuts";} ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo h($pageTitle); ?></title>
<link rel="stylesheet" href="styles/style.css">
</head>
<body>
<header class="container">
  <h1>CC Donuts</h1>
  <nav>
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="products.php">Products</a></li>
      <li><a href="cart.php">Cart</a></li>
    </ul>
  </nav>
</header>
