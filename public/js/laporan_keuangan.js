$(document).ready(function() {
    $('#dataTableLaporan').DataTable({
        "order": [
            [0, "asc"] // Urutkan berdasarkan tgl
        ],
        "pageLength": 10 // Tampilkan 10 entry/hlmn
    });

    // Logika  filter
    const yearFilter = document.getElementById('yearFilter');
    const quarterFilter = document.getElementById('quarterFilter');
    const filterButton = document.getElementById('filterButton');
    const currentUrl = new URL(window.location.href);

    // Set nilai default filter berdasarkan URL
    if (yearFilter && currentUrl.searchParams.has('year')) {
        yearFilter.value = currentUrl.searchParams.get('year');
    }
    if (quarterFilter && currentUrl.searchParams.has('quarter')) {
        quarterFilter.value = currentUrl.searchParams.get('quarter');
    }

    // Tambahkan event listener ke tombol filter
    if (filterButton) {
        filterButton.addEventListener('click', function() {
            const selectedYear = yearFilter ? yearFilter.value : null;
            const selectedQuarter = quarterFilter ? quarterFilter.value : null;

            const newUrl = new URL(window.location.pathname, window.location.origin);

            if (selectedYear) {
                newUrl.searchParams.set('year', selectedYear);
            }
            if (selectedQuarter) {
                newUrl.searchParams.set('quarter', selectedQuarter);
            }
            window.location.href = newUrl.href;
        });
    }
});
