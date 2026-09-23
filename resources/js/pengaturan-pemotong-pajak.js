document.addEventListener('alpine:init', () => {

    Alpine.data('pengaturanPemotongPajakPage', () => ({

        masaBulanList: [
            'Januari (Masa 1)', 'Februari (Masa 2)', 'Maret (Masa 3)', 'April (Masa 4)',
            'Mei (Masa 5)', 'Juni (Masa 6)', 'Juli (Masa 7)', 'Agustus (Masa 8)',
            'September (Masa 9)', 'Oktober (Masa 10)', 'November (Masa 11)', 'Desember (Masa 12)',
        ],

        // ===================================================================
        // FORM (tambah / edit)
        // ===================================================================
        form: {
            namaPerusahaan: '',
            npwpNitku: '',
            alamat: '',
            namaPejabat: '',
            jabatan: '',
            metodeDefault: 'Agustus (Masa 8)',
        },
        editingId: null,

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
                npwpNitku: '',
                alamat: '',
                namaPejabat: '',
                jabatan: '',
                metodeDefault: 'Agustus (Masa 8)',
            };
            this.editingId = null;
        },

        saveProfile() {
            if (this.editingId) {
                const idx = this.profiles.findIndex((p) => p.id === this.editingId);
                if (idx !== -1) this.profiles[idx] = { id: this.editingId, ...this.form };
            } else {
                this.profiles.push({ id: this.nextId++, ...this.form });
            }
            this.resetForm();
        },

        // ===================================================================
        // DUMMY DATA (nanti disambungkan ke backend)
        // ===================================================================
        nextId: 2,
        profiles: [
            {
                id: 1,
                namaPerusahaan: 'PT Nusantara Sinergi Pratama',
                npwpNitku: '01.234.567.8-012.000',
                alamat: 'Jl. Jenderal Sudirman Kav. 52-53, Senayan, Kebayoran Baru, Jakarta Selatan 12190',
                namaPejabat: 'Hendra Setiawan, S.E., Ak.',
                jabatan: 'Finance & Tax Manager',
                metodeDefault: 'Agustus (Masa 8)',
            },
        ],

        // ===================================================================
        // AKSI: EDIT — isi ulang form di atas dengan data row ini
        // ===================================================================
        editProfile(row) {
            this.form = {
                namaPerusahaan: row.namaPerusahaan,
                npwpNitku: row.npwpNitku,
                alamat: row.alamat,
                namaPejabat: row.namaPejabat,
                jabatan: row.jabatan,
                metodeDefault: row.metodeDefault,
            };
            this.editingId = row.id;
            this.$nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }));
        },

        // ===================================================================
        // AKSI: VIEW (modal detail read-only)
        // ===================================================================
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

        deleteConfirmed() {
            this.profiles = this.profiles.filter((p) => p.id !== this.confirmDeleteId);
            if (this.editingId === this.confirmDeleteId) this.resetForm();
            this.confirmDeleteId = null;
        },

    }));

});