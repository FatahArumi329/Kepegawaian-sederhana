<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
include 'db/koneksi.php';

// Mengambil statistik
$total_pegawai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pegawai"))['total'];
$total_departemen = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM departemen"))['total'];
$total_jabatan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM jabatan"))['total'];

// Mengambil data absensi hari ini
$today = date('Y-m-d');
$absensi_hari_ini = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM absensi WHERE tanggal = '$today'"))['total'];

// Mengambil data pegawai terbaru
$pegawai_terbaru = mysqli_query($conn, "
    SELECT p.*, d.nama_departemen, j.nama_jabatan 
    FROM pegawai p 
    LEFT JOIN departemen d ON p.id_departemen = d.id_departemen
    LEFT JOIN jabatan j ON p.id_jabatan = j.id_jabatan
    ORDER BY p.id_pegawai DESC LIMIT 5
");

// Mengambil data untuk grafik absensi
$query_absensi = mysqli_query($conn, "
    SELECT status, COUNT(*) as total 
    FROM absensi 
    WHERE tanggal = '$today' 
    GROUP BY status
");

$status_labels = [];
$status_data = [];
while ($row = mysqli_fetch_assoc($query_absensi)) {
    $status_labels[] = $row['status'];
    $status_data[] = $row['total'];
}

$page_title = "Dashboard";
include 'includes/header.php';
?>

<main class="flex-1">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">
                <i class="fas fa-tachometer-alt text-indigo-600 mr-3"></i>Dashboard
            </h1>

            <!-- Statistik Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Pegawai -->
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-500 rounded-xl p-3">
                            <i class="fas fa-users text-white text-2xl"></i>
                        </div>
                        <div class="ml-5">
                            <p class="text-sm font-medium text-gray-500 truncate">Total Pegawai</p>
                            <p class="mt-1 text-3xl font-semibold text-gray-900"><?php echo $total_pegawai; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Total Departemen -->
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-xl p-3">
                            <i class="fas fa-building text-white text-2xl"></i>
                        </div>
                        <div class="ml-5">
                            <p class="text-sm font-medium text-gray-500 truncate">Total Departemen</p>
                            <p class="mt-1 text-3xl font-semibold text-gray-900"><?php echo $total_departemen; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Total Jabatan -->
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-xl p-3">
                            <i class="fas fa-briefcase text-white text-2xl"></i>
                        </div>
                        <div class="ml-5">
                            <p class="text-sm font-medium text-gray-500 truncate">Total Jabatan</p>
                            <p class="mt-1 text-3xl font-semibold text-gray-900"><?php echo $total_jabatan; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Absensi Hari Ini -->
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-500 rounded-xl p-3">
                            <i class="fas fa-clock text-white text-2xl"></i>
                        </div>
                        <div class="ml-5">
                            <p class="text-sm font-medium text-gray-500 truncate">Absensi Hari Ini</p>
                            <p class="mt-1 text-3xl font-semibold text-gray-900"><?php echo $absensi_hari_ini; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik dan Tabel -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Grafik Absensi -->
                <div class="bg-white shadow-lg rounded-2xl p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">
                        <i class="fas fa-chart-pie text-indigo-600 mr-2"></i>Statistik Absensi Hari Ini
                    </h2>
                    <div class="relative" style="height: 300px;">
                        <canvas id="absensiChart"></canvas>
                    </div>
                </div>

                <!-- Tabel Pegawai Terbaru -->
                <div class="bg-white shadow-lg rounded-2xl p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">
                        <i class="fas fa-user-plus text-indigo-600 mr-2"></i>Pegawai Terbaru
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departemen</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php while ($pegawai = mysqli_fetch_assoc($pegawai_terbaru)): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                        <i class="fas fa-user text-indigo-500"></i>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?php echo $pegawai['nama']; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo $pegawai['nama_departemen']; ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo $pegawai['nama_jabatan']; ?></div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="pegawai.php" class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 flex items-center space-x-4">
                    <div class="bg-indigo-100 rounded-xl p-3">
                        <i class="fas fa-user-plus text-indigo-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Tambah Pegawai</h3>
                        <p class="text-sm text-gray-500">Tambah data pegawai baru</p>
                    </div>
                </a>

                <a href="absensi.php" class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 flex items-center space-x-4">
                    <div class="bg-green-100 rounded-xl p-3">
                        <i class="fas fa-clock text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Absensi</h3>
                        <p class="text-sm text-gray-500">Kelola absensi pegawai</p>
                    </div>
                </a>

                <a href="departemen.php" class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 flex items-center space-x-4">
                    <div class="bg-yellow-100 rounded-xl p-3">
                        <i class="fas fa-building text-yellow-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Departemen</h3>
                        <p class="text-sm text-gray-500">Kelola data departemen</p>
                    </div>
                </a>

                <a href="jabatan.php" class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 flex items-center space-x-4">
                    <div class="bg-red-100 rounded-xl p-3">
                        <i class="fas fa-briefcase text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Jabatan</h3>
                        <p class="text-sm text-gray-500">Kelola data jabatan</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</main>

<!-- Script untuk Chart -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data untuk chart
    const statusLabels = <?php echo json_encode($status_labels); ?>;
    const statusData = <?php echo json_encode($status_data); ?>;

    // Konfigurasi warna
    const backgroundColors = [
        'rgba(59, 130, 246, 0.5)', // Hadir
        'rgba(239, 68, 68, 0.5)',  // Tidak Hadir
        'rgba(245, 158, 11, 0.5)'  // Izin
    ];
    const borderColors = [
        'rgb(59, 130, 246)',
        'rgb(239, 68, 68)',
        'rgb(245, 158, 11)'
    ];

    // Membuat chart
    const ctx = document.getElementById('absensiChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusData,
                backgroundColor: backgroundColors,
                borderColor: borderColors,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Animasi untuk cards
    const cards = document.querySelectorAll('.hover\\:shadow-xl');
    cards.forEach(card => {
        card.addEventListener('mouseover', () => {
            card.style.transform = 'translateY(-5px)';
            card.style.transition = 'transform 0.3s ease-in-out';
        });
        card.addEventListener('mouseout', () => {
            card.style.transform = 'translateY(0)';
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
