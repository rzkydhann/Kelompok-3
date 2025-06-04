<?php
session_start();
if (!isset($_SESSION['loginpelanggan'])) {
    header("Location: loginpelanggan.php");
    exit;
}

$user = $_SESSION['userpelanggan'];
$layananPerbaikan = isset($_GET['layanan']) ? htmlspecialchars($_GET['layanan']) : '';

require '../function.php';

if (isset($_POST['kirim'])) {
    error_log("Data order: " . print_r($_POST, true)); // Debugging
    if (order($_POST) > 0) {
        echo "<script>alert('Permintaan berhasil dikirim'); document.location.href = 'history.php';</script>";
    } else {
        echo "<script>alert('Permintaan gagal dikirim'); document.location.href = 'history.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Halaman Order</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white fixed w-full z-10 top-0 shadow">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div>
                    <a href="../index.html" class="text-black font-bold text-xl">Rockshoes.id</a>
                </div>
                <div class="flex items-center space-x-6">
                    <a href="../index.html" class="hover:text-yellow-400">Beranda</a>
                    <a href="history.php" class="hover:text-yellow-400">Riwayat Pemesanan</a>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="font-medium"><?= htmlspecialchars($user); ?></span>
                    <a href="logout.php" class="hover:text-red-400">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <section class="pt-28 pb-12">
        <div class="max-w-3xl mx-auto bg-white p-8 shadow rounded-lg">
            <h2 class="text-2xl font-semibold mb-4">Form Permintaan Perbaikan</h2>
            <hr class="mb-6">
            <form action="" method="post" class="space-y-4">
                <div>
                    <label for="nama" class="block text-sm font-medium">Nama</label>
                    <input type="text" name="nama" id="nama" value="<?= htmlspecialchars($user); ?>" readonly
                           class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" />
                </div>
                <div>
                    <label for="hp" class="block text-sm font-medium">No. HP</label>
                    <input type="text" name="hp" id="hp" required
                           class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md" />
                </div>
                <div>
                    <label for="layananPerbaikan" class="block text-sm font-medium">Layanan Perbaikan</label>
                    <input type="text" name="layananPerbaikan" id="layananPerbaikan" value="<?= htmlspecialchars($layananPerbaikan); ?>" required
                           class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md" />
                </div>
                <div>
                    <label for="merk" class="block text-sm font-medium">Merk</label>
                    <input type="text" name="merk" id="merk" required
                           class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md" />
                </div>
                <div>
                    <label for="jenisperbaikan" class="block text-sm font-medium">Jenis Perbaikan</label>
                    <input type="text" name="jenisPerbaikan" id="jenisperbaikan" required
                           class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md" />
                </div>
                <div>
                    <label for="tanggal" class="block text-sm font-medium">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" required
                           class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md" />
                </div>
                <div>
                    <label for="waktu" class="block text-sm font-medium">Waktu</label>
                    <input type="time" name="waktu" id="waktu" required
                           class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md" />
                </div>
                <div>
                    <label for="alamat" class="block text-sm font-medium">Lokasi / Alamat</label>
                    <textarea name="alamat" id="alamat" rows="3" required
                              class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                </div>
                <input type="hidden" name="status" value="Menunggu Teknisi" />
                <input type="hidden" name="teknisi" value="" />
                <button type="submit" name="kirim"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                    Kirim
                </button>
            </form>
        </div>
    </section>
</body>
</html>