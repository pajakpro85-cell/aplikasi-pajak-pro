import { tagihanDummyList, buyerInfo, penandatanganInfo } from './data/tagihan-dummy';

document.addEventListener('alpine:init', () => {

    Alpine.data('daftarTagihanPage', () => ({

        // ===================================================================
        // DUMMY DATA (nanti disambungkan ke backend)
        // Field *Form* / *Raw* di tiap row dipakai khusus untuk prefill
        // form Hitung Tagihan saat tombol Edit diklik.
        // ===================================================================
        tagihanList: tagihanDummyList.map((r) => ({ ...r })),

        // Data perusahaan pemotong (Buyer) & penandatangan — sama untuk semua invoice,
        // nanti ganti dengan data perusahaan dari backend/profil akun.
        buyer: buyerInfo,
        penandatangan: penandatanganInfo,

        nextId: 6,

        // ===================================================================
        // FILTER OPTIONS
        // ===================================================================
        jenisPphList: ['Semua Jenis PPh', 'PPh Pasal 23', 'PPh Pasal 4(2)', 'PPh Pasal 22', 'PPh Pasal 15', 'PPh Pasal 26'],
        kategoriKlienList: ['Semua Kategori Klien', 'Badan (Corporate)', 'Orang Pribadi (OP)', 'WPLN (Luar Negeri)'],
        tahunList: ['Semua Tahun', 2024, 2025, 2026, 2027],
        masaList: [
            'Semua Masa',
            'Januari (Masa 1)', 'Februari (Masa 2)', 'Maret (Masa 3)', 'April (Masa 4)',
            'Mei (Masa 5)', 'Juni (Masa 6)', 'Juli (Masa 7)', 'Agustus (Masa 8)',
            'September (Masa 9)', 'Oktober (Masa 10)', 'November (Masa 11)', 'Desember (Masa 12)',
        ],
        statusList: ['Semua Status', 'Belum Dibayar', 'Siap Bayar', 'Sudah Dibayar & Dipotong'],

        // ===================================================================
        // SEARCH & FILTER STATE
        // ===================================================================
        search: '',
        filterJenisPph: 'Semua Jenis PPh',
        filterKategoriKlien: 'Semua Kategori Klien',
        filterTahun: 'Semua Tahun',
        filterMasa: 'Semua Masa',
        filterStatus: 'Semua Status',

        open: {
            jenisPph: false,
            kategoriKlien: false,
            tahun: false,
            masa: false,
            status: false,
        },

        toggle(key) {
            const wasOpen = this.open[key];
            Object.keys(this.open).forEach((k) => (this.open[k] = false));
            this.open[key] = !wasOpen;
        },

        closeAll() {
            Object.keys(this.open).forEach((k) => (this.open[k] = false));
        },

        get filteredList() {
            const q = this.search.trim().toLowerCase();

            return this.tagihanList.filter((row) => {
                if (q) {
                    const haystack = `${row.noInvoice} ${row.klien} ${row.npwp}`.toLowerCase();
                    if (!haystack.includes(q)) return false;
                }
                if (this.filterJenisPph !== 'Semua Jenis PPh' && row.jenisPphFilter !== this.filterJenisPph) return false;
                if (this.filterKategoriKlien !== 'Semua Kategori Klien' && row.kategoriKlien !== this.filterKategoriKlien) return false;
                if (this.filterTahun !== 'Semua Tahun' && row.tahun !== this.filterTahun) return false;
                if (this.filterMasa !== 'Semua Masa' && row.masaBulan !== this.filterMasa) return false;
                if (this.filterStatus !== 'Semua Status' && row.status !== this.filterStatus) return false;
                return true;
            });
        },

        formatIDR(value) {
            return 'IDR ' + Number(value || 0).toLocaleString('id-ID');
        },

        // ===================================================================
        // STATUS PILL STYLING
        // ===================================================================
        statusPillStyle(status) {
            if (status === 'Sudah Dibayar & Dipotong') {
                return { class: 'bg-emerald-100 text-emerald-700', style: '' };
            }
            if (status === 'Siap Bayar') {
                return { class: 'bg-blue-100 text-blue-700', style: '' };
            }
            // Belum Dibayar (termasuk hasil duplikasi/copy)
            return { class: '', style: 'background-color:#FDE68A; color:#B45309;' };
        },

        // ===================================================================
        // AKSI: COPY
        // ===================================================================
        copyRow(row) {
            const duplicated = {
                ...row,
                id: this.nextId++,
                status: 'Belum Dibayar',
                statusFormValue: 'Belum Dibayar',
            };
            this.tagihanList.unshift(duplicated);
        },

        // ===================================================================
        // AKSI: EDIT -> redirect ke Hitung Tagihan dengan prefill query params
        // ===================================================================
        editRow(row) {
            const params = new URLSearchParams({
                nomorInvoice: row.noInvoice,
                tanggalISO: row.tanggalISO,
                masaBulanSelected: row.masaBulan,
                tahunPajakSelected: row.tahun,
                nilaiDPP: row.dppRaw,
                perlakuanPPN: row.perlakuanPPN,
                statusPembayaranSelected: row.statusFormValue,
                namaKlien: row.klien,
                kategoriWajibPajakSelected: row.kategoriWPForm,
                npwp: row.npwp,
                nonNpwp: row.nonNpwp ? '1' : '0',
                klasterSelected: row.klasterCode,
                kopSelected: row.kopCode,
                fasilitasSelected: row.fasilitasForm,
            });
            window.location.href = '/hitung-tagihan?' + params.toString();
        },

        // ===================================================================
        // AKSI: LIHAT DETAIL (modal slip)
        // ===================================================================
        detailId: null,

        showDetail(id) {
            this.detailId = id;
        },

        closeDetail() {
            this.detailId = null;
        },

        get detailRow() {
            return this.tagihanList.find((r) => r.id === this.detailId) ?? null;
        },

        get detailTotalBruto() {
            if (!this.detailRow) return 0;
            return this.detailRow.dppRaw + this.detailRow.ppnRaw;
        },

        cetakSlipUrl(row) {
            return '/cetak-slip/' + row.id;
        },

        // ===================================================================
        // AKSI: DELETE (dengan modal konfirmasi)
        // ===================================================================
        confirmDeleteId: null,

        askDelete(id) {
            this.confirmDeleteId = id;
        },

        cancelDelete() {
            this.confirmDeleteId = null;
        },

        deleteConfirmed() {
            this.tagihanList = this.tagihanList.filter((r) => r.id !== this.confirmDeleteId);
            this.confirmDeleteId = null;
        },

    }));

});