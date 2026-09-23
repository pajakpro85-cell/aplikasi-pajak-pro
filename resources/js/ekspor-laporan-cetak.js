import { tagihanDummyList, buyerInfo } from './data/tagihan-dummy';

document.addEventListener('alpine:init', () => {

    Alpine.data('cetakLaporanPdfPage', () => {

        const params = new URLSearchParams(window.location.search);
        const masa = params.get('masa') ?? '';
        const tahun = parseInt(params.get('tahun'), 10) || new Date().getFullYear();

        return {
            masa,
            tahun,
            buyer: buyerInfo,

            get masaLabelPendek() {
                return this.masa ? this.masa.split(' (')[0] : '-';
            },

            get rows() {
                return tagihanDummyList.filter((r) => r.masaBulan === this.masa && r.tahun === this.tahun);
            },

            get totalDPP() {
                return this.rows.reduce((sum, r) => sum + r.dppRaw, 0);
            },

            get totalPotongan() {
                return this.rows.reduce((sum, r) => sum + r.potonganRaw, 0);
            },

            get totalBersih() {
                return this.rows.reduce((sum, r) => sum + r.bersihRaw, 0);
            },

            formatIDR(value) {
                return 'IDR ' + Number(value || 0).toLocaleString('id-ID');
            },

            cetak() {
                window.print();
            },

            init() {
                // Auto-buka dialog print begitu halaman siap, biar user tinggal pilih "Save as PDF"
                this.$nextTick(() => {
                    setTimeout(() => window.print(), 400);
                });
            },

        };
    });

});