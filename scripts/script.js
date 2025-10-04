// ログアウト前のダイアログによる確認
const elLogoutBtn = document.getElementById('logoutBtn');
if (elLogoutBtn) {
	elLogoutBtn.addEventListener('click', function (e) {
		e.preventDefault(); // 直接遷移するのを止める
		if (confirm("本当にログアウトしますか？")) {
			window.location.href = 'logout.php';
		}
	});
};

//
// ハンバーガーメニュー
//
const menuButton = document.getElementById('menuButton');
const globalNavV = document.getElementById('globalNavV');

// 縦オープン
menuButton.addEventListener('click', function () {
	globalNavV.classList.add('open');
});

// 縦クローズ
const globalNavVClose = document.getElementById('globalNavVClose');
globalNavVClose.addEventListener('click', function () {
	globalNavV.classList.remove('open');
});

// ESCで縦を閉じる
document.addEventListener('keydown', (e) => {
	if (e.key === 'Escape') {
		globalNavV.classList.remove('open');
	}
});

// 横ハンバーガーメニュー
const globalNavH = document.getElementById('globalNavH');
const menuHandle = document.getElementById('menuButton');
const pageContent = document.getElementById('pageContent');

let navWidth = 583;     // 実測で上書き
let contentLeft = 0;
let startX = 0;       // ドラッグ開始位置
let startNavX = 0;       // ドラッグ開始時のナビX
let dragging = false;
const OPEN_THRESHOLD_RATE = 0.35; // しきい値（幅の35%）

/** 画面中央の max-width:1366px に合わせて、コンテンツ左端を算出しCSS変数へ */
function setContentLeft() {
	// 左端の基準にする要素：中央寄せレイアウトに載っている要素を選ぶ
	const anchor = document.querySelector('.breadcrumbContainer') || document.querySelector('.headerBar') || document.body;

	// 実測の左端（サブピクセルもそのまま使う）
	const left = anchor.getBoundingClientRect().left;
	contentLeft = left;
	document.documentElement.style.setProperty('--content-left', `${left}px`);
}

/** ナビ幅を実測し、CSS変数へ */
function measureNav() {
	// 一瞬だけ表示して幅を測る（オフスクリーンのままでもOK）
	const prevTransform = globalNavH.style.transform;
	const prevTransition = globalNavH.style.transition;
	globalNavH.style.transition = 'none';
	globalNavH.style.transform = 'translateX(0)'; // レイアウト確定
	// 	navWidth = Math.round(globalNavH.getBoundingClientRect().width);
	document.documentElement.style.setProperty('--nav-width', `${navWidth}px`);
	// 元に戻す（CSS変数 --nav-x が最終状態を持つ）
	globalNavH.style.transform = prevTransform;
	// 次のフレームでtransitionを戻すと安全
	requestAnimationFrame(() => {
		globalNavH.style.transition = prevTransition;
	});
}

/** 隠し位置（完全に画面外）= -(navWidth + contentLeft) */
function getHiddenX() {
	return -(navWidth + contentLeft);
}


/** --nav-x をセット（px） */
function setNavX(px) {
	document.documentElement.style.setProperty('--nav-x', `${px}px`);
}

/** 現在の --nav-x を取得（px数値） */
function getNavX() {
	const v = getComputedStyle(document.documentElement).getPropertyValue('--nav-x').trim();
	return parseFloat(v || getHiddenX());
}

/** 開く／閉じる スナップ */
function snapOpen() {
	setNavX(0);
	menuHandle.setAttribute('aria-expanded', 'true');
	globalNavH.removeAttribute('inert');
	globalNavH.setAttribute('aria-hidden', 'false');
}

function snapClose() {
	setNavX(getHiddenX());
	menuHandle.setAttribute('aria-expanded', 'false');
	// 先にフォーカスを外へ戻す（ここが重要）
	menuHandle.focus();
	// それから隠す
	globalNavH.setAttribute('aria-hidden', 'true');
	globalNavH.setAttribute('inert', '');
}

/** ドラッグ開始（ハンバーガーのみ） */
function onPointerDown(e) {
	e.preventDefault();
	dragging = true;
	startX = (e.clientX ?? e.touches?.[0]?.clientX ?? 0);
	startNavX = getNavX();
	globalNavH.classList.add('dragging');
	// 閉じ状態から始めたときに、見えるようにしておく（スクリーンリーダー配慮は最後に更新）
	globalNavH.setAttribute('aria-hidden', 'false');
	// ドラッグ対象はページ全体で追う
	window.addEventListener('pointermove', onPointerMove);
	window.addEventListener('pointerup', onPointerUp, { once: true });
}

/** ドラッグ中：距離に応じて追従（0 〜 -navWidth の範囲） */
function onPointerMove(e) {
	if (!dragging) return;
	const x = (e.clientX ?? 0) - startX;
	// ドラッグは右方向のみ有効
	const delta = Math.max(0, x);
	const next = Math.min(0, startNavX + delta);        // 最大でも0（全開）
	setNavX(Math.max(getHiddenX(), next));              // 最小は hiddenX（完全オフ）
}

/** ドラッグ終了：しきい値でスナップ */
function onPointerUp(e) {
	window.removeEventListener('pointermove', onPointerMove);
	dragging = false;
	globalNavH.classList.remove('dragging');

	// 進捗率 = (現在位置 - 隠し位置) / (0 - 隠し位置)
	const hiddenX = getHiddenX();
	const progress = (getNavX() - hiddenX) / (0 - hiddenX); // 0〜1
	if (progress >= OPEN_THRESHOLD_RATE) {
		snapOpen();
	} else {
		snapClose();
	}
}

/** ×ボタンで閉じる（左へスライドアウト） */
function onCloseClick() {
	snapClose();
}

/** 初期化 */
function init() {
	setContentLeft();
	measureNav();
	snapClose(); // 初期は見えない
}

menuHandle.addEventListener('pointerdown', onPointerDown);
document.getElementById('globalNavHClose')?.addEventListener('click', onCloseClick);
window.addEventListener('resize', () => { setContentLeft(); measureNav(); });

window.addEventListener('keydown', (e) => {
	if (e.key === 'Escape') snapClose();
});

init();
