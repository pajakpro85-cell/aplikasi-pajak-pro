document.addEventListener('alpine:init', () => {

    Alpine.data('pengaturanVendorPage', () => ({

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
                id: this.nextId++,
                nama: row.nama + ' (Copy)',
                email: row.email,
                alamat: row.alamat,
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
                `${v.nama} ${v.email} ${v.alamat}`.toLowerCase().includes(q)
            );
        },

        // ===================================================================
        // DUMMY DATA (nanti disambungkan ke backend)
        // ===================================================================
        nextId: 5,
        vendors: [
            {
                id: 1,
                nama: 'PT Graha Pratama Solusindo',
                email: 'grahapratama@gmail.com',
                alamat: 'Gedung Menara Sudirman Lt. 14, Jl. Jend. Sudirman Kav. 60, Jakarta Selatan',
            },
            {
                id: 2,
                nama: 'PT Cipta Sarana Bangun Indonesia',
                email: '',
                alamat: 'Jl. TB Simatupang No. 88, Cilandak, Jakarta Selatan',
            },
            {
                id: 3,
                nama: 'PT Samudera Bahtera Nusantara',
                email: '',
                alamat: 'Jl. Pelabuhan Tanjung Priok No. 12, Jakarta Utara',
            },
            {
                id: 4,
                nama: 'CloudScale Technologies Pte. Ltd.',
                email: '',
                alamat: '1 Marina Boulevard #28-00, Marina Bay, Singapore 018989',
            },
        ],

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