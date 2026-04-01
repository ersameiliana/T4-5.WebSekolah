<?= $this->extend('layout/dashboard_template') ?>

<?= $this->section('page_title') ?>
Kelola Jurusan & Program Studi
<?= $this->endSection() ?>

<?php 
    $role = session()->get('role_admin') ?? 'Guest'; 
    $nama_admin = session()->get('nama_admin') ?? 'Administrator';
    $inisial = strtoupper(substr($nama_admin, 0, 1));
?>

<?= $this->section('sidebar_menu') ?>
    <style>
        .nav-item { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); display: block; border-radius: 8px; margin-bottom: 5px; }
        .nav-item:hover { transform: translateX(8px); background: rgba(255,255,255,0.05); }
    </style>
    <a href="<?= base_url('admin-secret-panel') ?>" class="nav-item">📊 Dashboard Admin</a>
    <?php if (in_array($role, ['Editing', 'Sistem/Database'])): ?>
        <div style="color: #64748b; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; margin: 20px 0 10px 15px; letter-spacing: 1px;">Konten & Publikasi</div>
        <a href="<?= base_url('admin/profil') ?>" class="nav-item">🏛️ Kelola Profil Web</a>
        <a href="<?= base_url('admin/berita') ?>" class="nav-item">📰 Kelola Berita</a>
    <?php endif; ?>
    <?php if (in_array($role, ['Administrasi', 'Sistem/Database'])): ?>
        <div style="color: #64748b; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; margin: 20px 0 10px 15px; letter-spacing: 1px;">Data Akademik</div>
        <a href="<?= base_url('admin/pengguna') ?>" class="nav-item">👥 Kelola Akun User</a>
        <a href="<?= base_url('admin/jurusan') ?>" class="nav-item active">🏢 Kelola Jurusan</a>
    <?php endif; ?>
    <?php if ($role === 'Sistem/Database'): ?>
        <div style="color: #ef4444; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; margin: 20px 0 10px 15px; letter-spacing: 1px;">Superadmin Tools</div>
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

    /* ENHANCED PREMIUM DASHBOARD */
    * { box-sizing: border-box; }
    
    /* TOP BAR WITH GLOW */
    .top-bar-area { display: flex; justify-content: flex-end; align-items: center; gap: 20px; margin-bottom: 30px; }
    .profile-info { display: flex; align-items: center; gap: 12px; text-align: right; }
    .profile-avatar { width: 45px; height: 45px; border-radius: 50%; background: linear-gradient(135deg, #f59e0b, #ef4444); display: flex; justify-content: center; align-items: center; font-weight: 800; font-size: 1.2rem; color: #fff; border: 3px solid rgba(255,255,255,0.2); box-shadow: 0 0 20px rgba(245,158,11,0.3); animation: pulse-glow 2s infinite; }
    @keyframes pulse-glow { 0%, 100% { box-shadow: 0 0 20px rgba(245,158,11,0.3); } 50% { box-shadow: 0 0 30px rgba(245,158,11,0.6); } }
    
    .btn-logout-top { background: linear-gradient(135deg, rgba(239,68,68,0.2), rgba(239,68,68,0.1)); color: #ef4444; border: 1px solid rgba(239,68,68,0.4); padding: 12px 24px; border-radius: 12px; font-weight: 700; font-size: 0.9rem; text-decoration: none; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px; position: relative; overflow: hidden; }
    .btn-logout-top:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(239,68,68,0.3); color: #fff; }

    /* ANIMATED STATS */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .stat-card { position: relative; overflow: hidden; }
    .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, var(--stat-color), transparent); }
    .stat-card h3 { margin: 0; font-size: 2.8rem; color: #fff; font-weight: 900; text-shadow: 0 2px 10px rgba(0,0,0,0.3); }
    .stat-card p { color: #cbd5e1; margin: 8px 0 0 0; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 700;}
    .stat-icon { font-size: 3.5rem; opacity: 0.15; position: absolute; top: 10px; right: 20px; filter: drop-shadow(0 0 10px currentColor); animation: float 3s ease-in-out infinite; }
    @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }
    
    /* UPGRADED GLASS */
    .glass-card { background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.12); border-radius: 24px; padding: 40px; transition: all 0.5s cubic-bezier(0.4,0,0.2,1); position: relative; box-shadow: 0 25px 50px rgba(0,0,0,0.2); }
    
    /* SEARCH BAR */
    .search-controls { background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 16px; margin-bottom: 24px; display: flex; gap: 12px; flex-wrap: wrap; align-items: center; }
    .search-input { flex: 1; min-width: 250px; }
    
    /* NEUMORPHIC TABLE */
    .table-glass { --bs-table-bg: transparent; --bs-table-color: #f8fafc; --bs-table-border-color: rgba(255,255,255,0.06); margin-top: 10px; }
    .table-glass thead th { color: #94a3b8; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.75rem; padding: 20px 12px 16px; border-bottom: 2px solid rgba(255,255,255,0.08) !important;}
    .table-glass tbody tr { transition: all 0.3s cubic-bezier(0.4,0,0.2,1); border-radius: 12px; margin-bottom: 4px; overflow: hidden; background: rgba(255,255,255,0.02); }
    .table-glass tbody tr:hover { transform: translateY(-4px) scale(1.01); box-shadow: 0 20px 40px rgba(139,92,246,0.2); background: rgba(139,92,246,0.08); }
    .table-glass td { vertical-align: middle; padding: 20px 16px; border-bottom: none !important; position: relative; font-size: 0.98rem; font-weight: 500; }
    mark { background: rgba(245, 158, 11, 0.4); color: #fff; border-radius: 4px; padding: 0 2px; }

    /* ACTION BUTTONS */
    .action-btn-group { display: flex; gap: 8px; justify-content: flex-end; }
    .btn-action { width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: none; transition: all 0.2s ease; font-size: 1.1rem; }
    .btn-edit-action { background: rgba(59, 130, 246, 0.1); color: #60a5fa; }
    .btn-edit-action:hover { background: #3b82f6; color: #fff; transform: translateY(-2px); }
    .btn-del-action { background: rgba(239, 68, 68, 0.1); color: #f87171; }
    .btn-del-action:hover { background: #ef4444; color: #fff; transform: translateY(-2px); }

    /* MODAL GLASS EFFECT */
    .glass-modal { background: rgba(15, 23, 42, 0.98) !important; backdrop-filter: blur(20px) !important; border: 1px solid rgba(255, 255, 255, 0.1) !important; border-radius: 20px !important; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5) !important; }
    .btn-close-custom { filter: invert(1) grayscale(100%) brightness(200%); opacity: 0.5; transition: 0.3s; }

    /* 🔥 INTERACTIVE FORM UPGRADES 🔥 */
    .input-group-glass { background: rgba(0, 0, 0, 0.25); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px; display: flex; align-items: center; transition: all 0.3s ease; width: 100%; }
    .input-group-glass:hover { border-color: rgba(139,92,246,0.5); }
    .input-group-glass:focus-within { background: rgba(0, 0, 0, 0.5); border-color: #8b5cf6; box-shadow: 0 0 25px rgba(139,92,246,0.25), inset 0 0 10px rgba(139, 92, 246, 0.05); transform: translateY(-2px); }
    
    .input-group-glass .icon-wrapper { padding: 0 15px; color: #64748b; font-size: 1.2rem; transition: color 0.3s ease; display: flex; align-items: center; justify-content: center; }
    .input-group-glass:focus-within .icon-wrapper { color: #a78bfa; }
    
    /* Input sizing & Custom Select Arrow */
    .input-group-glass .form-control, .input-group-glass .form-select { flex: 1; background: transparent; border: none; color: #f8fafc; padding: 22px 15px 8px 0; font-size: 0.95rem; box-shadow: none !important; }
    .input-group-glass select option { background: #0f172a; color: #fff; }
    .input-group-glass .form-select { appearance: none; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right 15px center; background-size: 16px 12px; padding-right: 40px; }

    /* FLOATING LABELS MAGIC */
    .floating-group { position: relative; }
    .floating-group label { position: absolute; left: 48px; top: 50%; transform: translateY(-50%); color: #64748b; font-size: 0.9rem; pointer-events: none; transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1); margin: 0; }
    .floating-group input:focus ~ label, 
    .floating-group input:not(:placeholder-shown) ~ label,
    .floating-group select:focus ~ label,
    .floating-group select:valid ~ label { top: 12px; font-size: 0.65rem; color: #a78bfa; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }

    /* BUTTONS */
    .btn-gradient-add, .btn-gradient-edit { color: #fff; border: none; font-weight: bold; border-radius: 12px; padding: 14px 25px; transition: all 0.3s ease; display: inline-flex; align-items: center; justify-content: center; gap: 8px; letter-spacing: 0.5px; text-transform: uppercase; font-size: 0.85rem;}
    .btn-gradient-add { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .btn-gradient-edit { background: linear-gradient(135deg, #3b82f6, #06b6d4); }
    .btn-gradient-add:hover, .btn-gradient-edit:hover { transform: translateY(-2px); color: #fff; filter: brightness(1.1); }
</style>

<div class="top-bar-area">
    <div class="profile-info">
        <div>
            <div style="font-weight: 700; color: #fff; font-size: 0.95rem;"><?= esc($nama_admin) ?></div>
            <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600; text-transform: uppercase;"><?= esc($role) ?></div>
        </div>
        <div class="profile-avatar"><?= $inisial ?></div>
    </div>
    <div style="width: 1px; height: 30px; background: rgba(255,255,255,0.1);"></div>
    <a href="<?= base_url('auth/logout') ?>" id="btn-logout-admin" class="btn-logout-top"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<div class="stats-grid">
    <div class="glass-card stat-card" style="--stat-color: #8b5cf6;">
        <i class="bi bi-buildings stat-icon" style="color: #8b5cf6;"></i>
        <div class="d-flex justify-content-between align-items-center">
            <div><h3 id="stat-fakultas"><?= esc($total_fakultas) ?></h3><p>Total Fakultas</p></div>
        </div>
    </div>
    <div class="glass-card stat-card" style="--stat-color: #3b82f6;">
        <i class="bi bi-journal-bookmark stat-icon" style="color: #3b82f6;"></i>
        <div class="d-flex justify-content-between align-items-center">
            <div><h3 id="stat-prodi"><?= esc($total_prodi) ?></h3><p>Program Studi</p></div>
        </div>
    </div>
</div>

<div class="glass-card">
    
    <div class="search-controls">
        <div class="input-group-glass search-input mb-0">
            <div class="icon-wrapper"><i class="bi bi-search"></i></div>
            <input type="text" id="searchProdi" class="form-control" placeholder="Cari prodi atau fakultas..." autocomplete="off" style="padding: 14px 15px 14px 0;">
        </div>
        <select id="filterStrata" class="form-select" style="min-width: 150px; padding: 14px 40px 14px 15px; background-color: rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; color: #fff;">
            <option value="">Semua Strata</option>
            <option value="D3">D3</option>
            <option value="D4">D4</option>
            <option value="S1">S1</option>
            <option value="S2">S2</option>
            <option value="S3">S3</option>
        </select>
    </div>
    
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-4 border-bottom border-secondary border-opacity-25 gap-3">
        <div>
            <h2 class="fw-bolder text-white mb-1" style="margin-top:0; font-size: 1.8rem;">🏛️ Daftar Program Studi</h2>
            <p class="text-secondary mb-0" style="font-size: 1rem;">Manajemen struktur akademik dengan interaksi modern.</p>
        </div>
        <button class="btn-gradient-add shadow-lg" onclick="openModalProdi('add')">
            <i class="bi bi-plus-circle fs-5"></i> <span>Tambah Prodi</span>
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-glass table-hover mb-0" id="prodiTable">
            <thead>
                <tr>
                    <th scope="col" width="8%"><i class="bi bi-hash"></i> ID</th>
                    <th scope="col" width="32%"><i class="bi bi-building"></i> Fakultas</th>
                    <th scope="col" width="28%"><i class="bi bi-mortarboard-fill"></i> Program Studi</th>
                    <th scope="col" width="12%" class="text-center"><i class="bi bi-award"></i> Strata</th>
                    <th scope="col" width="20%" class="text-end"><i class="bi bi-gear-fill"></i> Aksi</th>
                </tr>
            </thead>
            <tbody id="table-body-prodi">
                <?php if(!empty($daftar_prodi)): ?>
                    <?php 
                    $warna_fakultas = [
                        'Fakultas Teknologi & Informatika' => ['color' => '#3b82f6', 'icon' => 'bi-cpu'],
                        'Fakultas Sains & Matematika' => ['color' => '#10b981', 'icon' => 'bi-graph-up'],
                        'Fakultas Bisnis & Manajemen' => ['color' => '#f59e0b', 'icon' => 'bi-bag'],
                        'Fakultas Desain & Media Kreatif' => ['color' => '#ef4444', 'icon' => 'bi-easel']
                    ];
                    $strata_gradients = [
                        'D3' => 'linear-gradient(135deg, #6b7280, #4b5563)',
                        'D4' => 'linear-gradient(135deg, #10b981, #059669)',
                        'S1' => 'linear-gradient(135deg, #3b82f6, #1d4ed8)',
                        'S2' => 'linear-gradient(135deg, #8b5cf6, #7c3aed)',
                        'S3' => 'linear-gradient(135deg, #f59e0b, #d97706)'
                    ];
                    ?>
                    <?php foreach($daftar_prodi as $prodi): ?>
                        <?php $fak_info = $warna_fakultas[$prodi['fakultas']] ?? ['color' => '#94a3b8', 'icon' => 'bi-building']; ?>
                        <?php $strata_bg = $strata_gradients[$prodi['strata']] ?? 'linear-gradient(135deg, #6b7280, #4b5563)'; ?>
                        <tr id="row-<?= $prodi['id'] ?>" data-nama="<?= strtolower(esc($prodi['nama_prodi'])) ?>" data-fak="<?= strtolower(esc($prodi['fakultas'])) ?>" data-strata="<?= esc($prodi['strata']) ?>">
                            <td class="fw-bold text-white-400">#<?= esc($prodi['id']) ?></td>
                            <td class="col-fakultas">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="fak-avatar" style="background: <?= $fak_info['color'] ?>; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.9rem;">
                                        <i class="<?= $fak_info['icon'] ?>"></i>
                                    </div>
                                    <div class="fw-bold text-white search-target-fak"><?= esc($prodi['fakultas']) ?></div>
                                </div>
                            </td>
                            <td class="col-nama">
                                <div class="fw-bold text-white fs-6 search-target-nama"><?= esc($prodi['nama_prodi']) ?></div>
                            </td>
                            <td class="text-center col-strata">
                                <span class="badge" style="background: <?= $strata_bg ?> !important; color: white; border: none; padding: 8px 16px; border-radius: 20px;">
                                    <?= esc($prodi['strata']) ?>
                                </span>
                            </td>
                            <td class="action-col">
                                <div class="action-btn-group">
                                    <button class="btn-action btn-edit-action btn-edit" data-bs-toggle="tooltip" title="Edit Data"
                                        data-id="<?= $prodi['id'] ?>" data-fakultas="<?= esc($prodi['fakultas']) ?>" 
                                        data-nama="<?= esc($prodi['nama_prodi']) ?>" data-strata="<?= esc($prodi['strata']) ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <?php if($role === 'Sistem/Database'): ?>
                                        <button class="btn-action btn-del-action btn-delete" data-bs-toggle="tooltip" title="Hapus Permanen" data-id="<?= $prodi['id'] ?>" data-nama="<?= esc($prodi['nama_prodi']) ?>">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    <?php else: ?>
                                        <button class="btn-action btn-lock-action" data-bs-toggle="tooltip" title="Akses Ditolak" onclick="tolakHapus()"><i class="bi bi-lock-fill"></i></button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr id="empty-state">
                        <td colspan="5" class="text-center py-5 text-secondary">
                            <i class="bi bi-inbox fs-1 opacity-50 mb-3 d-block"></i>
                            <h4 class="text-white mb-2">Belum ada Program Studi</h4>
                            <button class="btn-gradient-add mt-3" onclick="openModalProdi('add')">Tambah Sekarang</button>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalProdi" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered border-0">
    <div class="modal-content glass-modal border-0 p-2">
      <div class="modal-header border-0 pb-0">
        <div class="d-flex align-items-center gap-3">
            <div id="modalProdiIcon" style="padding: 12px; border-radius: 12px; background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                <i class="bi bi-buildings fs-4"></i>
            </div>
            <div>
                <h5 class="modal-title fw-bolder text-white mb-0" id="modalProdiTitle">Form Prodi</h5>
                <small class="text-secondary" id="modalProdiSubtitle" style="font-size: 0.75rem;">Mengelola data prodi.</small>
            </div>
        </div>
        <button type="button" class="btn-close btn-close-custom align-self-start mt-1" data-bs-dismiss="modal"></button>
      </div>
      <form id="formProdi">
          <input type="hidden" name="id_prodi" id="prodi_id">
          <div class="modal-body px-4 py-4">
            <div class="mb-4">
                <div class="input-group-glass floating-group">
                    <div class="icon-wrapper"><i class="bi bi-building"></i></div>
                    <input type="text" class="form-control" name="fakultas" id="prodi_fakultas" placeholder=" " required autocomplete="off">
                    <label>Fakultas Induk</label>
                </div>
                <small class="error-text text-danger mt-1 ms-2" style="font-size: 0.75rem; font-weight: bold;"></small>
            </div>
            <div class="mb-4">
                <div class="input-group-glass floating-group">
                    <div class="icon-wrapper"><i class="bi bi-journal-code"></i></div>
                    <input type="text" class="form-control" name="nama_prodi" id="prodi_nama" placeholder=" " required autocomplete="off">
                    <label>Nama Program Studi</label>
                </div>
                <small class="error-text text-danger mt-1 ms-2" style="font-size: 0.75rem; font-weight: bold;"></small>
            </div>
            <div class="mb-2">
                <div class="input-group-glass floating-group">
                    <div class="icon-wrapper"><i class="bi bi-mortarboard"></i></div>
                    <select class="form-select" name="strata" id="prodi_strata" required>
                        <option value="" disabled selected></option>
                        <option value="D3">D3 (Diploma 3)</option>
                        <option value="D4">D4 (Sarjana Terapan)</option>
                        <option value="S1">S1 (Sarjana)</option>
                        <option value="S2">S2 (Magister)</option>
                        <option value="S3">S3 (Doktor)</option>
                    </select>
                    <label>Jenjang Strata</label>
                </div>
            </div>
          </div>
          <div class="modal-footer border-0 pt-0 pb-4 px-4 d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-dark text-secondary fw-bold px-4" style="border-radius: 12px; padding: 12px;" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn-gradient-edit" id="btnSubmitProdi">
                <i class="bi bi-save fs-5" id="btnSubmitProdiIcon"></i> <span id="btnSubmitProdiText">Simpan Data</span>
            </button>
          </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function initTooltips() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) { return new bootstrap.Tooltip(tooltipTriggerEl); });
    }
    initTooltips();

    const Toast = Swal.mixin({
        toast: true, position: 'top-end', showConfirmButton: false, timer: 3000,
        timerProgressBar: true, background: '#1e293b', color: '#fff'
    });

    // --- STATE & UTILS ---
    let prodiMode = 'add'; // State tracker untuk Unified Modal
    
    function getStrataBg(strata) {
        const gradients = {
            'D3': 'linear-gradient(135deg, #6b7280, #4b5563)',
            'D4': 'linear-gradient(135deg, #10b981, #059669)',
            'S1': 'linear-gradient(135deg, #3b82f6, #1d4ed8)',
            'S2': 'linear-gradient(135deg, #8b5cf6, #7c3aed)',
            'S3': 'linear-gradient(135deg, #f59e0b, #d97706)'
        };
        return gradients[strata] || 'linear-gradient(135deg, #6b7280, #4b5563)';
    }

    // --- 1. FUNGSI MEMBUKA UNIFIED MODAL ---
    function openModalProdi(mode, btn = null) {
        prodiMode = mode;
        const form = document.getElementById('formProdi');
        form.reset();
        document.querySelectorAll('.error-text').forEach(e => e.innerText = "");
        
        const title = document.getElementById('modalProdiTitle');
        const subtitle = document.getElementById('modalProdiSubtitle');
        const icon = document.getElementById('modalProdiIcon');
        const btnSubmitText = document.getElementById('btnSubmitProdiText');
        const btnSubmitIcon = document.getElementById('btnSubmitProdiIcon');
        const btnSubmit = document.getElementById('btnSubmitProdi');

        if (mode === 'add') {
            title.innerText = 'Registrasi Prodi Baru';
            subtitle.innerText = 'Tambahkan program studi ke sistem database.';
            icon.innerHTML = '<i class="bi bi-node-plus-fill fs-4"></i>';
            icon.style.background = 'rgba(245, 158, 11, 0.1)';
            icon.style.color = '#f59e0b';
            
            btnSubmit.className = 'btn-gradient-add';
            btnSubmitIcon.className = 'bi bi-check2-circle fs-5';
            btnSubmitText.innerText = 'Simpan Data';
            
            document.getElementById('prodi_id').value = '';
            form.setAttribute('data-url', "<?= base_url('admin/jurusan/add') ?>");
        } else {
            title.innerText = 'Update Data Prodi';
            subtitle.innerText = 'Live preview aktif pada baris tabel saat mengetik.';
            icon.innerHTML = '<i class="bi bi-pencil-square fs-4"></i>';
            icon.style.background = 'rgba(59, 130, 246, 0.1)';
            icon.style.color = '#3b82f6';
            
            btnSubmit.className = 'btn-gradient-edit';
            btnSubmitIcon.className = 'bi bi-cloud-arrow-up fs-5';
            btnSubmitText.innerText = 'Update Data';

            document.getElementById('prodi_id').value = btn.dataset.id;
            document.getElementById('prodi_fakultas').value = btn.dataset.fakultas;
            document.getElementById('prodi_nama').value = btn.dataset.nama;
            document.getElementById('prodi_strata').value = btn.dataset.strata;
            
            form.setAttribute('data-url', "<?= base_url('admin/jurusan/edit') ?>");
        }
        new bootstrap.Modal(document.getElementById('modalProdi')).show();
    }

    // --- 2. LIVE VALIDATION ---
    ['prodi_fakultas', 'prodi_nama'].forEach(id => {
        const input = document.getElementById(id);
        input.addEventListener('input', () => {
            const error = input.closest('.mb-4').querySelector('.error-text');
            if(input.value.length > 0 && input.value.length < 4){
                error.innerText = "Minimal 4 karakter diperlukan.";
                input.parentElement.style.borderColor = "#ef4444";
            } else {
                error.innerText = "";
                input.parentElement.style.borderColor = ""; 
            }
        });
    });

    // --- 3. LIVE SEARCH & FILTER ---
    function highlight(text, keyword) {
        if (!keyword) return text;
        return text.replace(new RegExp(keyword, "gi"), match => `<mark>${match}</mark>`);
    }

    function filterData() {
        const keyword = document.getElementById('searchProdi').value.toLowerCase();
        const strataFilter = document.getElementById('filterStrata').value;
        const rows = document.querySelectorAll('#table-body-prodi tr:not(#empty-state)');

        rows.forEach(row => {
            const namaRaw = row.dataset.nama || '';
            const fakRaw = row.dataset.fak || '';
            const strRaw = row.dataset.strata || '';

            let show = true;
            if (keyword && !namaRaw.includes(keyword) && !fakRaw.includes(keyword)) show = false;
            if (strataFilter && strRaw !== strataFilter) show = false;

            row.style.display = show ? '' : 'none';

            if (show) {
                const namaEl = row.querySelector('.search-target-nama');
                const fakEl = row.querySelector('.search-target-fak');
                if (namaEl) namaEl.innerHTML = highlight(row.querySelector('.btn-edit').dataset.nama, keyword);
                if (fakEl) fakEl.innerHTML = highlight(row.querySelector('.btn-edit').dataset.fakultas, keyword);
            }
        });
    }

    let searchDebounce;
    document.getElementById('searchProdi').addEventListener('input', () => {
        clearTimeout(searchDebounce);
        searchDebounce = setTimeout(filterData, 300);
    });
    document.getElementById('filterStrata').addEventListener('change', filterData);


    // --- 4. UNIFIED FORM SUBMITTER (ADD & EDIT) ---
    document.getElementById('formProdi').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btnSubmit = document.getElementById('btnSubmitProdi');
        const mode = prodiMode;
        
        btnSubmit.disabled = true; 
        btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Memproses...';

        const formData = new FormData(this);
        const url = this.getAttribute('data-url');

        try {
            const response = await fetch(url, { method: "POST", body: formData });
            const result = await response.json();

            if(result.success) {
                const data = result.data;
                const bgStrata = getStrataBg(data.strata);

                if (mode === 'add') {
                    // Logic ADD: Bikin Row Baru
                    const newRow = `
                        <tr id="row-${data.id}" data-nama="${data.nama_prodi.toLowerCase()}" data-fak="${data.fakultas.toLowerCase()}" data-strata="${data.strata}">
                            <td class="fw-bold text-white-400">#${data.id}</td>
                            <td class="col-fakultas">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="fak-avatar" style="background: #3b82f6; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.9rem;"><i class="bi bi-building"></i></div>
                                    <div class="fw-bold text-white search-target-fak">${data.fakultas}</div>
                                </div>
                            </td>
                            <td class="col-nama">
                                <div class="fw-bold text-white fs-6 search-target-nama">${data.nama_prodi}</div>
                            </td>
                            <td class="text-center col-strata">
                                <span class="badge" style="background: ${bgStrata} !important; color: white; border: none; padding: 8px 16px; border-radius: 20px;">${data.strata}</span>
                            </td>
                            <td>
                                <div class="action-btn-group">
                                    <button class="btn-action btn-edit-action btn-edit" data-bs-toggle="tooltip" title="Edit Data" data-id="${data.id}" data-fakultas="${data.fakultas}" data-nama="${data.nama_prodi}" data-strata="${data.strata}"><i class="bi bi-pencil-square"></i></button>
                                    <button class="btn-action btn-del-action btn-delete" data-bs-toggle="tooltip" title="Hapus Permanen" data-id="${data.id}" data-nama="${data.nama_prodi}"><i class="bi bi-trash3"></i></button>
                                </div>
                            </td>
                        </tr>
                    `;
                    
                    const tbody = document.getElementById('table-body-prodi');
                    if(document.getElementById('empty-state')) document.getElementById('empty-state').remove();
                    
                    tbody.insertAdjacentHTML('afterbegin', newRow);
                    const injectedRow = document.getElementById(`row-${data.id}`);
                    
                    injectedRow.style.opacity = 0;
                    injectedRow.style.transform = "translateY(20px)";
                    setTimeout(() => {
                        injectedRow.style.transition = "all 0.5s cubic-bezier(0.4, 0, 0.2, 1)";
                        injectedRow.style.opacity = 1;
                        injectedRow.style.transform = "translateY(0)";
                        injectedRow.classList.add('row-highlight');
                        setTimeout(() => { injectedRow.classList.remove('row-highlight'); }, 2000);
                    }, 50);

                    let currTotal = parseInt(document.getElementById('stat-prodi').innerText);
                    document.getElementById('stat-prodi').innerText = currTotal + 1;

                } else {
                    // Logic EDIT: Update Row Lama
                    const row = document.getElementById(`row-${data.id}`);
                    row.querySelector('.search-target-fak').innerText = data.fakultas;
                    row.querySelector('.search-target-nama').innerText = data.nama_prodi;
                    row.querySelector('.col-strata .badge').innerText = data.strata;
                    row.querySelector('.col-strata .badge').style.background = bgStrata;
                    
                    row.dataset.nama = data.nama_prodi.toLowerCase();
                    row.dataset.fak = data.fakultas.toLowerCase();
                    row.dataset.strata = data.strata;
                    
                    const btnEdit = row.querySelector('.btn-edit');
                    btnEdit.dataset.fakultas = data.fakultas;
                    btnEdit.dataset.nama = data.nama_prodi;
                    btnEdit.dataset.strata = data.strata;

                    row.classList.add('row-highlight');
                    setTimeout(() => { row.classList.remove('row-highlight'); }, 2000);
                }

                bootstrap.Modal.getInstance(document.getElementById('modalProdi')).hide();
                this.reset();
                Toast.fire({ icon: 'success', title: mode === 'add' ? 'Data berhasil ditambahkan! 🚀' : 'Data tersinkronisasi! ✨' });
                
                attachEventHandlers(); 
                initTooltips();
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal', text: result.message, background: '#1e293b', color: '#fff' });
            }
        } catch (error) { Toast.fire({ icon: 'error', title: 'Kesalahan server.' }); } 
        finally { 
            btnSubmit.disabled = false; 
            if(mode === 'add'){
                btnSubmit.innerHTML = '<i class="bi bi-check2-circle fs-5" id="btnSubmitProdiIcon"></i> <span id="btnSubmitProdiText">Simpan Data</span>';
            } else {
                btnSubmit.innerHTML = '<i class="bi bi-cloud-arrow-up fs-5" id="btnSubmitProdiIcon"></i> <span id="btnSubmitProdiText">Update Data</span>';
            }
        }
    });

    // --- 5. LIVE PREVIEW PADA EDIT FORM ---
    document.getElementById('prodi_nama').addEventListener('input', function() {
        if(prodiMode === 'edit') {
            const id = document.getElementById('prodi_id').value;
            const row = document.getElementById(`row-${id}`);
            if(row) row.querySelector('.search-target-nama').innerText = this.value || '(Kosong)';
        }
    });

    // Auto-revert ke data awal jika modal edit batal di-save
    document.getElementById('modalProdi').addEventListener('hidden.bs.modal', function () {
        if(prodiMode === 'edit') {
            const id = document.getElementById('prodi_id').value;
            const row = document.getElementById(`row-${id}`);
            if(row){
                const originalName = row.querySelector('.btn-edit').dataset.nama;
                row.querySelector('.search-target-nama').innerText = originalName;
            }
        }
    });

    // --- 6. EVENT DELEGATION EDIT & DELETE ---
    function attachEventHandlers() {
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.onclick = function() {
                bootstrap.Tooltip.getInstance(this)?.hide();
                openModalProdi('edit', this);
            }
        });

        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.onclick = async function() {
                bootstrap.Tooltip.getInstance(this)?.hide();
                const id = this.dataset.id;
                const nama = this.dataset.nama;

                const confirm = await Swal.fire({
                    title: 'Hapus Permanen?', html: `Yakin menghapus <b>${nama}</b>?`, icon: 'warning',
                    showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b', confirmButtonText: 'Ya, Eksekusi!', background: '#1e293b', color: '#fff'
                });

                if (confirm.isConfirmed) {
                    const row = document.getElementById(`row-${id}`);
                    row.style.opacity = 0.5; // Visual feedback langsung
                    try {
                        const response = await fetch(`<?= base_url('admin/jurusan') ?>/${id}`, { method: "DELETE" });
                        const result = await response.json();
                        if (result.success) {
                            row.style.transition = "all 0.4s ease"; 
                            row.style.opacity = 0; 
                            row.style.transform = "translateX(50px)";
                            setTimeout(() => {
                                row.remove();
                                let currTotal = parseInt(document.getElementById('stat-prodi').innerText);
                                document.getElementById('stat-prodi').innerText = currTotal - 1;
                            }, 400);
                            Toast.fire({ icon: 'success', title: 'Data musnah! 🗑️' });
                        } else {
                            row.style.opacity = 1;
                            Swal.fire({ icon: 'error', title: 'Gagal', text: result.message, background: '#1e293b', color: '#fff' });
                        }
                    } catch (err) { 
                        row.style.opacity = 1;
                        Toast.fire({ icon: 'error', title: 'Kesalahan server.' }); 
                    }
                }
            }
        });
    }

    document.addEventListener("DOMContentLoaded", attachEventHandlers);
    function tolakHapus() { Swal.fire({ title: 'Akses Ditolak!', text: 'Wewenang tidak mencukupi.', icon: 'warning', confirmButtonColor: '#f59e0b', background: '#1e293b', color: '#fff' }); }
</script>
<?= $this->endSection() ?>