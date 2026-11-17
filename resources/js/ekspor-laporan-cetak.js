document.addEventListener('alpine:init', () => {

    Alpine.data('cetakLaporanPdfPage', (dbInvoices = [], dbBuyer = { name: '-' }) => {

        const params = new URLSearchParams(window.location.search);
        const masa = params.get('masa') ?? '';
        const tahun = parseInt(params.get('tahun'), 10) || new Date().getFullYear();

        return {
            masa,
            tahun,
            buyer: dbBuyer,
            rows: Array.isArray(dbInvoices) ? dbInvoices : [],

            get masaLabelPendek() {
                return this.masa ? this.masa.split(' (')[0] : '-';
            },

            get totalDPP() {
                return this.rows.reduce((sum, r) => sum + Number(r.dppRaw || 0), 0);
            },

            get totalPotongan() {
                return this.rows.reduce((sum, r) => sum + Number(r.potonganRaw || 0), 0);
            },

            get totalBersih() {
                return this.rows.reduce((sum, r) => sum + Number(r.bersihRaw || 0), 0);
            },

            formatIDR(value) {
                return 'IDR ' + Number(value || 0).toLocaleString('id-ID');
            },

            cetak() {
                window.print();
            },

            init() {
                this.$nextTick(() => {
                    setTimeout(() => window.print(), 400);
                });
            },

        };
    });

});