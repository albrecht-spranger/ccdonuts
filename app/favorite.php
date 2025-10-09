<?php
// app/favorite.php
declare(strict_types=1);
require_once __DIR__ . '/dbConnect.php';
require_once __DIR__ . '/sessionManager.php';
require_once __DIR__ . '/commonFunctions.php';
require_once __DIR__ . '/auth.php';

header('Content-Type: application/json; charset=UTF-8');

$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'POST') {
	header('Allow: POST');
	http_response_code(405);
	echo json_encode('Method Not Allowed.', JSON_UNESCAPED_UNICODE);
	exit;
}

$customerId = (int)($_SESSION['customer']['id'] ?? 0);
if ($customerId <= 0) {
	http_response_code(401);
	echo json_encode('ログインしていない。', JSON_UNESCAPED_UNICODE);
	exit;
}

$raw = file_get_contents('php://input') ?: '';
$input = json_decode($raw, true);
$productId = isset($input['productId']) ? (int)$input['productId'] : 0;
if ($productId <= 0) {
	http_response_code(400);
	// ⇒400はパース不能／プロトコル違反
	echo json_encode('商品IDが指定されていない。', JSON_UNESCAPED_UNICODE);
	exit;
}

// DB
try {
	$pdo = getDbConnection();
	$pdo->beginTransaction();

	// まず削除を試みる（登録済みなら1件消える）
	$stmt = $pdo->prepare('DELETE FROM favorites WHERE customerId = :cid AND productId = :pid');
	$stmt->execute([':cid' => $customerId, ':pid' => $productId]);

	if ($stmt->rowCount() > 0) {
		// 解除できた
		$pdo->commit();
		http_response_code(200);
		echo json_encode(['favorited' => false, 'message' => 'お気に入りを解除しました'], JSON_UNESCAPED_UNICODE);
		exit;
	}

	// 解除できなかった＝未登録だったので登録を試す
	$stmt = $pdo->prepare('INSERT INTO favorites (customerId, productId) VALUES (:cid, :pid)');
	try {
		$stmt->execute([':cid' => $customerId, ':pid' => $productId]);
		$pdo->commit();
		http_response_code(200);
		echo json_encode(['favorited' => true, 'message' => 'お気に入りに登録しました'], JSON_UNESCAPED_UNICODE);
		exit;
	} catch (PDOException $e) {
		// 競合でユニーク制約に引っかかった場合（他トランザクションが直前に登録した）
		if ((int)($e->errorInfo[1] ?? 0) === 1062) { // MySQL duplicate key
			$pdo->commit(); // 状態は「登録済み」なのでそのまま確定
			http_response_code(200);
			echo json_encode(['favorited' => true, 'message' => 'お気に入りに登録しました。'], JSON_UNESCAPED_UNICODE);
			exit;
		}
		throw $e; // それ以外は上位へ
	}
} catch (Throwable $e) {
	if (isset($pdo) && $pdo->inTransaction()) {
		$pdo->rollBack();
	}
	// ログ：error_log($e->getMessage());
	http_response_code(500);
	echo json_encode(['error' => 'Internal Server Error.'], JSON_UNESCAPED_UNICODE);
	exit;
}
