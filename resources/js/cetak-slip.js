import { tagihanDummyList, buyerInfo, penandatanganInfo } from './data/tagihan-dummy';

document.addEventListener('alpine:init', () => {

    Alpine.data('cetakSlipPage', (idParam) => ({

        id: parseInt(idParam, 10),
        buyer: buyerInfo,
        penandatangan: penandatanganInfo,

        get row() {
            return tagihanDummyList.find((r) => r.id === this.id) ?? null;
        },

        get totalBruto() {
            return this.row ? this.row.dppRaw + this.row.ppnRaw : 0;
        },

        formatIDR(value) {
            return 'IDR ' + Number(value || 0).toLocaleString('id-ID');
        },

        cetak() {
            window.print();
        },

    }));

});