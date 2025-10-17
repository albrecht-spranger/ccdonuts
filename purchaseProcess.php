<?php
// purchaseProcess.php - 「購入を確定する」POST専用（PRG: 303 → purchaseDone.php）
declare(strict_types=1);

require_once __DIR__ . '/app/sessionManager.php';
require_once __DIR__ . '/app/commonFunctions.php'; // setFlash(), redirect(), csrf系
require_once __DIR__ . '/app/auth.php';            // isLoggedIn()

// 1) メソッド検証
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	redirect('purchaseConfirm.php', 303);
}

// 2) CSRF 検証（共通の check_csrf があれば使用。なければフォールバック）
// $postedToken = $_POST['csrf_token'] ?? $_POST['csrfToken'] ?? '';
// if (!check_csrf($postedToken)) {
// setFlash('error', '不正なリクエスト（CSRF）です。');
// redirect('purchaseConfirm.php', 303);
// }

// 3) ログイン検証
if (!isLoggedIn()) {
	setFlash('error', '購入手続きにはログインが必要です。');
	redirect('cartView.php', 303);
}

$customerId = (int)($_SESSION['customer']['id'] ?? 0);
if ($customerId <= 0) {
	setFlash('error', '顧客情報が見つかりません。再度ログインしてください。');
	redirect('cartView.php', 303);
}

// 4) カート検証・正規化
$cart  = $_SESSION['cart'] ?? ['items' => []];
$items = $cart['items'] ?? [];
if (empty($items)) {
	setFlash('error', 'カートが空です。');
	redirect('cartView.php', 303);
}

$lines = []; // productId => qty（同一IDは合算）
foreach ($items as $it) {
	$pid = (int)($it['id'] ?? 0);
	$qty = (int)($it['qty'] ?? $it['quantity'] ?? 0);
	if ($pid <= 0 || $qty <= 0) continue;
	$qty = max(1, min(99, $qty)); // 1〜99に丸め（必要に応じて調整）
	$lines[$pid] = ($lines[$pid] ?? 0) + $qty;
}
if (empty($lines)) {
	setFlash('error', '購入対象の商品が不正です。');
	redirect('cartView.php', 303);
}

// 5) 価格再計算（サーバ側を正：products.price）
try {
	$pdo = getDbConnection();

	$ids = array_keys($lines);
	if (empty($ids)) {
		throw new LogicException('購入対象の商品が不正です。'); // 業務エラーは例外で集約
	}

	$placeholders = implode(',', array_fill(0, count($ids), '?'));
	$stmt = $pdo->prepare("SELECT id, price FROM products WHERE id IN ($placeholders)");
	$stmt->execute($ids);

	$priceMap = []; // id => int (yen)
	foreach ($stmt as $row) {
		$priceMap[(int)$row['id']] = (int)$row['price'];
	}

	$totalYen = 0;
	foreach ($lines as $pid => $qty) {
		if (!isset($priceMap[$pid])) {
			setFlash('error', "一部の商品情報が取得できません（ID: {$pid}）。");
			redirect('cartView.php', 303);
		}
		$totalYen += $priceMap[$pid] * $qty;
	}

	// 6) トランザクション：purchases → purchase_details
	$pdo->beginTransaction();

	// purchases 追加（status は DEFAULT 'pending'、purchaseDate は DEFAULT CURRENT_TIMESTAMP）
	$sqlP = "INSERT INTO purchases (customerId, totalAmount) VALUES (?, ?)";
	$stmtP = $pdo->prepare($sqlP);
	// totalAmount は DECIMAL(10,2)。円なら .00 で保存
	$stmtP->execute([$customerId, $totalYen]);
	$purchaseId = (int)$pdo->lastInsertId();

	// purchase_details 追加
	$sqlD = "INSERT INTO purchase_details (purchaseId, productId, purchaseCount) VALUES (?, ?, ?)";
	$stmtD = $pdo->prepare($sqlD);
	foreach ($lines as $pid => $qty) {
		$stmtD->execute([$purchaseId, $pid, $qty]);
	}

	$pdo->commit();

	// 7) カートクリア → 完了へ（PRG）
	unset($_SESSION['cart']);
	redirect('purchaseDone.php', 303);
} catch (LogicException $e) {
	if (isset($pdo) && $pdo->inTransaction()) {
		$pdo->rollBack();
	}
	error_log('[purchaseProcess business] ' . $e->getMessage());
	setFlash('error', '購入処理で業務エラーが発生しました。時間をおいて再度お試しください。');
	redirect('cartView.php', 303);
} catch (PDOException $e) {
	if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
	// DB固有のエラーコードも残しておくと後で追跡しやすい
	error_log('[purchaseProcess PDO] ' . $e->getCode() . ' ' . $e->getMessage());
	setFlash('error', '購入処理でDBエラーが発生しました。時間をおいて再度お試しください。');
	redirect('cartView.php', 303);
} catch (Throwable $e) {
	if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
	error_log('[purchaseProcess] ' . $e->getMessage());
	setFlash('error', '購入処理でエラーが発生しました。時間をおいて再度お試しください。');
	redirect('cartView.php', 303);
}
