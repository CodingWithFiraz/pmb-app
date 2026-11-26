<script>
    (function () {
        document.querySelectorAll('[data-dt2-root]').forEach(function (root) {
            const table       = root.querySelector('[data-dt2-table]');
            const densitySel  = root.querySelector('[data-dt2-density]');
            const selectAll   = root.querySelector('[data-dt2-select-all]');
            const rowChecks   = Array.from(root.querySelectorAll('[data-dt2-row-check]'));

            const densityMap = {
                compact:  ['[&_td]:py-2', '[&_th]:py-2'],
                standard: ['[&_td]:py-3', '[&_th]:py-2.5'],
                flexible: ['[&_td]:py-4', '[&_th]:py-3.5'],
            };
            const allDensityClasses = Object.values(densityMap).flat();

            function applyDensity(mode) {
                if (!table) return;
                table.classList.remove(...allDensityClasses);
                table.classList.add(...(densityMap[mode] || densityMap.standard));
            }

            function syncSelection() {
                const checked = rowChecks.filter((cb) => cb.checked).length;
                rowChecks.forEach((cb) => {
                    const row = cb.closest('tr');
                    if (row) {
                        if (cb.checked) row.setAttribute('data-state', 'selected');
                        else row.removeAttribute('data-state');
                    }
                });
                if (selectAll) {
                    selectAll.checked = rowChecks.length > 0 && checked === rowChecks.length;
                    selectAll.indeterminate = checked > 0 && checked < rowChecks.length;
                }
            }

            densitySel?.addEventListener('change', () => applyDensity(densitySel.value));
            selectAll?.addEventListener('change', () => {
                rowChecks.forEach((cb) => { cb.checked = selectAll.checked; });
                syncSelection();
            });
            rowChecks.forEach((cb) => cb.addEventListener('change', syncSelection));

            applyDensity(densitySel?.value || 'standard');
        });
    })();
</script>
