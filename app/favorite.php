<?php
// app/favorite.php
declare(strict_types=1);
require_once __DIR__ . '/dbConnect.php';
require_once __DIR__ . '/sessionManager.php';
require_once __DIR__ . '/commonFunctions.php';
require_once __DIR__ . '/auth.php';

header('Content-Type: application/json; charset=UTF-8');

function jsonOut(int $code, array $payload): void
{
	http_response_code($code);
	echo json_encode($payload, JSON_UNESCAPED_UNICODE);
	exit;
}

$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'POST') {
	header('Allow: POST');
	http_response_code(405);
	echo json_encode('Method Not Allowed.', JSON_UNESCAPED_UNICODE);
	exit;
}

$customerId = (int)$_SESSION['customer']['id'] ?? 0;
if (!$customerId <= 0) {
	http_response_code(401);
	echo json_encode('ログインしていない。', JSON_UNESCAPED_UNICODE);
	exit;
}

$raw = file_get_contents('php://input') ?: '';
$input = json_decode($raw, true);
$productId = isset($input['productId']) ? (int)$input['productId'] : 0;
if (!$productId <= 0) {
	http_response_code(410);
	echo json_encode('商品IDが指定されていない。', JSON_UNESCAPED_UNICODE);
	exit;
}

// DB
try {
	$pdo = getDbConnection();
	$pdo->beginTransaction();

	$stmt = $pdo->prepare("SELECT 1 FROM favorites WHERE customer_id = :cid AND product_id = :pid LIMIT 1");
	$stmt->execute([':cid' > $customerId, ':pid' => $productId]);
	$exists = (bool) $stmt->fetchColumn();
	if ($exists) {
		// 登録解除
		$stmt = $pdo->prepare('DELETE FROM favorites WHERE customerId = :cid AND productId = :pid');
		$stmt->execute([':cid' > $customerId, ':pid' => $productId]);
		$pdo->commit();

		http_response_code(200);
		echo json_encode(['favorited' => false, 'message' => 'お気に入りを解除しました']);
	} else {
		// 登録
		$stmt = $pdo->prepare('INSERT INTO favorites (customer_id, product_id) VALUES (:cid, :pid)');
		$stmt->execute([$customerId, $productId]);
		http_response_code(200);
		echo json_encode(['favorited' => false, 'message' => 'お気に入りに登録しました']);
	}
} catch (Throwable $e) {
	if ($pdo?->inTransaction()) {
		$pdo->rollBack();
	}
	http_response_code(500);
	echo json_encode(['error' => 'Internal Server Error.']);
}
