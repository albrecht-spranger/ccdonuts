// ハンバーガーメニューの開閉
(function () {
	const menuButton = document.getElementById('menuButton');
	const globalNav = document.getElementById('globalNav');
	if (!menuButton || !globalNav) return;
	menuButton.addEventListener('click', function(){
		const expanded = this.getAttribute('aria-expanded') === 'true';
		if(expanded) { closeMenu(); } else { openMenu(); }
	});

	const menuClose = document.getElementById('menuClose');
	function openMenu() {
		menuButton.setAttribute('aria-expanded','true');
		globalNav.removeAttribute('hidden');
		document.documentElement.style.overflow='hidden';
	}
	function closeMenu() {
		menuButton.setAttribute('aria-expanded','false');
		globalNav.setAttribute('hidden','');
		document.documentElement.style.overflow='';
	}
	if (menuClose) {
		menuClose.addEventListener('click', closeMenu);
	}
	// Also close when pressing Escape
	document.addEventListener('keydown', (e)=>{ if(e.key==='Escape' && !globalNav.hasAttribute('hidden')) closeMenu(); });

})();