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
$nama_departemen = "";

// Proses form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_id']) && $_POST['update_id'] != "") {
        // PROSES UPDATE
        $id_update = $_POST['update_id'];
        $nama_departemen = mysqli_real_escape_string($conn, $_POST['nama_departemen']);
        
        $query = "UPDATE departemen SET 
                  nama_departemen='$nama_departemen'
                  WHERE id_departemen='$id_update'";
        
        if (!mysqli_query($conn, $query)) {
            die("Error updating record: " . mysqli_error($conn));
        }
        header("Location: departemen.php");
        exit();
    } else if (isset($_POST['submit'])) {
        // PROSES INSERT
        $nama_departemen = mysqli_real_escape_string($conn, $_POST['nama_departemen']);
        
        $query = "INSERT INTO departemen (nama_departemen) VALUES ('$nama_departemen')";
        if (!mysqli_query($conn, $query)) {
            die("Error inserting record: " . mysqli_error($conn));
        }
        header("Location: departemen.php");
        exit();
    }
}

// PROSES DELETE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        $query = "DELETE FROM departemen WHERE id_departemen='$id'";
        if (mysqli_query($conn, $query)) {
            showAlert('success', 'Departemen berhasil dihapus');
        }
    } catch (mysqli_sql_exception $e) {
        if (strpos($e->getMessage(), 'foreign key constraint fails') !== false) {
            showAlert('error', 'Tidak dapat menghapus departemen karena masih memiliki pegawai yang terkait. Harap pindahkan atau hapus pegawai terlebih dahulu.');
        } else {
            showAlert('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    header("Location: departemen.php");
    exit();
}

// MODE EDIT
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $update_mode = true;
    $id_update = $id;
    
    $result = mysqli_query($conn, "SELECT * FROM departemen WHERE id_departemen='$id'");
    $row = mysqli_fetch_assoc($result);
    
    $nama_departemen = $row['nama_departemen'];
}

// Query untuk menampilkan semua departemen
$result = mysqli_query($conn, "SELECT * FROM departemen ORDER BY nama_departemen ASC");

$page_title = "Departemen";
include 'includes/header.php';
?>

<main class="flex-1">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-building mr-3 text-indigo-600"></i>
                    Manajemen Departemen
                </h1>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow-lg rounded-2xl mb-8">
                <div class="px-6 py-5">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">
                        <?php echo ($update_mode ? "Edit Data Departemen" : "Input Data Departemen Baru"); ?>
                    </h3>
                    <form method="POST" action="">
                        <input type="hidden" name="update_id" value="<?php echo $id_update; ?>">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="nama_departemen" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-tag text-gray-400 mr-2"></i>Nama Departemen
                                </label>
                                <input type="text" name="nama_departemen" id="nama_departemen" 
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       value="<?php echo $nama_departemen; ?>" required>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <?php if ($update_mode): ?>
                                <a href="departemen.php" 
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
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Daftar Departemen</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Departemen</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if (mysqli_num_rows($result) > 0): ?>
                                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo $row['nama_departemen']; ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                <a href="?edit=<?php echo $row['id_departemen']; ?>" 
                                                   class="text-indigo-600 hover:text-indigo-900">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <button onclick="showDeleteConfirmation(<?php echo $row['id_departemen']; ?>, '<?php echo htmlspecialchars($row['nama_departemen'], ENT_QUOTES); ?>')" 
                                                        class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Belum ada data departemen
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

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Konfirmasi Penghapusan
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500" id="deleteConfirmationText">
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <form id="deleteForm" method="GET" class="inline">
                    <input type="hidden" name="delete" id="deleteId">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Hapus
                    </button>
                </form>
                <button type="button" onclick="hideDeleteConfirmation()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showDeleteConfirmation(id, name) {
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteId').value = id;
    document.getElementById('deleteConfirmationText').textContent = `Apakah Anda yakin ingin menghapus departemen "${name}"?`;
}

function hideDeleteConfirmation() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>

<?php include 'includes/footer.php'; ?>
