<?= $this->extend('layout/dashboard_template') ?>

<?= $this->section('page_title') ?>
Kelola Berita Publikasi
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
        <a href="<?= base_url('admin/berita') ?>" class="nav-item active">📰 Kelola Berita</a>
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
    .table-glass tbody tr:hover { transform: translateY(-4px) scale(1.01); box-shadow: 0 20px 40px rgba(16, 185, 129, 0.15); background: rgba(16, 185, 129, 0.05); }
    .table-glass td { vertical-align: middle; padding: 20px 16px; border-bottom: none !important; position: relative; font-size: 0.98rem; font-weight: 500; }
    mark { background: rgba(16, 185, 129, 0.4); color: #fff; border-radius: 4px; padding: 0 2px; }

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
    .input-group-glass:hover { border-color: rgba(16, 185, 129, 0.5); }
    .input-group-glass:focus-within { background: rgba(0, 0, 0, 0.5); border-color: #10b981; box-shadow: 0 0 25px rgba(16, 185, 129, 0.25), inset 0 0 10px rgba(16, 185, 129, 0.05); transform: translateY(-2px); }
    
    .input-group-glass .icon-wrapper { padding: 0 15px; color: #64748b; font-size: 1.2rem; transition: color 0.3s ease; display: flex; align-items: center; justify-content: center; }
    .input-group-glass:focus-within .icon-wrapper { color: #34d399; }
    
    /* Input sizing */
    .input-group-glass .form-control, .input-group-glass .form-select { flex: 1; background: transparent; border: none; color: #f8fafc; padding: 22px 15px 8px 0; font-size: 0.95rem; box-shadow: none !important; }
    
    /* FLOATING LABELS MAGIC */
    .floating-group { position: relative; }
    .floating-group label { position: absolute; left: 48px; top: 50%; transform: translateY(-50%); color: #64748b; font-size: 0.9rem; pointer-events: none; transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1); margin: 0; }
    .floating-group input:focus ~ label, 
    .floating-group input:not(:placeholder-shown) ~ label,
    .floating-group textarea:focus ~ label,
    .floating-group textarea:not(:placeholder-shown) ~ label { top: 12px; font-size: 0.65rem; color: #34d399; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }

    /* Custom Textarea adjustment */
    .floating-group textarea { padding-top: 28px !important; min-height: 120px; resize: vertical; }
    .floating-group.align-items-start .icon-wrapper { padding-top: 15px; }
    .floating-group.align-items-start label { top: 22px; }

    /* BUTTONS */
    .btn-gradient-add, .btn-gradient-edit { color: #fff; border: none; font-weight: bold; border-radius: 12px; padding: 14px 25px; transition: all 0.3s ease; display: inline-flex; align-items: center; justify-content: center; gap: 8px; letter-spacing: 0.5px; text-transform: uppercase; font-size: 0.85rem;}
    .btn-gradient-add { background: linear-gradient(135deg, #10b981, #059669); }
    .btn-gradient-edit { background: linear-gradient(135deg, #3b82f6, #06b6d4); }
    .btn-gradient-add:hover, .btn-gradient-edit:hover { transform: translateY(-2px); color: #fff; filter: brightness(1.1); box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);}
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
    <div class="glass-card stat-card" style="--stat-color: #10b981;">
        <i class="bi bi-newspaper stat-icon" style="color: #10b981;"></i>
        <div class="d-flex justify-content-between align-items-center">
            <div><h3 id="stat-berita"><?= esc($total_berita ?? 0) ?></h3><p>Total Artikel Berita</p></div>
        </div>
    </div>
    <div class="glass-card stat-card" style="--stat-color: #8b5cf6;">
        <i class="bi bi-robot stat-icon" style="color: #8b5cf6;"></i>
        <div class="d-flex justify-content-between align-items-center">
            <div><h3>Aktif</h3><p>Trigger AI Halaman</p></div>
        </div>
    </div>
</div>

<div class="glass-card">
    <div class="search-controls">
        <div class="input-group-glass search-input mb-0">
            <div class="icon-wrapper"><i class="bi bi-search"></i></div>
            <input type="text" id="searchBerita" class="form-control" placeholder="Cari judul, slug, atau penulis..." autocomplete="off" style="padding: 14px 15px 14px 0;">
        </div>
    </div>

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-4 border-bottom border-secondary border-opacity-25 gap-3">
        <div>
            <h2 class="fw-bolder text-white mb-1" style="margin-top:0; font-size: 1.8rem;">📰 Daftar Publikasi Berita</h2>
            <p class="text-secondary mb-0" style="font-size: 1rem;">Menulis Berita di sini akan otomatis di-<em>generate</em> menjadi Halaman Web lewat Trigger MySQL.</p>
        </div>
        <button class="btn-gradient-add shadow-lg" onclick="openModalBerita('add')">
            <i class="bi bi-plus-circle fs-5"></i> <span>Tulis Berita Baru</span>
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-glass table-hover mb-0" id="beritaTable">
            <thead>
                <tr>
                    <th scope="col" width="8%"><i class="bi bi-hash"></i> ID</th>
                    <th scope="col" width="40%"><i class="bi bi-type-h1"></i> Judul & Slug</th>
                    <th scope="col" width="15%"><i class="bi bi-pen"></i> Penulis</th>
                    <th scope="col" width="20%" class="text-center"><i class="bi bi-calendar-event"></i> Tanggal Rilis</th>
                    <th scope="col" width="17%" class="text-end"><i class="bi bi-gear-fill"></i> Aksi</th>
                </tr>
            </thead>
            <tbody id="table-body-berita">
                <?php if(!empty($daftar_berita)): ?>
                    <?php foreach($daftar_berita as $berita): ?>
                        <tr id="row-<?= $berita['id_berita'] ?>" data-judul="<?= strtolower(esc($berita['judul_berita'])) ?>" data-slug="<?= strtolower(esc($berita['link_url'])) ?>" data-penulis="<?= strtolower(esc($berita['penulis'])) ?>">
                            <td class="fw-bold text-white-400">#<?= esc($berita['id_berita']) ?></td>
                            <td>
                                <div class="fw-bold text-white fs-6 mb-1 search-target-judul"><?= esc($berita['judul_berita']) ?></div>
                                <div class="d-flex align-items-center gap-1">
                                    <i class="bi bi-link-45deg text-info"></i>
                                    <span class="badge bg-secondary bg-opacity-25 text-info fw-normal search-target-slug" style="font-family: monospace;">/<?= esc($berita['link_url']) ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="text-secondary fw-bold search-target-penulis">@<?= esc($berita['penulis']) ?></div>
                            </td>
                            <td class="text-center text-secondary">
                                <div class="badge bg-dark bg-opacity-50 border border-secondary border-opacity-25 px-3 py-2 text-light">
                                    <?= date('d M Y - H:i', strtotime($berita['created_at'])) ?>
                                </div>
                            </td>
                            <td class="action-col">
                                <div class="action-btn-group">
                                    <button class="btn-action btn-edit-action btn-edit" data-bs-toggle="tooltip" title="Edit Berita"
                                        data-id="<?= $berita['id_berita'] ?>" 
                                        data-judul="<?= esc($berita['judul_berita']) ?>" 
                                        data-slug="<?= esc($berita['link_url']) ?>" 
                                        data-penulis="<?= esc($berita['penulis']) ?>"
                                        data-konten="<?= esc($berita['isi_berita'] ?? '') ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    
                                    <button class="btn-action btn-del-action btn-delete" data-bs-toggle="tooltip" title="Hapus Permanen" 
                                        data-id="<?= $berita['id_berita'] ?>" 
                                        data-judul="<?= esc($berita['judul_berita']) ?>">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr id="empty-state">
                        <td colspan="5" class="text-center py-5 text-secondary">
                            <i class="bi bi-journal-x fs-1 opacity-50 mb-3 d-block"></i>
                            <h4 class="text-white mb-2">Belum ada berita dipublikasikan</h4>
                            <button class="btn-gradient-add mt-3" onclick="openModalBerita('add')">Tulis Artikel Pertama</button>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalBerita" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg border-0">
    <div class="modal-content glass-modal border-0 p-2">
      <div class="modal-header border-0 pb-0">
        <div class="d-flex align-items-center gap-3">
            <div id="modalBeritaIcon" style="padding: 12px; border-radius: 12px; background: rgba(16, 185, 129, 0.1); color: #10b981;">
                <i class="bi bi-newspaper fs-4"></i>
            </div>
            <div>
                <h5 class="modal-title fw-bolder text-white mb-0" id="modalBeritaTitle">Tulis Berita Baru</h5>
                <small class="text-secondary" id="modalBeritaSubtitle" style="font-size: 0.75rem;">AI akan men-generate halaman dari teks ini.</small>
            </div>
        </div>
        <button type="button" class="btn-close btn-close-custom align-self-start mt-1" data-bs-dismiss="modal"></button>
      </div>
      
      <form id="formBerita">
          <input type="hidden" name="id_berita" id="berita_id">
          <div class="modal-body px-4 py-4">
            
            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="input-group-glass floating-group">
                        <div class="icon-wrapper"><i class="bi bi-type-h1"></i></div>
                        <input type="text" class="form-control" name="judul_berita" id="berita_judul" placeholder=" " required autocomplete="off">
                        <label>Judul Berita</label>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="input-group-glass floating-group">
                        <div class="icon-wrapper"><i class="bi bi-link-45deg"></i></div>
                        <input type="text" class="form-control" name="link_url" id="berita_slug" placeholder=" " required autocomplete="off">
                        <label>URL Slug (Otomatis)</label>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="input-group-glass floating-group">
                        <div class="icon-wrapper"><i class="bi bi-pen"></i></div>
                        <input type="text" class="form-control" name="penulis" id="berita_penulis" value="<?= esc($nama_admin) ?>" placeholder=" " required autocomplete="off">
                        <label>Nama Penulis</label>
                    </div>
                </div>

                <div class="col-md-12 mb-2">
                    <div class="input-group-glass floating-group align-items-start">
                        <div class="icon-wrapper"><i class="bi bi-body-text"></i></div>
                        <textarea class="form-control" name="isi_berita" id="berita_konten" placeholder=" " required></textarea>
                        <label>Konten / Isi Artikel</label>
                    </div>
                </div>
            </div>

          </div>
          <div class="modal-footer border-0 pt-0 pb-4 px-4 d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-dark text-secondary fw-bold px-4" style="border-radius: 12px; padding: 12px;" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn-gradient-add" id="btnSubmitBerita">
                <i class="bi bi-send fs-5" id="btnSubmitBeritaIcon"></i> <span id="btnSubmitBeritaText">Publikasikan</span>
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
    let beritaMode = 'add'; 

    // Auto-Slug Generator
    function slugify(text) {
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-')           // Replace spaces with -
            .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
            .replace(/\-\-+/g, '-')         // Replace multiple - with single -
            .replace(/^-+/, '')             // Trim - from start of text
            .replace(/-+$/, '');            // Trim - from end of text
    }

    document.getElementById('berita_judul').addEventListener('input', function() {
        if(beritaMode === 'add') {
            document.getElementById('berita_slug').value = slugify(this.value);
        }
    });

    // --- 1. FUNGSI MEMBUKA UNIFIED MODAL ---
    function openModalBerita(mode, btn = null) {
        beritaMode = mode;
        const form = document.getElementById('formBerita');
        form.reset();
        
        const title = document.getElementById('modalBeritaTitle');
        const subtitle = document.getElementById('modalBeritaSubtitle');
        const icon = document.getElementById('modalBeritaIcon');
        const btnSubmitText = document.getElementById('btnSubmitBeritaText');
        const btnSubmitIcon = document.getElementById('btnSubmitBeritaIcon');
        const btnSubmit = document.getElementById('btnSubmitBerita');

        if (mode === 'add') {
            title.innerText = 'Tulis Berita Baru';
            subtitle.innerText = 'AI akan men-generate halaman dari teks ini.';
            icon.innerHTML = '<i class="bi bi-newspaper fs-4"></i>';
            icon.style.background = 'rgba(16, 185, 129, 0.1)';
            icon.style.color = '#10b981';
            
            btnSubmit.className = 'btn-gradient-add';
            btnSubmitIcon.className = 'bi bi-send fs-5';
            btnSubmitText.innerText = 'Publikasikan';
            
            document.getElementById('berita_id').value = '';
            document.getElementById('berita_penulis').value = "<?= esc($nama_admin) ?>"; // Default to current admin
            
            // Asumsikan URL backend (Silakan sesuaikan dengan routes CodeIgniter Anda)
            form.setAttribute('data-url', "<?= base_url('admin/berita/store') ?>");
        } else {
            title.innerText = 'Edit Artikel Berita';
            subtitle.innerText = 'Perubahan akan otomatis terupdate di website.';
            icon.innerHTML = '<i class="bi bi-pencil-square fs-4"></i>';
            icon.style.background = 'rgba(59, 130, 246, 0.1)';
            icon.style.color = '#3b82f6';
            
            btnSubmit.className = 'btn-gradient-edit';
            btnSubmitIcon.className = 'bi bi-cloud-arrow-up fs-5';
            btnSubmitText.innerText = 'Update Berita';

            document.getElementById('berita_id').value = btn.dataset.id;
            document.getElementById('berita_judul').value = btn.dataset.judul;
            document.getElementById('berita_slug').value = btn.dataset.slug;
            document.getElementById('berita_penulis').value = btn.dataset.penulis;
            document.getElementById('berita_konten').value = btn.dataset.konten;
            
            // Asumsikan URL backend (Silakan sesuaikan dengan routes CodeIgniter Anda)
            form.setAttribute('data-url', "<?= base_url('admin/berita/store') ?>"); 
        }
        new bootstrap.Modal(document.getElementById('modalBerita')).show();
    }


    // --- 2. LIVE SEARCH & FILTER ---
    function highlight(text, keyword) {
        if (!keyword) return text;
        return text.replace(new RegExp(keyword, "gi"), match => `<mark>${match}</mark>`);
    }

    function filterData() {
        const keyword = document.getElementById('searchBerita').value.toLowerCase();
        const rows = document.querySelectorAll('#table-body-berita tr:not(#empty-state)');

        rows.forEach(row => {
            const judulRaw = row.dataset.judul || '';
            const slugRaw = row.dataset.slug || '';
            const penulisRaw = row.dataset.penulis || '';

            let show = true;
            if (keyword && !judulRaw.includes(keyword) && !slugRaw.includes(keyword) && !penulisRaw.includes(keyword)) {
                show = false;
            }

            row.style.display = show ? '' : 'none';

            if (show) {
                const judulEl = row.querySelector('.search-target-judul');
                const slugEl = row.querySelector('.search-target-slug');
                const penulisEl = row.querySelector('.search-target-penulis');
                
                const btnData = row.querySelector('.btn-edit');
                if (judulEl) judulEl.innerHTML = highlight(btnData.dataset.judul, keyword);
                if (slugEl) slugEl.innerHTML = "/" + highlight(btnData.dataset.slug, keyword);
                if (penulisEl) penulisEl.innerHTML = "@" + highlight(btnData.dataset.penulis, keyword);
            }
        });
    }

    let searchDebounce;
    document.getElementById('searchBerita').addEventListener('input', () => {
        clearTimeout(searchDebounce);
        searchDebounce = setTimeout(filterData, 300);
    });

    // --- 3. UNIFIED FORM SUBMITTER (ADD & EDIT) ---
    document.getElementById('formBerita').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btnSubmit = document.getElementById('btnSubmitBerita');
        const mode = beritaMode;
        
        btnSubmit.disabled = true; 
        btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Memproses...';

        const formData = new FormData(this);
        const url = this.getAttribute('data-url');

        try {
            const response = await fetch(url, { method: "POST", body: formData });
            const result = await response.json();

            if(result.success) {
                bootstrap.Modal.getInstance(document.getElementById('modalBerita')).hide();
                this.reset();
                Toast.fire({ icon: 'success', title: mode === 'add' ? 'Berita berhasil dipublikasikan! 🚀' : 'Berita terupdate! ✨' });
                
                // Reload to fetch fresh data (and triggers)
                setTimeout(() => location.reload(), 1500);
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal', text: result.message || 'Terjadi kesalahan sistem.', background: '#1e293b', color: '#fff' });
            }
        } catch (error) { Toast.fire({ icon: 'error', title: 'Kesalahan server/jaringan.' }); } 
        finally { 
            btnSubmit.disabled = false; 
            if(mode === 'add'){
                btnSubmit.innerHTML = '<i class="bi bi-send fs-5" id="btnSubmitBeritaIcon"></i> <span id="btnSubmitBeritaText">Publikasikan</span>';
            } else {
                btnSubmit.innerHTML = '<i class="bi bi-cloud-arrow-up fs-5" id="btnSubmitBeritaIcon"></i> <span id="btnSubmitBeritaText">Update Berita</span>';
            }
        }
    });


    // --- 4. EVENT DELEGATION EDIT & DELETE ---
    function attachEventHandlers() {
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.onclick = function() {
                bootstrap.Tooltip.getInstance(this)?.hide();
                openModalBerita('edit', this);
            }
        });

        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.onclick = async function() {
                bootstrap.Tooltip.getInstance(this)?.hide();
                const id = this.dataset.id;
                const judul = this.dataset.judul;

                const confirm = await Swal.fire({
                    title: 'Hapus Berita?', html: `Yakin menghapus artikel <b>${judul}</b>?<br><br><i>Trigger MySQL akan otomatis menghapus Halaman Web yang terkait!</i>`, icon: 'warning',
                    showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b', confirmButtonText: 'Ya, Hapus!', background: '#1e293b', color: '#fff'
                });

                if (confirm.isConfirmed) {
                    const row = document.getElementById(`row-${id}`);
                    row.style.opacity = 0.5; // Visual feedback
                    try {
                        const response = await fetch(`<?= base_url('admin/berita/delete') ?>/${id}`, { method: "GET" }); // Menyesuaikan routes GET delete yg ada di system anda
                        
                        // Menangani jika backend mengembalikan JSON
                        // Jika backend controller Anda belum JSON, Anda harus merubahnya.
                        const result = await response.json();
                        
                        if (result.success) {
                            row.style.transition = "all 0.4s ease"; 
                            row.style.opacity = 0; 
                            row.style.transform = "translateX(50px)";
                            setTimeout(() => {
                                row.remove();
                                let currTotal = parseInt(document.getElementById('stat-berita').innerText);
                                document.getElementById('stat-berita').innerText = currTotal - 1;
                            }, 400);
                            Toast.fire({ icon: 'success', title: 'Artikel terhapus! 🗑️' });
                        } else {
                            row.style.opacity = 1;
                            Swal.fire({ icon: 'error', title: 'Gagal', text: result.message || 'Terjadi kesalahan', background: '#1e293b', color: '#fff' });
                        }
                    } catch (err) { 
                        row.style.opacity = 1;
                        Toast.fire({ icon: 'info', title: 'Periksa Controller. Pastikan mereturn JSON.' }); 
                    }
                }
            }
        });
    }

    document.addEventListener("DOMContentLoaded", attachEventHandlers);

    // LOGOUT SCRIPT
    document.getElementById('btn-logout-admin').addEventListener('click', function(e) {
        e.preventDefault(); const logoutUrl = this.getAttribute('href');
        Swal.fire({ title: 'Akhiri Sesi?', text: "Pastikan artikel draft Anda sudah disimpan.", icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b', confirmButtonText: 'Ya, Keluar', background: '#1e293b', color: '#fff' }).then((result) => { if (result.isConfirmed) { window.location.href = logoutUrl; } });
    });
</script>
<?= $this->endSection() ?>