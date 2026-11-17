document.addEventListener('alpine:init', () => {

    Alpine.data('pengaturanPemotongPajakPage', (initialProfiles = []) => ({

        metodeList: [
            'Normal (Pajak dipotong dari penghasilan)',
            'Gross Up (Pajak ditanggung oleh pemberi penghasilan)',
        ],

        // ===================================================================
        // FORM (tambah / edit)
        // ===================================================================
        form: {
            namaPerusahaan: '',
            npwp: '',
            alamat: '',
            namaPejabat: '',
            jabatan: '',
            metodeDefault: 'Normal (Pajak dipotong dari penghasilan)',
        },
        editingId: null,
        saving: false,

        open: {
            metode: false,
        },

        toggle(key) {
            const wasOpen = this.open[key];
            Object.keys(this.open).forEach((k) => (this.open[k] = false));
            this.open[key] = !wasOpen;
        },

        closeAll() {
            Object.keys(this.open).forEach((k) => (this.open[k] = false));
        },

        resetForm() {
            this.form = {
                namaPerusahaan: '',
                npwp: '',
                alamat: '',
                namaPejabat: '',
                jabatan: '',
                metodeDefault: 'Normal (Pajak dipotong dari penghasilan)',
            };
            this.editingId = null;
        },

        getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        },

        buildPayload(data) {
            return {
                nama_perusahaan: data.namaPerusahaan.trim(),
                npwp: data.npwp.trim(),
                alamat: data.alamat.trim(),
                nama_pejabat: data.namaPejabat.trim(),
                jabatan: data.jabatan.trim(),
                metode_default: data.metodeDefault,
            };
        },

        async saveProfile() {
            if (!this.form.namaPerusahaan.trim() || this.saving) return;
            this.saving = true;

            const isEdit = Boolean(this.editingId);
            const url = isEdit ? `/pengaturan/pemotong-pajak/${this.editingId}` : '/pengaturan/pemotong-pajak';
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
                    alert(result.message || 'Gagal menyimpan profil pemotong.');
                    return;
                }

                if (isEdit) {
                    const idx = this.profiles.findIndex((p) => p.id === this.editingId);
                    if (idx !== -1) this.profiles[idx] = result.data;
                } else {
                    this.profiles.unshift(result.data);
                }

                this.resetForm();
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan koneksi saat menyimpan data.');
            } finally {
                this.saving = false;
            }
        },

        profiles: Array.isArray(initialProfiles) ? initialProfiles : [],

        editProfile(row) {
            this.form = {
                namaPerusahaan: row.namaPerusahaan || '',
                npwp: row.npwp || '',
                alamat: row.alamat || '',
                namaPejabat: row.namaPejabat || '',
                jabatan: row.jabatan || '',
                metodeDefault: row.metodeDefault || 'Normal',
            };
            this.editingId = row.id;
            this.$nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }));
        },

        viewId: null,

        viewProfile(id) {
            this.viewId = id;
        },

        closeView() {
            this.viewId = null;
        },

        get viewRow() {
            return this.profiles.find((p) => p.id === this.viewId) ?? null;
        },

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
                const response = await fetch(`/pengaturan/pemotong-pajak/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.getCsrfToken(),
                    },
                });

                const result = await response.json();

                if (!response.ok) {
                    alert(result.message || 'Gagal menghapus data.');
                    this.confirmDeleteId = null;
                    return;
                }

                this.profiles = this.profiles.filter((p) => p.id !== id);
                if (this.editingId === id) this.resetForm();
                this.confirmDeleteId = null;
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan koneksi saat menghapus data.');
            }
        },

    }));

});