<?= $this->extend('layout/dashboard_template') ?>

<?= $this->section('page_title') ?>
Control Center (Admin Panel)
<?= $this->endSection() ?>

<?php 
    // 🔥 SINKRON 100% DENGAN ENUM DATABASE: 'Editing', 'Administrasi', 'Sistem/Database' 🔥
    $role = session()->get('role_admin') ?? 'Guest'; 
    $nama_admin = session()->get('nama_admin') ?? 'Administrator';
    
    // Mengambil inisial huruf pertama untuk Avatar Profil
    $inisial = strtoupper(substr($nama_admin, 0, 1));
?>

<?= $this->section('sidebar_menu') ?>
    <style>
        .nav-item { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); display: block; border-radius: 8px; margin-bottom: 5px; }
        .nav-item:hover { transform: translateX(8px); background: rgba(255,255,255,0.05); }
    </style>

    <a href="<?= base_url('admin-secret-panel') ?>" class="nav-item active">📊 Dashboard Admin</a>

    <?php if (in_array($role, ['Editing', 'Sistem/Database'])): ?>
        <div style="color: #64748b; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; margin: 20px 0 10px 15px; letter-spacing: 1px;">Konten & Publikasi</div>
        <a href="<?= base_url('admin/profil') ?>" class="nav-item">🏛️ Kelola Profil Web</a>
        <a href="<?= base_url('admin/berita') ?>" class="nav-item">📰 Kelola Berita</a>
    <?php endif; ?>

    <?php if (in_array($role, ['Administrasi', 'Sistem/Database'])): ?>
        <div style="color: #64748b; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; margin: 20px 0 10px 15px; letter-spacing: 1px;">Data Akademik</div>
        <a href="<?= base_url('admin/pengguna') ?>" class="nav-item">👥 Kelola Akun User</a>
        <a href="<?= base_url('admin/jurusan') ?>" class="nav-item">🏢 Kelola Jurusan</a>
    <?php endif; ?>

    <?php if ($role === 'Sistem/Database'): ?>
        <div style="color: #ef4444; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; margin: 20px 0 10px 15px; letter-spacing: 1px;">Superadmin Tools</div>
        <a href="<?= base_url('admin/otorisasi') ?>" class="nav-item" style="color: #fca5a5;">🔐 Cabut Hak Akses</a>
        <a href="<?= base_url('admin/system-logs') ?>" class="nav-item" style="color: #fca5a5;">⚙️ Database Logs</a>
    <?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
    /* KUSTOMISASI TOP BAR & PROFIL (POJOK KANAN ATAS) */
    .top-bar-area { display: flex; justify-content: flex-end; align-items: center; gap: 20px; margin-bottom: 30px; }
    .profile-info { display: flex; align-items: center; gap: 12px; text-align: right; }
    .profile-avatar { width: 45px; height: 45px; border-radius: 50%; background: linear-gradient(135deg, #8b5cf6, #3b82f6); display: flex; justify-content: center; align-items: center; font-weight: 800; font-size: 1.2rem; color: #fff; border: 2px solid rgba(255,255,255,0.1); }
    
    /* TOMBOL LOGOUT ATAS */
    .btn-logout-top { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); padding: 10px 20px; border-radius: 10px; font-weight: 700; font-size: 0.9rem; text-decoration: none; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px; }
    .btn-logout-top:hover { background: rgba(239, 68, 68, 0.2); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(239, 68, 68, 0.2); color: #ef4444; }

    /* GRID & CARD STYLING */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .stat-card h3 { margin: 0; font-size: 1.8rem; color: #fff; }
    .stat-card p { color: #cbd5e1; margin: 5px 0 0 0; font-size: 0.9rem; }
    .role-badge { display: inline-block; padding: 5px 12px; border-radius: 50px; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; margin-bottom: 15px; }

    .glass-card { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.08); border-radius: 1.2rem; padding: 25px; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); cursor: default; }
    .glass-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.4); }
</style>

<?php 
    // 🔥 LOGIKA TEMA WARNA & TEKS BERDASARKAN ROLE 🔥
    if ($role == 'Editing') {
        $nama_jabatan = "Admin Content & Editing"; 
        $warna_tema   = "#3b82f6"; // Biru
        $bg_badge     = "rgba(59, 130, 246, 0.1)";
    } elseif ($role == 'Administrasi') {
        $nama_jabatan = "Admin Administrasi Data"; 
        $warna_tema   = "#f59e0b"; // Kuning/Orange
        $bg_badge     = "rgba(245, 158, 11, 0.1)";
    } else {
        $nama_jabatan = "Database Superadmin"; 
        $warna_tema   = "#9f1239"; // Merah Gelap
        $bg_badge     = "rgba(159, 18, 57, 0.1)";
    }
?>

<div class="top-bar-area">
    <div class="profile-info">
        <div>
            <div style="font-weight: 700; color: #fff; font-size: 0.95rem;"><?= esc($nama_admin) ?></div>
            <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600; text-transform: uppercase;"><?= esc($role) ?></div>
        </div>
        <div class="profile-avatar">
            <?= $inisial ?>
        </div>
    </div>
    
    <div style="width: 1px; height: 30px; background: rgba(255,255,255,0.1);"></div>

    <a href="<?= base_url('auth/logout') ?>" id="btn-logout-admin" class="btn-logout-top">
        🚪 Logout
    </a>
</div>

<div class="stats-grid">
    <div class="glass-card stat-card" style="border-top: 3px solid #10b981;">
        <h3>Sistem Online</h3>
        <p>Status Database Server</p>
    </div>
    <div class="glass-card stat-card" style="border-top: 3px solid <?= $warna_tema ?>;">
        <h3><?= esc($nama_admin) ?></h3>
        <p>User ID Login Saat Ini</p>
    </div>
</div>

<div class="glass-card">
    <span class="role-badge" style="background: <?= $bg_badge ?>; color: <?= $warna_tema ?>; border: 1px solid <?= $warna_tema ?>;">
        🛡️ LEVEL: <?= strtoupper($role) ?>
    </span>

    <h3 style="margin-top:0; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px; color: #fff;">
        Selamat Datang di Pusat Kendali Astryveil
    </h3>
    
    <p style="color: #cbd5e1; line-height: 1.6;">
        Anda saat ini login sebagai <strong><?= $nama_jabatan ?></strong>. 
        Gunakan menu di sidebar sebelah kiri untuk melakukan tugas dan fungsi sesuai dengan wewenang yang diberikan kepada Anda.
    </p>

    <?php if ($role === 'Editing'): ?>
        <div style="background: rgba(59, 130, 246, 0.1); border-left: 4px solid #3b82f6; padding: 15px; margin-top: 20px; border-radius: 0 10px 10px 0;">
            <p style="color: #60a5fa; font-size: 0.95rem; margin: 0;">📝 <strong>Hak Akses Anda:</strong> Anda memiliki wewenang untuk menambah, mengubah, dan menghapus konten Publikasi web dan memposting berita. Pastikan konten bebas dari *typo* sebelum di-<em>publish</em>.</p>
        </div>

    <?php elseif ($role === 'Administrasi'): ?>
        <div style="background: rgba(245, 158, 11, 0.1); border-left: 4px solid #f59e0b; padding: 15px; margin-top: 20px; border-radius: 0 10px 10px 0;">
            <p style="color: #fbbf24; font-size: 0.95rem; margin: 0;">🔒 <strong>Peringatan Akses:</strong> Anda berwenang mengelola data Pengguna (Akun login, data Mahasiswa/Dosen/Guest). <strong>Namun, wewenang Hapus (Delete) dikunci.</strong> Silakan hubungi Admin Database jika ada data yang wajib dihapus.</p>
        </div>

    <?php elseif ($role === 'Sistem/Database'): ?>
        <div style="background: rgba(159, 18, 57, 0.1); border-left: 4px solid #9F1239; padding: 15px; margin-top: 20px; border-radius: 0 10px 10px 0;">
            <p style="color: #ff4d4d; font-size: 0.95rem; margin: 0;">⚠️ <strong>Perhatian Superadmin:</strong> Anda memiliki akses tertinggi (Full CRUD) dan wewenang untuk mencabut izin admin lain. Perubahan atau penghapusan data pada panel ini akan langsung memicu *Trigger* MySQL dan memengaruhi *database* utama.</p>
        </div>

    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('btn-logout-admin').addEventListener('click', function(e) {
        e.preventDefault(); 
        const logoutUrl = this.getAttribute('href');

        Swal.fire({
            title: 'Akhiri Sesi?',
            text: "Pastikan semua perubahan data sudah Anda simpan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Keluar',
            cancelButtonText: 'Batal',
            background: '#1e293b',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading screen saat memproses logout
                Swal.fire({
                    title: 'Memutuskan koneksi...',
                    background: '#1e293b', color: '#fff',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                window.location.href = logoutUrl; 
            }
        });
    });
</script>

<?= $this->endSection() ?>