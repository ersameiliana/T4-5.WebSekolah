<?= $this->extend('layout/dashboard_template') ?>

<?= $this->section('page_title') ?>
Otorisasi & Hak Akses
<?= $this->endSection() ?>

<?php 
    $role = session()->get('role_admin') ?? 'Guest'; 
    $nama_admin = session()->get('nama_admin') ?? 'Administrator';
    $inisial = strtoupper(substr($nama_admin, 0, 1));
    
    // Fungsi untuk warna avatar acak berdasarkan nama
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
        <a href="<?= base_url('admin/pengguna') ?>" class="nav-item">👥 Kelola Akun User</a>
        <a href="<?= base_url('admin/jurusan') ?>" class="nav-item">🏢 Kelola Jurusan</a>
    <?php endif; ?>
    
    <?php if ($role === 'Sistem/Database'): ?>
        <div class="nav-section-title" style="color: #ff6b6b;">Superadmin Tools</div>
        <a href="<?= base_url('admin/otorisasi') ?>" class="nav-item active" style="color: #fca5a5;">🔐 Cabut Hak Akses</a>
        <a href="<?= base_url('admin/system-logs') ?>" class="nav-item" style="color: #fca5a5;">⚙️ Database Logs</a>
    <?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    /* FIX MODAL BOCOR */
    .modal { display: none; z-index: 1055; }

    /* BASIC STYLING */
    .top-bar-area { display: flex; justify-content: flex-end; align-items: center; gap: 20px; margin-bottom: 30px; }
    .profile-info { display: flex; align-items: center; gap: 12px; text-align: right; }
    .profile-avatar { width: 45px; height: 45px; border-radius: 50%; background: linear-gradient(135deg, #3a86ff, #64ffda); display: flex; justify-content: center; align-items: center; font-weight: 800; font-size: 1.2rem; color: #0a192f; border: 2px solid rgba(100,255,218,0.2); }
    .btn-logout-top { background: rgba(220, 53, 69, 0.1); color: #ff6b6b; border: 1px solid rgba(220, 53, 69, 0.3); padding: 10px 20px; border-radius: 10px; font-weight: 700; font-size: 0.9rem; text-decoration: none; transition: 0.3s ease; display: flex; align-items: center; gap: 8px; }
    .btn-logout-top:hover { background: rgba(220, 53, 69, 0.2); transform: translateY(-2px); color: #ff6b6b; }

    /* GLASS CARD WADAH UTAMA */
    .glass-card { position: relative; background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.08); border-radius: 20px; padding: 30px; }
    
    /* SEARCH & ACTION ROW */
    .controls-row { display: flex; justify-content: space-between; align-items: center; gap: 15px; flex-wrap: wrap; margin-bottom: 25px;}
    .input-group-glass { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; display: flex; align-items: center; transition: 0.3s; flex: 1; min-width: 250px;}
    .input-group-glass:focus-within { border-color: #64ffda; box-shadow: 0 0 15px rgba(100, 255, 218, 0.2); }
    .input-group-glass .icon-wrapper { padding: 0 15px; color: #8892b0; }
    .input-group-glass .form-control { background: transparent; border: none; color: #fff; padding: 12px 15px 12px 0; box-shadow: none; width: 100%;}
    
    .btn-gradient-add { background: linear-gradient(135deg, #3a86ff, #64ffda); color: #0a192f; border: none; font-weight: 600; border-radius: 12px; padding: 12px 25px; transition: 0.3s; display: inline-flex; align-items: center; gap: 8px; text-transform: uppercase; font-size: 0.85rem;}
    .btn-gradient-add:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(100,255,218,0.3); color: #0a192f;}

    /* MODAL FORM ELEMENTS */
    .form-group-custom label { color: #8892b0; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; font-size: 0.8rem; margin-bottom: 8px; display: block; }
    .form-group-custom .form-control, .form-group-custom .form-select { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); color: #ccd6f6; border-radius: 10px; padding: 12px 15px; font-size: 0.95rem; transition: 0.3s; }
    .form-group-custom .form-control:focus, .form-group-custom .form-select:focus { background: rgba(255, 255, 255, 0.08); border-color: #64ffda; box-shadow: 0 0 10px rgba(100, 255, 218, 0.2); outline: none; color: #ffffff;}
    .form-group-custom .form-control::placeholder { color: rgba(204, 214, 246, 0.4); }
    .form-group-custom select option { background: #0a192f; color: #ccd6f6; }

    /* TABLES */
    .table-glass { --bs-table-bg: transparent; --bs-table-color: #ccd6f6; --bs-table-border-color: rgba(255,255,255,0.05); margin-top: 10px; }
    .table-glass thead th { color: #8892b0; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; font-size: 0.85rem; padding: 15px 10px; border-bottom: 1px solid rgba(255,255,255,0.1) !important;}
    .table-glass tbody tr { transition: 0.3s; border-bottom: 1px solid rgba(255,255,255,0.05); }
    .table-glass tbody tr:hover { background: rgba(255,255,255,0.03); }
    .table-glass td { vertical-align: middle; padding: 15px 10px; font-size: 0.95rem; }
    
    .user-avatar { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1rem; text-transform: uppercase; }

    .action-btn-group { display: flex; gap: 6px; justify-content: flex-end; }
    .btn-action { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: none; transition: 0.2s; font-size: 1rem; }
    .btn-edit-action { background: rgba(100, 255, 218, 0.1); color: #64ffda; }
    .btn-edit-action:hover { background: #64ffda; color: #0a192f; }
    .btn-del-action { background: rgba(220, 53, 69, 0.1); color: #ff6b6b; }
    .btn-del-action:hover { background: #ff6b6b; color: #fff; }

    /* MODAL GLASSMORPHISM */
    .glass-modal { background: rgba(10, 25, 47, 0.85) !important; backdrop-filter: blur(25px) !important; -webkit-backdrop-filter: blur(25px); border: 1px solid rgba(255, 255, 255, 0.15) !important; border-radius: 18px !important; box-shadow: 0 20px 60px rgba(0,0,0,0.6) !important; }
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

<div class="mb-4">
    <h2 class="fw-bolder text-white mb-1">Manajemen Administrator</h2>
    <p class="text-secondary mb-0">Kelola daftar *User ID* dan Hak Akses seluruh sistem Astryveil.</p>
</div>

<div class="glass-card">
    <div class="controls-row">
        <div class="input-group-glass">
            <div class="icon-wrapper"><i class="bi bi-search"></i></div>
            <input type="text" id="searchAdmin" class="form-control" placeholder="Cari User ID admin..." autocomplete="off">
        </div>
        <button class="btn-gradient-add" onclick="openModalAdmin('add')">
            <i class="bi bi-person-plus-fill"></i> Tambah Admin Baru
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-glass table-hover mb-0" id="adminTable">
            <thead>
                <tr>
                    <th width="35%">User ID (Username)</th>
                    <th width="25%">Hak Akses (Role)</th>
                    <th width="20%" class="text-center">Status Keamanan</th>
                    <th width="20%" class="text-end">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($daftar_admin)): ?>
                    <?php foreach($daftar_admin as $adm): ?>
                        <tr class="data-row" data-search="<?= strtolower(esc($adm['user_id'])) ?>">
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-avatar" style="background: <?= getAvatarColor($adm['user_id']) ?>;">
                                        <?= strtoupper(substr($adm['user_id'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-white search-target">@<?= esc($adm['user_id']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php 
                                    if ($adm['jenis_admin'] == 'Editing') {
                                        echo '<span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 py-2 rounded-pill">✏️ Editing</span>';
                                    } elseif ($adm['jenis_admin'] == 'Administrasi') {
                                        echo '<span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-3 py-2 rounded-pill">🗂️ Administrasi</span>';
                                    } else {
                                        echo '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2 rounded-pill">👑 Sistem/Database</span>';
                                    }
                                ?>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success bg-opacity-10 text-success"><i class="bi bi-shield-check"></i> Enkripsi Aktif</span>
                            </td>
                            <td class="text-end">
                                <div class="action-btn-group">
                                    <?php if($adm['user_id'] !== $nama_admin): ?>
                                        <button class="btn-action btn-edit-action" data-bs-toggle="tooltip" title="Edit Role" onclick="openModalAdmin('edit', this)" data-userid="<?= esc($adm['user_id']) ?>" data-role="<?= esc($adm['jenis_admin']) ?>"><i class="bi bi-pencil-square"></i></button>
                                        <button class="btn-action btn-del-action" data-bs-toggle="tooltip" title="Cabut Akses" onclick="hapusAdmin('<?= esc($adm['user_id']) ?>')"><i class="bi bi-trash3"></i></button>
                                    <?php else: ?>
                                        <span class="text-secondary small fst-italic mt-2 d-inline-block">Anda (Sedang Login)</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr id="empty-state">
                        <td colspan="4" class="text-center py-5 text-secondary">Data admin tidak ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalAdmin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered border-0">
        <div class="modal-content glass-modal border-0">
            <div class="modal-header">
                <h5 class="modal-title fw-bolder text-white" id="modalAdminTitle">Tambah Admin Baru</h5>
                <button type="button" class="btn-close btn-close-custom" data-bs-dismiss="modal"></button>
            </div>
            <form id="formAdmin">
                <input type="hidden" name="user_id_lama" id="adm_user_id_lama">
                <div class="modal-body px-4 py-4">
                    <div class="form-group-custom mb-3">
                        <label>User ID (Username)</label>
                        <input type="text" class="form-control" name="user_id" id="adm_user_id" required autocomplete="off" placeholder="Misal: admin_akademik">
                    </div>
                    <div class="form-group-custom mb-3">
                        <label>Hak Akses (Role)</label>
                        <select class="form-select" name="jenis_admin" id="adm_role" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="Editing">Editing (Konten & Publikasi)</option>
                            <option value="Administrasi">Administrasi (Data Akademik)</option>
                            <option value="Sistem/Database">Sistem/Database (Superadmin)</option>
                        </select>
                    </div>
                    <div class="form-group-custom mb-2">
                        <label id="adm_pass_label">Password Akun (Wajib)</label>
                        <input type="password" class="form-control" name="password" id="adm_password" placeholder="Masukkan password...">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-light px-4 border-secondary text-secondary fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-gradient-add" style="border-radius: 10px;" id="btnSubmitAdmin"><i class="bi bi-save"></i> Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Inisialisasi Toast untuk Notifikasi Sukses
    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, background: '#0a192f', color: '#64ffda' });

    document.addEventListener("DOMContentLoaded", () => {
        // Init Tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (t) { return new bootstrap.Tooltip(t); });

        // Simple Search Filter
        const searchInput = document.getElementById('searchAdmin');
        if(searchInput) {
            searchInput.addEventListener('input', function() {
                const keyword = this.value.toLowerCase();
                const rows = document.querySelectorAll('#adminTable tbody tr.data-row');
                let hasVisible = false;

                rows.forEach(row => {
                    const searchData = row.getAttribute('data-search');
                    if(searchData.includes(keyword)) {
                        row.style.display = '';
                        hasVisible = true;
                    } else {
                        row.style.display = 'none';
                    }
                });

                let emptyState = document.getElementById('empty-state');
                if(!hasVisible && !emptyState) {
                    document.querySelector('#adminTable tbody').insertAdjacentHTML('beforeend', '<tr id="empty-state"><td colspan="4" class="text-center py-5 text-secondary">Admin tidak ditemukan.</td></tr>');
                } else if (hasVisible && emptyState) {
                    emptyState.remove();
                }
            });
        }
    });

    // Logout Script
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
            background: '#1e293b', 
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Memutuskan koneksi...', background: '#1e293b', color: '#fff', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});
                window.location.href = logoutUrl; 
            }
        });
    });

    // FUNGSI UNTUK MEMBUKA MODAL (TAMBAH / EDIT)
    function openModalAdmin(mode, btn = null) {
        const form = document.getElementById('formAdmin');
        form.reset();
        const title = document.getElementById('modalAdminTitle');

        if(mode === 'add') {
            title.innerHTML = '<i class="bi bi-shield-lock-fill" style="color:#64ffda;"></i> Tambah Admin Baru';
            document.getElementById('adm_user_id_lama').value = '';
            document.getElementById('adm_pass_label').innerText = 'Password Akun (Wajib)';
            document.getElementById('adm_password').required = true;
            form.setAttribute('data-url', "<?= base_url('admin/otorisasi/add') ?>");
        } else {
            title.innerHTML = '<i class="bi bi-pencil-square" style="color:#64ffda;"></i> Edit Hak Akses';
            document.getElementById('adm_user_id_lama').value = btn.dataset.userid;
            document.getElementById('adm_user_id').value = btn.dataset.userid;
            document.getElementById('adm_role').value = btn.dataset.role;
            
            document.getElementById('adm_pass_label').innerText = 'Reset Password (Opsional)';
            document.getElementById('adm_password').required = false;
            form.setAttribute('data-url', "<?= base_url('admin/otorisasi/edit') ?>");
        }
        
        // Hide tooltip sebelum membuka modal
        if (btn) {
            const tooltip = bootstrap.Tooltip.getInstance(btn);
            if (tooltip) tooltip.hide();
        }
        
        new bootstrap.Modal(document.getElementById('modalAdmin')).show();
    }

    // PROSES SUBMIT FORM KE CONTROLLER MENGGUNAKAN FETCH API (AJAX)
    document.getElementById('formAdmin').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSubmitAdmin');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...'; 
        btn.disabled = true;

        const url = this.getAttribute('data-url');
        const formData = new FormData(this);

        try {
            const response = await fetch(url, { method: "POST", body: formData });
            const result = await response.json();
            
            if(result.success) {
                bootstrap.Modal.getInstance(document.getElementById('modalAdmin')).hide();
                Toast.fire({ icon: 'success', title: result.message });
                setTimeout(() => location.reload(), 1500); // Refresh otomatis setelah 1.5 detik
            } else { 
                Swal.fire({ icon: 'error', title: 'Gagal', text: result.message, background: '#1e293b', color: '#fff' }); 
            }
        } catch (error) { 
            Toast.fire({ icon: 'error', title: 'Kesalahan Server / Jaringan.' }); 
        } finally { 
            btn.innerHTML = originalText; btn.disabled = false; 
        }
    });

    // PROSES HAPUS DATA ADMIN KE CONTROLLER
    function hapusAdmin(userId) {
        Swal.fire({
            title: 'Cabut Hak Akses?',
            html: `Apakah Anda yakin ingin menghapus <b>@${userId}</b> selamanya? Tindakan ini tidak bisa dibatalkan!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus Permanen!',
            background: '#1e293b',
            color: '#fff'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await fetch(`<?= base_url('admin/otorisasi/') ?>${userId}`, {
                        method: 'DELETE' // Pastikan rute DELETE sudah ditambahkan di Routes.php
                    });
                    const res = await response.json();
                    
                    if(res.success) {
                        Swal.fire({ title: 'Terhapus!', text: res.message, icon: 'success', background: '#1e293b', color: '#fff' }).then(() => location.reload());
                    } else {
                        Swal.fire({ title: 'Gagal!', text: res.message, icon: 'error', background: '#1e293b', color: '#fff' });
                    }
                } catch (error) {
                    Swal.fire({ title: 'Error!', text: 'Terjadi kesalahan sistem atau jaringan.', icon: 'error', background: '#1e293b', color: '#fff' });
                }
            }
        });
    }
</script>

<?= $this->endSection() ?>