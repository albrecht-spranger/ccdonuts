// ハンバーガーメニューの開閉
(function () {
	const menuButton = document.getElementById('menuButton');
	const globalNav = document.getElementById('globalNav');
	if (!menuButton || !globalNav) return;
	menuButton.addEventListener('click', function () {
		const expanded = this.getAttribute('aria-expanded') === 'true';
		this.setAttribute('aria-expanded', String(!expanded));
		if (globalNav.hasAttribute('hidden')) {
			globalNav.removeAttribute('hidden');
		} else {
			globalNav.setAttribute('hidden', '');
		}
	});
})();