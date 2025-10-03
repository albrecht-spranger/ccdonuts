<?php

declare(strict_types=1);
require_once __DIR__ . '/app/sessionManager.php';
require_once __DIR__ . '/app/commonFunctions.php';
require_once __DIR__ . '/app/auth.php';
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
		<section class="hero">
			<img src="images/pcHero.jpg" alt="ドーナツと手の写真">
		</section>

		<!-- 特集 -->
		<section class="featureBlocks">
			<div class="featureRow">
				<div class="featureItem">
					<div class="featureBadge">新商品</div>
					<img src="images/summerCitrus.jpg" alt="新商品の画像">
					<p class="featureCaption">サマーシトラス</p>
				</div>
				<div class="featureItem">
					<img src="images/lifeWithDonuts.jpg" alt="ドーナツのある生活">
					<p class="featureCaption">ドーナツのある生活</p>
				</div>
			</div>
			<div class="featureItem linkAll wide">
				<img src="images/productBanner.jpg" alt="商品一覧">
				<p class="featureCaption">商品一覧</p>
			</div>
		</section>

		<!-- Philosophy -->
		<section class="philosophyBlock">
			<h2 class="philosophyTitle">Philosophy</h2>
			<p class="philosophySub">私たちの信念</p>
			<blockquote class="philosophyCopy">
				<span class="en">"Creating Connections!"</span>
				<span class="ja">「ドーナツでつながる」</span>
			</blockquote>
		</section>

		<!-- ランキング -->
		<section class="rankingSection">
			<h2 class="sectionTitle">人気ランキング</h2>

			<div class="cardGrid">
				<?php
				// 実装時はDBから取得してループに差し替え
				$items = [
					["rank" => 1, "title" => "CCドーナツ 当店オリジナル（5個入り）", "price" => "1,500", "image" => "original.jpg"],
					["rank" => 2, "title" => "フルーツドーナツセット（12個入り）", "price" => "3,500", "image" => "fruitDonutAssortment.jpg"],
					["rank" => 3, "title" => "フルーツドーナツセット（14個入り）", "price" => "4,000", "image" => "fruitDonutSet.jpg"],
					["rank" => 4, "title" => "チョコレートデライト（5個入り）", "price" => "1,600", "image" => "chocolateDelight.jpg"],
					["rank" => 5, "title" => "ベストセレクションボックス（4個入り）", "price" => "1,200", "image" => "bestSelectionBox.jpg"],
					["rank" => 6, "title" => "ストロベリークラッシュ（5個入り）", "price" => "1,800", "image" => "strawberryCrush.jpg"],
				];
				foreach ($items as $it):
				?>
					<article class="cardItem">
						<span class="rankBadge"><?php echo $it["rank"]; ?></span>
						<img src="images/<?php echo $it['image']; ?>" alt="<?php echo htmlspecialchars($it['title'], ENT_QUOTES, 'UTF-8'); ?>">
						<h3 class="cardTitle"><?php echo $it["title"]; ?></h3>
						<p class="cardPrice">税込 ￥<?php echo $it["price"]; ?></p>
						<button class="cartButton" type="button">カートに入れる</button>
					</article>
				<?php endforeach; ?>
			</div>
		</section>
	</main>

	<?php require "footer.php"; ?>
</body>

</html>