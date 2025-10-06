<?php
// cart.php  - カート更新用（表示なし）
declare(strict_types=1);
require_once __DIR__ . '/app/sessionManager.php';
require_once __DIR__ . '/app/commonFunctions.php';
require_once __DIR__ . '/app/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	redirect('cartView.php'); // GET直叩きはビューへ
	exit;
}

// CSRFチェック
// $postedToken = $_POST['csrfToken'] ?? '';
// $sessionToken = $_SESSION['csrfToken'] ?? '';
// if (!hash_equals((string)$sessionToken, (string)$postedToken)) {
// 	setFlash('error', '不正なリクエストです（CSRF）。');
// 	redirect('cartView.php', 303);
// 	exit;
// }

// 入力取り出し
$action     = (string)($_POST['action'] ?? 'add');            // add / update / remove / clear
$productId  = (int)($_POST['productId'] ?? 0);
$quantity   = (int)($_POST['quantity'] ?? 1);

// 量の妥当性（例：1〜99に丸め）
if ($quantity < 0) $quantity = 0;
if ($quantity > 99) $quantity = 99;

// DBから商品を必ず取得（価格等は“絶対に”クライアントから信用しない）
$pdo = getDbConnection();

if ($action !== 'clear') {
	$stmt = $pdo->prepare("SELECT id, name, price, image
        FROM products
        WHERE id = ? LIMIT 1
    ");
	$stmt->execute([$productId]);
	$product = $stmt->fetch(PDO::FETCH_ASSOC);

	if (!$product) {
		setFlash('error', '指定の商品が見つかりません。');
		redirect('cartView.php', 303);
		exit;
	}
}

// セッション上のカート構造
// $_SESSION['cart'] = [ 'items' => [productId => ['id','name','price','image','qty']], 'updatedAt' => int ]
$cart = $_SESSION['cart'] ?? ['items' => [], 'updatedAt' => time()];
$items = &$cart['items'];

switch ($action) {
	case 'add':
		// 既に入っていれば加算、なければ新規
		if (isset($items[$product['id']])) {
			$items[$product['id']]['qty'] = min(99, (int)$items[$product['id']]['qty'] + max(1, $quantity));
		} else {
			$items[$product['id']] = [
				'id'    => (int)$product['id'],
				'name'  => (string)$product['name'],
				'price' => (int)$product['price'],          // 税込価格ならそのまま、税別なら後で計算
				'image' => (string)($product['image'] ?? ''),
				'qty'   => max(1, $quantity),
			];
		}
		setFlash('success', 'カートに商品を追加しました。');
		break;

	case 'update':
		if ($productId <= 0 || !isset($items[$productId])) {
			setFlash('error', '更新対象の商品がカートにありません。');
			redirect('cartView.php', 303);
			exit;
		}
		if ($quantity === 0) {
			unset($items[$productId]);
			setFlash('success', '商品をカートから削除しました。');
		} else {
			$items[$productId]['qty'] = $quantity;
			// 価格改ざん対策：サーバ側価格で都度上書きしておくと安心
			$items[$productId]['price'] = (int)$product['price'];
			setFlash('success', '数量を更新しました。');
		}
		break;

	case 'remove':
		if (isset($items[$productId])) {
			unset($items[$productId]);
			setFlash('success', '商品をカートから削除しました。');
		}
		break;

	case 'clear':
		$items = [];
		setFlash('success', 'カートを空にしました。');
		break;

	default:
		setFlash('error', '不明な操作です。');
		break;
}

$cart['updatedAt'] = time();
$_SESSION['cart'] = $cart;

redirect('cartView.php', 303); // PRG: 303 See Other
exit;
