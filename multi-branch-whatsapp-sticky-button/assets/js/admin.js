(function () {
	'use strict';

	function maxRowIndex() {
		var tbody = document.getElementById('mbwsb-rows');
		if (!tbody) {
			return -1;
		}
		var rows = tbody.querySelectorAll('.mbwsb-row');
		var max = -1;
		for (var i = 0; i < rows.length; i++) {
			var inputs = rows[i].querySelectorAll('input[name^="mbwsb_branches["]');
			if (inputs.length) {
				var m = inputs[0].name.match(/mbwsb_branches\[(\d+)\]/);
				if (m) {
					var n = parseInt(m[1], 10);
					if (n > max) {
						max = n;
					}
				}
			}
		}
		return max;
	}

	function setRowIndex(row, index) {
		var base = 'mbwsb_branches[' + index + ']';
		var labelInp = row.querySelector('input[name$="[label]"]');
		var phoneInp = row.querySelector('input[name$="[phone]"]');
		var labels = row.querySelectorAll('label[for]');
		if (labelInp) {
			labelInp.name = base + '[label]';
			labelInp.id = 'mbwsb-label-' + index;
			if (labels[0]) {
				labels[0].setAttribute('for', 'mbwsb-label-' + index);
			}
		}
		if (phoneInp) {
			phoneInp.name = base + '[phone]';
			phoneInp.id = 'mbwsb-phone-' + index;
			if (labels[1]) {
				labels[1].setAttribute('for', 'mbwsb-phone-' + index);
			}
		}
	}

	function addRow() {
		var tbody = document.getElementById('mbwsb-rows');
		if (!tbody) {
			return;
		}
		var last = tbody.querySelector('.mbwsb-row:last-child');
		if (!last) {
			return;
		}
		var clone = last.cloneNode(true);
		var next = maxRowIndex() + 1;
		setRowIndex(clone, next);
		clone.querySelectorAll('input').forEach(function (inp) {
			inp.value = '';
		});
		tbody.appendChild(clone);
		var focusEl = clone.querySelector('input[name$="[label]"]');
		if (focusEl) {
			focusEl.focus();
		}
	}

	function removeRow(btn) {
		var row = btn.closest('.mbwsb-row');
		var tbody = document.getElementById('mbwsb-rows');
		if (!row || !tbody) {
			return;
		}
		var rows = tbody.querySelectorAll('.mbwsb-row');
		if (rows.length <= 1) {
			row.querySelectorAll('input').forEach(function (inp) {
				inp.value = '';
			});
			return;
		}
		row.remove();
	}

	document.addEventListener('click', function (e) {
		if (e.target && e.target.id === 'mbwsb-add-row') {
			e.preventDefault();
			addRow();
		}
		if (e.target && e.target.classList && e.target.classList.contains('mbwsb-remove-row')) {
			e.preventDefault();
			removeRow(e.target);
		}
	});
})();
