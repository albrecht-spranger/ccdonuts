<?php
// /products.php  （商品一覧）
declare(strict_types=1);

// セッション・ユーティリティ
require_once __DIR__ . '/app/sessionManager.php';
require_once __DIR__ . '/app/commonFunctions.php';
require_once __DIR__ . '/app/dbConnect.php';

$pageTitle = '商品一覧 | CC Donuts';

// DB 取得
$pdo = getDbConnection();
// 必要に応じて WHERE 句を調整（例: published=1 / valid=1 など）
$stmt = $pdo->query("
    SELECT id, name, price, image
    FROM products
    WHERE 1
    ORDER BY id DESC
");
$products = $stmt->fetchAll();

require 'header.php';
?>
<main class="productListPage">
  <div class="breadcrumb">
    <a href="index.php">TOP</a> <span>＞</span> <span>商品一覧</span>
  </div>

  <h1 class="pageTitle">商品一覧</h1>

  <section class="productGrid" aria-label="商品一覧">
    <?php foreach ($products as $p): ?>
      <?php
        $pid   = (int)$p['id'];
        $name  = $p['name'] ?? '';
        $price = (int)($p['price'] ?? 0);
        $img   = trim((string)$p['image'] ?? '');
        // 画像パス（images ディレクトリに配置済みのファイル名想定）
        $imgSrc = $img !== '' ? "images/" . rawurlencode($img) : "images/noimage.jpg";
      ?>
      <article class="productCard">
        <a href="product_detail.php?id=<?php echo $pid; ?>" class="thumb" aria-label="<?php echo h($name); ?>">
          <img src="<?php echo h($imgSrc); ?>"
               alt="<?php echo h($name); ?>"
               width="320" height="320"
               loading="lazy">
        </a>
        <h2 class="productName">
          <a href="product_detail.php?id=<?php echo $pid; ?>"><?php echo h($name); ?></a>
        </h2>
        <p class="productPrice">税込　￥<?php echo number_format($price); ?></p>

        <form action="cart.php" method="post" class="addToCartForm">
          <input type="hidden" name="csrfToken" value="<?php echo h($_SESSION['csrfToken']); ?>">
          <input type="hidden" name="product_id" value="<?php echo $pid; ?>">
          <button type="submit" name="action" value="add" class="btnAddToCart">カートに入れる</button>
        </form>
      </article>
    <?php endforeach; ?>
  </section>
</main>

<?php require 'footer.php'; ?>
