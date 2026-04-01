<?= $this->extend('layout/dashboard_template') ?>

<?= $this->section('page_title') ?>
Kelola Akun Pengguna
<?= $this->endSection() ?>

<?php 
    $role = session()->get('role_admin') ?? 'Guest'; 
    $nama_admin = session()->get('nama_admin') ?? 'Administrator';
    $inisial = strtoupper(substr($nama_admin, 0, 1));
    
    function getAvatarColor($name) {
        $colors = ['#ef4444', '#f59e0b', '#10b981', '#3b82f6', '#8b5cf6', '#ec4899'];
        return $colors[strlen($name) % count($colors)];
    }
?>

<?= $this->section('sidebar_menu') ?>
    <style>
        .nav-item { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); display: block; border-radius: 8px; margin-bottom: 5px; }
        .nav-item:hover { transform: translateX(8px); background: rgba(255,255,255,0.05); }
        .nav-section-title { color: #8892b0; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; margin: 20px 0 10px 15px; letter-spacing: 1px;}
    </style>
    <a href="<?= base_url('admin-secret-panel') ?>" class="nav-item">📊 Dashboard Admin</a>
    <?php if (in_array($role, ['Editing', 'Sistem/Database'])): ?>
        <div class="nav-section-title">Konten & Publikasi</div>
        <a href="<?= base_url('admin/profil') ?>" class="nav-item">🏛️ Kelola Profil Web</a>
        <a href="<?= base_url('admin/berita') ?>" class="nav-item">📰 Kelola Berita</a>
    <?php endif; ?>
    <?php if (in_array($role, ['Administrasi', 'Sistem/Database'])): ?>
        <div class="nav-section-title">Data Akademik</div>
        <a href="<?= base_url('admin/pengguna') ?>" class="nav-item active">👥 Kelola Akun User</a>
        <a href="<?= base_url('admin/jurusan') ?>" class="nav-item">🏢 Kelola Jurusan</a>
    <?php endif; ?>
    <?php if ($role === 'Sistem/Database'): ?>
        <div class="nav-section-title" style="color: #ff6b6b;">Superadmin Tools</div>
        <a href="<?= base_url('admin/otorisasi') ?>" class="nav-item" style="color: #fca5a5;">🔐 Cabut Hak Akses</a>
        <a href="<?= base_url('admin/system-logs') ?>" class="nav-item" style="color: #fca5a5;">⚙️ Database Logs</a>
    <?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    /* FIX MODAL BOCOR: Paksa semua pop-up sembunyi sampai dipanggil */
    .modal { display: none; z-index: 1055; }

    /* BASIC STYLING */
    .top-bar-area { display: flex; justify-content: flex-end; align-items: center; gap: 20px; margin-bottom: 30px; }
    .profile-info { display: flex; align-items: center; gap: 12px; text-align: right; }
    .profile-avatar { width: 45px; height: 45px; border-radius: 50%; background: linear-gradient(135deg, #3a86ff, #64ffda); display: flex; justify-content: center; align-items: center; font-weight: 800; font-size: 1.2rem; color: #0a192f; border: 2px solid rgba(100,255,218,0.2); }
    .btn-logout-top { background: rgba(220, 53, 69, 0.1); color: #ff6b6b; border: 1px solid rgba(220, 53, 69, 0.3); padding: 10px 20px; border-radius: 10px; font-weight: 700; font-size: 0.9rem; text-decoration: none; transition: 0.3s ease; display: flex; align-items: center; gap: 8px; }
    .btn-logout-top:hover { background: rgba(220, 53, 69, 0.2); transform: translateY(-2px); color: #ff6b6b; }

    /* STATS CARD */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .stat-card { position: relative; overflow: hidden; background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 25px; transition: 0.3s; }
    .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, var(--stat-color), transparent); }
    .stat-card h3 { margin: 0; font-size: 2.2rem; color: #fff; font-weight: 900; }
    .stat-card p { color: #ccd6f6; margin: 5px 0 0 0; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 700;}
    .stat-icon { font-size: 3rem; opacity: 0.1; position: absolute; top: 15px; right: 20px; }

    /* GLASS CARD WADAH UTAMA */
    .glass-card { position: relative; background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.08); border-radius: 20px; padding: 30px; }
    
    /* SEARCH & ACTION ROW */
    .controls-row { display: flex; justify-content: space-between; align-items: center; gap: 15px; flex-wrap: wrap;}
    .input-group-glass { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; display: flex; align-items: center; transition: 0.3s; flex: 1; min-width: 250px;}
    .input-group-glass:focus-within { border-color: #64ffda; box-shadow: 0 0 15px rgba(100, 255, 218, 0.2); }
    .input-group-glass .icon-wrapper { padding: 0 15px; color: #8892b0; }
    .input-group-glass .form-control { background: transparent; border: none; color: #fff; padding: 12px 15px 12px 0; box-shadow: none; width: 100%;}
    
    .btn-gradient-add { background: linear-gradient(135deg, #3a86ff, #64ffda); color: #0a192f; border: none; font-weight: 600; border-radius: 12px; padding: 12px 25px; transition: 0.3s; display: inline-flex; align-items: center; gap: 8px; text-transform: uppercase; font-size: 0.85rem;}
    .btn-gradient-add:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(100,255,218,0.3); color: #0a192f;}
    .btn-gradient-edit { background: linear-gradient(135deg, #3a86ff, #64ffda); color: #0a192f; border: none; font-weight: 600; border-radius: 10px; padding: 10px 25px; transition: 0.3s; }
    .btn-gradient-edit:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(100,255,218,0.3); color: #0a192f; }

    .form-group-custom label { color: #8892b0; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; font-size: 0.8rem; margin-bottom: 8px; display: block; }
    .form-group-custom .form-control, .form-group-custom .form-select { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); color: #ccd6f6; border-radius: 10px; padding: 12px 15px; font-size: 0.95rem; transition: 0.3s; }
    .form-group-custom .form-control:focus, .form-group-custom .form-select:focus { background: rgba(255, 255, 255, 0.08); border-color: #64ffda; box-shadow: 0 0 10px rgba(100, 255, 218, 0.2); outline: none; color: #ffffff;}
    .form-group-custom .form-control::placeholder { color: rgba(204, 214, 246, 0.4); }
    .form-group-custom select option { background: #0a192f; color: #ccd6f6; }
    .form-control:disabled, .form-control[readonly] { background-color: rgba(255, 255, 255, 0.02); opacity: 0.7; cursor: not-allowed; color: #64ffda; font-weight: bold;}

    /* TABS & TABLES */
    .filter-tabs { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 25px; margin-bottom: 20px; background: rgba(0,0,0,0.2); padding: 6px; border-radius: 14px; border: 1px solid rgba(255,255,255,0.05); }
    .btn-tab { flex: 1; background: transparent; border: none; color: #8892b0; border-radius: 10px; padding: 10px 15px; font-size: 0.9rem; font-weight: 700; transition: 0.3s; cursor: pointer; text-align: center; }
    .btn-tab.active { background: rgba(100, 255, 218, 0.1); color: #64ffda; box-shadow: 0 4px 10px rgba(100, 255, 218, 0.05); }
    .btn-tab:hover:not(.active) { color: #fff; background: rgba(255,255,255,0.05); }

    .table-glass { --bs-table-bg: transparent; --bs-table-color: #ccd6f6; --bs-table-border-color: rgba(255,255,255,0.05); margin-top: 10px; }
    .table-glass thead th { color: #8892b0; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; font-size: 0.85rem; padding: 15px 10px; border-bottom: 1px solid rgba(255,255,255,0.1) !important;}
    .table-glass tbody tr { transition: 0.3s; border-bottom: 1px solid rgba(255,255,255,0.05); }
    .table-glass tbody tr:hover { background: rgba(255,255,255,0.03); }
    .table-glass td { vertical-align: middle; padding: 15px 10px; font-size: 0.95rem; }
    mark { background: rgba(100, 255, 218, 0.4); color: #fff; border-radius: 3px; padding: 0 2px; }
    .user-avatar { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1rem; text-transform: uppercase; }

    .action-btn-group { display: flex; gap: 6px; justify-content: flex-end; }
    .btn-action { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: none; transition: 0.2s; font-size: 1rem; }
    .btn-edit-action { background: rgba(100, 255, 218, 0.1); color: #64ffda; }
    .btn-edit-action:hover { background: #64ffda; color: #0a192f; }
    .btn-del-action { background: rgba(220, 53, 69, 0.1); color: #ff6b6b; }
    .btn-del-action:hover { background: #ff6b6b; color: #fff; }
    .btn-lock-action { background: rgba(148, 163, 184, 0.1); color: #8892b0; cursor: not-allowed; }

    /* PAGINATION */
    .pagination-controls { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; flex-wrap: wrap; gap: 15px;}
    .page-size-selector { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255,255,255,0.1); color: #ccd6f6; border-radius: 8px; padding: 6px 12px; outline: none; }
    .page-size-selector:focus { border-color: #64ffda; }
    .pagination-glass { display: flex; gap: 5px; margin: 0; padding: 0; list-style: none; }
    .page-btn { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #8892b0; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 8px; cursor: pointer; transition: 0.2s; font-size: 0.85rem; font-weight: bold;}
    .page-btn:hover { background: rgba(255,255,255,0.1); color: #fff; }
    .page-btn.active { background: linear-gradient(135deg, #3a86ff, #64ffda); color: #0a192f; border-color: transparent; }
    .page-btn:disabled { opacity: 0.3; cursor: not-allowed; }

    /* MODAL GLASSMORPHISM */
    .glass-modal { background: rgba(10, 25, 47, 0.85) !important; backdrop-filter: blur(25px) !important; border: 1px solid rgba(255, 255, 255, 0.15) !important; border-radius: 18px !important; box-shadow: 0 20px 60px rgba(0,0,0,0.6) !important; }
    .glass-modal .modal-header { border-bottom: 1px solid rgba(255,255,255,0.1); padding: 20px 30px; }
    .glass-modal .modal-footer { border-top: 1px solid rgba(255,255,255,0.1); padding: 20px 30px; }
    .btn-close-custom { color: #ffffff; filter: invert(1) grayscale(100%) brightness(200%); opacity: 0.7; transition: 0.3s; }
    .btn-close-custom:hover { opacity: 1; filter: drop-shadow(0 0 5px #64ffda) invert(1); }
</style>

<div class="top-bar-area">
    <div class="profile-info">
        <div>
            <div style="font-weight: 700; color: #fff; font-size: 0.95rem;"><?= esc($nama_admin) ?></div>
            <div style="font-size: 0.75rem; color: #8892b0; font-weight: 600; text-transform: uppercase;"><?= esc($role) ?></div>
        </div>
        <div class="profile-avatar"><?= $inisial ?></div>
    </div>
    <div style="width: 1px; height: 30px; background: rgba(255,255,255,0.1);"></div>
    <a href="<?= base_url('auth/logout') ?>" id="btn-logout-admin" class="btn-logout-top"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<div class="stats-grid">
    <div class="glass-card stat-card" style="--stat-color: #8b5cf6;">
        <i class="bi bi-file-earmark-person stat-icon" style="color: #8b5cf6;"></i>
        <h3><?= esc($stats['pendaftar'] ?? '0') ?></h3><p>Calon Mahasiswa</p>
    </div>
    <div class="glass-card stat-card" style="--stat-color: #64ffda;">
        <i class="bi bi-mortarboard stat-icon" style="color: #64ffda;"></i>
        <h3><?= esc($stats['mhs'] ?? '0') ?></h3><p>Mhs Terdaftar</p>
    </div>
    <div class="glass-card stat-card" style="--stat-color: #10b981;">
        <i class="bi bi-person-video3 stat-icon" style="color: #10b981;"></i>
        <h3><?= esc($stats['dosen'] ?? '0') ?></h3><p>Dosen / Pendidik</p>
    </div>
    <div class="glass-card stat-card" style="--stat-color: #f59e0b;">
        <i class="bi bi-person-badge stat-icon" style="color: #f59e0b;"></i>
        <h3><?= esc($stats['guest'] ?? '0') ?></h3><p>Guest / Wali</p>
    </div>
</div>

<div class="glass-card">
    <div class="controls-row">
        <div class="input-group-glass">
            <div class="icon-wrapper"><i class="bi bi-search"></i></div>
            <input type="text" id="searchUser" class="form-control" placeholder="Ketik nama atau ID untuk mencari..." autocomplete="off">
        </div>
        <button class="btn-gradient-add" data-bs-toggle="modal" data-bs-target="#modalPilihKategori">
            <i class="bi bi-person-plus-fill"></i> Tambah Akun
        </button>
    </div>

    <div class="filter-tabs">
        <button class="btn-tab active" onclick="bukaTab('tab-pendaftar', this)"><i class="bi bi-pencil-square me-1"></i> Pendaftar</button>
        <button class="btn-tab" onclick="bukaTab('tab-mhs', this)"><i class="bi bi-mortarboard me-1"></i> Mahasiswa</button>
        <button class="btn-tab" onclick="bukaTab('tab-dosen', this)"><i class="bi bi-person-workspace me-1"></i> Dosen</button>
        <button class="btn-tab" onclick="bukaTab('tab-guest', this)"><i class="bi bi-people me-1"></i> Guest</button>
    </div>

    <div id="tab-pendaftar" class="table-area" style="display: block;">
        <div class="table-responsive">
            <table class="table table-glass table-hover mb-0">
                <thead><tr><th width="35%">Profil Pendaftar</th><th width="25%">Prodi Pilihan</th><th width="20%" class="text-center">Status</th><th width="20%" class="text-end">Tindakan</th></tr></thead>
                <tbody>
                    <?php if(!empty($pendaftar)): ?>
                        <?php foreach($pendaftar as $pmb): ?>
                            <tr class="data-row" data-search="<?= strtolower(esc($pmb['nama_lengkap'] . ' ' . $pmb['id'] . ' ' . $pmb['email'])) ?>">
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="user-avatar" style="background: <?= getAvatarColor($pmb['nama_lengkap']) ?>;"><?= substr($pmb['nama_lengkap'], 0, 1) ?></div>
                                        <div>
                                            <div class="fw-bold text-white search-target title-nama"><?= esc($pmb['nama_lengkap']) ?></div>
                                            <div class="small text-secondary search-target title-email">#REG-<?= esc($pmb['id']) ?> • <?= esc($pmb['email']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><div class="fw-bold title-prodi" style="color: #64ffda;"><?= esc($pmb['prodi_pilihan']) ?></div><span class="small text-secondary title-sekolah"><i class="bi bi-building"></i> <?= esc($pmb['asal_sekolah']) ?></span></td>
                                <td class="text-center col-status">
                                    <?php if($pmb['status_pendaftaran'] == 'Lulus'): ?><span class="badge" style="background: rgba(100,255,218,0.15); color: #64ffda; border: 1px solid rgba(100,255,218,0.3); padding: 6px 12px;"><i class="bi bi-check-circle"></i> Lulus</span>
                                    <?php elseif($pmb['status_pendaftaran'] == 'Ditolak'): ?><span class="badge" style="background: rgba(220,53,69,0.15); color: #ff6b6b; border: 1px solid rgba(220,53,69,0.3); padding: 6px 12px;"><i class="bi bi-x-circle"></i> Ditolak</span>
                                    <?php else: ?><span class="badge" style="background: rgba(245,158,11,0.15); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3); padding: 6px 12px;"><i class="bi bi-hourglass-split"></i> Menunggu</span><?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="action-btn-group">
                                        <button class="btn-action btn-edit-action btn-edit-pendaftar" data-bs-toggle="tooltip" title="Review Pendaftar" data-id="<?= $pmb['id'] ?>" data-nama="<?= esc($pmb['nama_lengkap']) ?>" data-email="<?= esc($pmb['email']) ?>" data-sekolah="<?= esc($pmb['asal_sekolah']) ?>" data-prodi="<?= esc($pmb['prodi_pilihan']) ?>" data-status="<?= esc($pmb['status_pendaftaran']) ?>"><i class="bi bi-clipboard-check"></i></button>
                                        <?php if($role === 'Sistem/Database'): ?><button class="btn-action btn-del-action" data-bs-toggle="tooltip" title="Hapus Data" onclick="hapusUser('<?= esc($pmb['nama_lengkap']) ?>')"><i class="bi bi-trash3"></i></button>
                                        <?php else: ?><button class="btn-action btn-lock-action" data-bs-toggle="tooltip" title="Akses Ditolak" onclick="tolakHapus()"><i class="bi bi-lock-fill"></i></button><?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr id="empty-state"><td colspan="4" class="text-center py-5 text-secondary">Data kosong.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="tab-mhs" class="table-area" style="display: none;">
        <div class="table-responsive">
            <table class="table table-glass table-hover mb-0">
                <thead><tr><th width="35%">Profil Mahasiswa</th><th width="25%">Program Studi</th><th width="20%" class="text-center">Status</th><th width="20%" class="text-end">Tindakan</th></tr></thead>
                <tbody>
                    <?php if(!empty($mahasiswa)): ?>
                        <?php foreach($mahasiswa as $mhs): ?>
                            <tr class="data-row" data-search="<?= strtolower(esc($mhs['nama_mahasiswa'] . ' ' . $mhs['nim'])) ?>">
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="user-avatar" style="background: <?= getAvatarColor($mhs['nama_mahasiswa']) ?>;"><?= substr($mhs['nama_mahasiswa'], 0, 1) ?></div>
                                        <div><div class="fw-bold text-white search-target"><?= esc($mhs['nama_mahasiswa']) ?></div><div class="small text-secondary search-target">NIM: <?= esc($mhs['nim']) ?></div></div>
                                    </div>
                                </td>
                                <td><div class="fw-bold" style="color: #64ffda;"><?= esc($mhs['prodi']) ?></div></td>
                                <td class="text-center"><span class="badge" style="background: rgba(100,255,218,0.15); color: #64ffda; border: 1px solid rgba(100,255,218,0.3); padding: 6px 12px;"><?= esc($mhs['status_studi']) ?></span></td>
                                <td class="text-end">
                                    <div class="action-btn-group">
                                        <button class="btn-action btn-edit-action" 
                                            onclick="openModalMhs('edit', this)"
                                            data-nim="<?= esc($mhs['nim']) ?>" 
                                            data-nama="<?= esc($mhs['nama_mahasiswa']) ?>" 
                                            data-fakultas="<?= esc($mhs['fakultas']) ?>" 
                                            data-prodi="<?= esc($mhs['prodi']) ?>" 
                                            data-jalur="<?= esc($mhs['jalur_masuk'] ?? 'SNBP') ?>" 
                                            data-tgl="<?= esc($mhs['tanggal_lahir'] ?? '') ?>" 
                                            data-telp="<?= esc($mhs['no_telp'] ?? '') ?>" 
                                            data-status="<?= esc($mhs['status_studi']) ?>"
                                            data-bs-toggle="tooltip" title="Edit Data">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <?php if($role === 'Sistem/Database'): ?><button class="btn-action btn-del-action" data-bs-toggle="tooltip" title="Hapus"><i class="bi bi-trash3"></i></button>
                                        <?php else: ?><button class="btn-action btn-lock-action" onclick="tolakHapus()"><i class="bi bi-lock-fill"></i></button><?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="tab-dosen" class="table-area" style="display: none;">
        <div class="table-responsive">
            <table class="table table-glass table-hover mb-0">
                <thead><tr><th width="35%">Profil Dosen</th><th width="25%">Prodi</th><th width="20%" class="text-center">Status</th><th width="20%" class="text-end">Tindakan</th></tr></thead>
                <tbody>
                    <?php if(!empty($dosen)): ?>
                        <?php foreach($dosen as $dsn): ?>
                            <tr class="data-row" data-search="<?= strtolower(esc($dsn['nama_dosen'] . ' ' . $dsn['nidn'])) ?>">
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="user-avatar" style="background: <?= getAvatarColor($dsn['nama_dosen']) ?>;"><?= substr($dsn['nama_dosen'], 0, 1) ?></div>
                                        <div><div class="fw-bold text-white search-target"><?= esc($dsn['nama_dosen']) ?></div><div class="small text-secondary search-target">NIDN: <?= esc($dsn['nidn']) ?></div></div>
                                    </div>
                                </td>
                                <td><div class="fw-bold" style="color: #64ffda;"><?= esc($dsn['prodi']) ?></div></td>
                                <td class="text-center"><span class="badge" style="background: rgba(100,255,218,0.15); color: #64ffda; border: 1px solid rgba(100,255,218,0.3); padding: 6px 12px;"><?= esc($dsn['status_dosen'] ?? 'Aktif') ?></span></td>
                                <td class="text-end">
                                    <div class="action-btn-group">
                                        <button class="btn-action btn-edit-action" 
                                            onclick="openModalDosen('edit', this)"
                                            data-nidn="<?= esc($dsn['nidn']) ?>" 
                                            data-nama="<?= esc($dsn['nama_dosen']) ?>" 
                                            data-gelar_depan="<?= esc($dsn['gelar_depan'] ?? '') ?>" 
                                            data-gelar_belakang="<?= esc($dsn['gelar_belakang'] ?? '') ?>" 
                                            data-tgl="<?= esc($dsn['tanggal_lahir'] ?? '') ?>" 
                                            data-prodi="<?= esc($dsn['prodi']) ?>" 
                                            data-bs-toggle="tooltip" title="Edit Data">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <?php if($role === 'Sistem/Database'): ?><button class="btn-action btn-del-action"><i class="bi bi-trash3"></i></button>
                                        <?php else: ?><button class="btn-action btn-lock-action" onclick="tolakHapus()"><i class="bi bi-lock-fill"></i></button><?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="tab-guest" class="table-area" style="display: none;">
        <div class="table-responsive">
            <table class="table table-glass table-hover mb-0">
                <thead><tr><th width="35%">Profil Akun</th><th width="25%">Jenis Akses</th><th width="20%" class="text-center">NIM Kaitan</th><th width="20%" class="text-end">Tindakan</th></tr></thead>
                <tbody>
                    <?php if(!empty($guest)): ?>
                        <?php foreach($guest as $gst): ?>
                            <tr class="data-row" data-search="<?= strtolower(esc($gst['nama_lengkap'] . ' ' . $gst['username'])) ?>">
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="user-avatar" style="background: #475569;"><i class="bi bi-person"></i></div>
                                        <div><div class="fw-bold text-white search-target"><?= esc($gst['nama_lengkap']) ?></div><div class="small text-secondary search-target">@<?= esc($gst['username']) ?></div></div>
                                    </div>
                                </td>
                                <td><div class="fw-bold" style="color: #f59e0b;"><?= esc($gst['jenis_akun']) ?></div></td>
                                <td class="text-center text-secondary"><?= !empty($gst['nim_mahasiswa']) ? esc($gst['nim_mahasiswa']) : '-' ?></td>
                                <td class="text-end">
                                    <div class="action-btn-group">
                                        <button class="btn-action btn-edit-action" 
                                            onclick="openModalGuest('edit', this)"
                                            data-id="<?= esc($gst['id_guest']) ?>" 
                                            data-nama="<?= esc($gst['nama_lengkap']) ?>" 
                                            data-username="<?= esc($gst['username']) ?>" 
                                            data-jenis="<?= esc($gst['jenis_akun']) ?>" 
                                            data-nim="<?= esc($gst['nim_mahasiswa']) ?>"
                                            data-bs-toggle="tooltip" title="Edit Data">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <?php if($role === 'Sistem/Database'): ?><button class="btn-action btn-del-action"><i class="bi bi-trash3"></i></button>
                                        <?php else: ?><button class="btn-action btn-lock-action" onclick="tolakHapus()"><i class="bi bi-lock-fill"></i></button><?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="pagination-controls mt-4">
        <div class="d-flex align-items-center gap-2 text-secondary small">
            <span>Tampilkan:</span>
            <select id="pageSize" class="page-size-selector" onchange="changePageSize()">
                <option value="5">5</option><option value="10" selected>10</option><option value="20">20</option><option value="50">50</option>
            </select>
            <span>data per halaman</span>
        </div>
        <div><ul class="pagination-glass" id="paginationBox"></ul></div>
    </div>
</div>

<div class="modal fade" id="modalPilihKategori" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm border-0">
        <div class="modal-content glass-modal border-0">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bolder text-white mx-auto">Pilih Kategori Tambah</h5>
            </div>
            <div class="modal-body text-center py-4">
                <button class="btn btn-outline-light w-100 mb-3 py-2 fw-bold" onclick="pilihKategori('mahasiswa')" style="border-radius: 12px;"><i class="bi bi-mortarboard me-2" style="color: #64ffda;"></i> Mahasiswa</button>
                <button class="btn btn-outline-light w-100 mb-3 py-2 fw-bold" onclick="pilihKategori('dosen')" style="border-radius: 12px;"><i class="bi bi-person-workspace me-2" style="color: #64ffda;"></i> Dosen / Pendidik</button>
                <button class="btn btn-outline-light w-100 py-2 fw-bold" onclick="pilihKategori('guest')" style="border-radius: 12px;"><i class="bi bi-people me-2" style="color: #64ffda;"></i> Guest / Wali</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditPendaftar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered border-0">
        <div class="modal-content glass-modal border-0">
            <div class="modal-header">
                <h5 class="modal-title fw-bolder text-white"><i class="bi bi-clipboard-check me-2" style="color: #64ffda;"></i> Review Pendaftar</h5>
                <button type="button" class="btn-close btn-close-custom" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditPendaftar">
                <input type="hidden" name="id_pendaftar" id="edit_pmb_id">
                <div class="modal-body px-4 py-4">
                    <div class="form-group-custom mb-3"><label>Nama Lengkap</label><input type="text" class="form-control" name="nama_lengkap" id="edit_pmb_nama" required></div>
                    <div class="form-group-custom mb-3"><label>Email Pendaftar</label><input type="email" class="form-control" name="email" id="edit_pmb_email" required></div>
                    <div class="form-group-custom mb-3"><label>Asal Sekolah</label><input type="text" class="form-control" name="asal_sekolah" id="edit_pmb_sekolah" required></div>
                    <div class="form-group-custom mb-3"><label>Program Studi Pilihan</label><input type="text" class="form-control" name="prodi_pilihan" id="edit_pmb_prodi" required></div>
                    <div class="form-group-custom mb-2">
                        <label style="color: #ff6b6b;">Keputusan Kelulusan</label>
                        <select class="form-select" name="status_pendaftaran" id="edit_pmb_status" required>
                            <option value="Menunggu Seleksi">Menunggu Seleksi</option><option value="Lulus">Lulus</option><option value="Ditolak">Ditolak</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-light px-4 border-secondary text-secondary fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-gradient-edit" id="btnSubmitEditPmb">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalMhs" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered border-0">
        <div class="modal-content glass-modal border-0">
            <div class="modal-header">
                <h5 class="modal-title fw-bolder text-white" id="modalMhsTitle">Tambah Mahasiswa</h5>
                <button type="button" class="btn-close btn-close-custom" data-bs-dismiss="modal"></button>
            </div>
            <form id="formMhs">
                <input type="hidden" name="nim_lama" id="mhs_nim_lama">
                <div class="modal-body px-4 py-4">
                    <div class="row">
                        <div class="col-md-6 form-group-custom mb-3">
                            <label>NIM Mahasiswa</label>
                            <input type="text" class="form-control" id="mhs_nim_display" readonly>
                            <input type="hidden" name="nim" id="mhs_nim_hidden">
                        </div>

                        <div class="col-md-6 form-group-custom mb-3"><label>Nama Lengkap</label><input type="text" class="form-control" name="nama_mahasiswa" id="mhs_nama" required></div>
                        <div class="col-md-6 form-group-custom mb-3">
                            <label>Program Studi</label>
                            <select class="form-select" name="prodi" id="mhs_prodi" onchange="autoFillFakultas(this, 'mhs_fakultas')" required>
                                <option value="">-- Pilih Program Studi --</option>
                                <?php if(!empty($daftar_prodi)): foreach($daftar_prodi as $pr): ?>
                                    <option value="<?= esc($pr['nama_prodi']) ?>" data-fakultas="<?= esc($pr['fakultas']) ?>"><?= esc($pr['nama_prodi']) ?> (<?= esc($pr['strata']) ?>)</option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group-custom mb-3"><label>Fakultas</label><input type="text" class="form-control" name="fakultas" id="mhs_fakultas" readonly required placeholder="Terisi otomatis..."></div>
                        <div class="col-md-4 form-group-custom mb-3"><label>Jalur Masuk</label><select class="form-select" name="jalur_masuk" id="mhs_jalur"><option value="SNBP">SNBP</option><option value="SNBT">SNBT</option><option value="Mandiri">Mandiri</option></select></div>
                        <div class="col-md-4 form-group-custom mb-3"><label>Tanggal Lahir</label><input type="date" class="form-control" name="tanggal_lahir" id="mhs_tgl" required></div>
                        <div class="col-md-4 form-group-custom mb-3"><label>Nomor Telepon</label><input type="text" class="form-control" name="no_telp" id="mhs_telp" placeholder="08xxxxxx"></div>
                        <div class="col-md-12 form-group-custom mb-3" id="mhs_status_container"><label>Status Studi</label><select class="form-select" name="status_studi" id="mhs_status"><option value="Aktif">Aktif</option><option value="Cuti">Cuti</option><option value="Lulus">Lulus</option><option value="DO">DO</option></select></div>
                        <div class="col-md-12 form-group-custom mb-2"><label id="mhs_pass_label">Password Akun (Opsional)</label><input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak ingin ganti / reset"></div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-light px-4 border-secondary text-secondary fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-gradient-edit"><i class="bi bi-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDosen" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered border-0">
        <div class="modal-content glass-modal border-0">
            <div class="modal-header">
                <h5 class="modal-title fw-bolder text-white" id="modalDosenTitle">Tambah Dosen</h5>
                <button type="button" class="btn-close btn-close-custom" data-bs-dismiss="modal"></button>
            </div>
            <form id="formDosen">
                <input type="hidden" name="nidn_lama" id="dsn_nidn_lama">
                <div class="modal-body px-4 py-4">
                    <div class="row">
                        <div class="col-md-6 form-group-custom mb-3">
                            <label>NIDN Dosen</label>
                            <input type="number" class="form-control" name="nidn" id="dsn_nidn" required>
                        </div>

                        <div class="col-md-6 form-group-custom mb-3"><label>Nama Lengkap (Tanpa Gelar)</label><input type="text" class="form-control" name="nama_dosen" id="dsn_nama" required></div>
                        <div class="col-md-4 form-group-custom mb-3"><label>Gelar Depan</label><input type="text" class="form-control" name="gelar_depan" id="dsn_gelar_depan" placeholder="Misal: Dr."></div>
                        <div class="col-md-4 form-group-custom mb-3"><label>Gelar Belakang</label><input type="text" class="form-control" name="gelar_belakang" id="dsn_gelar_belakang" placeholder="Misal: S.Kom."></div>
                        <div class="col-md-4 form-group-custom mb-3"><label>Tanggal Lahir</label><input type="date" class="form-control" name="tanggal_lahir" id="dsn_tgl" required></div>
                        <div class="col-md-12 form-group-custom mb-3">
                            <label>Program Studi</label>
                            <select class="form-select" name="prodi" id="dsn_prodi" required>
                                <option value="">-- Pilih Program Studi --</option>
                                <?php if(!empty($daftar_prodi)): foreach($daftar_prodi as $pr): ?><option value="<?= esc($pr['nama_prodi']) ?>"><?= esc($pr['nama_prodi']) ?> (<?= esc($pr['strata']) ?>)</option><?php endforeach; endif; ?>
                            </select>
                        </div>
                        <div class="col-md-12 form-group-custom mb-3" id="dsn_status_container"><label>Status Dosen</label><select class="form-select" name="status_dosen" id="dsn_status"><option value="Aktif">Aktif</option><option value="Tugas Belajar">Tugas Belajar</option><option value="Non-Aktif">Non-Aktif</option></select></div>
                        <div class="col-md-12 form-group-custom mb-2"><label>Password Akun (Opsional)</label><input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak ingin ganti"></div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-light px-4 border-secondary text-secondary fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-gradient-edit"><i class="bi bi-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalGuest" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered border-0">
        <div class="modal-content glass-modal border-0">
            <div class="modal-header">
                <h5 class="modal-title fw-bolder text-white" id="modalGuestTitle">Tambah Guest</h5>
                <button type="button" class="btn-close btn-close-custom" data-bs-dismiss="modal"></button>
            </div>
            <form id="formGuest">
                <input type="hidden" name="id_guest" id="gst_id">
                <div class="modal-body px-4 py-4">
                    <div class="form-group-custom mb-3"><label>Nama Lengkap</label><input type="text" class="form-control" name="nama_lengkap" id="gst_nama" required></div>
                    <div class="form-group-custom mb-3"><label>Username</label><input type="text" class="form-control" name="username" id="gst_user" required></div>
                    <div class="form-group-custom mb-3"><label>Jenis Akses</label><select class="form-select" name="jenis_akun" id="gst_jenis" onchange="toggleNimWali()"><option value="Tamu umum">Tamu Publik</option><option value="Orang tua/Wali Mahasiswa">Wali Mahasiswa</option></select></div>
                    <div class="form-group-custom mb-3" id="field_nim_wali" style="display:none;"><label>NIM Mahasiswa yang Diwakili</label><input type="number" class="form-control" name="nim_mahasiswa" id="gst_nim" placeholder="Kaitkan dengan NIM..."></div>
                    <div class="form-group-custom mb-3"><label id="gst_pass_label">Password Akun (Wajib)</label><input type="password" class="form-control" name="password" id="gst_password" placeholder="Masukkan password..."></div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-light px-4 border-secondary text-secondary fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-gradient-edit"><i class="bi bi-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, background: '#0a192f', color: '#64ffda' });
    
    function autoFillFakultas(selectElement, targetFakultasId) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        document.getElementById(targetFakultasId).value = selectedOption.getAttribute('data-fakultas') || '';
    }

    function toggleNimWali() {
        const jenisGuest = document.getElementById('gst_jenis').value;
        document.getElementById('field_nim_wali').style.display = (jenisGuest === 'Orang tua/Wali Mahasiswa') ? 'block' : 'none';
    }

    function pilihKategori(kategori) {
        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalPilihKategori')).hide();
        setTimeout(() => {
            if(kategori === 'mahasiswa') openModalMhs('add');
            else if(kategori === 'dosen') openModalDosen('add');
            else if(kategori === 'guest') openModalGuest('add');
        }, 400);
    }

    // --- FUNGSI DYNAMIC MODALS --- //
    function openModalMhs(mode, btn = null) {
        const form = document.getElementById('formMhs'); form.reset();
        const title = document.getElementById('modalMhsTitle');
        const nimDisplay = document.getElementById('mhs_nim_display');
        const nimHidden = document.getElementById('mhs_nim_hidden');
        
        if(mode === 'add') {
            title.innerHTML = '<i class="bi bi-person-plus" style="color:#64ffda;"></i> Tambah Mahasiswa';
            
            // Konfigurasi Input NIM saat TAMBAH (Memicu Trigger Database)
            document.getElementById('mhs_nim_lama').value = '';
            nimDisplay.value = 'Dibuat Otomatis (Trigger)';
            nimHidden.value = '0'; // INI KUNCI UTAMANYA: Mengirim 0 agar trigger jalan!
            
            document.getElementById('mhs_status_container').style.display = 'none';
            document.getElementById('mhs_pass_label').innerText = 'Password Akun (Opsional)';
            form.setAttribute('data-url', "<?= base_url('admin/pengguna/mahasiswa/add') ?>");
        } else {
            title.innerHTML = '<i class="bi bi-pencil-square" style="color:#64ffda;"></i> Edit Mahasiswa';
            
            // Konfigurasi Input NIM saat EDIT
            document.getElementById('mhs_nim_lama').value = btn.dataset.nim;
            nimDisplay.value = btn.dataset.nim; // Hanya Tampilan
            nimHidden.value = btn.dataset.nim;  // Mengirim data sebenarnya

            document.getElementById('mhs_status_container').style.display = 'block';
            document.getElementById('mhs_pass_label').innerText = 'Reset Password (Opsional)';
            
            document.getElementById('mhs_nama').value = btn.dataset.nama;
            document.getElementById('mhs_prodi').value = btn.dataset.prodi;
            document.getElementById('mhs_fakultas').value = btn.dataset.fakultas;
            document.getElementById('mhs_jalur').value = btn.dataset.jalur;
            document.getElementById('mhs_tgl').value = btn.dataset.tgl;
            document.getElementById('mhs_telp').value = btn.dataset.telp;
            document.getElementById('mhs_status').value = btn.dataset.status;
            
            form.setAttribute('data-url', "<?= base_url('admin/pengguna/mahasiswa/edit') ?>");
        }
        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalMhs')).show();
    }

    function openModalDosen(mode, btn = null) {
        const form = document.getElementById('formDosen'); form.reset();
        const title = document.getElementById('modalDosenTitle');
        const nidnInput = document.getElementById('dsn_nidn');

        if(mode === 'add') {
            title.innerHTML = '<i class="bi bi-person-plus" style="color:#64ffda;"></i> Tambah Dosen';
            
            document.getElementById('dsn_nidn_lama').value = '';
            nidnInput.value = '';
            nidnInput.placeholder = 'Masukkan NIDN Manual...';
            nidnInput.readOnly = false;
            nidnInput.setAttribute('required', 'true');

            document.getElementById('dsn_status_container').style.display = 'none';
            form.setAttribute('data-url', "<?= base_url('admin/pengguna/dosen/add') ?>");
        } else {
            title.innerHTML = '<i class="bi bi-pencil-square" style="color:#64ffda;"></i> Edit Dosen';
            
            nidnInput.placeholder = 'Ubah NIDN';
            nidnInput.readOnly = false;
            nidnInput.setAttribute('required', 'true');

            document.getElementById('dsn_status_container').style.display = 'block';
            document.getElementById('dsn_nidn_lama').value = btn.dataset.nidn;
            nidnInput.value = btn.dataset.nidn;
            document.getElementById('dsn_nama').value = btn.dataset.nama;
            document.getElementById('dsn_gelar_depan').value = btn.dataset.gelar_depan;
            document.getElementById('dsn_gelar_belakang').value = btn.dataset.gelar_belakang;
            document.getElementById('dsn_prodi').value = btn.dataset.prodi;
            document.getElementById('dsn_tgl').value = btn.dataset.tgl;
            form.setAttribute('data-url', "<?= base_url('admin/pengguna/dosen/edit') ?>");
        }
        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalDosen')).show();
    }

    function openModalGuest(mode, btn = null) {
        const form = document.getElementById('formGuest'); form.reset();
        const title = document.getElementById('modalGuestTitle');
        if(mode === 'add') {
            title.innerHTML = '<i class="bi bi-person-plus" style="color:#64ffda;"></i> Tambah Guest / Wali';
            document.getElementById('gst_id').value = '';
            document.getElementById('gst_pass_label').innerText = 'Password Akun (Wajib)';
            document.getElementById('gst_password').required = true;
            form.setAttribute('data-url', "<?= base_url('admin/pengguna/guest/add') ?>");
        } else {
            title.innerHTML = '<i class="bi bi-pencil-square" style="color:#64ffda;"></i> Edit Guest / Wali';
            document.getElementById('gst_id').value = btn.dataset.id;
            document.getElementById('gst_nama').value = btn.dataset.nama;
            document.getElementById('gst_user').value = btn.dataset.username;
            document.getElementById('gst_jenis').value = btn.dataset.jenis;
            document.getElementById('gst_nim').value = btn.dataset.nim;
            document.getElementById('gst_pass_label').innerText = 'Reset Password (Opsional)';
            document.getElementById('gst_password').required = false;
            form.setAttribute('data-url', "<?= base_url('admin/pengguna/guest/edit') ?>");
        }
        toggleNimWali();
        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalGuest')).show();
    }

    // --- UNIFIED AJAX SUBMITTER --- //
    function bindUnifiedSubmit(formId, modalId) {
        document.getElementById(formId).addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...'; btn.disabled = true;

            const url = this.getAttribute('data-url');
            const formData = new FormData(this);
            try {
                const response = await fetch(url, { method: "POST", body: formData });
                const result = await response.json();
                if(result.success) {
                    bootstrap.Modal.getOrCreateInstance(document.getElementById(modalId)).hide();
                    Toast.fire({ icon: 'success', title: result.message });
                    setTimeout(() => location.reload(), 1500);
                } else { Swal.fire({ icon: 'error', title: 'Gagal', text: result.message, background: '#1e293b', color: '#fff' }); }
            } catch (error) { Toast.fire({ icon: 'error', title: 'Kesalahan Jaringan.' }); } 
            finally { btn.innerHTML = originalText; btn.disabled = false; }
        });
    }

    bindUnifiedSubmit('formMhs', 'modalMhs');
    bindUnifiedSubmit('formDosen', 'modalDosen');
    bindUnifiedSubmit('formGuest', 'modalGuest');

    // --- OTHER UI BEHAVIORS --- //
    let currentPage = 1; let rowsPerPage = 10;
    function changePageSize() { rowsPerPage = parseInt(document.getElementById('pageSize').value); currentPage = 1; renderPagination(); }
    
    function renderPagination() {
        const activeTab = document.querySelector('.table-area[style="display: block;"]');
        if (!activeTab) return;
        const keyword = document.getElementById('searchUser').value.toLowerCase();
        const tbody = activeTab.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr.data-row')); 
        let visibleRows = [];
        
        rows.forEach(row => {
            const dataSearch = row.dataset.search || '';
            if (!keyword || dataSearch.includes(keyword)) {
                visibleRows.push(row);
                const targets = row.querySelectorAll('.search-target');
                targets.forEach(target => {
                    if(!target.dataset.original) target.dataset.original = target.innerHTML;
                    if(keyword) target.innerHTML = target.dataset.original.replace(new RegExp(keyword, "gi"), match => `<mark>${match}</mark>`);
                    else target.innerHTML = target.dataset.original;
                });
            } else row.style.display = 'none';
        });

        let emptyTr = tbody.querySelector('#empty-state');
        if(visibleRows.length === 0) {
            if(!emptyTr) tbody.insertAdjacentHTML('beforeend', '<tr id="empty-state"><td colspan="5" class="text-center py-5 text-secondary">Data tidak ditemukan.</td></tr>');
            document.getElementById('paginationBox').innerHTML = ''; return;
        } else if(emptyTr) emptyTr.remove();

        const totalPages = Math.ceil(visibleRows.length / rowsPerPage);
        if(currentPage > totalPages) currentPage = totalPages || 1;
        const start = (currentPage - 1) * rowsPerPage; const end = start + rowsPerPage;
        visibleRows.forEach((row, index) => { row.style.display = (index >= start && index < end) ? '' : 'none'; });

        let paginationHTML = '';
        paginationHTML += `<li class="page-item"><button class="page-btn" ${currentPage === 1 ? 'disabled' : `onclick="goToPage(${currentPage - 1})"`}><i class="bi bi-chevron-left"></i></button></li>`;
        for(let i = 1; i <= totalPages; i++) {
            if(i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                paginationHTML += `<li class="page-item"><button class="page-btn ${currentPage === i ? 'active' : ''}" onclick="goToPage(${i})">${i}</button></li>`;
            } else if (i === currentPage - 2 || i === currentPage + 2) {
                paginationHTML += `<li class="page-item"><span class="page-btn text-secondary border-0" style="background:transparent; cursor:default;">...</span></li>`;
            }
        }
        paginationHTML += `<li class="page-item"><button class="page-btn" ${currentPage === totalPages ? 'disabled' : `onclick="goToPage(${currentPage + 1})"`}><i class="bi bi-chevron-right"></i></button></li>`;
        document.getElementById('paginationBox').innerHTML = paginationHTML;
    }

    function goToPage(page) { currentPage = page; renderPagination(); }
    let searchDebounce;
    document.getElementById('searchUser').addEventListener('input', () => { clearTimeout(searchDebounce); searchDebounce = setTimeout(() => { currentPage = 1; renderPagination(); }, 300); });
    
    function bukaTab(idTab, tombol) {
        document.getElementById('searchUser').value = ''; 
        document.querySelectorAll('.table-area').forEach(area => area.style.display = 'none');
        document.querySelectorAll('.btn-tab').forEach(btn => btn.classList.remove('active'));
        document.getElementById(idTab).style.display = 'block'; tombol.classList.add('active');
        currentPage = 1; renderPagination(); 
    }

    document.addEventListener("DOMContentLoaded", () => {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (t) { return new bootstrap.Tooltip(t); });
        renderPagination(); 
    });

    document.querySelectorAll('.btn-edit-pendaftar').forEach(btn => {
        btn.onclick = function() {
            bootstrap.Tooltip.getInstance(this)?.hide();
            document.getElementById('edit_pmb_id').value = this.dataset.id;
            document.getElementById('edit_pmb_nama').value = this.dataset.nama;
            document.getElementById('edit_pmb_email').value = this.dataset.email;
            document.getElementById('edit_pmb_sekolah').value = this.dataset.sekolah;
            document.getElementById('edit_pmb_prodi').value = this.dataset.prodi;
            document.getElementById('edit_pmb_status').value = this.dataset.status;
            bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditPendaftar')).show();
        }
    });

    document.getElementById('formEditPendaftar').addEventListener('submit', async function(e) {
        e.preventDefault(); const btnSubmit = document.getElementById('btnSubmitEditPmb');
        btnSubmit.innerHTML = 'Memproses...'; btnSubmit.disabled = true;
        const formData = new FormData(this); const id = formData.get('id_pendaftar');
        try {
            const response = await fetch("<?= base_url('admin/pengguna/pendaftar/edit') ?>", { method: "POST", body: formData });
            const result = await response.json();
            if(result.success) {
                bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditPendaftar')).hide();
                Toast.fire({ icon: 'success', title: result.message }); renderPagination(); setTimeout(() => location.reload(), 1500);
            } else { Swal.fire({ icon: 'error', title: 'Gagal', text: result.message, background: '#1e293b', color: '#fff' }); }
        } catch (error) { Toast.fire({ icon: 'error', title: 'Kesalahan server.' }); } finally { btnSubmit.innerHTML = 'Update Status'; btnSubmit.disabled = false; }
    });

    function tolakHapus() { Swal.fire({ title: 'Akses Ditolak!', text: 'Role Anda tidak memiliki wewenang untuk MENGHAPUS data.', icon: 'warning', confirmButtonColor: '#f59e0b', background: '#1e293b', color: '#fff' }); }
    document.getElementById('btn-logout-admin').addEventListener('click', function(e) {
        e.preventDefault(); const logoutUrl = this.getAttribute('href');
        Swal.fire({ title: 'Akhiri Sesi?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b', confirmButtonText: 'Ya, Keluar', background: '#1e293b', color: '#fff' }).then((result) => { if (result.isConfirmed) { window.location.href = logoutUrl; } });
    });
</script>

<?= $this->endSection() ?>