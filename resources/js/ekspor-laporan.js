document.addEventListener('alpine:init', () => {

    Alpine.data('eksporLaporanPage', (dbInvoices = []) => {

        const masaBulanList = [
            'Januari (Masa 1)', 'Februari (Masa 2)', 'Maret (Masa 3)', 'April (Masa 4)',
            'Mei (Masa 5)', 'Juni (Masa 6)', 'Juli (Masa 7)', 'Agustus (Masa 8)',
            'September (Masa 9)', 'Oktober (Masa 10)', 'November (Masa 11)', 'Desember (Masa 12)',
        ];

        const today = new Date();

        return {
            invoices: Array.isArray(dbInvoices) ? dbInvoices : [],
            masaBulanList,
            masaSelected: masaBulanList[today.getMonth()],
            tahunPembukuan: today.getFullYear(),

            formatSelected: 'excel',

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

            // Ambil baris tagihan asli dari database yang cocok dengan masa & tahun pilihan
            get filteredRows() {
                return this.invoices.filter(
                    (row) => row.masaBulan === this.masaSelected && Number(row.tahun) === Number(this.tahunPembukuan)
                );
            },

            unduhLaporan() {
                if (this.formatSelected === 'excel') {
                    this.downloadCsv();
                } else {
                    this.bukaCetakPdf();
                }
            },

            downloadCsv() {
                const rows = this.filteredRows;

                if (rows.length === 0) {
                    alert(`Tidak ada data tagihan pada Masa ${this.masaLabelPendek} ${this.tahunPembukuan}.`);
                    return;
                }

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