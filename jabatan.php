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
$nama_jabatan = "";
$gaji_pokok = "";

// Proses form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_id']) && $_POST['update_id'] != "") {
        // PROSES UPDATE
        $id_update = $_POST['update_id'];
        $nama_jabatan = mysqli_real_escape_string($conn, $_POST['nama_jabatan']);
        $gaji_pokok = mysqli_real_escape_string($conn, $_POST['gaji_pokok']);
        
        $query = "UPDATE jabatan SET 
                  nama_jabatan='$nama_jabatan',
                  gaji_pokok='$gaji_pokok'
                  WHERE id_jabatan='$id_update'";
        
        if (!mysqli_query($conn, $query)) {
            die("Error updating record: " . mysqli_error($conn));
        }
        header("Location: jabatan.php");
        exit();
    } else if (isset($_POST['submit'])) {
        // PROSES INSERT
        $nama_jabatan = mysqli_real_escape_string($conn, $_POST['nama_jabatan']);
        $gaji_pokok = mysqli_real_escape_string($conn, $_POST['gaji_pokok']);
        
        $query = "INSERT INTO jabatan (nama_jabatan, gaji_pokok) 
                  VALUES ('$nama_jabatan', '$gaji_pokok')";
        if (!mysqli_query($conn, $query)) {
            die("Error inserting record: " . mysqli_error($conn));
        }
        header("Location: jabatan.php");
        exit();
    }
}

// PROSES DELETE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM jabatan WHERE id_jabatan='$id'";
    mysqli_query($conn, $query);
    header("Location: jabatan.php");
    exit();
}

// MODE EDIT
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $update_mode = true;
    $id_update = $id;
    
    $result = mysqli_query($conn, "SELECT * FROM jabatan WHERE id_jabatan='$id'");
    $row = mysqli_fetch_assoc($result);
    
    $nama_jabatan = $row['nama_jabatan'];
    $gaji_pokok = $row['gaji_pokok'];
}

// Query untuk menampilkan semua jabatan
$result = mysqli_query($conn, "SELECT * FROM jabatan ORDER BY nama_jabatan ASC");

$page_title = "Jabatan";
include 'includes/header.php';
require_once 'includes/delete_modal.php';
?>

<main class="flex-1">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-briefcase mr-3 text-indigo-600"></i>
                    Manajemen Jabatan
                </h1>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow-lg rounded-2xl mb-8">
                <div class="px-6 py-5">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">
                        <?php echo ($update_mode ? "Edit Data Jabatan" : "Input Data Jabatan Baru"); ?>
                    </h3>
                    <form method="POST" action="">
                        <input type="hidden" name="update_id" value="<?php echo $id_update; ?>">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nama_jabatan" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-tag text-gray-400 mr-2"></i>Nama Jabatan
                                </label>
                                <input type="text" name="nama_jabatan" id="nama_jabatan" 
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       value="<?php echo $nama_jabatan; ?>" required>
                            </div>

                            <div>
                                <label for="gaji_pokok" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-money-bill-wave text-gray-400 mr-2"></i>Gaji Pokok
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="gaji_pokok" id="gaji_pokok" 
                                           class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-12 pr-12 sm:text-sm border-gray-300 rounded-md"
                                           value="<?php echo $gaji_pokok; ?>" required
                                           min="0" step="1000">
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <?php if ($update_mode): ?>
                                <a href="jabatan.php" 
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
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Daftar Jabatan</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Jabatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gaji Pokok</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if (mysqli_num_rows($result) > 0): ?>
                                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo $row['nama_jabatan']; ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    Rp <?php echo number_format($row['gaji_pokok'], 0, ',', '.'); ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="jabatan.php?edit=<?= $row['id_jabatan'] ?>" 
                                                   class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <button onclick="showDeleteConfirmation(<?= $row['id_jabatan'] ?>, '<?= htmlspecialchars($row['nama_jabatan'], ENT_QUOTES) ?>', 'jabatan')" 
                                                        class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Belum ada data jabatan
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