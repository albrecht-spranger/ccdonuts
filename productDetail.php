<?php

declare(strict_types=1);
require_once __DIR__ . '/app/sessionManager.php';
require_once __DIR__ . '/app/commonFunctions.php';
require_once __DIR__ . '/app/auth.php';
require_once __DIR__ . '/db.php';

// productDetail.php
// 個々のドーナツ詳細ページ（添付PDF準拠）
// 依存: db.php, header.php, footer.php, productsテーブル（id, name, price, introduction, image_url など）

$breadcrumbs = [
	['label' => 'TOP', 'url' => 'index.php'],
	['label' => '商品一覧', 'url' => 'products.php'],
	['label' => '商品詳細（仮）'],
];

// ===== 入力取得 =====
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
	http_response_code(400);
	echo '不正な商品IDです。';
	exit;
}

// ===== 商品取得 =====
$pdo = getDbConnection();
$sql = $pdo->prepare(
	'SELECT id, name, price, introduction, image_url
       FROM products
      WHERE id = :id AND (valid = 1 OR valid IS NULL)
      LIMIT 1'
);
$sql->execute([':id' => $id]);
$product = $sql->fetch(PDO::FETCH_ASSOC);

if (!$product) {
	http_response_code(404);
	echo '指定の商品が見つかりませんでした。';
	exit;
}

// フィールド整形
$prod_id    = (int)$product['id'];
$prod_name  = (string)($product['name'] ?? '商品名未設定');
$prod_price = is_null($product['price']) ? null : (int)$product['price'];
$prod_desc  = (string)($product['introduction'] ?? '');
$prod_img   = (string)($product['image_url'] ?? 'images/noimage.png');

// 税込表示（PDF準拠）
$price_label = is_null($prod_price) ? '価格未定' : ('税込　￥' . number_format($prod_price));
?>

<style>
	/* ===== ページ固有スタイル（PDFの情報構造を再現） ===== */
	:root {
		--cc-black: #111;
		--cc-gray: #666;
		--cc-light: #f7f7f7;
		--cc-accent: #c83;
	}

	.detail-hero {
		background: var(--cc-light);
		padding: 24px 0;
	}

	.detail-wrap {
		width: min(1100px, 92%);
		margin: 0 auto;
	}

	/* グリッド */
	.detail-grid {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 32px;
	}

	@media (max-width: 900px) {
		.detail-grid {
			grid-template-columns: 1fr;
		}
	}

	/* 画像 */
	.detail-image {
		background: #fff;
		border: 1px solid #eee;
		border-radius: 12px;
		overflow: hidden;
	}

	.detail-image figure {
		margin: 0;
	}

	.detail-image img {
		display: block;
		width: 100%;
		aspect-ratio: 1 / 1;
		object-fit: cover;
		object-position: center;
	}

	/* 情報 */
	.detail-info h1 {
		font-size: clamp(1.3rem, 2.2vw, 1.8rem);
		line-height: 1.4;
		margin: 0 0 12px;
	}

	.price-line {
		font-weight: 700;
		font-size: 1.4rem;
		margin: 12px 0 16px;
	}

	.qty-row {
		display: flex;
		align-items: center;
		gap: 10px;
		margin: 8px 0 18px;
	}

	.qty-row label {
		font-size: 1rem;
		color: var(--cc-black);
	}

	.qty-input {
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.qty-input select {
		appearance: none;
		-webkit-appearance: none;
		-moz-appearance: none;
		padding: 8px 12px;
		border: 1px solid #ccc;
		border-radius: 8px;
		background: #fff;
		font-size: 1rem;
	}

	.qty-input .unit {
		font-size: 1rem;
	}

	.cart-row {
		display: flex;
		gap: 14px;
		align-items: center;
	}

	.cart-btn {
		display: inline-block;
		border: none;
		border-radius: 999px;
		padding: 12px 24px;
		font-weight: 700;
		cursor: pointer;
		background: #000;
		color: #fff;
		font-size: 1rem;
		letter-spacing: .05em;
		transition: transform .05s ease;
	}

	.cart-btn:active {
		transform: translateY(1px);
	}

	.desc-box {
		margin-top: 20px;
		background: #fff;
		border: 1px solid #eee;
		border-radius: 12px;
		padding: 16px 18px;
		line-height: 1.9;
		white-space: pre-line;
		/* 改行を保持（PDFの縦組み風原稿に配慮） */
	}
</style>

<!DOCTYPE html>
<html lang="ja">

<body>

	<?php require __DIR__ . '/header.php'; ?>

	<main>
		<section class="detail-hero">
			<div class="detail-wrap">
				<div class="detail-grid">
					<!-- 商品画像 -->
					<div class="detail-image">
						<figure>
							<img src="<?php echo htmlspecialchars($prod_img, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($prod_name, ENT_QUOTES, 'UTF-8'); ?>">
						</figure>
					</div>

					<!-- 商品情報 -->
					<div class="detail-info">
						<h1><?php echo htmlspecialchars($prod_name, ENT_QUOTES, 'UTF-8'); ?></h1>

						<div class="price-line"><?php echo htmlspecialchars($price_label, ENT_QUOTES, 'UTF-8'); ?></div>

						<form action="cart_add.php" method="post" class="buy-form">
							<input type="hidden" name="product_id" value="<?php echo $prod_id; ?>">
							<!-- CSRF対策トークン（任意: 実装済みなら埋めてください） -->
							<?php /* <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf'] ?? '', ENT_QUOTES, 'UTF-8') ?>"> */ ?>

							<div class="qty-row">
								<label for="qty">カートに入れる</label>
								<div class="qty-input">
									<select id="qty" name="qty" aria-label="数量">
										<?php for ($i = 1; $i <= 12; $i++): ?>
											<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
										<?php endfor; ?>
									</select>
									<span class="unit">個</span>
								</div>
							</div>

							<div class="cart-row">
								<button type="submit" class="cart-btn">カートに入れる</button>
								<a href="cart.php" class="to-cart">カート</a>
								<a href="login.php" class="to-login">ログイン</a>
							</div>
						</form>

						<div class="desc-box">
							<?php echo nl2br(htmlspecialchars($prod_desc, ENT_QUOTES, 'UTF-8')); ?>
						</div>
					</div>
				</div>

			</div>
		</section>
	</main>

	<?php require __DIR__ . '/footer.php'; ?>

</body>

</html>