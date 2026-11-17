document.addEventListener('alpine:init', () => {

    Alpine.data('pengaturanVendorPage', (initialVendors = []) => ({

        vendors: Array.isArray(initialVendors) ? initialVendors : [],

        // ===================================================================
        // FORM (tambah / edit)
        // ===================================================================
        form: {
            nama: '',
            kategoriWp: 'WP Badan Dalam Negeri',
            npwp: '',
            negara: 'Indonesia',
        },
        editingId: null,
        saving: false,

        get isWpln() {
            return (this.form.kategoriWp || '').includes('WPLN');
        },

        onKategoriChange() {
            if (this.isWpln && this.form.negara === 'Indonesia') {
                this.form.negara = '';
            } else if (!this.isWpln && !this.form.negara) {
                this.form.negara = 'Indonesia';
            }
        },

        resetForm() {
            this.form = {
                nama: '',
                kategoriWp: 'WP Badan Dalam Negeri',
                npwp: '',
                negara: 'Indonesia',
            };
            this.editingId = null;
        },

        getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        },

        buildPayload(data) {
            const wpln = (data.kategoriWp || '').includes('WPLN');
            return {
                nama_vendor: data.nama.trim(),
                kategori_wp: data.kategoriWp,
                npwp: wpln ? null : (data.npwp?.trim() || null),
                tax_id_luar_negeri: wpln ? (data.npwp?.trim() || null) : null,
                negara_domisili: data.negara?.trim() || (wpln ? null : 'Indonesia'),
            };
        },

        async saveProfile() {
            if (!this.form.nama.trim() || this.saving) return;
            this.saving = true;

            const isEdit = Boolean(this.editingId);
            const url = isEdit ? `/pengaturan/vendor/${this.editingId}` : '/pengaturan/vendor';
            const method = isEdit ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.getCsrfToken(),
                    },
                    body: JSON.stringify(this.buildPayload(this.form)),
                });

                const result = await response.json();

                if (!response.ok) {
                    alert(result.message || 'Gagal menyimpan data vendor.');
                    return;
                }

                if (isEdit) {
                    const idx = this.vendors.findIndex((v) => v.id === this.editingId);
                    if (idx !== -1) this.vendors[idx] = result.data;
                } else {
                    this.vendors.unshift(result.data);
                }

                this.resetForm();
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan koneksi saat menyimpan vendor.');
            } finally {
                this.saving = false;
            }
        },

        editVendor(row) {
            this.form = {
                nama: row.nama || '',
                kategoriWp: row.kategoriWp || 'WP Badan Dalam Negeri',
                npwp: row.npwp || '',
                negara: row.negara || 'Indonesia',
            };
            this.editingId = row.id;
            this.$nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }));
        },

        async copyVendor(row) {
            try {
                const copyData = {
                    nama: `${row.nama} (Copy)`,
                    kategoriWp: row.kategoriWp || 'WP Badan Dalam Negeri',
                    npwp: row.npwp || '',
                    negara: row.negara || 'Indonesia',
                };

                const response = await fetch('/pengaturan/vendor', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.getCsrfToken(),
                    },
                    body: JSON.stringify(this.buildPayload(copyData)),
                });

                const result = await response.json();
                if (response.ok && result.data) {
                    this.vendors.unshift(result.data);
                } else {
                    alert(result.message || 'Gagal menduplikat vendor.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan koneksi saat menduplikat vendor.');
            }
        },

        // ===================================================================
        // SEARCH
        // ===================================================================
        search: '',

        get filteredVendors() {
            const q = this.search.trim().toLowerCase();
            if (!q) return this.vendors;
            return this.vendors.filter((v) =>
                `${v.nama} ${v.kategoriWp ?? ''} ${v.npwp ?? ''} ${v.negara ?? ''}`.toLowerCase().includes(q)
            );
        },

        // ===================================================================
        // AKSI: DELETE (modal konfirmasi)
        // ===================================================================
        confirmDeleteId: null,

        askDelete(id) {
            this.confirmDeleteId = id;
        },

        cancelDelete() {
            this.confirmDeleteId = null;
        },

        async deleteConfirmed() {
            const id = this.confirmDeleteId;
            if (!id) return;

            try {
                const response = await fetch(`/pengaturan/vendor/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.getCsrfToken(),
                    },
                });

                const result = await response.json();

                if (!response.ok) {
                    alert(result.message || 'Gagal menghapus vendor.');
                    this.confirmDeleteId = null;
                    return;
                }

                this.vendors = this.vendors.filter((v) => v.id !== id);
                if (this.editingId === id) this.resetForm();
                this.confirmDeleteId = null;
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan koneksi saat menghapus vendor.');
            }
        },

    }));

});