<?php
$pageTitle = "CC Donuts | トップページ";
require "header.php";
?>
<!-- メインビジュアル（差し替え推奨） -->
<section class="hero">
	<img src="images/pcHero.jpg" alt="ドーナツと手の写真">
</section>

<!-- 特集 -->
<section class="featureBlocks">
	<div class="featureItem">
		<div class="featureBadge">新商品</div>
		<img src="images/summerCitras.jpg" alt="新商品の画像">
		<p class="featureCaption">サマーシトラス</p>
	</div>
	<div class="featureItem">
		<img src="images/lifeWithDonuts.jpg" alt="ドーナツのある生活">
		<p class="featureCaption">ドーナツのある生活</p>
	</div>
	<div class="featureItem linkAll">
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
			["rank" => 1, "title" => "CCドーナツ 当店オリジナル（5個入り）", "price" => "1,500", "image" => "item1.jpg"],
			["rank" => 2, "title" => "フルーツドーナツセット（12個入り）", "price" => "3,500", "image" => "item2.jpg"],
			["rank" => 3, "title" => "フルーツドーナツセット（14個入り）", "price" => "4,000", "image" => "item3.jpg"],
			["rank" => 4, "title" => "チョコレートデライト（5個入り）", "price" => "1,600", "image" => "item4.jpg"],
			["rank" => 5, "title" => "ベストセレクションボックス（4個入り）", "price" => "1,200", "image" => "item5.jpg"],
			["rank" => 6, "title" => "ストロベリークラッシュ（5個入り）", "price" => "1,800", "image" => "item6.jpg"],
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
<?php require "footer.php"; ?>