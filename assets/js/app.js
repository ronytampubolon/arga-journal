(function () {
	'use strict';

	var root = document.documentElement;
	var meta = document.getElementById('meta-theme-color');

	var toggle = document.getElementById('theme-toggle');
	if (toggle) {
		var sync = function () {
			var dark = root.classList.contains('dark');
			toggle.setAttribute('aria-pressed', String(dark));
			if (meta) {
				meta.setAttribute('content', dark ? '#020617' : '#ffffff');
			}
		};
		sync();
		toggle.addEventListener('click', function () {
			var dark = root.classList.toggle('dark');
			try {
				localStorage.setItem('inkwell-theme', dark ? 'dark' : 'light');
			} catch (e) {}
			sync();
		});
	}

	var navToggle = document.getElementById('nav-toggle');
	var menu = document.getElementById('primary-menu');
	if (navToggle && menu) {
		navToggle.addEventListener('click', function () {
			var open = menu.classList.toggle('hidden') === false;
			navToggle.setAttribute('aria-expanded', String(open));
		});
	}
})();
