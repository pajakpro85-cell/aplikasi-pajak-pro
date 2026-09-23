document.addEventListener('alpine:init', () => {

    Alpine.data('hitungTagihanForm', () => ({

        // ===================================================================
        // DUMMY / STATIC DATA (nanti disambungkan ke backend)
        // ===================================================================
        masaBulanList: [
            'Januari (Masa 1)', 'Februari (Masa 2)', 'Maret (Masa 3)', 'April (Masa 4)',
            'Mei (Masa 5)', 'Juni (Masa 6)', 'Juli (Masa 7)', 'Agustus (Masa 8)',
            'September (Masa 9)', 'Oktober (Masa 10)', 'November (Masa 11)', 'Desember (Masa 12)',
        ],
        tahunPajakList: [2024, 2025, 2026, 2027],

        statusPembayaranList: [
            'Belum Dibayar',
            'Siap Bayar (Approved)',
            'Sudah Dibayar & Dipotong',
        ],

        kategoriWajibPajakList: [
            'Badan Usaha (PT / CV / Firma)',
            'Orang Pribadi (Freelancer / Tenaga Ahli)',
            'Wajib Pajak Luar Negeri (WPLN)',
        ],

        klasterList: [
            { code: '23', label: 'Pph 23', desc: 'Jasa & Sewa Harta' },
            { code: '4(2)', label: 'Pph 4(2)', desc: 'Sewa Gedung & Tanah' },
            { code: '22', label: 'Pph 22', desc: 'Pengadaan Barang' },
            { code: '15', label: 'Pph 15', desc: 'Pelayaran & Penerbangan' },
            { code: '26', label: 'Pph 26', desc: 'WP Luar Negeri' },
        ],

        kopList: [
            { code: '24-104-01', desc: 'Jasa Manajemen, Jasa Konsultasi, Jasa Teknik & Jasa Lainnya', tarif: 2 },
            { code: '24-104-02', desc: 'Jasa Perawatan / Pemeliharaan/ Perbaikan Mesin & Bangunan', tarif: 2 },
            { code: '24-104-03', desc: 'Jasa kebersihan (Cleaning Service) & Keamanan (Security)', tarif: 2 },
            { code: '24-104-04', desc: 'Jasa IT, Software Development, Server & Maintenance Web', tarif: 2 },
            { code: '24-104-05', desc: 'Jasa Freight Forwarding, Logistik & Ekspedisi Pengiriman', tarif: 2 },
            { code: '24-102-01', desc: 'Sewa & Penggunaan Harta (Selain Tanah dan/atau Bangunan)', tarif: 2 },
            { code: '24-100-01', desc: 'Dividen Diterima Wajib Pajak Badan Dalam Negeri', tarif: 15 },
            { code: '24-101-01', desc: 'Bunga Pinjaman (Selain Bunga Bank)', tarif: 15 },
            { code: '24-103-01', desc: 'Royalti atas Penggunaan Hak Cipta, Paten, Merek Dagang', tarif: 15 },
            { code: '24-105-01', desc: 'Hadiah, Penghargaan & Bonus Selain PPh 21', tarif: 15 },
        ],

        fasilitasList: [
            'Tanpa Fasilitas (Tarif Normal)',
            'Surat Keterangan Bebas (SKB - Tarif 0%)',
            'Suket PP 24 / PP 55 (UMKM - Tarif 0.5%)',
            'Ditanggung Pemerintah (DTP - Tarif 0%)',
        ],

        perlakuanPPNList: [
            'Non-PPN (0%)',
            'PPN 11%',
            'PPN 12%',
        ],

        // ===================================================================
        // FORM STATE
        // ===================================================================
        nomorInvoice: '',
        masaBulanSelected: (() => {
            const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            const now = new Date();
            return `${monthNames[now.getMonth()]} (Masa ${now.getMonth() + 1})`;
        })(),
        tahunPajakSelected: new Date().getFullYear(),
        nilaiDPP: 10000000,
        perlakuanPPN: 'PPN 11%',
        statusPembayaranSelected: 'Siap Bayar (Approved)',

        namaKlien: '',
        kategoriWajibPajakSelected: 'Badan Usaha (PT / CV / Firma)',
        npwp: '',
        nonNpwp: false,

        klasterSelected: '23',
        kopSelected: '24-104-01',
        kopSearch: '',
        fasilitasSelected: 'Tanpa Fasilitas (Tarif Normal)',

        // ===================================================================
        // OPEN/CLOSE STATE UNTUK SEMUA DROPDOWN (reusable, satu object)
        // ===================================================================
        open: {
            calendar: false,
            masaBulan: false,
            tahunPajak: false,
            perlakuanPPN: false,
            statusPembayaran: false,
            kategoriWajibPajak: false,
            kop: false,
            fasilitas: false,
        },

        toggle(key) {
            const wasOpen = this.open[key];
            this.closeAll();
            this.open[key] = !wasOpen;
            if (key === 'kop' && this.open.kop) {
                this.kopSearch = '';
                this.$nextTick(() => this.$refs.kopSearchInput?.focus());
            }
        },

        closeAll() {
            Object.keys(this.open).forEach((k) => (this.open[k] = false));
        },

        select(key, value) {
            this[key] = value;
            this.open[Object.keys(this.open).find((k) => k === key) ?? key] = false;
        },

        // ===================================================================
        // DATE PICKER
        // ===================================================================
        selectedDate: null, // Date object
        viewMonth: new Date().getMonth(),
        viewYear: new Date().getFullYear(),

        monthNames: [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ],
        dayNames: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],

        initDatePicker() {
            const today = new Date();
            this.selectedDate = today;
            this.viewMonth = today.getMonth();
            this.viewYear = today.getFullYear();

            // Default Masa & Tahun Pajak otomatis mengikuti tanggal sistem saat halaman dibuka
            this.masaBulanSelected = this.masaBulanList[today.getMonth()];
            this.tahunPajakSelected = today.getFullYear();
        },

        // Dipanggil setelah initDatePicker() — mengisi form otomatis
        // kalau halaman ini dibuka dari tombol "Edit" di Daftar Tagihan
        // (data dikirim lewat query string, lihat resources/js/daftar-tagihan.js -> editRow()).
        initFromQuery() {
            const params = new URLSearchParams(window.location.search);
            if (!params.has('nomorInvoice')) return;

            this.nomorInvoice = params.get('nomorInvoice') ?? '';
            this.masaBulanSelected = params.get('masaBulanSelected') ?? this.masaBulanSelected;
            this.tahunPajakSelected = parseInt(params.get('tahunPajakSelected')) || this.tahunPajakSelected;
            this.nilaiDPP = parseInt(params.get('nilaiDPP')) || 0;
            this.perlakuanPPN = params.get('perlakuanPPN') ?? this.perlakuanPPN;
            this.statusPembayaranSelected = params.get('statusPembayaranSelected') ?? this.statusPembayaranSelected;
            this.namaKlien = params.get('namaKlien') ?? '';
            this.kategoriWajibPajakSelected = params.get('kategoriWajibPajakSelected') ?? this.kategoriWajibPajakSelected;
            this.npwp = params.get('npwp') ?? '';
            this.nonNpwp = params.get('nonNpwp') === '1';
            this.klasterSelected = params.get('klasterSelected') ?? this.klasterSelected;
            this.kopSelected = params.get('kopSelected') ?? this.kopSelected;
            this.fasilitasSelected = params.get('fasilitasSelected') ?? this.fasilitasSelected;

            const tglISO = params.get('tanggalISO');
            if (tglISO) {
                const d = new Date(tglISO + 'T00:00:00');
                if (!isNaN(d)) {
                    this.selectedDate = d;
                    this.viewMonth = d.getMonth();
                    this.viewYear = d.getFullYear();
                }
            }
        },

        get tanggalInvoiceDisplay() {
            if (!this.selectedDate) return '';
            const d = String(this.selectedDate.getDate()).padStart(2, '0');
            const m = String(this.selectedDate.getMonth() + 1).padStart(2, '0');
            const y = this.selectedDate.getFullYear();
            return `${d}/${m}/${y}`;
        },

        get calendarYearOptions() {
            const years = [];
            const current = new Date().getFullYear();
            for (let y = current - 6; y <= current + 3; y++) years.push(y);
            return years;
        },

        get calendarDays() {
            const firstDay = new Date(this.viewYear, this.viewMonth, 1).getDay();
            const totalDays = new Date(this.viewYear, this.viewMonth + 1, 0).getDate();
            const days = [];
            for (let i = 0; i < firstDay; i++) days.push(null);
            for (let d = 1; d <= totalDays; d++) days.push(d);
            return days;
        },

        prevMonth() {
            if (this.viewMonth === 0) {
                this.viewMonth = 11;
                this.viewYear--;
            } else {
                this.viewMonth--;
            }
        },

        nextMonth() {
            if (this.viewMonth === 11) {
                this.viewMonth = 0;
                this.viewYear++;
            } else {
                this.viewMonth++;
            }
        },

        pickDay(day) {
            if (!day) return;
            this.selectedDate = new Date(this.viewYear, this.viewMonth, day);
        },

        isSelectedDay(day) {
            if (!day || !this.selectedDate) return false;
            return (
                this.selectedDate.getDate() === day &&
                this.selectedDate.getMonth() === this.viewMonth &&
                this.selectedDate.getFullYear() === this.viewYear
            );
        },

        isToday(day) {
            if (!day) return false;
            const today = new Date();
            return (
                today.getDate() === day &&
                today.getMonth() === this.viewMonth &&
                today.getFullYear() === this.viewYear
            );
        },

        clearDate() {
            this.selectedDate = null;
        },

        setToday() {
            const today = new Date();
            this.selectedDate = today;
            this.viewMonth = today.getMonth();
            this.viewYear = today.getFullYear();
            this.open.calendar = false;
        },

        // ===================================================================
        // KOP: computed & search
        // ===================================================================
        get selectedKop() {
            return this.kopList.find((k) => k.code === this.kopSelected) ?? null;
        },

        get filteredKopList() {
            if (!this.kopSearch.trim()) return this.kopList;
            const q = this.kopSearch.toLowerCase();
            return this.kopList.filter(
                (k) => k.code.toLowerCase().includes(q) || k.desc.toLowerCase().includes(q)
            );
        },

        selectKop(code) {
            this.kopSelected = code;
            this.open.kop = false;
        },

        // ===================================================================
        // KLASTER
        // ===================================================================
        get selectedKlaster() {
            return this.klasterList.find((k) => k.code === this.klasterSelected) ?? this.klasterList[0];
        },

        // ===================================================================
        // KALKULASI RINGKASAN
        // ===================================================================
        get tarifEfektif() {
            const dasar = this.selectedKop ? this.selectedKop.tarif : 0;

            if (this.fasilitasSelected.includes('SKB')) return 0;
            if (this.fasilitasSelected.includes('DTP')) return 0;
            if (this.fasilitasSelected.includes('UMKM')) return 0.5;

            return this.nonNpwp ? dasar * 2 : dasar;
        },

        get ppnPersen() {
            const match = this.perlakuanPPN.match(/(\d+(\.\d+)?)%/);
            return match ? parseFloat(match[1]) : 0;
        },

        get ppnMasukan() {
            return Math.round(Number(this.nilaiDPP || 0) * (this.ppnPersen / 100));
        },

        get totalTagihan() {
            return Number(this.nilaiDPP || 0) + this.ppnMasukan;
        },

        get potonganPPh() {
            return Math.round(Number(this.nilaiDPP || 0) * (this.tarifEfektif / 100));
        },

        get bersihDitransfer() {
            return this.totalTagihan - this.potonganPPh;
        },

        formatIDR(value) {
            return 'IDR ' + Number(value || 0).toLocaleString('id-ID');
        },

        // Format angka polos jadi berpisah titik ribuan, contoh: 10000000 -> "10.000.000"
        formatNumber(value) {
            return Number(value || 0).toLocaleString('id-ID');
        },

        // Ambil hanya digit dari input text (buang titik/karakter lain) lalu jadikan number
        parseNumber(str) {
            const digitsOnly = String(str || '').replace(/\D/g, '');
            return digitsOnly ? parseInt(digitsOnly, 10) : 0;
        },

        onNilaiDPPInput(event) {
            this.nilaiDPP = this.parseNumber(event.target.value);
            event.target.value = this.formatNumber(this.nilaiDPP);
        },

        // ===================================================================
        // RESET
        // ===================================================================
        resetForm() {
            this.nomorInvoice = '';
            this.nilaiDPP = 0;
            this.perlakuanPPN = 'PPN 11%';
            this.statusPembayaranSelected = 'Siap Bayar (Approved)';
            this.namaKlien = '';
            this.kategoriWajibPajakSelected = 'Badan Usaha (PT / CV / Firma)';
            this.npwp = '';
            this.nonNpwp = false;
            this.klasterSelected = '23';
            this.kopSelected = '24-104-01';
            this.kopSearch = '';
            this.fasilitasSelected = 'Tanpa Fasilitas (Tarif Normal)';
            this.initDatePicker();
            this.closeAll();
        },

    }));

});