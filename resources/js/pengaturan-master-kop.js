document.addEventListener('alpine:init', () => {

    Alpine.data('pengaturanMasterKopPage', (dbObjects = [], dbClusters = []) => ({

        // ===================================================================
        // MAPPING DATA DARI DATABASE
        // ===================================================================
        rows: Array.isArray(dbObjects) ? dbObjects.map(obj => ({
            id: obj.id_object,
            kode: obj.kode_objek,
            klaster: obj.tax_cluster?.nama_klaster ?? obj.tax_cluster?.nama_cluster ?? 'Lainnya',
            nama: obj.nama_objek,
            deskripsi: obj.deskripsi ?? '',
            tarif: parseFloat(obj.tarif ?? 0),
        })) : [],

        // ===================================================================
        // FILTER
        // ===================================================================
        jenisPphList: ['Semua Jenis Pph', ...(Array.isArray(dbClusters) && dbClusters.length ? dbClusters : ['PPh Pasal 23', 'PPh Pasal 4(2)', 'PPh Pasal 22', 'PPh Pasal 15', 'PPh Pasal 26'])],
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

        getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        },

        // ===================================================================
        // AKSI: Simpan perubahan tarif per baris ke database
        // ===================================================================
        async updateTarif(row) {
            if (row.tarif === '' || isNaN(row.tarif) || row.tarif < 0) return;

            try {
                const response = await fetch(`/pengaturan/master-kop/${row.id}/tarif`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.getCsrfToken()
                    },
                    body: JSON.stringify({ tarif: row.tarif })
                });

                if (!response.ok) {
                    alert('Gagal menyimpan perubahan tarif.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan koneksi saat menyimpan tarif.');
            }
        },

        // ===================================================================
        // AKSI: Reset semua tarif ke nilai default standar di database
        // ===================================================================
        async resetToDefault() {
            if (!confirm('Reset semua tarif objek pajak ke nilai standar?')) return;

            try {
                const response = await fetch('/pengaturan/master-kop/reset', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.getCsrfToken()
                    }
                });

                const result = await response.json();
                if (response.ok && Array.isArray(result.data)) {
                    this.rows = result.data.map(obj => ({
                        id: obj.id_object,
                        kode: obj.kode_objek,
                        klaster: obj.tax_cluster?.nama_klaster ?? obj.tax_cluster?.nama_cluster ?? 'Lainnya',
                        nama: obj.nama_objek,
                        deskripsi: obj.deskripsi ?? '',
                        tarif: parseFloat(obj.tarif ?? 0),
                    }));
                } else {
                    alert('Gagal me-reset tarif ke standar.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan koneksi ke server.');
            }
        },

    }));

});