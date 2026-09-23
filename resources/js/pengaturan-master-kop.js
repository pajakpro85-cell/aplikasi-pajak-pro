document.addEventListener('alpine:init', () => {

    Alpine.data('pengaturanMasterKopPage', () => ({

        // ===================================================================
        // FILTER
        // ===================================================================
        jenisPphList: ['Semua Jenis Pph', 'PPh Pasal 23', 'PPh Pasal 4(2)', 'PPh Pasal 22', 'PPh Pasal 15', 'PPh Pasal 26'],
        filterJenisPph: 'Semua Jenis Pph',
        search: '',

        open: {
            jenisPph: false,
        },

        toggle(key) {
            const wasOpen = this.open[key];
            Object.keys(this.open).forEach((k) => (this.open[k] = false));
            this.open[key] = !wasOpen;
        },

        closeAll() {
            Object.keys(this.open).forEach((k) => (this.open[k] = false));
        },

        get filteredRows() {
            const q = this.search.trim().toLowerCase();
            return this.rows.filter((row) => {
                if (this.filterJenisPph !== 'Semua Jenis Pph' && row.klaster !== this.filterJenisPph) return false;
                if (q) {
                    const haystack = `${row.kode} ${row.nama} ${row.deskripsi}`.toLowerCase();
                    if (!haystack.includes(q)) return false;
                }
                return true;
            });
        },

        // ===================================================================
        // AKSI: Reset semua tarif ke nilai default standar
        // ===================================================================
        resetToDefault() {
            this.rows.forEach((row) => {
                row.tarif = row.tarifDefault;
            });
        },

        // ===================================================================
        // DUMMY DATA (nanti disambungkan ke backend — master KOP resmi)
        // ===================================================================
        rows: [
            { kode: '24-104-01', klaster: 'PPh Pasal 23', nama: 'Jasa Manajemen, Jasa Konsultasi, Jasa Teknik & Jasa Lainnya', deskripsi: 'Imbalan sehubungan dengan jasa teknik, manajemen, konsultan, dan jasa lain sesuai PMK 141.', tarif: 2, tarifDefault: 2 },
            { kode: '24-104-02', klaster: 'PPh Pasal 23', nama: 'Jasa Perawatan / Pemeliharaan / Perbaikan Mesin & Bangunan', deskripsi: 'Jasa pemeliharaan, perawatan fasilitas, peralatan mesin, dan perbaikan sarana kantor.', tarif: 2, tarifDefault: 2 },
            { kode: '24-104-03', klaster: 'PPh Pasal 23', nama: 'Jasa Kebersihan (Cleaning Service) & Keamanan (Security)', deskripsi: 'Jasa tenaga kebersihan kantor dan jasa satuan pengamanan lingkungan.', tarif: 2, tarifDefault: 2 },
            { kode: '24-104-04', klaster: 'PPh Pasal 23', nama: 'Jasa IT, Software Development, Server & Maintenance Web', deskripsi: 'Jasa pengembangan aplikasi, integrasi sistem IT, instalasi perangkat lunak dan web hosting.', tarif: 2, tarifDefault: 2 },
            { kode: '24-104-05', klaster: 'PPh Pasal 23', nama: 'Jasa Freight Forwarding, Logistik & Ekspedisi Pengiriman', deskripsi: 'Jasa pengurusan transportasi barang atau ekspedisi pengiriman.', tarif: 2, tarifDefault: 2 },
            { kode: '24-102-01', klaster: 'PPh Pasal 23', nama: 'Sewa & Penggunaan Harta (Selain Tanah dan/atau Bangunan)', deskripsi: 'Sewa kendaraan operasional, sewa alat berat, dan mesin kantor.', tarif: 2, tarifDefault: 2 },
            { kode: '24-100-01', klaster: 'PPh Pasal 23', nama: 'Dividen Diterima Wajib Pajak Badan Dalam Negeri', deskripsi: 'Pembagian dividen kepada perseroan dalam negeri (non-reinvestasi).', tarif: 2, tarifDefault: 2 },
            { kode: '24-101-01', klaster: 'PPh Pasal 23', nama: 'Bunga Pinjaman (Selain Bunga Bank)', deskripsi: 'Bunga pinjaman antar perusahaan atau pihak ketiga non-perbankan.', tarif: 2, tarifDefault: 2 },
            { kode: '24-103-01', klaster: 'PPh Pasal 23', nama: 'Royalti atas Penggunaan Hak Cipta, Paten, Merek Dagang', deskripsi: 'Imbalan royalti software, lisensi formula, paten atau hak cipta.', tarif: 2, tarifDefault: 2 },
            { kode: '24-105-01', klaster: 'PPh Pasal 23', nama: 'Hadiah, Penghargaan & Bonus Selain PPh 21', deskripsi: 'Hadiah perlombaan atau penghargaan yang diterima oleh WP Badan.', tarif: 2, tarifDefault: 2 },
            { kode: '28-402-01', klaster: 'PPh Pasal 4 ayat (2)', nama: 'Sewa Tanah dan/atau Bangunan (Gedung, Kantor, Ruko, Gudang)', deskripsi: 'Penghasilan dari persewaan tanah, gedung kantor, gudang, ruko, atau ruang usaha.', tarif: 2, tarifDefault: 2 },
            { kode: '28-403-01', klaster: 'PPh Pasal 4 ayat (2)', nama: 'Pengalihan Hak atas Tanah dan/atau Bangunan', deskripsi: 'Penghasilan dari pengalihan hak / penjualan properti tanah & bangunan.', tarif: 2, tarifDefault: 2 },
            { kode: '28-409-01', klaster: 'PPh Pasal 4 ayat (2)', nama: 'Jasa Konstruksi - Pelaksanaan (Sertifikat Kualifikasi Kecil)', deskripsi: 'Pelaksanaan konstruksi oleh penyedia jasa kualifikasi usaha kecil bersertifikat LPJK.', tarif: 2, tarifDefault: 2 },
            { kode: '28-409-02', klaster: 'PPh Pasal 4 ayat (2)', nama: 'Jasa Konstruksi - Pelaksanaan (Kualifikasi Menengah / Besar)', deskripsi: 'Pelaksanaan konstruksi oleh kontraktor kualifikasi menengah atau besar.', tarif: 2, tarifDefault: 2 },
            { kode: '28-409-03', klaster: 'PPh Pasal 4 ayat (2)', nama: 'Jasa Konstruksi - Konsultansi / Pengawasan Bersertifikat', deskripsi: 'Konsultansi perencanaan atau pengawasan pekerjaan konstruksi bersertifikat resmi.', tarif: 2, tarifDefault: 2 },
            { kode: '28-409-04', klaster: 'PPh Pasal 4 ayat (2)', nama: 'Jasa Konstruksi - Pelaksanaan Tanpa Sertifikat Kualifikasi', deskripsi: 'Pelaksanaan konstruksi oleh penyedia yang tidak memiliki sertifikat kualifikasi.', tarif: 2, tarifDefault: 2 },
            { kode: '28-401-01', klaster: 'PPh Pasal 4 ayat (2)', nama: 'Bunga Deposito, Tabungan & Diskonto SBI', deskripsi: 'Bunga dari deposito dan tabungan perbankan.', tarif: 2, tarifDefault: 2 },
            { kode: '28-419-01', klaster: 'PPh Pasal 4 ayat (2)', nama: 'Dividen Diterima Wajib Pajak Orang Pribadi Dalam Negeri', deskripsi: 'Dividen yang diterima oleh WP Orang Pribadi (non-reinvestasi).', tarif: 2, tarifDefault: 2 },
            { kode: '22-100-01', klaster: 'PPh Pasal 23', nama: 'Pengadaan / Pembelian Barang oleh Instansi Pemerintah & BUMN', deskripsi: 'Pemungutan PPh 22 atas pengadaan barang oleh bendaharawan instansi pemerintah dan BUMN.', tarif: 2, tarifDefault: 2 },
            { kode: '22-100-02', klaster: 'PPh Pasal 23', nama: 'Penjualan Bahan Bakar Minyak & Pelumas oleh Pertamina/Produsen', deskripsi: 'Penjualan BBM atau pelumas oleh badan usaha distributor/produsen.', tarif: 2, tarifDefault: 2 },
            { kode: '22-101-01', klaster: 'PPh Pasal 23', nama: 'Impor Barang Menggunakan API (Angka Pengenal Impor)', deskripsi: 'Impor barang resmi dengan izin API dari nilai impor (CIF + Bea Masuk).', tarif: 2, tarifDefault: 2 },
            { kode: '24-104-02', klaster: 'PPh Pasal 23', nama: 'Imbalan Jasa Pelayaran Dalam Negeri (Charter Kapal/Freight)', deskripsi: 'Charter kapal pengangkutan orang atau barang oleh perusahaan pelayaran nasional.', tarif: 2, tarifDefault: 2 },
            { kode: '25-100-02', klaster: 'PPh Pasal 23', nama: 'Imbalan Jasa Penerbangan Dalam Negeri (Charter Pesawat)', deskripsi: 'Penghasilan charter penerbangan maskapai nasional.', tarif: 2, tarifDefault: 2 },
            { kode: '27-100-01', klaster: 'PPh Pasal 23', nama: 'Imbalan Jasa Teknik, Manajemen, IT & Jasa Lainnya ke WPLN', deskripsi: 'Imbalan atas jasa yang dilakukan oleh Wajib Pajak Luar Negeri / vendor asing.', tarif: 2, tarifDefault: 2 },
            { kode: '27-100-03', klaster: 'PPh Pasal 23', nama: 'Royalti, Lisensi & Hak Penggunaan Cipta ke WPLN', deskripsi: 'Pembayaran lisensi software, SaaS subscription, royalti cipta ke vendor luar negeri.', tarif: 2, tarifDefault: 2 },
        ],

    }));

});