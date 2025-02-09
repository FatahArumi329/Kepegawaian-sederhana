        <!-- Footer -->
        <footer class="bg-gray-800 text-white mt-auto">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Kolom 1: Tentang -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">
                            <i class="fas fa-building mr-2"></i>Sistem Kepegawaian
                        </h3>
                        <p class="text-gray-300 text-sm">
                            Sistem manajemen kepegawaian yang memudahkan pengelolaan data karyawan, 
                            absensi, departemen, dan jabatan secara efisien dan terintegrasi.
                        </p>
                    </div>

                    <!-- Kolom 2: Fitur -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">
                            <i class="fas fa-list-alt mr-2"></i>Fitur Utama
                        </h3>
                        <ul class="text-gray-300 text-sm space-y-2">
                            <li><i class="fas fa-users mr-2"></i>Manajemen Pegawai</li>
                            <li><i class="fas fa-clock mr-2"></i>Pencatatan Absensi</li>
                            <li><i class="fas fa-building mr-2"></i>Pengelolaan Departemen</li>
                            <li><i class="fas fa-briefcase mr-2"></i>Manajemen Jabatan</li>
                        </ul>
                    </div>

                    <!-- Kolom 3: Kontak -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">
                            <i class="fas fa-envelope mr-2"></i>Kontak
                        </h3>
                        <ul class="text-gray-300 text-sm space-y-2">
                            <li>
                                <i class="fas fa-phone mr-2"></i>
                                <span>+62 123 4567 890</span>
                            </li>
                            <li>
                                <i class="fas fa-envelope mr-2"></i>
                                <span>info@kepegawaian.com</span>
                            </li>
                            <li>
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                <span>Jl. Contoh No. 123, Kota</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Copyright -->
                <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-400">
                    <p>&copy; <?php echo date('Y'); ?> Sistem Kepegawaian. All rights reserved.</p>
                    <p class="mt-2">
                        <span>Version 1.0.0</span>
                        <span class="mx-2">|</span>
                        <span>Last updated: <?php echo date('d M Y'); ?></span>
                    </p>
                </div>
            </div>
        </footer>

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Toggle mobile menu
            const menuButton = document.querySelector('[data-collapse-toggle="mobile-menu"]');
            const mobileMenu = document.getElementById('mobile-menu');

            if (menuButton && mobileMenu) {
                menuButton.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            // Close alert messages
            document.querySelectorAll('[data-dismiss-target]').forEach(button => {
                button.addEventListener('click', () => {
                    const target = document.querySelector(button.dataset.dismissTarget);
                    if (target) {
                        target.classList.add('hidden');
                    }
                });
            });
        </script>
    </div>
</body>
</html>
