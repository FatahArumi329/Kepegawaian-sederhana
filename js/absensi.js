// Fungsi untuk format tanggal
function formatDate(date) {
    const options = { day: 'numeric', month: 'long', year: 'numeric' };
    return new Date(date).toLocaleDateString('id-ID', options);
}

// Fungsi untuk format status badge
function getStatusBadgeClass(status) {
    switch(status) {
        case 'Hadir': return 'bg-green-100 text-green-800';
        case 'Izin': return 'bg-yellow-100 text-yellow-800';
        case 'Sakit': return 'bg-blue-100 text-blue-800';
        case 'Alpha': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}

// Filter tabel berdasarkan pencarian
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchText = this.value.toLowerCase();
    const table = document.getElementById('absensiTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        let found = false;

        for (let j = 0; j < cells.length; j++) {
            const cell = cells[j];
            if (cell.textContent.toLowerCase().indexOf(searchText) > -1) {
                found = true;
                break;
            }
        }

        row.style.display = found ? '' : 'none';
    }
});

// Filter berdasarkan status
document.getElementById('statusFilter').addEventListener('change', function() {
    const selectedStatus = this.value.toLowerCase();
    const table = document.getElementById('absensiTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const statusCell = row.querySelector('[data-status]');
        if (!statusCell) continue;

        const status = statusCell.getAttribute('data-status').toLowerCase();
        row.style.display = (selectedStatus === '' || status === selectedStatus) ? '' : 'none';
    }
});

// Filter berdasarkan tanggal
document.getElementById('dateFilter').addEventListener('change', function() {
    const selectedDate = this.value;
    const table = document.getElementById('absensiTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const dateCell = row.querySelector('[data-date]');
        if (!dateCell) continue;

        const date = dateCell.getAttribute('data-date');
        row.style.display = (selectedDate === '' || date === selectedDate) ? '' : 'none';
    }
});

// Reset semua filter
document.getElementById('resetFilters').addEventListener('click', function() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('dateFilter').value = '';

    const table = document.getElementById('absensiTable');
    const rows = table.getElementsByTagName('tr');
    for (let i = 1; i < rows.length; i++) {
        rows[i].style.display = '';
    }
});

// Konfirmasi hapus dengan SweetAlert2
function confirmDelete(id) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: "Apakah Anda yakin ingin menghapus data absensi ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `absensi.php?delete=${id}`;
        }
    });
    return false;
}

// Inisialisasi DataTable
$(document).ready(function() {
    $('#absensiTable').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"<"flex-1"l><"flex-1 md:text-right"f>>rtip',
        pageLength: 10,
        order: [[1, 'desc']], // Urutkan berdasarkan tanggal secara descending
    });
});
