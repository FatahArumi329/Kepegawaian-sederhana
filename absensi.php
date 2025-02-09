<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}
include 'db/koneksi.php';

// Inisialisasi variabel
$update_mode = false;
$id_update = "";
$id_pegawai = "";
$tanggal = "";
$status = "";

// Proses form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_id']) && $_POST['update_id'] != "") {
        // PROSES UPDATE
        $id_update = $_POST['update_id'];
        $id_pegawai = mysqli_real_escape_string($conn, $_POST['id_pegawai']);
        $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);
        
        $query = "UPDATE absensi SET 
                  id_pegawai='$id_pegawai',
                  tanggal='$tanggal',
                  status='$status'
                  WHERE id_absensi='$id_update'";
        
        if (!mysqli_query($conn, $query)) {
            die("Error updating record: " . mysqli_error($conn));
        }
        header("Location: absensi.php");
        exit();
    } else if (isset($_POST['submit'])) {
        // PROSES INSERT
        $id_pegawai = mysqli_real_escape_string($conn, $_POST['id_pegawai']);
        $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);
        
        $query = "INSERT INTO absensi (id_pegawai, tanggal, status) 
                  VALUES ('$id_pegawai', '$tanggal', '$status')";
        if (!mysqli_query($conn, $query)) {
            die("Error inserting record: " . mysqli_error($conn));
        }
        header("Location: absensi.php");
        exit();
    }
}

// PROSES DELETE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM absensi WHERE id_absensi='$id'";
    mysqli_query($conn, $query);
    header("Location: absensi.php");
    exit();
}

// MODE EDIT
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $update_mode = true;
    $id_update = $id;
    
    $result = mysqli_query($conn, "SELECT * FROM absensi WHERE id_absensi='$id'");
    $row = mysqli_fetch_assoc($result);
    
    $id_pegawai = $row['id_pegawai'];
    $tanggal = $row['tanggal'];
    $status = $row['status'];
}

// Query untuk data pegawai (untuk dropdown)
$pegawai_result = mysqli_query($conn, "SELECT id_pegawai, nama FROM pegawai ORDER BY nama ASC");

// Query untuk menampilkan data absensi dengan nama pegawai
$result = mysqli_query($conn, "
    SELECT a.*, p.nama as nama_pegawai 
    FROM absensi a 
    LEFT JOIN pegawai p ON a.id_pegawai = p.id_pegawai 
    ORDER BY a.tanggal DESC
");

$page_title = "Absensi";
$active_page = "absensi";
include 'includes/header.php';
require_once 'includes/delete_modal.php';
?>

<main>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-calendar-check mr-3 text-indigo-600"></i>
                    Manajemen Absensi
                </h1>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow-lg rounded-2xl mb-8">
                <div class="px-6 py-5">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">
                        <?php echo ($update_mode ? "Edit Data Absensi" : "Input Data Absensi Baru"); ?>
                    </h3>
                    <form method="POST" action="">
                        <input type="hidden" name="update_id" value="<?php echo $id_update; ?>">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="id_pegawai" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-user text-gray-400 mr-2"></i>Pegawai
                                </label>
                                <select name="id_pegawai" id="id_pegawai" required
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Pilih Pegawai</option>
                                    <?php while ($pegawai = mysqli_fetch_assoc($pegawai_result)): ?>
                                        <option value="<?php echo $pegawai['id_pegawai']; ?>" 
                                                <?php echo ($id_pegawai == $pegawai['id_pegawai']) ? 'selected' : ''; ?>>
                                            <?php echo $pegawai['nama']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div>
                                <label for="tanggal" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-calendar text-gray-400 mr-2"></i>Tanggal
                                </label>
                                <input type="date" name="tanggal" id="tanggal" 
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       value="<?php echo $tanggal; ?>" required>
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-info-circle text-gray-400 mr-2"></i>Status
                                </label>
                                <select name="status" id="status" required
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Pilih Status</option>
                                    <option value="Hadir" <?php echo ($status == 'Hadir') ? 'selected' : ''; ?>>Hadir</option>
                                    <option value="Izin" <?php echo ($status == 'Izin') ? 'selected' : ''; ?>>Izin</option>
                                    <option value="Sakit" <?php echo ($status == 'Sakit') ? 'selected' : ''; ?>>Sakit</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <?php if ($update_mode): ?>
                                <a href="absensi.php" 
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas fa-times mr-2"></i>Batal
                                </a>
                            <?php endif; ?>
                            <button type="submit" name="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-save mr-2"></i><?php echo ($update_mode ? "Update" : "Simpan"); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
                <div class="px-6 py-5">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Daftar Absensi</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pegawai</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if (mysqli_num_rows($result) > 0): ?>
                                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo $row['nama_pegawai']; ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    <?php echo date('d/m/Y', strtotime($row['tanggal'])); ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    <?php 
                                                    echo match($row['status']) {
                                                        'Hadir' => 'bg-green-100 text-green-800',
                                                        'Izin' => 'bg-yellow-100 text-yellow-800',
                                                        'Sakit' => 'bg-red-100 text-red-800',
                                                        default => 'bg-gray-100 text-gray-800'
                                                    };
                                                    ?>">
                                                    <?php echo $row['status']; ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="absensi.php?edit=<?= $row['id_absensi'] ?>" 
                                                   class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <button onclick="showDeleteConfirmation(<?= $row['id_absensi'] ?>, '<?= htmlspecialchars($row['nama_pegawai'], ENT_QUOTES) ?>', 'absensi')" 
                                                        class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Belum ada data absensi
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php includeDeleteModal(); ?>
<?php include 'includes/footer.php'; ?>
