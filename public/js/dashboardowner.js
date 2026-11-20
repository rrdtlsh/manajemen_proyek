// Tunggu hingga seluruh elemen HTML selesai dimuat
document.addEventListener("DOMContentLoaded", function () {
    
    Chart.defaults.font.family = 'Nunito, -apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.color = '#858796';

    // =======================================================
    // 1. GRAFIK TREN PENJUALAN (LINE CHART)
    // =======================================================
    const ctxSales = document.getElementById('salesChart');
    if (ctxSales && typeof salesData !== 'undefined' && salesData.length > 0) {
        
        // PENTING: Sesuaikan key 'i.tanggal' dan 'i.total' dengan hasil query Model DWH
        const salesLabels = salesData.map(i => {
            const date = new Date(i.tanggal); // Pastikan Model DWH return kolom 'tanggal'
            return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
        });
        const salesValues = salesData.map(i => i.total); // DWH return 'total' (SUM omzet)

        new Chart(ctxSales, {
            type: 'line',
            data: {
                labels: salesLabels,
                datasets: [{
                    label: 'Omzet Harian',
                    data: salesValues,
                    backgroundColor: "rgba(78, 115, 223, 0.05)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    fill: true,
                    tension: 0.3
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: { padding: { left: 10, right: 25, top: 25, bottom: 0 } },
                scales: {
                    x: { time: { unit: 'date' }, grid: { display: false, drawBorder: false }, ticks: { maxTicksLimit: 7 } },
                    y: {
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            callback: function(value) {
                                // Format Y Axis jadi Rupiah Singkat (Jt/rb)
                                if (value >= 1000000) return 'Rp ' + (value/1000000).toFixed(1) + 'Jt';
                                if (value >= 1000) return 'Rp ' + (value/1000).toFixed(0) + 'rb';
                                return 'Rp ' + value;
                            }
                        },
                        grid: { color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2] }
                    },
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyColor: "#858796",
                        titleMarginBottom: 10,
                        titleColor: '#6e707e',
                        titleFont: { size: 14 },
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        intersect: false,
                        mode: 'index',
                        caretPadding: 10,
                        callbacks: {
                            label: function(context) {
                                var label = context.dataset.label || '';
                                if (label) { label += ': '; }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }

    // =======================================================
    // 2. GRAFIK TOP PRODUK (DOUGHNUT)
    // =======================================================
    const ctxTop = document.getElementById('topProductsChart');
    if (ctxTop && typeof topProductsData !== 'undefined' && topProductsData.length > 0) {
        new Chart(ctxTop, {
            type: 'doughnut',
            data: {
                // Sesuaikan key dengan Model DWH: 'nama_produk' & 'total_qty'
                labels: topProductsData.map(i => i.nama_produk), 
                datasets: [{
                    data: topProductsData.map(i => i.total_qty), 
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                plugins: {
                    legend: { display: true, position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                },
                cutout: '75%',
            },
        });
    }

    // =======================================================
    // 3. GRAFIK KATEGORI (BAR CHART HORIZONTAL)
    // =======================================================
    const ctxCat = document.getElementById('categorySalesChart');
    if (ctxCat && typeof categorySalesData !== 'undefined' && categorySalesData.length > 0) {
        new Chart(ctxCat, {
            type: 'bar',
            data: {
                // Sesuaikan key: 'kategori' & 'total_pendapatan'
                labels: categorySalesData.map(i => i.kategori || 'Lainnya'), 
                datasets: [{
                    label: 'Pendapatan',
                    data: categorySalesData.map(i => i.total_pendapatan),
                    backgroundColor: '#1cc88a',
                    borderColor: '#1cc88a',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                indexAxis: 'y',
                maintainAspectRatio: false,
                scales: {
                    x: {
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) return 'Rp ' + (value/1000000).toFixed(1) + 'Jt';
                                return value;
                            }
                        },
                        grid: { display: false }
                    },
                    y: { grid: { display: false } }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) { label += ': '; }
                                if (context.parsed.x !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.x);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }

    // =======================================================
    // 4. GRAFIK METODE PEMBAYARAN (PIE CHART)
    // =======================================================
    const ctxPay = document.getElementById('paymentMethodChart');
    if (ctxPay && typeof paymentMethodData !== 'undefined' && paymentMethodData.length > 0) {
        new Chart(ctxPay, {
            type: 'pie', // Bisa ganti 'doughnut' jika ingin bolong tengahnya
            data: {
                // Key dari Model DWH: 'metode_pembayaran' & 'jumlah'
                labels: paymentMethodData.map(i => i.metode_pembayaran),
                datasets: [{
                    data: paymentMethodData.map(i => i.jumlah),
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                },
            },
        });
    }

    // =======================================================
    // 5. GRAFIK PERGERAKAN STOK (BAR CHART: MASUK vs KELUAR)
    // =======================================================
    const ctxStok = document.getElementById('stockMovementChart');
    if (ctxStok && typeof stockMovementData !== 'undefined' && stockMovementData.length > 0) {
        
        // Mapping Data
        const labels = stockMovementData.map(i => {
            const date = new Date(i.tanggal);
            return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
        });
        const dataMasuk = stockMovementData.map(i => i.total_masuk);
        const dataKeluar = stockMovementData.map(i => i.total_keluar);

        new Chart(ctxStok, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Barang Masuk',
                        data: dataMasuk,
                        backgroundColor: '#1cc88a', // Hijau
                        borderColor: '#17a673',
                        borderWidth: 1,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8
                    },
                    {
                        label: 'Barang Keluar',
                        data: dataKeluar,
                        backgroundColor: '#e74a3b', // Merah
                        borderColor: '#be2617',
                        borderWidth: 1,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { maxTicksLimit: 7 }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) { return value + ' Unit'; }
                        },
                        grid: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }
                },
                plugins: {
                    legend: { display: false }, // Kita pakai legend custom di HTML biar rapi
                    tooltip: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyColor: "#858796",
                        titleColor: '#6e707e',
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        mode: 'index',
                        intersect: false
                    }
                }
            }
        });
    }
});