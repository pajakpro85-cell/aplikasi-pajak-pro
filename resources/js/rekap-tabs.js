document.addEventListener('alpine:init', () => {

    // Sliding underline indicator untuk tab Rekapitulasi.
    // Karena tiap tab adalah halaman terpisah (bukan SPA), animasi "geser"
    // dilakukan dengan mengingat posisi tab sebelumnya lewat sessionStorage,
    // lalu menganimasikan indikator dari posisi lama ke posisi tab yang aktif sekarang.
    Alpine.data('rekapTabs', (activeKey) => ({
        active: activeKey,

        init() {
            this.$nextTick(() => {
                const wrap = this.$el;
                const indicator = this.$refs.indicator;
                const currentTab = wrap.querySelector(`[data-tab-key="${this.active}"]`);
                if (!currentTab || !indicator) return;

                const storageKey = 'rekapTabsActiveKey';
                const prevKey = sessionStorage.getItem(storageKey);
                const prevTab = prevKey ? wrap.querySelector(`[data-tab-key="${prevKey}"]`) : null;

                const positionOf = (el) => {
                    const wrapRect = wrap.getBoundingClientRect();
                    const elRect = el.getBoundingClientRect();
                    return { left: elRect.left - wrapRect.left, width: elRect.width };
                };

                const applyPosition = (pos) => {
                    indicator.style.left = pos.left + 'px';
                    indicator.style.width = pos.width + 'px';
                };

                if (prevTab && prevTab !== currentTab) {
                    // Mulai dari posisi tab sebelumnya tanpa transisi...
                    indicator.style.transition = 'none';
                    applyPosition(positionOf(prevTab));
                    // ...paksa reflow, baru animasikan geser ke tab yang aktif sekarang.
                    // eslint-disable-next-line no-unused-expressions
                    indicator.offsetHeight;
                    indicator.style.transition = 'left 280ms cubic-bezier(0.4, 0, 0.2, 1), width 280ms cubic-bezier(0.4, 0, 0.2, 1)';
                    requestAnimationFrame(() => applyPosition(positionOf(currentTab)));
                } else {
                    indicator.style.transition = 'none';
                    applyPosition(positionOf(currentTab));
                }

                sessionStorage.setItem(storageKey, this.active);
            });
        },
    }));

});