<?php
// /products.php  （商品一覧）
declare(strict_types=1);
require_once __DIR__ . '/app/sessionManager.php';
require_once __DIR__ . '/app/commonFunctions.php';
require_once __DIR__ . '/app/auth.php';

// DB 取得
$pdo = getDbConnection();
// 必要に応じて WHERE 句を調整（例: published=1 / valid=1 など）
$stmt = $pdo->query("
    SELECT id, name, price, image, isNew, isSet
    FROM products
    WHERE 1
    ORDER BY id ASC
");
$allPproducts = $stmt->fetchAll();

// isSetで振り分け
$productsMain = []; // isSet=0
$productsSet  = []; // isSet=1
foreach ($allPproducts as $p) {
	if ((int)$p['isSet'] === 1) {
		$productsSet[] = $p;
	} else {
		$productsMain[] = $p;
	}
}

/**
 * 商品カード群を描画する関数
 *
 * @param array $products  商品配列
 * @return void
 */
function renderProductCards(array $products): void
{
	foreach ($products as $p):
		$pid   = (int)$p['id'];
		$name  = (string)($p['name'] ?? '');
		$price = (int)($p['price'] ?? 0);
		$img   = trim((string)($p['image'] ?? ''));
		$imgSrc = $img !== '' ? "images/" . rawurlencode($img) : "images/noimage.jpg";
		$isNew = (int)($p['isNew'] ?? 0);
?>
		<article class="cardItem">
			<a href="productDetail.php?id=<?= $pid ?>" class="thumb" aria-label="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>">
				<img src="<?= htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8'); ?>"
					alt="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>"
					loading="lazy">
			</a>
			<h4 class="cardTitle">
				<a href="productDetail.php?id=<?= $pid ?>">
					<?= ($isNew ? '【新作】' : '') . htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>
				</a>
			</h4>
			<p class="cardPrice">税込　￥<?= number_format($price) ?></p>

			<form action="cart.php" method="post" class="cartButton">
				<input type="hidden" name="csrfToken" value="<?= htmlspecialchars($_SESSION['csrfToken'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
				<input type="hidden" name="product_id" value="<?= $pid ?>">
				<input type="hidden" name="quantity" value="1">
				<button type="submit" name="action" value="add" class="btnAddToCart">カートに入れる</button>
			</form>
		</article>
<?php
	endforeach;
}
?>

<!DOCTYPE html>
<html lang="ja">

<?php
$pageTitle = "CCドーナツ | 商品一覧";
require "head.php";
?>

<body>
	<!-- ヘッダ -->
	<?php require "header.php" ?>

	<main>
		<!-- パンくずリスト -->
		<?php
		$breadcrumbs = [
			['label' => 'TOP', 'url' => 'index.php'],
			['label' => '商品一覧', 'url' => null],
		];
		require "breadcrumbs.php"
		?>

		<!-- ログインユーザ名 -->
		<div class="loginUserContainer">
			<p><?= isLoggedIn() ? getLoginUserName() : 'ようこそ　ゲスト' ?> 様</p>
		</div>

		<section class="productsSection">
			<h1 class="sectionTitle">商品一覧</h1>

			<section class="productsSubSection">
				<h2 class="productsSubTitle">メインメニュー</h2>
				<div class="cardGrid">
					<?php renderProductCards($productsMain); ?>
				</div>
			</section>
			<section class="productsSubSection">
				<h2 class="productsSubTitle">バラエティセット</h2>
				<div class="cardGrid">
					<?php renderProductCards($productsSet); ?>
				</div>
			</section>
		</section>
	</main>

	<!-- フッタ -->
	<?php require "footer.php"; ?>
</body>

</html>