<?php

declare(strict_types=1);
require_once __DIR__ . '/app/sessionManager.php';
require_once __DIR__ . '/app/commonFunctions.php';
require_once __DIR__ . '/app/auth.php';

$data = $_SESSION['register.data'] ?? []; // 戻り時の下書き
$fromConfirm = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (!check_csrf($_POST['csrf_token'] ?? '')) {
		http_response_code(400);
		die('不正なリクエストです（CSRF）');
	}
	// 「確認画面から戻る」ケース：セッションの下書きを維持したまま再表示
	if (isset($_POST['back'])) {
		$fromConfirm = true;
		$data = $_SESSION['register.data'] ?? [];
	} else {
		// 新規入力のバリデーション
		$data = collectRegisterInput($_POST);
		$errors = validateRegister($data);
		if ($errors) {
			http_response_code(422);
		} else {
			// 確認画面用にセッションへ保存
			$_SESSION['register.data'] = $data;
			$_SESSION['register.nonce'] = bin2hex(random_bytes(16)); // 二重送信対策
			header('Location: registerConfirm.php', true, 303);
			exit;
		}
	}
}
?>

<!DOCTYPE html>
<html lang="ja">

<?php
$pageTitle = "CCドーナツ | 会員登録";
require "head.php";
?>

<body>
	<!-- ヘッダ -->
	<?php require "header.php" ?>

	<main>
		<!-- パンくずリスト -->
		<?php
		$breadcrumbs = [
			['label' => 'TOP', 'url' => 'index.php'],
			['label' => 'ログイン', 'url' => 'login.php'],
			['label' => 'ログイン完了', 'url' => null],
		];
		require "breadcrumbs.php"
		?>

		<!-- ログインユーザ名 -->
		<div class="loginUserContainer">
			<p><?= isLoggedIn() ? getLoginUserName() : 'ようこそ　ゲスト' ?> 様</p>
		</div>

		<!-- 会員登録セクション -->
		<section>
			<div class="loginTitleContainer">
				<h1 class="h1Login">会員登録</h1>
			</div>

			<div class="loginContents">
				<form action="registerConfirm.php" method="post" class="registerBox">
					<div class="formRow">
						<label for="name" class="require">お名前</label>
						<input id="name" name="name" type="text" required>
					</div>

					<div class="formRow">
						<label for="furigana" class="require">お名前（フリガナ）</label>
						<input id="furigana" name="furigana" type="text" required>
					</div>

					<div class="formRow">
						<label for="postcode" class="require">郵便番号</label>
						<div class="postcodePair">
							<input id="postcodeHead" name="postcodeHead" type="text" inputmode="numeric" pattern="\d{3}" placeholder="123" required>
							<input id="postcodeTail" name="postcodeTail" type="text" inputmode="numeric" pattern="\d{4}" placeholder="4567" required>
						</div>
					</div>

					<div class="formRow">
						<label for="address" class="require">住所</label>
						<input id="address" name="address" type="text" required>
					</div>

					<div class="formRow">
						<label for="mail" class="require">メールアドレス</label>
						<input id="mail" name="mail" type="email" required>
					</div>

					<div class="formRow">
						<label for="mailConfirm" class="require">メールアドレス確認用</label>
						<input id="mailConfirm" name="mailConfirm" type="email" required>
					</div>

					<div class="formRow">
						<label for="password" class="require">パスワード</label>
						<p class="note">半角英数字8文字以上20文字以内で入力してください。※記号の使用はできません</p>
						<input id="password" name="password" type="password" minlength="8" maxlength="20" pattern="[A-Za-z0-9]+" required>
					</div>

					<div class="formRow">
						<label for="passwordConfirm" class="require">パスワード確認用</label>
						<input id="passwordConfirm" name="passwordConfirm" type="password" required>
					</div>

					<div class="buttons">
						<button type="submit" class="primaryBtn">入力確認する</button>
					</div>
				</form>
			</div>
		</section>
	</main>

	<!-- フッタ -->
	<?php require "footer.php"; ?>
</body>

</html>