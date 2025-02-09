<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}
include 'db/koneksi.php';

/* PROSES INSERT */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $id_pegawai = $_POST['id_pegawai'];
    $bulan = mysqli_real_escape_string($conn, $_POST['bulan']);
    $tahun = $_POST['tahun'];
    $total_gaji = mysqli_real_escape_string($conn, $_POST['total_gaji']);
    $query = "INSERT INTO gaji (id_pegawai, bulan, tahun, total_gaji) VALUES ('$id_pegawai', '$bulan', '$tahun', '$total_gaji')";
    mysqli_query($conn, $query);
    header("Location: gaji.php");
    exit();
}

/* PROSES DELETE */
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM gaji WHERE id_gaji='$id'";
    mysqli_query($conn, $query);
    header("Location: gaji.php");
    exit();
}

/* MODE EDIT */
$update_mode = false;
$id_update = "";
$id_pegawai_val = "";
$bulan_val = "";
$tahun_val = "";
$total_gaji_val = "";
if (isset($_GET['edit'])) {
    $id_update = $_GET['edit'];
    $sql = "SELECT * FROM gaji WHERE id_gaji='$id_update'";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $update_mode = true;
        $id_pegawai_val = $row['id_pegawai'];
        $bulan_val = $row['bulan'];
        $tahun_val = $row['tahun'];
        $total_gaji_val = $row['total_gaji'];
    }
}

/* PROSES UPDATE */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_id']) && $_POST['update_id'] != "") {
    $id_update = $_POST['update_id'];
    $id_pegawai_val = $_POST['id_pegawai'];
    $bulan_val = mysqli_real_escape_string($conn, $_POST['bulan']);
    $tahun_val = $_POST['tahun'];
    $total_gaji_val = mysqli_real_escape_string($conn, $_POST['total_gaji']);
    $query = "UPDATE gaji SET id_pegawai='$id_pegawai_val', bulan='$bulan_val', tahun='$tahun_val', total_gaji='$total_gaji_val' WHERE id_gaji='$id_update'";
    mysqli_query($conn, $query);
    header("Location: gaji.php");
    exit();
}

/* AMBIL DATA GAJI (JOIN dengan tabel pegawai) */
$query = "SELECT g.id_gaji, p.nama as nama_pegawai, g.bulan, g.tahun, g.total_gaji
          FROM gaji g
          LEFT JOIN pegawai p ON g.id_pegawai = p.id_pegawai";
$result = mysqli_query($conn, $query);

/* AMBIL DATA PEGAWAI untuk dropdown */
$pegawaiResult = mysqli_query($conn, "SELECT * FROM pegawai");

$page_title = "Manajemen Gaji";
$active_page = "gaji";
include 'includes/header.php';
?>
<?php require_once 'includes/delete_modal.php'; ?>
<main>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-money-bill-wave mr-3 text-indigo-600"></i>
                    Manajemen Gaji
                </h1>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow-lg rounded-2xl mb-8">
                <div class="px-6 py-5">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">
                        <?php echo ($update_mode ? "Edit Data Gaji" : "Input Data Gaji Baru"); ?>
                    </h3>
                    <form method="POST" action="">
                        <input type="hidden" name="update_id" value="<?php echo $id_update; ?>">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <label for="id_pegawai" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-user text-gray-400 mr-2"></i>Pegawai
                                </label>
                                <select name="id_pegawai" id="id_pegawai" 
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    required>
                                    <option value="">Pilih Pegawai</option>
                                    <?php
                                    while ($row = mysqli_fetch_assoc($pegawaiResult)) {
                                        $selected = ($row['id_pegawai'] == $id_pegawai_val) ? "selected" : "";
                                        echo "<option value='{$row['id_pegawai']}' $selected>{$row['nama']}</option>";
                                    }
                                    mysqli_data_seek($pegawaiResult, 0);
                                    ?>
                                </select>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="bulan" class="block text-sm font-medium text-gray-700">
                                    Bulan
                                </label>
                                <div class="mt-1">
                                    <select name="bulan" id="bulan" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                        <option value="" disabled <?php echo empty($bulan_val) ? 'selected' : ''; ?>>Pilih Bulan</option>
                                        <?php
                                        $bulan_list = array(
                                            '1' => 'Januari',
                                            '2' => 'Februari',
                                            '3' => 'Maret',
                                            '4' => 'April',
                                            '5' => 'Mei',
                                            '6' => 'Juni',
                                            '7' => 'Juli',
                                            '8' => 'Agustus',
                                            '9' => 'September',
                                            '10' => 'Oktober',
                                            '11' => 'November',
                                            '12' => 'Desember'
                                        );
                                        foreach ($bulan_list as $value => $label) {
                                            $selected = ($bulan_val == $value) ? 'selected' : '';
                                            echo "<option value='$value' $selected>$label</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="tahun" class="block text-sm font-medium text-gray-700">
                                    Tahun
                                </label>
                                <div class="mt-1">
                                    <select name="tahun" id="tahun" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                        <option value="" disabled <?php echo empty($tahun_val) ? 'selected' : ''; ?>>Pilih Tahun</option>
                                        <?php
                                        $current_year = date('Y');
                                        $start_year = $current_year - 5;
                                        $end_year = $current_year + 5;
                                        
                                        for ($year = $start_year; $year <= $end_year; $year++) {
                                            $selected = ($tahun_val == $year) ? 'selected' : '';
                                            echo "<option value='$year' $selected>$year</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="total_gaji" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-plus-circle text-gray-400 mr-2"></i>Total Gaji
                                </label>
                                <div class="mt-1 relative rounded-lg shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" step="0.01" name="total_gaji" id="total_gaji" 
                                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-12 pr-12 sm:text-sm border-gray-300 rounded-lg"
                                        placeholder="Masukkan total gaji" value="<?php echo $total_gaji_val; ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <?php if ($update_mode) { ?>
                                <a href="gaji.php" 
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas fa-times mr-2"></i>Batal
                                </a>
                            <?php } ?>
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
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Daftar Gaji</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pegawai</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bulan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Gaji</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if (mysqli_num_rows($result) > 0): ?>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?= $row['id_gaji'] ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?= $row['nama_pegawai'] ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?= $row['bulan'] ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?= $row['tahun'] ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                Rp <?= number_format($row['total_gaji'], 0, ',', '.') ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="gaji.php?edit=<?= $row['id_gaji'] ?>" 
                                                   class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <button onclick="showDeleteConfirmation(<?= $row['id_gaji'] ?>, '<?= htmlspecialchars($row['nama_pegawai'], ENT_QUOTES) ?>', 'gaji')" 
                                                        class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Belum ada data gaji
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
