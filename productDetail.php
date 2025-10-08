<?php
// productDetail.php
declare(strict_types=1);
require_once __DIR__ . '/app/sessionManager.php';
require_once __DIR__ . '/app/commonFunctions.php';
require_once __DIR__ . '/app/auth.php';

// ===== 入力取得 =====
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// ===== DB取得 =====
$pdo = getDbConnection();
$stmt = $pdo->prepare("SELECT
		id,
		name,
		price,
		introduction,
		image
	FROM products
	WHERE id = :id
	LIMIT 1
");
$stmt->execute([':id' => $id]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$p) {
	http_response_code(404);
	echo '指定の商品が見つかりませんでした。';
	exit;
}

// ===== 整形 =====
$pid       = (int)$p['id'];
$name      = (string)($p['name'] ?? '商品名未設定');
$price     = isset($p['price']) ? (int)$p['price'] : null;
$desc      = (string)($p['introduction'] ?? '');
$img       = trim((string)($p['image'] ?? ''));
$imgSrc    = $img !== '' ? "images/" . rawurlencode($img) : "images/noimage.jpg";
$priceText = is_null($price) ? '価格未定' : ('税込　￥' . number_format($price));

// お気に入り反映のためログイン状態の取得から
$customerId = null;
$isFavorited = false;
$isLoggedIn = isLoggedIn();
if ($isLoggedIn) {
	$customerId = (int)$_SESSION['customer']['id'];
	// 初期のお気に入り状態
	$stmtFav = $pdo->prepare(
		"SELECT 1 FROM favorites WHERE customerId = :cid AND productId = :pid LIMIT 1"
	);
	$stmtFav->execute([':cid' => $customerId, ':pid' => $pid]);
	$isFavorited = (bool)$stmtFav->fetchColumn();
}
?>

<!DOCTYPE html>
<html lang="ja">
<?php
$pageTitle = "CCドーナツ | " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
require __DIR__ . '/head.php';
?>

<body>
	<?php require __DIR__ . '/header.php'; ?>

	<main>
		<!-- パンくずリスト -->
		<?php
		$breadcrumbs = [
			['label' => 'TOP',   'url' => 'index.php'],
			['label' => '商品一覧', 'url' => 'products.php'],
			['label' => $name,    'url' => null],
		];
		require __DIR__ . '/breadcrumbs.php';
		?>

		<!-- ログインユーザ名 -->
		<div class="loginUserContainer">
			<p><?= isLoggedIn() ? getLoginUserName() : 'ようこそ　ゲスト' ?> 様</p>
		</div>

		<section class="detailSection">
			<!-- 画像 -->
			<div class="detailImage">
				<img src="<?= htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8'); ?>"
					alt="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>"
					loading="lazy">
			</div>

			<!-- 情報 -->
			<div class="detailInfo">
				<h1 class="detailTitle"><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></h1>

				<div class="descBox">
					<?= nl2br(htmlspecialchars($desc, ENT_QUOTES, 'UTF-8')); ?>
				</div>
				<div class="priceLine">
					<p><?= htmlspecialchars($priceText, ENT_QUOTES, 'UTF-8'); ?></p>
				</div>

				<form action="cart.php" method="post" class="buyForm">
					<!-- <input type="hidden" name="csrfToken" value="<?= htmlspecialchars($_SESSION['csrfToken'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"> -->
					<input type="hidden" name="productId" value="<?= $pid ?>">
					<input class="qtyBox" type="number" name="quantity" min="0" max="99" value="1">
					<span class="qtyLabel">個</span>
					<button type="submit" name="action" value="add" class="addToCartBtn">カートに入れる</button>
					<button type="button" name="favorite" id="favoriteBtn" data-product-id="<?= $pid ?>">
						<svg id="notFavoritedHeart" class="icon <?= $isFavorited ? '' : 'show' ?>" viewBox="0 0 24 24" width="24" height="24">
							<path
								d="M12.1 8.64l-.1.1-.11-.1C10.14 6.77 7.39 6.5 5.5 8.4 3.42 10.47 3.43 13.71 5.5 15.78l6.45 6.36c.03.03.07.05.11.07.04.02.09.03.14.03s.1-.01.14-.03c.04-.02.08-.04.11-.07l6.45-6.36c2.07-2.07 2.08-5.31.01-7.38-1.89-1.9-4.64-1.63-6.5.24z"
								fill="none"
								stroke="currentColor"
								stroke-width="2"
								stroke-linecap="round"
								stroke-linejoin="round" />
						</svg>
						<svg id="favoritedHeart" class="icon  <?= $isFavorited ? 'show' : '' ?>" viewBox="0 0 24 24" width="24" height="24">
							<path
								d="M12.1 8.64l-.1.1-.11-.1C10.14 6.77 7.39 6.5 5.5 8.4 3.42 10.47 3.43 13.71 5.5 15.78l6.45 6.36c.03.03.07.05.11.07.04.02.09.03.14.03s.1-.01.14-.03c.04-.02.08-.04.11-.07l6.45-6.36c2.07-2.07 2.08-5.31.01-7.38-1.89-1.9-4.64-1.63-6.5.24z"
								fill="currentColor"
								stroke="currentColor"
								stroke-width="2"
								stroke-linecap="round"
								stroke-linejoin="round" />
						</svg>
					</button>
				</form>
			</div>
		</section>
	</main>

	<?php require __DIR__ . '/footer.php'; ?>

	<!-- お気に入り操作のためのJavaScript -->
	<script src="scripts/productDetail.js"></script>
</body>

</html>