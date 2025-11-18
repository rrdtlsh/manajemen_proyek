// Tunggu hingga seluruh elemen HTML selesai dimuat
document.addEventListener("DOMContentLoaded", function () {
    
    // Konfigurasi font default Chart.js agar sesuai style SB Admin 2
    Chart.defaults.font.family = 'Nunito, -apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.color = '#858796';

    // -------------------------------------------------------------------------
    // 1. GRAFIK TREN PENJUALAN (LINE CHART)
    // -------------------------------------------------------------------------
    const ctxSales = document.getElementById('salesChart');
    if (ctxSales && typeof salesData !== 'undefined' && salesData.length > 0) {
        const salesLabels = salesData.map(i => {
            // Format tanggal agar lebih cantik (contoh: 2023-11-01 -> 01 Nov)
            const date = new Date(i.tanggal);
            return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
        });
        const salesValues = salesData.map(i => i.jumlah);

        new Chart(ctxSales, {
            type: 'line',
            data: {
                labels: salesLabels,
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: salesValues,
                    backgroundColor: "rgba(78, 115, 223, 0.05)", // Area di bawah garis (transparan biru)
                    borderColor: "rgba(78, 115, 223, 1)",       // Warna garis (biru utama)
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
                layout: {
                    padding: { left: 10, right: 25, top: 25, bottom: 0 }
                },
                scales: {
                    x: {
                        time: { unit: 'date' },
                        grid: { display: false, drawBorder: false },
                        ticks: { maxTicksLimit: 7 }
                    },
                    y: {
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            stepSize: 1 // Pastikan sumbu Y hanya menampilkan bilangan bulat
                        },
                        grid: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    },
                },
                plugins: {
                    legend: { display: false }, // Sembunyikan legenda default
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
                    }
                }
            }
        });
    }

    // -------------------------------------------------------------------------
    // 2. GRAFIK PRODUK TERLARIS (DOUGHNUT CHART)
    // -------------------------------------------------------------------------
    const ctxTop = document.getElementById('topProductsChart');
    if (ctxTop && typeof topProductsData !== 'undefined' && topProductsData.length > 0) {
        new Chart(ctxTop, {
            type: 'doughnut',
            data: {
                labels: topProductsData.map(i => i.nama_produk),
                datasets: [{
                    data: topProductsData.map(i => i.total_terjual),
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
                            usePointStyle: true, // Gunakan titik bulat di legenda
                            padding: 20
                        }
                    }
                },
                cutout: '75%', // Ukuran lubang tengah donat
            },
        });
    }

    // -------------------------------------------------------------------------
    // 3. GRAFIK PENDAPATAN PER KATEGORI (HORIZONTAL BAR CHART)
    // -------------------------------------------------------------------------
    const ctxCat = document.getElementById('categorySalesChart');
    if (ctxCat && typeof categorySalesData !== 'undefined' && categorySalesData.length > 0) {
        new Chart(ctxCat, {
            type: 'bar',
            data: {
                labels: categorySalesData.map(i => i.nama_kategori || 'Tanpa Kategori'),
                datasets: [{
                    label: 'Total Pendapatan',
                    data: categorySalesData.map(i => i.total_pendapatan),
                    backgroundColor: '#1cc88a', // Hijau
                    hoverBackgroundColor: '#17a673',
                    borderColor: '#1cc88a',
                    borderWidth: 1,
                    borderRadius: 5 // Sudut bar sedikit membulat
                }]
            },
            options: {
                indexAxis: 'y', // PENTING: Mengubah orientasi menjadi Horizontal
                maintainAspectRatio: false,
                scales: {
                    x: {
                        ticks: {
                            callback: function(value) {
                                // Format angka sumbu X menjadi format mata uang singkat (Jt / rb)
                                if (value >= 1000000) return 'Rp ' + (value/1000000).toFixed(1) + 'Jt';
                                if (value >= 1000) return 'Rp ' + (value/1000).toFixed(0) + 'rb';
                                return value;
                            }
                        },
                        grid: { display: false }
                    },
                    y: {
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false }, // Sembunyikan legenda karena label sudah ada di sumbu Y
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                // Format tooltip menjadi Rupiah penuh (Rp 1.500.000)
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
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

    // -------------------------------------------------------------------------
    // 4. GRAFIK METODE PEMBAYARAN (PIE CHART)
    // -------------------------------------------------------------------------
    const ctxPay = document.getElementById('paymentMethodChart');
    if (ctxPay && typeof paymentMethodData !== 'undefined' && paymentMethodData.length > 0) {
        new Chart(ctxPay, {
            type: 'pie',
            data: {
                labels: paymentMethodData.map(i => i.metode_pembayaran || 'Lainnya'),
                datasets: [{
                    data: paymentMethodData.map(i => i.jumlah),
                    // Warna-warni: Kuning, Cyan, Merah, Abu-abu, Biru
                    backgroundColor: ['#f6c23e', '#36b9cc', '#e74a3b', '#858796', '#4e73df'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                }
            },
        });
    }
});