<?php
require_once __DIR__."/app/commonFunctions.php";
require_once __DIR__."/app/sessionManager.php";
$pageTitle="CC Donuts | トップページ";
require "header.php";
?>
<main class="container">
  <h2>ようこそ、CC Donutsへ！</h2>
  <p>美味しいドーナツを取り揃えています。</p>
  <p><a href="products.php">商品一覧を見る</a></p>
</main>
<?php require "footer.php"; ?>
