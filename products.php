<?php
// /products.php  （商品一覧）
declare(strict_types=1);
require_once __DIR__ . '/app/sessionManager.php';
require_once __DIR__ . '/app/commonFunctions.php';
require_once __DIR__ . '/app/auth.php';

// DB 取得
try {
	$pdo = getDbConnection();
	$stmt = $pdo->query("SELECT id, name, price, image, isNew, isSet
		FROM products WHERE 1 ORDER BY id ASC
	");
	$allPproducts = $stmt->fetchAll();
} catch (Throwable $e) {
	error_log("[DB Error] " . $e->getMessage());
	echo 'エラーが発生しました。';
	exit;
}
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

// 商品1カードを表示
require_once __DIR__ . '/app/renderProductCard.php';
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