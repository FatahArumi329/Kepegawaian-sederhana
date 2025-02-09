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
$nama = "";
$tgl_lahir = "";
$alamat = "";
$jenis_kelamin = "";
$status_nikah = "";
$id_departemen = "";
$id_jabatan = "";

// Proses form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_id']) && $_POST['update_id'] != "") {
        // PROSES UPDATE
        $id_update = $_POST['update_id'];
        $nama = mysqli_real_escape_string($conn, $_POST['nama']);
        $tgl_lahir = $_POST['tgl_lahir'];
        $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
        $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
        $status_nikah = mysqli_real_escape_string($conn, $_POST['status_nikah']);
        $id_departemen = $_POST['id_departemen'];
        $id_jabatan = $_POST['id_jabatan'];
        
        $query = "UPDATE pegawai SET 
                  nama='$nama',
                  tgl_lahir='$tgl_lahir',
                  alamat='$alamat',
                  jenis_kelamin='$jenis_kelamin',
                  status_nikah='$status_nikah',
                  id_departemen='$id_departemen',
                  id_jabatan='$id_jabatan'
                  WHERE id_pegawai='$id_update'";
        
        if (!mysqli_query($conn, $query)) {
            die("Error updating record: " . mysqli_error($conn));
        }
        header("Location: pegawai.php");
        exit();
    } else if (isset($_POST['submit'])) {
        // PROSES INSERT
        $nama = mysqli_real_escape_string($conn, $_POST['nama']);
        $tgl_lahir = $_POST['tgl_lahir'];
        $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
        $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
        $status_nikah = mysqli_real_escape_string($conn, $_POST['status_nikah']);
        $id_departemen = $_POST['id_departemen'];
        $id_jabatan = $_POST['id_jabatan'];
        
        $query = "INSERT INTO pegawai (nama, tgl_lahir, alamat, jenis_kelamin, status_nikah, id_departemen, id_jabatan) 
                  VALUES ('$nama', '$tgl_lahir', '$alamat', '$jenis_kelamin', '$status_nikah', '$id_departemen', '$id_jabatan')";
        
        if (!mysqli_query($conn, $query)) {
            die("Error inserting record: " . mysqli_error($conn));
        }
        header("Location: pegawai.php");
        exit();
    }
}

// PROSES DELETE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM pegawai WHERE id_pegawai='$id'";
    mysqli_query($conn, $query);
    header("Location: pegawai.php");
    exit();
}

// MODE EDIT
if (isset($_GET['edit'])) {
    $id_update = $_GET['edit'];
    $sql = "SELECT * FROM pegawai WHERE id_pegawai='$id_update'";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $update_mode = true;
        $nama = $row['nama'];
        $tgl_lahir = $row['tgl_lahir'];
        $alamat = $row['alamat'];
        $jenis_kelamin = $row['jenis_kelamin'];
        $status_nikah = $row['status_nikah'];
        $id_departemen = $row['id_departemen'];
        $id_jabatan = $row['id_jabatan'];
    }
}

// AMBIL DATA PEGAWAI
$result = mysqli_query($conn, "SELECT p.*, d.nama_departemen, j.nama_jabatan FROM pegawai p 
                              LEFT JOIN departemen d ON p.id_departemen = d.id_departemen
                              LEFT JOIN jabatan j ON p.id_jabatan = j.id_jabatan");

$page_title = "Manajemen Pegawai";
$active_page = "pegawai";
include 'includes/header.php';
require_once 'includes/delete_modal.php';
?>

<main>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-users mr-3 text-indigo-600"></i>
                    Manajemen Data Pegawai
                </h1>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow-lg rounded-2xl mb-8">
                <div class="px-6 py-5">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">
                        <?php echo ($update_mode ? "Edit Pegawai" : "Tambah Pegawai Baru"); ?>
                    </h3>
                    <form method="POST" action="">
                        <input type="hidden" name="update_id" value="<?php echo $id_update; ?>">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-user text-gray-400 mr-2"></i>Nama Lengkap
                                </label>
                                <input type="text" name="nama" id="nama" 
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg"
                                    value="<?php echo $nama; ?>" required>
                            </div>

                            <div>
                                <label for="tgl_lahir" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-calendar text-gray-400 mr-2"></i>Tanggal Lahir
                                </label>
                                <input type="date" name="tgl_lahir" id="tgl_lahir" 
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg"
                                    value="<?php echo $tgl_lahir; ?>" required>
                            </div>

                            <div>
                                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-venus-mars text-gray-400 mr-2"></i>Jenis Kelamin
                                </label>
                                <select name="jenis_kelamin" id="jenis_kelamin" 
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki" <?php echo ($jenis_kelamin == 'Laki-laki' ? 'selected' : ''); ?>>Laki-laki</option>
                                    <option value="Perempuan" <?php echo ($jenis_kelamin == 'Perempuan' ? 'selected' : ''); ?>>Perempuan</option>
                                </select>
                            </div>

                            <div>
                                <label for="status_nikah" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-ring text-gray-400 mr-2"></i>Status Pernikahan
                                </label>
                                <select name="status_nikah" id="status_nikah" 
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    <option value="">Pilih Status</option>
                                    <option value="Belum Menikah" <?php echo ($status_nikah == 'Belum Menikah' ? 'selected' : ''); ?>>Belum Menikah</option>
                                    <option value="Menikah" <?php echo ($status_nikah == 'Menikah' ? 'selected' : ''); ?>>Menikah</option>
                                    <option value="Cerai" <?php echo ($status_nikah == 'Cerai' ? 'selected' : ''); ?>>Cerai</option>
                                </select>
                            </div>

                            <div>
                                <label for="id_departemen" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-building text-gray-400 mr-2"></i>Departemen
                                </label>
                                <select name="id_departemen" id="id_departemen" 
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    <option value="">Pilih Departemen</option>
                                    <?php
                                    $dept_result = mysqli_query($conn, "SELECT * FROM departemen ORDER BY nama_departemen");
                                    while ($dept = mysqli_fetch_assoc($dept_result)) {
                                        $selected = ($id_departemen == $dept['id_departemen']) ? 'selected' : '';
                                        echo "<option value='" . $dept['id_departemen'] . "' $selected>" . $dept['nama_departemen'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div>
                                <label for="id_jabatan" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-sitemap text-gray-400 mr-2"></i>Jabatan
                                </label>
                                <select name="id_jabatan" id="id_jabatan" 
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    <option value="">Pilih Jabatan</option>
                                    <?php
                                    $jabatan_result = mysqli_query($conn, "SELECT * FROM jabatan ORDER BY nama_jabatan");
                                    while ($jabatan = mysqli_fetch_assoc($jabatan_result)) {
                                        $selected = ($id_jabatan == $jabatan['id_jabatan']) ? 'selected' : '';
                                        echo "<option value='" . $jabatan['id_jabatan'] . "' $selected>" . $jabatan['nama_jabatan'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label for="alamat" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>Alamat
                                </label>
                                <textarea name="alamat" id="alamat" rows="3"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg"
                                    required><?php echo $alamat; ?></textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <?php if ($update_mode): ?>
                                <a href="pegawai.php" 
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas fa-times mr-2"></i>Batal
                                </a>
                            <?php endif; ?>
                            <button type="submit" name="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-save mr-2"></i>
                                <?php echo ($update_mode ? "Update" : "Simpan"); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
                <div class="px-6 py-5">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Daftar Pegawai</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Kelamin</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Lahir</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Nikah</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departemen</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if (mysqli_num_rows($result) > 0): ?>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900"><?= $row['nama'] ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900"><?= $row['jenis_kelamin'] ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900"><?= date('d/m/Y', strtotime($row['tgl_lahir'])) ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900"><?= $row['status_nikah'] ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900"><?= $row['nama_departemen'] ?: '-' ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900"><?= $row['nama_jabatan'] ?: '-' ?></div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900"><?= $row['alamat'] ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="pegawai.php?edit=<?= $row['id_pegawai'] ?>" 
                                                   class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <button onclick="showDeleteConfirmation(<?= $row['id_pegawai'] ?>, '<?= htmlspecialchars($row['nama'], ENT_QUOTES) ?>', 'pegawai')" 
                                                        class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Belum ada data pegawai
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
