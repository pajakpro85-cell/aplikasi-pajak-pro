import { tagihanDummyList } from './data/tagihan-dummy';

document.addEventListener('alpine:init', () => {

    Alpine.data('eksporLaporanPage', () => {

        const masaBulanList = [
            'Januari (Masa 1)', 'Februari (Masa 2)', 'Maret (Masa 3)', 'April (Masa 4)',
            'Mei (Masa 5)', 'Juni (Masa 6)', 'Juli (Masa 7)', 'Agustus (Masa 8)',
            'September (Masa 9)', 'Oktober (Masa 10)', 'November (Masa 11)', 'Desember (Masa 12)',
        ];

        // Otomatis pilih bulan berjalan sesuai tanggal perangkat user saat halaman dibuka
        const today = new Date();

        return {
            masaBulanList,
            masaSelected: masaBulanList[today.getMonth()],
            tahunPembukuan: today.getFullYear(),

            // Default saat pertama buka halaman: format Excel (hijau) yang aktif
            formatSelected: 'excel', // 'excel' | 'pdf'

            open: {
                masa: false,
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

            incrementTahun() {
                this.tahunPembukuan++;
            },

            decrementTahun() {
                this.tahunPembukuan--;
            },

            // Ambil baris tagihan yang cocok sama masa & tahun yang lagi dipilih
            get filteredRows() {
                return tagihanDummyList.filter(
                    (row) => row.masaBulan === this.masaSelected && row.tahun === this.tahunPembukuan
                );
            },

            // ===================================================================
            // AKSI: UNDUH LAPORAN — beneran generate file, bukan tombol dummy
            // ===================================================================
            unduhLaporan() {
                if (this.formatSelected === 'excel') {
                    this.downloadCsv();
                } else {
                    this.bukaCetakPdf();
                }
            },

            // Format Excel/CSV: generate file asli via Blob, murni di browser (nggak butuh backend)
            downloadCsv() {
                const rows = this.filteredRows;

                const header = [
                    'No Invoice', 'Tanggal', 'Klien/Vendor', 'NPWP', 'Jenis PPh', 'Kode Objek',
                    'Nilai DPP', 'PPN', 'Total Tagihan', 'Tarif', 'Potongan PPh', 'Bersih Ditransfer', 'Status',
                ];

                const lines = [header.join(';')];

                rows.forEach((r) => {
                    const totalTagihan = r.dppRaw + r.ppnRaw;
                    lines.push([
                        r.noInvoice,
                        r.tanggalISO,
                        r.klien,
                        r.npwp,
                        r.jenisPphDisplay,
                        r.kopCode,
                        r.dppRaw,
                        r.ppnRaw,
                        totalTagihan,
                        r.tarifDisplay,
                        r.potonganRaw,
                        r.bersihRaw,
                        r.status,
                    ].join(';'));
                });

                // \ufeff (BOM) supaya Excel baca karakter non-ASCII (Rp, dsb) dengan benar
                const csvContent = '\ufeff' + lines.join('\r\n');
                const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                const url = URL.createObjectURL(blob);

                const filename = `Rekap-Tagihan-${this.masaLabelPendek}-${this.tahunPembukuan}.csv`;

                const link = document.createElement('a');
                link.href = url;
                link.download = filename;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(url);
            },

            // Format PDF: buka halaman cetak baru (voucher/approval), auto-trigger print dialog browser
            bukaCetakPdf() {
                const params = new URLSearchParams({
                    masa: this.masaSelected,
                    tahun: this.tahunPembukuan,
                });
                window.open('/ekspor-laporan/cetak?' + params.toString(), '_blank');
            },

        };
    });

});