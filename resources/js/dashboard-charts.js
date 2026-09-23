import {
    Chart,
    BarController,
    BarElement,
    DoughnutController,
    ArcElement,
    CategoryScale,
    LinearScale,
    Tooltip,
} from 'chart.js';

// Daftarkan cuma komponen yang dipakai (lebih ringan daripada import semua 'chart.js/auto')
Chart.register(
    BarController,
    BarElement,
    DoughnutController,
    ArcElement,
    CategoryScale,
    LinearScale,
    Tooltip
);

function initDashboardCharts() {
    // ---- Bar chart: Potongan PPh per Klaster Unifikasi ----
    const barEl = document.getElementById('pphBarChart');
    if (barEl) {
        new Chart(barEl.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['PPh 23', 'PPh 4(2)', 'PPh 22', 'PPh 15'],
                datasets: [{
                    label: 'Potongan PPh',
                    data: [4200000, 25000000, 2800000, 20000000],
                    backgroundColor: '#2563eb',
                    hoverBackgroundColor: '#1d4ed8',
                    borderRadius: 6,
                    maxBarThickness: 64,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        enabled: true,
                        backgroundColor: '#E8F0FE',
                        titleColor: '#0f172a',
                        bodyColor: '#0f172a',
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            title: (items) => items[0].label,
                            label: (item) => 'Potongan PPh: Rp' + item.raw.toLocaleString('id-ID'),
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 26000000,
                        ticks: {
                            stepSize: 6500000,
                            callback: (v) => 'Rp' + (v / 1000000).toFixed(1) + 'M',
                            color: '#94a3b8',
                        },
                        grid: { color: '#f1f5f9' },
                    },
                    x: {
                        ticks: { color: '#64748b', font: { weight: '500' } },
                        grid: { display: false },
                    },
                },
            },
        });
    }

    // ---- Donut chart: Distribusi Kategori Klien / Vendor ----
    const donutEl = document.getElementById('klienDonutChart');
    if (donutEl) {
        new Chart(donutEl.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Badan (PT/CV)', 'WPLN (Asing)'],
                datasets: [{
                    data: [72, 28],
                    backgroundColor: ['#2563eb', '#065f46'],
                    hoverBackgroundColor: ['#1d4ed8', '#064e3b'],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        enabled: true,
                        backgroundColor: '#E8F0FE',
                        titleColor: '#0f172a',
                        bodyColor: '#0f172a',
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: (item) => item.label + ': ' + item.raw + '%',
                        },
                    },
                },
            },
        });
    }
}

// Dijalankan setiap kali dashboard di-load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDashboardCharts);
} else {
    // DOM udah siap duluan (misal kalau app.js ke-load belakangan / navigasi client-side)
    initDashboardCharts();
}

// Kalau project kamu pakai Livewire wire:navigate / Turbo-like navigation,
// event 'DOMContentLoaded' cuma nembak sekali di initial load.
// Uncomment baris di bawah kalau dashboard kamu diakses lewat navigasi tanpa full reload:
// document.addEventListener('livewire:navigated', initDashboardCharts);