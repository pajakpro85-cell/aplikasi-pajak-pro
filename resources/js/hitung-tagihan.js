document.addEventListener('alpine:init', () => {

    Alpine.data('hitungTagihanForm', () => ({

        // ===================================================================
        // 1. STATE DATA FORM (Sesuaikan dengan nama di card-data-tagihan.blade.php)
        // ===================================================================
        nomorInvoice: '',
        selectedDate: null,
        tanggalInvoiceDisplay: '',
        masaBulanSelected: 'Oktober (Masa 10)',
        tahunPajakSelected: 2026,
        nilaiDPP: 10000000,
        perlakuanPPN: 'PPN 11%',
        statusPembayaranSelected: 'Siap Bayar (Approved)',

        // State Dropdown Popup Menu
        open: {
            calendar: false,
            masaBulan: false,
            tahunPajak: false,
            perlakuanPPN: false,
            statusPembayaran: false
        },

        // Helper Dropdown Toggle
        toggle(menu) {
            this.open[menu] = !this.open[menu];
        },

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
            { code: '23', label: 'Pph 23', desc: 'Jasa & Sewa Harta', dasarHukum: 'PMK-141/PMK.03/2015 & PER-24/PJ/2021' },
            { code: '4(2)', label: 'Pph 4(2)', desc: 'Sewa Gedung & Tanah', dasarHukum: 'UU PPh Pasal 4 ayat (2) Final & PER-24/PJ/2021' },
            { code: '22', label: 'Pph 22', desc: 'Pengadaan Barang', dasarHukum: 'UU PPh Pasal 22 / PMK-34/2017 & PER-24/PJ/2021' },
            { code: '15', label: 'Pph 15', desc: 'Pelayaran & Penerbangan', dasarHukum: 'UU PPh Pasal 15 Norma Khusus & PER-24/PJ/2021' },
            { code: '26', label: 'Pph 26', desc: 'WP Luar Negeri', dasarHukum: 'UU PPh Pasal 26 / P3B Tax Treaty & PER-24/PJ/2021' },
        ],

        // Setiap KOP terikat ke satu klaster PPh (field `klaster` = code klaster di atas)
        kopList: [
            // --- PPh 23 ---
            { klaster: '23', code: '24-104-01', desc: 'Jasa Manajemen, Jasa Konsultasi, Jasa Teknik & Jasa Lainnya', tarif: 2 },
            { klaster: '23', code: '24-104-02', desc: 'Jasa Perawatan / Pemeliharaan/ Perbaikan Mesin & Bangunan', tarif: 2 },
            { klaster: '23', code: '24-104-03', desc: 'Jasa kebersihan (Cleaning Service) & Keamanan (Security)', tarif: 2 },
            { klaster: '23', code: '24-104-04', desc: 'Jasa IT, Software Development, Server & Maintenance Web', tarif: 2 },
            { klaster: '23', code: '24-104-05', desc: 'Jasa Freight Forwarding, Logistik & Ekspedisi Pengiriman', tarif: 2 },
            { klaster: '23', code: '24-102-01', desc: 'Sewa & Penggunaan Harta (Selain Tanah dan/atau Bangunan)', tarif: 2 },
            { klaster: '23', code: '24-100-01', desc: 'Dividen Diterima Wajib Pajak Badan Dalam Negeri', tarif: 15 },
            { klaster: '23', code: '24-101-01', desc: 'Bunga Pinjaman (Selain Bunga Bank)', tarif: 15 },
            { klaster: '23', code: '24-103-01', desc: 'Royalti atas Penggunaan Hak Cipta, Paten, Merek Dagang', tarif: 15 },
            { klaster: '23', code: '24-105-01', desc: 'Hadiah, Penghargaan & Bonus Selain PPh 21', tarif: 15 },

            // --- PPh 4(2) ---
            { klaster: '4(2)', code: '28-402-01', desc: 'Sewa Tanah dan/atau Bangunan (Gedung, Kantor, Ruko, Gudang)', tarif: 10 },
            { klaster: '4(2)', code: '28-403-01', desc: 'Pengalihan Hak atas Tanah dan/atau Bangunan', tarif: 2.5 },
            { klaster: '4(2)', code: '28-409-01', desc: 'Jasa Konstruksi - Pelaksanaan (Sertifikat Kualifikasi Kecil)', tarif: 1.75 },
            { klaster: '4(2)', code: '28-409-02', desc: 'Jasa Konstruksi - Pelaksanaan (Sertifikat Kualifikasi Menengah/Besar)', tarif: 2.65 },
            { klaster: '4(2)', code: '28-409-03', desc: 'Jasa Konstruksi - Konsultasi / Pengawasan Bersertifikat', tarif: 3.5 },
            { klaster: '4(2)', code: '28-409-04', desc: 'Jasa Konstruksi - Pelaksanaan Tanpa Sertifikat Kualifikasi', tarif: 4 },
            { klaster: '4(2)', code: '28-401-01', desc: 'Bunga Deposito, Tabungan & Diskonto SBI', tarif: 20 },
            { klaster: '4(2)', code: '28-419-01', desc: 'Dividen Diterima Wajib Pajak Orang Pribadi Dalam Negeri', tarif: 10 },

            // --- PPh 22 ---
            { klaster: '22', code: '22-100-01', desc: 'Pengadaan / Pembelian Barang Oleh Instansi Pemerintah & BUMN', tarif: 1.5 },
            { klaster: '22', code: '22-100-02', desc: 'Penjualan Bahan Bakar Minyak & Pelumas oleh Pertamina/Produsen', tarif: 0.25 },
            { klaster: '22', code: '22-101-01', desc: 'Impor Barang Menggunakan API (Angka Pengenal Impor)', tarif: 2.5 },

            // --- PPh 15 ---
            { klaster: '15', code: '25-100-01', desc: 'Imbalan Jasa Pelayaran Dalam Negeri (Charter Kapal/Freight)', tarif: 1.2 },
            { klaster: '15', code: '25-100-02', desc: 'Imbalan Jasa Penerbangan Dalam Negeri (Charter Pesawat)', tarif: 1.8 },

            // --- PPh 26 ---
            { klaster: '26', code: '27-100-01', desc: 'Imbalan Jasa Teknik, Manajemen, IT & Jasa Lainnya ke WPLN', tarif: 20 },
            { klaster: '26', code: '27-100-03', desc: 'Royalti, Lisensi & Hak Penggunaan Cipta ke WPLN', tarif: 20 },
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

        // Helper Format Angka
        formatNumber(val) {
            if (!val) return '0';
            return new Intl.NumberFormat('id-ID').format(val);
        },

        onNilaiDPPInput(e) {
            let raw = e.target.value.replace(/\D/g, '');
            this.nilaiDPP = raw ? parseInt(raw, 10) : 0;
        },

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
        taxId: '',
        negaraDomisili: 'Singapore (SG)',

        klasterSelected: '23',
        kopSelected: '24-104-01',
        kopSearch: '',
        fasilitasSelected: 'Tanpa Fasilitas (Tarif Normal)',

        // ===================================================================
        // FUNGSI UNTUK MENGIRIM DATA KE BACKEND LARAVEL
        // ===================================================================
        async simpanInvoice() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            const selectedKop = this.kopList.find(k => k.code === this.kopSelected) || {};
            const tarif = selectedKop.tarif || 2;
            const potonganPPh = (this.nilaiDPP * tarif) / 100;

            try {
                const response = await fetch('/simpan-tagihan', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        nomor_invoice: this.nomorInvoice,
                        tanggal_invoice: this.selectedDate,
                        masa_pajak: this.masaBulanSelected,
                        tahun_pajak: this.tahunPajakSelected,
                        nilai_dpp: this.nilaiDPP,
                        perlakuan_ppn: this.perlakuanPPN,
                        status_pembayaran: this.statusPembayaranSelected,
                        nama_klien: this.namaKlien || '',
                        npwp_klien: this.npwp || '',
                        kategori_wp: this.kategoriWajibPajakSelected || 'Badan Usaha',
                        vendor_id: null,
                        klaster_pph: this.klasterSelected || '23',
                        kode_objek: this.kopSelected || '24-104-01',
                        tarif: tarif,
                        nilai_pph: potonganPPh
                    })
                });

                const result = await response.json();
                if (response.ok && result.success) {
                    alert('Perhitungan invoice berhasil disimpan!');
                    window.location.href = result.redirect || '/daftar-tagihan';
                } else {
                    alert('Gagal menyimpan data: ' + (result.message || 'Periksa kembali inputan form.'));
                }
            } catch (error) {
                console.error('Terjadi kesalahan:', error);
            }
        },

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
            if (this.klasterSelected === '26') this.taxId = this.npwp;

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

        // KOP yang tersedia hanya milik klaster yang sedang dipilih
        get kopListKlaster() {
            return this.kopList.filter((k) => k.klaster === this.klasterSelected);
        },

        get filteredKopList() {
            const base = this.kopListKlaster;
            if (!this.kopSearch.trim()) return base;
            const q = this.kopSearch.toLowerCase();
            return base.filter(
                (k) => k.code.toLowerCase().includes(q) || k.desc.toLowerCase().includes(q)
            );
        },

        // Ganti klaster: KOP otomatis pindah ke KOP pertama milik klaster baru
        selectKlaster(code) {
            if (this.klasterSelected === code) return;
            const sebelumnya = this.klasterSelected;
            this.klasterSelected = code;

            const KAT_WPLN = 'Wajib Pajak Luar Negeri (WPLN)';
            const KAT_BADAN = 'Badan Usaha (PT / CV / Firma)';
            if (code === '26') {
                // PPh 26 = vendor luar negeri: default kategori WPLN, tanpa opsi Non-NPWP
                this.kategoriWajibPajakSelected = KAT_WPLN;
                this.nonNpwp = false;
            } else if (sebelumnya === '26' && this.kategoriWajibPajakSelected === KAT_WPLN) {
                // keluar dari PPh 26: kembalikan default kategori seperti semula
                this.kategoriWajibPajakSelected = KAT_BADAN;
            }
            this.kopSearch = '';
            this.open.kop = false;
            const first = this.kopList.find((k) => k.klaster === code);
            this.kopSelected = first ? first.code : '';
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
            this.taxId = '';
            this.negaraDomisili = 'Singapore (SG)';
            this.klasterSelected = '23';
            this.kopSelected = '24-104-01';
            this.kopSearch = '';
            this.fasilitasSelected = 'Tanpa Fasilitas (Tarif Normal)';
            this.initDatePicker();
            this.closeAll();
        },

    }));

});