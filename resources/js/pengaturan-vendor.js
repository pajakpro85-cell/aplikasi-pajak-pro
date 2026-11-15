document.addEventListener('alpine:init', () => {

    // initialVendors = data dari database yang dikirim controller lewat Blade (@js)
    Alpine.data('pengaturanVendorPage', (initialVendors = []) => ({

        // ===================================================================
        // FORM (tambah / edit)
        // ===================================================================
        form: {
            nama: '',
            email: '',
            alamat: '',
        },
        editingId: null,

        resetForm() {
            this.form = { nama: '', email: '', alamat: '' };
            this.editingId = null;
        },

        saveProfile() {
            if (!this.form.nama.trim()) return;

            if (this.editingId) {
                const idx = this.vendors.findIndex((v) => v.id === this.editingId);
                if (idx !== -1) this.vendors[idx] = { id: this.editingId, ...this.form };
            } else {
                this.vendors.unshift({ id: this.nextId++, ...this.form });
            }
            this.resetForm();
        },

        editVendor(row) {
            this.form = { nama: row.nama, email: row.email, alamat: row.alamat };
            this.editingId = row.id;
            this.$nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }));
        },

        copyVendor(row) {
            this.vendors.unshift({
                ...row,
                id: this.nextId++,
                nama: row.nama + ' (Copy)',
            });
        },

        // ===================================================================
        // SEARCH
        // ===================================================================
        search: '',

        get filteredVendors() {
            const q = this.search.trim().toLowerCase();
            if (!q) return this.vendors;
            return this.vendors.filter((v) =>
                `${v.nama} ${v.kategoriWp ?? ''} ${v.npwp ?? ''} ${v.negara ?? ''} ${v.email ?? ''} ${v.alamat ?? ''}`.toLowerCase().includes(q)
            );
        },

        // ===================================================================
        // DATA VENDOR — sengaja dikosongkan, nanti diisi langsung dari database.
        // Field yang dipakai tabel untuk tiap baris:
        //   id, nama, kategoriWp, npwp (NPWP atau Tax ID luar negeri), negara
        // ===================================================================
        nextId: initialVendors.reduce((max, v) => Math.max(max, Number(v.id) || 0), 0) + 1,
        vendors: initialVendors,

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
            this.vendors = this.vendors.filter((v) => v.id !== this.confirmDeleteId);
            if (this.editingId === this.confirmDeleteId) this.resetForm();
            this.confirmDeleteId = null;
        },

    }));

});