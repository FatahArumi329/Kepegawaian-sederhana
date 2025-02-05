<?php
include 'config.php';

// Jika ada parameter action, proses request AJAX untuk CRUD
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    header('Content-Type: application/json');
    
    if ($action == 'list') {
        // Mengambil data seluruh pegawai
        $sql = "SELECT pegawai.*, departemen.nama_departemen FROM pegawai 
                JOIN departemen ON pegawai.id_departemen = departemen.id_departemen 
                ORDER BY id_pegawai DESC";
        $result = $conn->query($sql);
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
        exit;
    } 
    elseif ($action == 'create') {
        // Menambahkan data pegawai baru
        $nama          = $_POST['nama'];
        $alamat        = $_POST['alamat'];
        $email         = $_POST['email'];
        $tgl_masuk     = $_POST['tgl_masuk'];
        $id_departemen = $_POST['id_departemen'];
        
        $sql  = "INSERT INTO pegawai (nama, alamat, email, tgl_masuk, id_departemen) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $nama, $alamat, $email, $tgl_masuk, $id_departemen);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Data berhasil disimpan', 'id' => $conn->insert_id]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Data gagal disimpan: ' . $stmt->error]);
        }
        exit;
    } 
    elseif ($action == 'update') {
        // Mengupdate data pegawai
        $id            = $_POST['id'];
        $nama          = $_POST['nama'];
        $alamat        = $_POST['alamat'];
        $email         = $_POST['email'];
        $tgl_masuk     = $_POST['tgl_masuk'];
        $id_departemen = $_POST['id_departemen'];
        
        $sql  = "UPDATE pegawai SET nama = ?, alamat = ?, email = ?, tgl_masuk = ?, id_departemen = ? WHERE id_pegawai = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssii", $nama, $alamat, $email, $tgl_masuk, $id_departemen, $id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Data berhasil diperbarui']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Data gagal diperbarui: ' . $stmt->error]);
        }
        exit;
    } 
    elseif ($action == 'delete') {
        // Menghapus data pegawai
        $id = $_POST['id'];
        $sql  = "DELETE FROM pegawai WHERE id_pegawai = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Data berhasil dihapus']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Data gagal dihapus: ' . $stmt->error]);
        }
        exit;
    }
}

// --- Di bawah ini adalah bagian HTML (aplikasi satu halaman) ---

// Ambil data departemen untuk dropdown
$deptQuery = "SELECT * FROM departemen";
$deptResult = $conn->query($deptQuery);
$deptOptions = [];
while($row = $deptResult->fetch_assoc()){
    $deptOptions[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Aplikasi CRUD Kepegawaian</title>
    <!-- Memuat Tailwind CSS dari CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Aplikasi CRUD Kepegawaian</h1>
    
    <!-- Container pesan alert -->
    <div id="alert-container" class="mb-4"></div>
    
    <!-- Form untuk tambah/edit pegawai -->
    <div id="form-container" class="mb-4 p-4 bg-white rounded shadow">
        <h2 class="text-xl font-semibold mb-2" id="form-title">Tambah Pegawai</h2>
        <form id="pegawai-form">
            <input type="hidden" id="pegawai-id" name="id">
            <div class="mb-2">
                <label class="block text-gray-700">Nama:</label>
                <!-- Tambahkan style text-transform untuk tampilan -->
                <input type="text" id="nama" name="nama" class="w-full border border-gray-300 p-2 rounded" style="text-transform: capitalize;" required>
            </div>
            <div class="mb-2">
                <label class="block text-gray-700">Alamat:</label>
                <input type="text" id="alamat" name="alamat" class="w-full border border-gray-300 p-2 rounded">
            </div>
            <div class="mb-2">
                <label class="block text-gray-700">Email:</label>
                <input type="email" id="email" name="email" class="w-full border border-gray-300 p-2 rounded">
            </div>
            <div class="mb-2">
                <label class="block text-gray-700">Tanggal Masuk:</label>
                <input type="date" id="tgl_masuk" name="tgl_masuk" class="w-full border border-gray-300 p-2 rounded">
            </div>
            <div class="mb-2">
                <label class="block text-gray-700">Departemen:</label>
                <select id="id_departemen" name="id_departemen" class="w-full border border-gray-300 p-2 rounded" required>
                    <option value="">Pilih Departemen</option>
                    <?php foreach ($deptOptions as $dept): ?>
                        <option value="<?= $dept['id_departemen'] ?>"><?= $dept['nama_departemen'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
                <button type="button" id="cancel-btn" class="bg-gray-500 text-white px-4 py-2 rounded hidden">Batal</button>
            </div>
        </form>
    </div>

    <!-- Tombol untuk menampilkan/menyembunyikan tabel Data Pegawai -->
    <div class="mb-4">
        <button id="toggle-data-btn" class="bg-indigo-500 text-white px-4 py-2 rounded">Tampilkan Data Pegawai</button>
    </div>

    <!-- Tabel untuk menampilkan data pegawai (disembunyikan secara default) -->
    <div id="data-container" class="bg-white p-4 rounded shadow hidden">
        <h2 class="text-xl font-semibold mb-2">Data Pegawai</h2>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alamat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Masuk</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Departemen</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody id="pegawai-table" class="bg-white divide-y divide-gray-200">
                <!-- Data akan di-inject melalui JavaScript -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal untuk menampilkan detail pegawai -->
<div id="detail-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded shadow-lg w-96 p-4 relative">
        <h2 class="text-xl font-bold mb-4">Detail Pegawai</h2>
        <div id="detail-content">
            <!-- Detail data pegawai akan muncul di sini -->
        </div>
        <button id="close-modal" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800">&times;</button>
    </div>
</div>

<!-- JavaScript untuk menangani CRUD, alert, modal, dan efek kapitalisasi -->
<script>
document.addEventListener('DOMContentLoaded', function(){
    const form = document.getElementById('pegawai-form');
    const cancelBtn = document.getElementById('cancel-btn');
    const formTitle = document.getElementById('form-title');
    const alertContainer = document.getElementById('alert-container');
    const toggleDataBtn = document.getElementById('toggle-data-btn');
    const dataContainer = document.getElementById('data-container');
    let editMode = false;

    // Fungsi untuk menampilkan pesan alert
    function showAlert(message, type = 'success'){
        // type: 'success' atau 'error'
        let bgColor = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
        alertContainer.innerHTML = `
            <div class="border ${bgColor} px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">${message}</span>
            </div>
        `;
        // Hapus pesan setelah 3 detik
        setTimeout(() => { alertContainer.innerHTML = ''; }, 3000);
    }

    // Fungsi untuk mengubah huruf pertama menjadi kapital (untuk setiap kata)
    function capitalizeWords(str) {
        return str.replace(/\b\w/g, function(match) {
            return match.toUpperCase();
        });
    }

    // Fungsi untuk mengambil dan menampilkan data pegawai
    function loadData(){
        fetch('?action=list')
        .then(response => response.json())
        .then(data => {
            let table = document.getElementById('pegawai-table');
            table.innerHTML = '';
            data.forEach(item => {
                let tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">${item.id_pegawai}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${item.nama}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${item.alamat}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${item.email}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${item.tgl_masuk}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${item.nama_departemen}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <button class="bg-green-500 text-white px-2 py-1 rounded mr-2 edit-btn" data-id="${item.id_pegawai}">Edit</button>
                        <button class="bg-blue-500 text-white px-2 py-1 rounded mr-2 detail-btn" data-id="${item.id_pegawai}">Detail</button>
                        <button class="bg-red-500 text-white px-2 py-1 rounded delete-btn" data-id="${item.id_pegawai}">Delete</button>
                    </td>
                `;
                table.appendChild(tr);
            });
            addEventListeners();
        });
    }

    // Menambahkan event listener untuk tombol edit, delete, dan detail
    function addEventListeners(){
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function(){
                let id = this.getAttribute('data-id');
                // Ambil data pegawai berdasarkan id dengan request list dan filter
                fetch('?action=list')
                .then(response => response.json())
                .then(data => {
                    let pegawai = data.find(p => p.id_pegawai == id);
                    if (pegawai) {
                        editMode = true;
                        formTitle.textContent = 'Edit Pegawai';
                        document.getElementById('pegawai-id').value = pegawai.id_pegawai;
                        // Terapkan fungsi kapitalisasi sebelum mengisi form
                        document.getElementById('nama').value = capitalizeWords(pegawai.nama);
                        document.getElementById('alamat').value = pegawai.alamat;
                        document.getElementById('email').value = pegawai.email;
                        document.getElementById('tgl_masuk').value = pegawai.tgl_masuk;
                        document.getElementById('id_departemen').value = pegawai.id_departemen;
                        cancelBtn.classList.remove('hidden');
                    }
                });
            });
        });

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(){
                if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                    let id = this.getAttribute('data-id');
                    let formData = new FormData();
                    formData.append('id', id);
                    fetch('?action=delete', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(result => {
                        showAlert(result.message, result.success ? 'success' : 'error');
                        if (result.success) loadData();
                    });
                }
            });
        });
        
        document.querySelectorAll('.detail-btn').forEach(btn => {
            btn.addEventListener('click', function(){
                let id = this.getAttribute('data-id');
                // Ambil data pegawai dan tampilkan di modal
                fetch('?action=list')
                .then(response => response.json())
                .then(data => {
                    let pegawai = data.find(p => p.id_pegawai == id);
                    if (pegawai) {
                        let detailContent = document.getElementById('detail-content');
                        detailContent.innerHTML = `
                            <p><strong>ID:</strong> ${pegawai.id_pegawai}</p>
                            <p><strong>Nama:</strong> ${capitalizeWords(pegawai.nama)}</p>
                            <p><strong>Alamat:</strong> ${pegawai.alamat}</p>
                            <p><strong>Email:</strong> ${pegawai.email}</p>
                            <p><strong>Tanggal Masuk:</strong> ${pegawai.tgl_masuk}</p>
                            <p><strong>Departemen:</strong> ${pegawai.id_departemen}</p>
                        `;
                        document.getElementById('detail-modal').classList.remove('hidden');
                    }
                });
            });
        });
    }

    // Menangani submit form untuk proses tambah atau update
    form.addEventListener('submit', function(e){
        e.preventDefault();
        // Pastikan nama memiliki kapital di awal setiap kata
        let namaInput = document.getElementById('nama');
        namaInput.value = capitalizeWords(namaInput.value);
        
        let formData = new FormData(form);
        let actionUrl = editMode ? '?action=update' : '?action=create';
        fetch(actionUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            showAlert(result.message, result.success ? 'success' : 'error');
            if (result.success) {
                form.reset();
                editMode = false;
                formTitle.textContent = 'Tambah Pegawai';
                cancelBtn.classList.add('hidden');
                loadData();
            }
        });
    });

    // Tombol batal untuk keluar dari mode edit
    cancelBtn.addEventListener('click', function(){
        form.reset();
        editMode = false;
        formTitle.textContent = 'Tambah Pegawai';
        cancelBtn.classList.add('hidden');
    });

    // Tombol untuk menampilkan/menyembunyikan data pegawai
    toggleDataBtn.addEventListener('click', function(){
        if (dataContainer.classList.contains('hidden')) {
            dataContainer.classList.remove('hidden');
            toggleDataBtn.textContent = 'Sembunyikan Data Pegawai';
            loadData();
        } else {
            dataContainer.classList.add('hidden');
            toggleDataBtn.textContent = 'Tampilkan Data Pegawai';
        }
    });

    // Event untuk menutup modal detail
    document.getElementById('close-modal').addEventListener('click', function(){
        document.getElementById('detail-modal').classList.add('hidden');
    });

    // Data awal akan dimuat ketika tabel ditampilkan
});
</script>
</body>
</html>
