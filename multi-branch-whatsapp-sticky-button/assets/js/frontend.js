(function () {
	'use strict';

	function getData(root) {
		var data =
			typeof mbwsbData !== 'undefined' && mbwsbData && mbwsbData.branches
				? mbwsbData
				: null;
		if (!data && root && root.getAttribute) {
			var raw = root.getAttribute('data-mbwsb-branches');
			if (raw) {
				try {
					var branches = JSON.parse(raw);
					if (Array.isArray(branches)) {
						data = { branches: branches, i18n: {} };
					}
				} catch (e) {
					return null;
				}
			}
		}
		return data;
	}

	function init() {
		var root = document.getElementById('mbwsb-root');
		var data = getData(root);
		if (!data || !data.branches || data.branches.length < 2) {
			return;
		}

		var trigger = document.getElementById('mbwsb-trigger');
		var panel = document.getElementById('mbwsb-panel');
		var list = document.getElementById('mbwsb-list');

		if (!root || !trigger || !panel || !list) {
			return;
		}

		function buildList() {
			list.innerHTML = '';
			data.branches.forEach(function (b, i) {
				var li = document.createElement('li');
				li.className = 'mbwsb__item';
				li.setAttribute('role', 'none');
				var a = document.createElement('a');
				a.className = 'mbwsb__link';
				a.href = b.url;
				a.target = '_blank';
				a.rel = 'noopener noreferrer';
				a.setAttribute('role', 'menuitem');
				a.textContent = b.label;
				a.id = 'mbwsb-item-' + i;
				li.appendChild(a);
				list.appendChild(li);
			});
		}

		function setOpen(open) {
			trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
			panel.classList.toggle('is-open', open);
			if (data.i18n) {
				trigger.setAttribute(
					'aria-label',
					open ? data.i18n.closeMenu || '' : data.i18n.openMenu || ''
				);
			}
		}

		function toggle() {
			setOpen(!panel.classList.contains('is-open'));
		}

		buildList();

		if (data.i18n && data.i18n.openMenu) {
			trigger.setAttribute('aria-label', data.i18n.openMenu);
		}

		trigger.addEventListener('click', function (e) {
			e.preventDefault();
			e.stopPropagation();
			if (typeof e.stopImmediatePropagation === 'function') {
				e.stopImmediatePropagation();
			}
			toggle();
		});

		document.addEventListener(
			'click',
			function (e) {
				if (!root.contains(e.target)) {
					setOpen(false);
				}
			},
			false
		);

		panel.addEventListener('click', function (e) {
			e.stopPropagation();
		});

		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && panel.classList.contains('is-open')) {
				setOpen(false);
				trigger.focus();
			}
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
