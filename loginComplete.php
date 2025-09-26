<?php
// /loginComplete.php
declare(strict_types=1);
require_once __DIR__ . '/app/sessionManager.php';
require_once __DIR__ . '/app/commonFunctions.php';

$pageTitle = 'CCドーナツ | ログイン完了';
$breadcrumbs = [
	['label' => 'TOP', 'url' => 'index.php'],
	['label' => 'ログイン完了', 'url' => null],
];
require 'header.php';
?>
<main class="authPage loginDonePage">
	<h1 class="pageTitle">ログイン完了</h1>

	<p class="loginDoneMessage">ログインが完了しました。</p>
	<p class="loginDoneLead"><?= getLoginUserName(); ?> 様</p>
	<p class="loginDoneSub">引き続きお楽しみください。</p>

	<div class="doneActions">
		<a class="btnPrimary" href="checkout.php">購入確認ページへすすむ</a>
		<a class="btnSecondary" href="index.php">TOPページへもどる</a>
	</div>
</main>
<?php require 'footer.php'; ?>