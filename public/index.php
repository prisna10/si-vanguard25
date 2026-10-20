<?php
require_once '../config/Database.php';
require_once '../models/PortalModel.php';

$database = new Database();
$db = $database->getConnection();
$portal = new PortalModel($db);

// Handle tambah tugas baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi']) && $_POST['aksi'] === 'tambah_tugas') {
    $portal->tambahTugas($_POST['mata_kuliah'], $_POST['judul_tugas'], $_POST['deadline'], $_POST['kategori']);
    header("Location: index.php");
    exit;
}

$daftarTugas = $db ? $portal->getAllTugas() : [];
$daftarMahasiswa = $db ? $portal->getAllMahasiswa() : [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SI-Vanguard 25 | UHW Perbanas</title>
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#1e3a8a">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800 pb-20 select-none">

    <!-- Header App Bar -->
    <header class="bg-blue-900 text-white p-4 shadow-md sticky top-0 z-10 flex justify-between items-center">
        <div>
            <h1 class="text-lg font-bold tracking-wide">SI-Vanguard 25</h1>
            <p class="text-xs text-blue-200">S1 Sistem Informasi • UHW Perbanas</p>
        </div>
        <span class="bg-amber-400 text-blue-950 text-xs font-bold px-2.5 py-1 rounded-full">Angkatan 2025</span>
    </header>

    <!-- Konten Utama -->
    <main class="p-4 max-w-md mx-auto space-y-6">

        <!-- Banner Info -->
        <div class="bg-gradient-to-r from-blue-800 to-indigo-900 rounded-2xl p-4 text-white shadow">
            <p class="text-xs uppercase tracking-wider text-blue-200">Status Server</p>
            <h2 class="text-base font-semibold mt-1">Portal Akademik & Proyek Aktif</h2>
            <p class="text-xs text-blue-100 mt-1">Pantau deadline tugas kuliah, kelompok proyek, dan poin softskill angkatan 2025.</p>
        </div>

        <!-- Section: Daftar Tugas Aktif -->
        <section>
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-bold text-slate-700 text-sm uppercase tracking-wider">📌 Deadline Tugas Terdekat</h3>
            </div>
            <div class="space-y-3">
                <?php foreach ($daftarTugas as $t): ?>
                <div class="bg-white p-3.5 rounded-xl shadow-sm border border-slate-200 flex justify-between items-start">
                    <div>
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded bg-blue-50 text-blue-700">
                            <?= htmlspecialchars($t['kategori']) ?>
                        </span>
                        <h4 class="font-bold text-sm mt-1.5 text-slate-800"><?= htmlspecialchars($t['judul_tugas']) ?></h4>
                        <p class="text-xs text-slate-500"><?= htmlspecialchars($t['mata_kuliah']) ?></p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-medium text-rose-600 bg-rose-50 px-2 py-1 rounded-lg block">
                            <?= htmlspecialchars($t['deadline']) ?>
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Form Tambah Tugas Cepat -->
        <section class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <h3 class="font-bold text-slate-700 text-sm mb-3">➕ Tambah Agenda Tugas (Komti)</h3>
            <form method="POST" class="space-y-3 text-sm">
                <input type="hidden" name="aksi" value="tambah_tugas">
                <input type="text" name="mata_kuliah" placeholder="Nama Mata Kuliah" required class="w-full p-2.5 border rounded-lg bg-slate-50">
                <input type="text" name="judul_tugas" placeholder="Judul Tugas / Proyek" required class="w-full p-2.5 border rounded-lg bg-slate-50">
                <div class="grid grid-cols-2 gap-2">
                    <input type="date" name="deadline" required class="w-full p-2.5 border rounded-lg bg-slate-50">
                    <select name="kategori" class="w-full p-2.5 border rounded-lg bg-slate-50">
                        <option value="Kelompok">Kelompok</option>
                        <option value="Individu">Individu</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-900 text-white font-semibold py-2.5 rounded-lg active:scale-95 transition">
                    Simpan ke Server
                </button>
            </form>
        </section>

        <!-- Section: Leaderboard Softskill Mahasiswa -->
        <section>
            <h3 class="font-bold text-slate-700 text-sm uppercase tracking-wider mb-3">🏆 Poin Softskill Angkatan 2025</h3>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 divide-y">
                <?php foreach ($daftarMahasiswa as $m): ?>
                <div class="p-3 flex justify-between items-center">
                    <div>
                        <p class="font-semibold text-sm"><?= htmlspecialchars($m['nama']) ?></p>
                        <p class="text-xs text-slate-400">NIM: <?= htmlspecialchars($m['nim']) ?> • <?= strtoupper($m['role']) ?></p>
                    </div>
                    <span class="font-bold text-sm text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg">
                        <?= (int)$m['poin_softskill'] ?> Pts
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

    </main>

    <!-- Bottom Navigation ala Aplikasi Android -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 flex justify-around py-2.5 text-xs font-medium text-slate-600 z-20">
        <a href="#" class="text-blue-900 font-bold flex flex-col items-center">
            <span>🏠</span><span>Beranda</span>
        </a>
        <a href="#" class="flex flex-col items-center">
            <span>👥</span><span>Kelompok</span>
        </a>
        <a href="#" class="flex flex-col items-center">
            <span>📜</span><span>Softskill</span>
        </a>
    </nav>

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('sw.js');
        }
    </script>
</body>
</html>