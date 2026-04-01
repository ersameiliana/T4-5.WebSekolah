<?= $this->extend('layout/dashboard_template') ?>

<?= $this->section('page_title') ?>
Overview Akademik
<?= $this->endSection() ?>

<?php 
    // Ambil data sesi (sesuaikan dengan nama session mahasiswa kamu)
    $nama_mhs = session()->get('nama_mhs') ?? 'Mahasiswa';
    $nim_mhs = session()->get('nim') ?? '1234567890';
    $inisial = strtoupper(substr($nama_mhs, 0, 1));
?>

<?= $this->section('sidebar_menu') ?>
    <style>
        /* Desain Tombol Sidebar agar Clickable & Modern */
        .nav-item { 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            display: block; 
            border-radius: 10px; 
            margin-bottom: 8px; 
            padding: 12px 18px; /* Memberikan ruang klik yang luas */
            text-decoration: none; /* Hilangkan garis bawah link default */
            color: #94a3b8; /* Warna teks abu-abu terang */
            font-weight: 600;
            font-size: 0.95rem;
        }
        
        .nav-item:hover { 
            transform: translateX(8px); 
            background: rgba(255,255,255,0.05); 
            color: #fff;
        }
        
        /* Desain Khusus Menu yang Sedang Aktif / Dibuka */
        .nav-item.active { 
            background: linear-gradient(135deg, rgba(100, 255, 218, 0.1), rgba(59, 130, 246, 0.1)); 
            color: #64ffda; 
            border-left: 4px solid #64ffda;
        }
    </style>

    <?php 
        // Deteksi URL saat ini agar menu yang menyala bisa dinamis
        $current_uri = uri_string(); 
    ?>

    <a href="<?= base_url('mahasiswa/dashboard') ?>" class="nav-item <?= ($current_uri == 'mahasiswa/dashboard' || $current_uri == 'mahasiswa') ? 'active' : '' ?>">📊 Dashboard</a>
    <a href="<?= base_url('mahasiswa/jadwal') ?>" class="nav-item <?= ($current_uri == 'mahasiswa/jadwal') ? 'active' : '' ?>">📅 Jadwal Kuliah</a>
    <a href="<?= base_url('mahasiswa/khs') ?>" class="nav-item <?= ($current_uri == 'mahasiswa/khs') ? 'active' : '' ?>">📝 Kartu Hasil Studi (KHS)</a>
    <a href="<?= base_url('mahasiswa/materi') ?>" class="nav-item <?= ($current_uri == 'mahasiswa/materi') ? 'active' : '' ?>">📚 Materi & Tugas</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    /* KUSTOMISASI TOP BAR & PROFIL (POJOK KANAN ATAS) */
    .top-bar-area { display: flex; justify-content: flex-end; align-items: center; gap: 20px; margin-bottom: 30px; }
    .profile-info { display: flex; align-items: center; gap: 12px; text-align: right; }
    /* Avatar Mahasiswa menggunakan gradien warna Hijau-Teal */
    .profile-avatar { width: 45px; height: 45px; border-radius: 50%; background: linear-gradient(135deg, #10b981, #3b82f6); display: flex; justify-content: center; align-items: center; font-weight: 800; font-size: 1.2rem; color: #fff; border: 2px solid rgba(255,255,255,0.1); }
    
    /* TOMBOL LOGOUT ATAS */
    .btn-logout-top { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); padding: 10px 20px; border-radius: 10px; font-weight: 700; font-size: 0.9rem; text-decoration: none; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px; }
    .btn-logout-top:hover { background: rgba(239, 68, 68, 0.2); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(239, 68, 68, 0.2); color: #ef4444; }

    /* GRID & CARD STYLING */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
    
    .glass-card { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.08); border-radius: 1.2rem; padding: 25px; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); cursor: default; }
    .glass-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.4); }

    .stat-card h3 { margin: 0; font-size: 2rem; font-weight: 800; background: linear-gradient(to right, #64ffda, #3b82f6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .stat-card p { color: #94a3b8; margin: 5px 0 0 0; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;}
    
    /* Styling Tambahan untuk KHS & Tabel */
    .table-container { width: 100%; overflow-x: auto; }
    .khs-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    .khs-table th { text-align: left; padding: 12px 15px; color: #94a3b8; border-bottom: 1px solid rgba(255,255,255,0.1); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;}
    .khs-table td { padding: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); color: #f8fafc; font-size: 0.95rem; vertical-align: middle;}
    .khs-table tr { transition: 0.3s; }
    .khs-table tr:hover { background: rgba(255,255,255,0.03); }

    .nilai-huruf { font-weight: 900; font-size: 1.1rem; }
</style>

<div class="top-bar-area">
    <div class="profile-info">
        <div>
            <div style="font-weight: 700; color: #fff; font-size: 0.95rem;"><?= esc($nama_mhs) ?></div>
            <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600; text-transform: uppercase;">NIM: <?= esc($nim_mhs) ?></div>
        </div>
        <div class="profile-avatar">
            <?= $inisial ?>
        </div>
    </div>
    
    <div style="width: 1px; height: 30px; background: rgba(255,255,255,0.1);"></div>

    <a href="<?= base_url('auth/logout') ?>" id="btn-logout-mhs" class="btn-logout-top">
        <i class="bi bi-box-arrow-right"></i> Logout
    </a>
</div>

<div class="mb-4">
    <h2 class="fw-bolder text-white mb-1">Halo, <?= esc($nama_mhs) ?>! 👋</h2>
    <p class="text-secondary mb-0">Selamat datang kembali di Portal Akademik Mahasiswa.</p>
</div>

<div class="stats-grid">
    <div class="glass-card stat-card" style="border-top: 3px solid #64ffda;">
        <h3><?= esc($mahasiswa['total_sks'] ?? '0') ?></h3>
        <p>Total SKS Ditempuh</p>
    </div>
    <div class="glass-card stat-card" style="border-top: 3px solid #3b82f6;">
        <h3><?= esc($mahasiswa['ipk'] ?? '0.00') ?></h3>
        <p>IPK Saat Ini</p>
    </div>
    <div class="glass-card stat-card" style="border-top: 3px solid <?= ($mahasiswa['status_akademik'] ?? 'Aktif') != 'Aktif' ? '#ef4444' : '#10b981' ?>;">
        <?php $status_mhs = $mahasiswa['status_akademik'] ?? 'Aktif'; ?>
        <h3 style="<?= ($status_mhs != 'Aktif') ? 'background: none; -webkit-text-fill-color: #ef4444;' : 'background: none; -webkit-text-fill-color: #10b981;' ?>">
            <?= esc($status_mhs) ?>
        </h3>
        <p>Status Akademik</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 30px;">
    
    <div class="glass-card">
        <h3 style="margin-top:0; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 15px; font-size: 1.1rem; color: #fff; font-weight: 700; display: flex; align-items: center; gap: 10px;">
            <i class="bi bi-calendar-event" style="color: #64ffda;"></i> Jadwal Kuliah Hari Ini
        </h3>
        
        <?php if(!empty($jadwal_hari_ini)): ?>
            <div class="mt-3">
                <?php foreach($jadwal_hari_ini as $jadwal): ?>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <div class="d-flex align-items-center gap-3">
                            <div style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 8px; padding: 10px; text-align: center; min-width: 80px;">
                                <div style="font-weight: 700; color: #64ffda; font-size: 0.9rem;"><?= esc($jadwal['jam_mulai']) ?></div>
                                <div style="color: #64748b; font-size: 0.8rem;">s/d <?= esc($jadwal['jam_selesai']) ?></div>
                            </div>
                            <div>
                                <div style="font-weight: bold; color: #fff; font-size: 1rem;"><?= esc($jadwal['mata_kuliah']) ?></div>
                                <div style="color: #94a3b8; font-size: 0.85rem;"><i class="bi bi-geo-alt-fill me-1"></i> Ruang: <?= esc($jadwal['ruangan']) ?></div>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary rounded-pill px-3 py-1"><?= esc($jadwal['sks']) ?> SKS</span>
                            <div style="color: #64748b; font-size: 0.8rem; margin-top: 5px;"><i class="bi bi-person-video3 me-1"></i> <?= esc($jadwal['dosen']) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="padding: 40px 0; text-align: center; color: #64748b;">
                <i class="bi bi-cup-hot" style="font-size: 2.5rem; opacity: 0.5; margin-bottom: 10px; display: block;"></i>
                <p class="mb-0 fw-bold">Tidak ada jadwal kuliah untuk hari ini.</p>
                <p class="small">Selamat beristirahat atau mengerjakan tugas mandiri!</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="glass-card">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 15px;">
            <h3 style="margin: 0; font-size: 1.1rem; color: #fff; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                <i class="bi bi-journal-check" style="color: #3b82f6;"></i> KHS Semester Ini
            </h3>
            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-3 py-2 rounded-pill">Semester Genap 2025/2026</span>
        </div>

        <div class="table-container">
            <table class="khs-table">
                <thead>
                    <tr>
                        <th width="50%">Mata Kuliah</th>
                        <th width="15%" class="text-center">SKS</th>
                        <th width="15%" class="text-center">Nilai</th>
                        <th width="20%" class="text-end">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($data_khs)): ?>
                        <?php foreach($data_khs as $mk): ?>
                            <?php 
                                // LOGIKA LULUS / TIDAK LULUS (D dan E dianggap tidak lulus)
                                $nilai = strtoupper($mk['nilai_huruf']);
                                $isLulus = !in_array($nilai, ['D', 'E', 'T']); 
                            ?>
                            <tr>
                                <td>
                                    <strong class="text-white"><?= esc($mk['mata_kuliah']) ?></strong><br>
                                    <span style="font-size: 0.75rem; color: #64748b; text-transform: uppercase;"><?= esc($mk['kode_mk']) ?></span>
                                </td>
                                <td class="text-center fw-bold text-secondary"><?= esc($mk['sks']) ?></td>
                                <td class="text-center">
                                    <span class="nilai-huruf" style="color: <?= $isLulus ? '#10b981' : '#ef4444' ?>;"><?= esc($nilai) ?></span>
                                </td>
                                <td class="text-end">
                                    <?php if($isLulus): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-2 py-1"><i class="bi bi-check-circle"></i> Lulus</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-2 py-1"><i class="bi bi-x-circle"></i> Ulang</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 40px; color: #64748b;">
                                <i class="bi bi-file-earmark-x" style="font-size: 2rem; opacity: 0.5; margin-bottom: 10px; display: block;"></i>
                                Data KHS belum tersedia untuk semester ini.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-3 text-end">
             <a href="<?= base_url('mahasiswa/khs') ?>" style="color: #64ffda; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; transition: 0.2s;">
                 Lihat Semua KHS <i class="bi bi-arrow-right"></i>
             </a>
        </div>
    </div>
    
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // SweetAlert Logout Script khusus Mahasiswa
    document.getElementById('btn-logout-mhs').addEventListener('click', function(e) {
        e.preventDefault(); 
        const logoutUrl = this.getAttribute('href');

        Swal.fire({
            title: 'Akhiri Sesi Akademik?',
            text: "Pastikan Anda sudah menyimpan semua tugas atau form sebelum keluar.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Keluar',
            cancelButtonText: 'Batal',
            background: '#1e293b',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
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