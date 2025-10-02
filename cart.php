<?php
// /cart.php
declare(strict_types=1);
require_once __DIR__ . '/app/sessionManager.php';
require_once __DIR__ . '/app/commonFunctions.php';
require_once __DIR__ . '/app/auth.php';
require_once __DIR__ . '/app/cartLib.php';

$pageTitle = 'CC Donuts | カート';

// ---- アクション処理 ----
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$action = $_REQUEST['action'] ?? '';

if ($method === 'POST') {
	// CSRF
	if (empty($_POST['csrfToken']) || !hash_equals($_SESSION['csrfToken'] ?? '', (string)$_POST['csrfToken'])) {
		setFlash('error', '不正なリクエストです。もう一度お試しください。');
		header('Location: cart.php');
		exit;
	}
	if ($action === 'add') {
		$pid = (int)($_POST['product_id'] ?? 0);
		$qty = (int)($_POST['qty'] ?? 1);
		if ($pid > 0) {
			cart_add($pid, $qty);
			setFlash('done', 'カートに商品を追加しました。');
		}
		header('Location: cart.php');
		exit;
	} elseif ($action === 'update') {
		$pid = (int)($_POST['product_id'] ?? 0);
		$qty = (int)($_POST['qty'] ?? 1);
		if ($pid > 0) {
			cart_update_qty($pid, $qty);
			setFlash('done', '数量を更新しました。');
		}
		header('Location: cart.php');
		exit;
	} elseif ($action === 'remove') {
		$pid = (int)($_POST['product_id'] ?? 0);
		if ($pid > 0) {
			cart_remove($pid);
			setFlash('done', 'カートから削除しました。');
		}
		header('Location: cart.php');
		exit;
	}
}

// 画面用データ
$cart = cart_get();
$tot  = cart_totals();
$err  = getFlash('error');
$done = getFlash('done');

require 'header.php';
?>
<main class="cartPage">
	<h1 class="pageTitle">カート</h1>

	<?php if ($err): ?><p class="alert error"><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8'); ?></p><?php endif; ?>
	<?php if ($done): ?><p class="alert done"><?= htmlspecialchars($done, ENT_QUOTES, 'UTF-8'); ?></p><?php endif; ?>

	<?php if ($cart): ?>
		<?php /* 上部サマリー（ユーザー名と商品の間） */ ?>
		<section class="cartTopSummary" aria-label="カートサマリー">
			<div class="cartTopSummary__inner">
				<div class="cartTopSummary__text">
					<div class="cartTopSummary__items">現在　商品<?php echo (int)$tot['items']; ?>点</div>
					<div class="cartTopSummary__subtotal">ご注文小計：<span class="tax">税込</span> ￥<?php echo number_format($tot['subtotal']); ?></div>
				</div>
				<a class="btnPrimary cartTopSummary__cta" href="checkout.php">購入確認へ進む</a>
			</div>
		</section>
	<?php endif; ?>

	<?php if (!$cart): ?>
		<p>カートに商品がありません。</p>
		<p><a class="btnSecondary" href="products.php">買い物を続ける</a></p>
	<?php else: ?>

		<section class="cartList" aria-label="カート商品一覧">
			<?php foreach ($cart as $row): ?>
				<?php
				$pid   = (int)$row['id'];
				$name  = (string)$row['name'];
				$price = (int)$row['price'];
				$qty   = (int)$row['qty'];
				$img   = trim((string)($row['image'] ?? ''));
				$imgSrc = $img !== '' ? "images/" . rawurlencode($img) : "images/noimage.jpg";
				?>
				<article class="cartItem">
					<a href="product_detail.php?id=<?php echo $pid; ?>" class="thumb" aria-label="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>">
						<img src="<?= htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($_SESSION['csrfToken'], ENT_QUOTES, 'UTF-8'); ?>" width="120" height="120" loading="lazy">
					</a>

					<div class="meta">
						<h2 class="name">
							<a href="product_detail.php?id=<?php echo $pid; ?>"><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></a>
						</h2>
						<p class="unitPrice">個 <span class="tax">税込</span> ￥<?php echo number_format($price); ?></p>
					</div>

					<form class="qtyForm" method="post" action="cart.php">
						<input type="hidden" name="csrfToken" value="<?= htmlspecialchars($_SESSION['csrfToken'], ENT_QUOTES, 'UTF-8'); ?>">
						<input type="hidden" name="action" value="update">
						<input type="hidden" name="product_id" value="<?php echo $pid; ?>">
						<label>数量
							<input type="number" name="qty" min="1" value="<?php echo $qty; ?>" inputmode="numeric" pattern="[0-9]*">
						</label>
						<button type="submit" class="btnSmall">再計算</button>
					</form>

					<form class="removeForm" method="post" action="cart.php" onsubmit="return confirm('削除しますか？');">
						<input type="hidden" name="csrfToken" value="<?= htmlspecialchars($_SESSION['csrfToken'], ENT_QUOTES, 'UTF-8'); ?>">
						<input type="hidden" name="action" value="remove">
						<input type="hidden" name="product_id" value="<?php echo $pid; ?>">
						<button type="submit" class="linkDanger">削除する</button>
					</form>
				</article>
			<?php endforeach; ?>
		</section>

		<section class="cartSummary">
			<div class="sumLine">
				<div>
					<a class="btnSecondary" href="products.php">買い物を続ける</a>
				</div>
				<div class="totals">
					<div class="subtotal">ご注文小計：<span class="tax">税込</span> ￥<?php echo number_format($tot['subtotal']); ?></div>
					<div class="items">現在　商品<?php echo (int)$tot['items']; ?>点</div>
				</div>
			</div>

			<div class="actions">
				<a class="btnPrimary" href="checkout.php">購入確認へ進む</a>
			</div>
		</section>

	<?php endif; ?>
</main>
<?php require 'footer.php'; ?>