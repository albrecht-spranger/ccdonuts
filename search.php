<?php
// search.php （商品検索：introductionをLIKE検索）
declare(strict_types=1);
require_once __DIR__ . '/app/sessionManager.php';
require_once __DIR__ . '/app/commonFunctions.php';
require_once __DIR__ . '/app/auth.php';

// ---------- 検索語の取得・整形 ----------
$raw = (string) ($_GET['q'] ?? '');
$q = trim(mb_convert_kana($raw, 's')); // 全角スペース→半角、連続空白の正規化
$terms = array_values(array_filter(preg_split('/[\s　]+/u', $q), fn($t) => $t !== ''));

// ---------- DB 検索 ----------
$products = [];
if (!empty($terms)) {
	$pdo = getDbConnection();
	$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

	// すべての語をANDで部分一致（introductionのみ検索）
	$wheres = [];
	$params = [];
	foreach ($terms as $t) {
		$wheres[] = 'introduction LIKE ?';
		$params[] = '%' . $t . '%';
	}

	// 必要に応じて WHERE に published=1 等を追加してください
	$sql = "SELECT id, name, price, image, isNew, isSet
        FROM products
        WHERE " . implode(' AND ', $wheres) . "
        ORDER BY id ASC
    ";
	$stmt = $pdo->prepare($sql);
	$stmt->execute($params);
	$products = $stmt->fetchAll();
}

// ---------- 共通：商品カード描画 ----------
require_once __DIR__ . '/app/renderProductCard.php';
?>

<!DOCTYPE html>
<html lang="ja">
<?php
$pageTitle = "CCドーナツ | 検索結果";
require "head.php";
?>

<body>
	<!-- ヘッダ -->
	<?php require "header.php"; ?>

	<main>
		<!-- パンくず -->
		<?php
		$breadcrumbs = [
			['label' => 'TOP', 'url' => 'index.php'],
			['label' => '検索結果', 'url' => null],
		];
		require "breadcrumbs.php";
		?>

		<!-- ログインユーザ名 -->
		<div class="loginUserContainer">
			<p><?= isLoggedIn() ? getLoginUserName() : 'ようこそ　ゲスト' ?> 様</p>
		</div>

		<section class="productsSection">
			<h1 class="sectionTitle">検索結果</h1>

			<div class="productsSubSection">
				<?php if ($q === '' || empty($terms)): ?>
					<p class="oops">キーワードを入力してください。</p>
				<?php else: ?>
					<p class="searchText">
						「<strong><?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?></strong>」の検索結果：
						<strong><?= number_format(count($products)); ?></strong>件
					</p>

					<?php if (count($products) > 0): ?>
						<div class="cardGrid">
							<?php renderProductCards($products); ?>
						</div>
					<?php else: ?>
						<p class="oops">該当する商品は見つかりませんでした。</p>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</section>
	</main>

	<!-- フッタ -->
	<?php require "footer.php"; ?>
</body>

</html>