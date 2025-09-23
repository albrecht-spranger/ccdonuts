<?php
require_once __DIR__."/app/commonFunctions.php";
require_once __DIR__."/app/sessionManager.php";
require_once __DIR__."/app/dbConnect.php";
$pageTitle="CC Donuts | 商品一覧";
require "header.php";

$db = getDbConnection();
$stmt = $db->query("SELECT id, name, price, introduction, is_new FROM products ORDER BY id ASC");
$products = $stmt->fetchAll();
?>
<main class="container">
  <h2>商品一覧</h2>
  <?php foreach($products as $p): ?>
    <div class="product">
      <h3><?php echo h($p["name"]); ?> <?php if((int)$p["is_new"]===1): ?><span class="badge">NEW</span><?php endif; ?></h3>
      <p><?php echo nl2br(h($p["introduction"])); ?></p>
      <p>価格：<?php echo number_format((int)$p["price"]); ?> 円</p>
      <form action="app/addToCart.php" method="post">
        <input type="hidden" name="csrfToken" value="<?php echo h($_SESSION['csrfToken']); ?>">
        <input type="hidden" name="productId" value="<?php echo (int)$p['id']; ?>">
        <label>数量：<input type="number" name="quantity" min="1" value="1" required></label>
        <button type="submit">カートに入れる</button>
      </form>
    </div>
  <?php endforeach; ?>
</main>
<?php require "footer.php"; ?>
