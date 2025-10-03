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
const pageContent= document.getElementById('pageContent');

let navWidth = 320;     // 実測で上書き
let startX = 0;       // ドラッグ開始位置
let startNavX = 0;       // ドラッグ開始時のナビX
let dragging = false;
const OPEN_THRESHOLD_RATE = 0.35; // しきい値（幅の35%）

/** 画面中央の max-width:1366px に合わせて、コンテンツ左端を算出しCSS変数へ */
function setContentLeft() {
	const maxW = 1366;
	const vw = window.innerWidth;
	const contentW = Math.min(maxW, vw);
	const left = (vw - contentW) / 2;
	document.documentElement.style.setProperty('--content-left', `${left}px`);
}

/** ナビ幅を実測し、CSS変数へ */
function measureNav() {
	// 一瞬だけ表示して幅を測る（オフスクリーンのままでもOK）
	const prev = globalNavH.style.transform;
	globalNavH.style.transform = 'translateX(0)'; // レイアウト確定
	navWidth = Math.round(globalNavH.getBoundingClientRect().width);
	document.documentElement.style.setProperty('--nav-width', `${navWidth}px`);
	// 元に戻す（CSS変数 --nav-x が最終状態を持つ）
	globalNavH.style.transform = prev;
}

/** --nav-x をセット（px） */
function setNavX(px) {
	document.documentElement.style.setProperty('--nav-x', `${px}px`);
}

/** 現在の --nav-x を取得（px数値） */
function getNavX() {
	const v = getComputedStyle(document.documentElement).getPropertyValue('--nav-x').trim();
	return parseFloat(v || -navWidth);
}

/** 開く／閉じる スナップ */
function snapOpen() {
	setNavX(0);
	menuHandle.setAttribute('aria-expanded', 'true');
	globalNavH.setAttribute('aria-hidden', 'false');
}
function snapClose() {
	setNavX(-navWidth);
	menuHandle.setAttribute('aria-expanded', 'false');
	globalNavH.setAttribute('aria-hidden', 'true');
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
	const next = Math.min(0, startNavX + delta); // 最大でも0（全開）
	setNavX(Math.max(-navWidth, next));          // 最小は -navWidth（全隠し）
}

/** ドラッグ終了：しきい値でスナップ */
function onPointerUp(e) {
	window.removeEventListener('pointermove', onPointerMove);
	dragging = false;
	globalNavH.classList.remove('dragging');

	const exposed = navWidth + getNavX(); // 露出ピクセル= 全幅 - 隠れてる幅
	const threshold = navWidth * OPEN_THRESHOLD_RATE;
	if (exposed >= threshold) snapOpen();
	else snapClose();
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
