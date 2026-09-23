document.addEventListener('alpine:init', () => {

    Alpine.data('rekapObjekPajakPage', () => ({

        // ===================================================================
        // FILTER MASA & TAHUN
        // ===================================================================
        masaBulanList: [
            'Januari (Masa 1)', 'Februari (Masa 2)', 'Maret (Masa 3)', 'April (Masa 4)',
            'Mei (Masa 5)', 'Juni (Masa 6)', 'Juli (Masa 7)', 'Agustus (Masa 8)',
            'September (Masa 9)', 'Oktober (Masa 10)', 'November (Masa 11)', 'Desember (Masa 12)',
        ],
        tahunList: [2024, 2025, 2026, 2027],
        masaSelected: 'Agustus (Masa 8)',
        tahunSelected: 2026,

        open: {
            masa: false,
            tahun: false,
        },

        toggle(key) {
            const wasOpen = this.open[key];
            Object.keys(this.open).forEach((k) => (this.open[k] = false));
            this.open[key] = !wasOpen;
        },

        closeAll() {
            Object.keys(this.open).forEach((k) => (this.open[k] = false));
        },

        get masaLabelPendek() {
            return this.masaSelected.split(' (')[0];
        },

        // ===================================================================
        // DUMMY DATA (nanti disambungkan ke backend, mengikuti filter masa/tahun)
        // ===================================================================
        totalPotonganPPh: 'IDR 47.940.000',
        totalTagihanPokok: 'IDR 625.000.000',
        totalTransferBersih: 'IDR 646.010.000',
        jumlahKlienVendor: 4,

        kopRows: [
            {
                kode: '24-104-01',
                desk: 'Jasa Manajemen, Jasa Konsultasi, Jasa Teknik & Jasa Lainnya',
                klaster: 'PPh Pasal 23',
                jumlahTagihan: 1,
                nilaiDPP: 'IDR 75.000.000',
                ppn: 'PPN 11%: RP8.250.000',
                totalTagihan: 'IDR 83.250.000',
                potonganPPh: 'IDR 1.500.000',
                transferBersih: 'IDR 81.750.000',
            },
            {
                kode: '28-402-01',
                desk: 'Sewa Tanah dan/atau Bangunan (Gedung, Kantor, Ruko, Gudang)',
                klaster: 'PPh Pasal 4 ayat (2)',
                jumlahTagihan: 1,
                nilaiDPP: 'IDR 250.000.000',
                ppn: 'PPN 11%: RP27.500.000',
                totalTagihan: 'IDR 277.500.000',
                potonganPPh: 'IDR 25.000.000',
                transferBersih: 'IDR 252.500.000',
            },
            {
                kode: '22-101-01',
                desk: 'Impor Barang Menggunakan API (Angka Pengenal Impor)',
                klaster: 'PPh Pasal 22',
                jumlahTagihan: 1,
                nilaiDPP: 'IDR 120.000.000',
                ppn: 'PPN 11%: RP13.200.000',
                totalTagihan: 'IDR 133.200.000',
                potonganPPh: 'IDR 1.440.000',
                transferBersih: 'IDR 131.760.000',
            },
            {
                kode: '25-100-02',
                desk: 'Imbalan Jasa Penerbangan Dalam Negeri (Charter Pesawat)',
                klaster: 'PPh Pasal 15',
                jumlahTagihan: 1,
                nilaiDPP: 'IDR 180.000.000',
                ppn: null,
                totalTagihan: 'IDR 180.000.000',
                potonganPPh: 'IDR 20.000.000',
                transferBersih: 'IDR 180.000.000',
            },
        ],

    }));

    Alpine.data('rekapLawanTransaksiPage', () => ({

        tahunList: [2024, 2025, 2026, 2027],
        tahunSelected: 2026,

        open: {
            tahun: false,
        },

        toggle(key) {
            const wasOpen = this.open[key];
            Object.keys(this.open).forEach((k) => (this.open[k] = false));
            this.open[key] = !wasOpen;
        },

        closeAll() {
            Object.keys(this.open).forEach((k) => (this.open[k] = false));
        },

        // Dummy data — nanti diagregasi dari data tagihan sesungguhnya per klien/vendor (year-to-date)
        klienRows: [
            {
                nama: 'CloudScale Technologies Pte. Ltd.',
                npwp: '02.456.789.1-013.000',
                negara: null,
                kategori: 'Badan',
                klaster: 'PPh Pasal 23',
                jumlahTagihan: 1,
                nilaiDPP: 'IDR 75.000.000',
                ppn: 'PPN 11%: RP8.250.000',
                totalTagihan: 'IDR 83.250.000',
                potonganPPh: 'IDR 1.500.000',
                bersihDitransfer: 'IDR 81.750.000',
            },
            {
                nama: 'PT Cipta Sarana Bangun Indonesia',
                npwp: '03.112.233.4-021.000',
                negara: null,
                kategori: 'Badan',
                klaster: 'PPh Pasal 4 ayat (2)',
                jumlahTagihan: 1,
                nilaiDPP: 'IDR 250.000.000',
                ppn: 'PPN 11%: RP27.500.000',
                totalTagihan: 'IDR 277.500.000',
                potonganPPh: 'IDR 25.000.000',
                bersihDitransfer: 'IDR 252.500.000',
            },
            {
                nama: 'PT Samudera Bahtera Nusantara',
                npwp: '01.998.877.6-044.000',
                negara: null,
                kategori: 'Badan',
                klaster: 'PPh Pasal 22',
                jumlahTagihan: 1,
                nilaiDPP: 'IDR 120.000.000',
                ppn: 'PPN 11%: RP13.200.000',
                totalTagihan: 'IDR 133.200.000',
                potonganPPh: 'IDR 1.440.000',
                bersihDitransfer: 'IDR 131.760.000',
            },
            {
                nama: 'CloudScale Technologies Pte. Ltd.',
                npwp: 'TAXID-SG-201844919',
                negara: 'Singapore (SG)',
                kategori: 'Wajib Pajak Luar Negeri (WPLN)',
                klaster: 'PPh Pasal 15',
                jumlahTagihan: 1,
                nilaiDPP: 'IDR 180.000.000',
                ppn: null,
                totalTagihan: 'IDR 180.000.000',
                potonganPPh: 'IDR 20.000.000',
                bersihDitransfer: 'IDR 180.000.000',
            },
            {
                nama: 'CV Mitra Logistik Sejahtera',
                npwp: '07.334.556.7-081.000',
                negara: null,
                kategori: 'Badan',
                klaster: 'PPh Pasal 23',
                jumlahTagihan: 1,
                nilaiDPP: 'IDR 45.000.000',
                ppn: 'PPN 11%: RP4.950.000',
                totalTagihan: 'IDR 49.950.000',
                potonganPPh: 'IDR 900.000',
                bersihDitransfer: 'IDR 49.050.000',
            },
        ],

    }));

});