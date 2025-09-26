<?php
// login.php
$pageTitle = "CCドーナツ | ログイン";
$breadcrumbs = [
	['label' => 'TOP', 'url' => 'index.php'],
	['label' => 'ログイン', 'url' => null],
];
require __DIR__ . '/header.php';
$error = getFlash('error');
?>

<main class="registerPage"><!-- 既存のカード/ボタンスタイルを使うため class を流用 -->
	<h1 class="pageTitle">ログイン</h1>

    <?php if ($error) { ?>
        <div class="errorMessage"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php } ?>

	<form method="post" class="registerForm" action="app/loginProcess.php">
		<div class="formRow">
			<label for="mail">メールアドレス</label>
			<input id="mail" name="mail" type="email" required value="<?= htmlspecialchars($_POST['mail'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
		</div>

		<div class="formRow">
			<label for="password">パスワード</label>
			<input id="password" name="password" type="password" required>
		</div>

		<div class="buttons">
			<button type="submit" class="primaryBtn">ログイン</button>
			<a class="secondaryBtn" href="registerInput.php">新規会員登録はこちら</a>
		</div>
	</form>
</main>
<?php require __DIR__ . '/footer.php'; ?>