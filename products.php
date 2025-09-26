<?php
// /products.php  （商品一覧）
declare(strict_types=1);

$pageTitle = 'CCドーナツ | 商品一覧';
$breadcrumbs = [
	['label' => 'TOP', 'url' => 'index.php'],
	['label' => '商品一覧', 'url' => null]
];
require 'header.php';

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
?>
<main class="productListPage">
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
				<a href="productDetail.php?id=<?php echo $pid; ?>" class="thumb" aria-label="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>">
					<img src="<?= htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8'); ?>"
						alt="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>"
						width="320" height="320"
						loading="lazy">
				</a>
				<h2 class="productName">
					<a href="productDetail.php?id=<?php echo $pid; ?>"><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></a>
				</h2>
				<p class="productPrice">税込　￥<?php echo number_format($price); ?></p>

				<form action="cart.php" method="post" class="addToCartForm">
					<input type="hidden" name="csrfToken" value="<?= htmlspecialchars($_SESSION['csrfToken'], ENT_QUOTES, 'UTF-8'); ?>">
					<input type="hidden" name="product_id" value="<?php echo $pid; ?>">
					<button type="submit" name="action" value="add" class="btnAddToCart">カートに入れる</button>
				</form>
			</article>
		<?php endforeach; ?>
	</section>
</main>

<?php require 'footer.php'; ?>