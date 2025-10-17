<?php

declare(strict_types=1);
require_once __DIR__ . '/app/sessionManager.php';
require_once __DIR__ . '/app/commonFunctions.php';
require_once __DIR__ . '/app/auth.php';

// ---- 人気ランキング（TOP6）をDBから取得 ----
$ranking = [];
try {
	$pdo = getDbConnection();
	$sql = 'SELECT p.id, p.name, p.price, p.image, SUM(d.purchaseCount) AS total_count
        FROM purchase_details d
        JOIN products p ON p.id = d.productId
        GROUP BY p.id, p.name, p.price, p.image
        ORDER BY total_count DESC, p.id ASC
        LIMIT 6';
	$stmt = $pdo->query($sql);                  // これだけで実行
	$ranking = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
	error_log('[index ranking] ' . $e->getMessage());
	// 失敗時は空配列のまま（下で「まだランキングがありません」を表示）
}
?>

<!DOCTYPE html>
<html lang="ja">

<?php
$pageTitle = "CCドーナツ | トップ";
require "head.php";
?>

<body>
	<!-- ヘッダ -->
	<?php require "header.php" ?>

	<!-- <main class="siteMain"> -->
	<main id="pageContent" class="pageContent">
		<!-- ログインユーザ名 -->
		<div class="loginUserContainer noBottomLine">
			<p><?= isLoggedIn() ? getLoginUserName() : 'ようこそ　ゲスト' ?> 様</p>
		</div>

		<!-- ヒーロー画像 -->
		<div class="hero">
			<img src="images/pcHero.jpg" alt="ドーナツと手の写真">
		</div>

		<!-- 特集 -->
		<section class="featureBlocks">
			<div class="featureRow">
				<div class="featureItem">
					<div class="featureBadge">新商品</div>
					<img src="images/summerCitrus.jpg" alt="新商品の画像">
					<p class="newItemCaption">サマーシトラス</p>
				</div>
				<div class="lifeWithDonuts">
					<img src="images/lifeWithDonuts.jpg" alt="ドーナツのある生活">
					<p class="lifeWithDonutsCaption">ドーナツのある生活</p>
				</div>
			</div>
			<div class="link2Products">
				<a href="products.php">
					<img src="images/productBanner.jpg" alt="商品一覧">
				</a>
				<p class="link2ProductsCaption">商品一覧</p>
			</div>
		</section>

		<!-- Philosophy -->
		<section class="philosophyBlock">
			<div class="philosophyCopy">
				<h2 class="philosophyTitle">Philosophy</h2>
				<p class="philosophySub">私たちの信念</p>
				<p class="en">"Creating Connections!"</p>
				<p class="ja">「ドーナツでつながる」</p>
			</div>
		</section>

		<!-- ランキング -->
		<section class="rankingSection">
			<h1 class="sectionTitle">人気ランキング</h1>

			<div class="cardGrid">
				<?php if (empty($ranking)): ?>
					<p>まだランキングがありません。</p>
				<?php else: ?>
					<?php $rank = 1;
					foreach ($ranking as $row): ?>
						<?php
						$pid   = (int)$row['id'];
						$title = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
						$price = (int)$row['price'];
						$img   = htmlspecialchars($row['image'] ?? '', ENT_QUOTES, 'UTF-8');
						?>
						<article class="cardItem">
							<span class="rankBadge"><?= $rank ?></span>
							<a href="productDetail.php?id=<?= $pid ?>">
								<img src="images/<?= $img ?>" alt="<?= $title ?>">
							</a>
							<h4 class="cardTitle">
								<a href="productDetail.php?id=<?= $pid ?>"><?= $title ?></a>
							</h4>
							<p class="cardPrice">税込 ￥<?= number_format($price) ?></p>

							<form action="cart.php" method="post" class="cartButton">
								<?php if (function_exists('csrf_field')): ?>
									<?= csrf_field(); ?>
								<?php else: ?>
									<input type="hidden" name="csrfToken"
										value="<?= htmlspecialchars($_SESSION['csrfToken'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
								<?php endif; ?>
								<input type="hidden" name="productId" value="<?= $pid ?>">
								<input type="hidden" name="quantity" value="1">
								<button type="submit" name="action" value="add" class="cartButton">カートに入れる</button>
							</form>
						</article>
						<?php $rank++; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</section>
	</main>

	<?php require "footer.php"; ?>
</body>

</html>