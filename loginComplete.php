<?php
// /loginComplete.php
declare(strict_types=1);
require_once __DIR__ . '/app/sessionManager.php';
require_once __DIR__ . '/app/commonFunctions.php';

$pageTitle = 'ログイン完了 | CC Donuts';
$name = $_SESSION['customer']['name'] ?? '';
require 'header.php';
?>
<main class="authPage loginDonePage">
  <nav class="breadcrumb" aria-label="breadcrumb">
    <span class="crumb current">ログイン完了</span>
    <span class="sep">＞</span><a class="crumb" href="login.php">ログイン</a>
    <span class="sep">＞</span><a class="crumb" href="index.php">TOP</a>
  </nav>

  <h1 class="pageTitle">ログイン完了</h1>

  <p class="loginDoneMessage">ログインが完了しました。</p>
  <p class="loginDoneLead"><?php echo h($name ?: ''); ?>　様</p>
  <p class="loginDoneSub">引き続きお楽しみください。</p>

  <div class="doneActions">
    <a class="btnPrimary" href="checkout.php">購入確認ページへすすむ</a>
    <a class="btnSecondary" href="index.php">TOPページへもどる</a>
  </div>
</main>
<?php require 'footer.php'; ?>
