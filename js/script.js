// script.js
document.addEventListener('DOMContentLoaded', function() {
    // Konfirmasi logout
    var logoutLink = document.getElementById('logout');
    if (logoutLink) {
        logoutLink.addEventListener('click', function(e) {
            var confirmLogout = confirm("Apakah anda yakin ingin logout?");
            if (!confirmLogout) {
                e.preventDefault();
            }
        });
    }

    // Menangani tombol Edit: mengisikan data ke modal edit
    var editButtons = document.querySelectorAll('.editBtn');
    editButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            var nama = this.getAttribute('data-nama');
            var email = this.getAttribute('data-email');
            var phone = this.getAttribute('data-phone');
            var jabatan = this.getAttribute('data-jabatan');

            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_phone').value = phone;
            document.getElementById('edit_jabatan').value = jabatan;
        });
    });
});
