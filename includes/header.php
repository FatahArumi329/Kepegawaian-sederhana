<?php
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . " - " : ""; ?>Sistem Kepegawaian</title>
    <?php 
    require_once __DIR__ . '/alert.php';
    require_once __DIR__ . '/logout_modal.php';
    ?>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        /* Untuk memastikan footer selalu di bawah */
        html {
            height: 100%;
        }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1 0 auto;
        }
        footer {
            flex-shrink: 0;
        }
    </style>
</head>
<body class="h-full">
    <?php displayAlert(); ?>
    <?php includeLogoutModal(); ?>
    <div class="min-h-full flex flex-col">
        <!-- Navigation -->
        <nav class="bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-building text-indigo-500 text-2xl"></i>
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?> px-3 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-home mr-2"></i>Dashboard
                                </a>
                                <a href="pegawai.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'pegawai.php' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?> px-3 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-users mr-2"></i>Pegawai
                                </a>
                                <a href="absensi.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'absensi.php' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?> px-3 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-clock mr-2"></i>Absensi
                                </a>
                                <a href="departemen.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'departemen.php' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?> px-3 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-building mr-2"></i>Departemen
                                </a>
                                <a href="jabatan.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'jabatan.php' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?> px-3 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-briefcase mr-2"></i>Jabatan
                                </a>
                                <a href="gaji.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'gaji.php' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?> px-3 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-money-bill-wave mr-2"></i>Gaji
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-4 flex items-center md:ml-6">
                            <button onclick="showLogoutConfirmation()" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Logout
                            </button>
                        </div>
                    </div>
                    <div class="-mr-2 flex md:hidden">
                        <!-- Mobile menu button -->
                        <button type="button" class="bg-gray-800 inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white" aria-controls="mobile-menu" aria-expanded="false" data-collapse-toggle="mobile-menu">
                            <span class="sr-only">Open main menu</span>
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div class="md:hidden" id="mobile-menu">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?> block px-3 py-2 rounded-md text-base font-medium">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                    <a href="pegawai.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'pegawai.php' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?> block px-3 py-2 rounded-md text-base font-medium">
                        <i class="fas fa-users mr-2"></i>Pegawai
                    </a>
                    <a href="absensi.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'absensi.php' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?> block px-3 py-2 rounded-md text-base font-medium">
                        <i class="fas fa-clock mr-2"></i>Absensi
                    </a>
                    <a href="departemen.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'departemen.php' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?> block px-3 py-2 rounded-md text-base font-medium">
                        <i class="fas fa-building mr-2"></i>Departemen
                    </a>
                    <a href="jabatan.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'jabatan.php' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?> block px-3 py-2 rounded-md text-base font-medium">
                        <i class="fas fa-briefcase mr-2"></i>Jabatan
                    </a>
                    <a href="gaji.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'gaji.php' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'; ?> block px-3 py-2 rounded-md text-base font-medium">
                        <i class="fas fa-money-bill-wave mr-2"></i>Gaji
                    </a>
                    <button onclick="showLogoutConfirmation()" 
                            class="w-full text-left inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Logout
                    </button>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="flex-grow flex flex-col min-h-0">
