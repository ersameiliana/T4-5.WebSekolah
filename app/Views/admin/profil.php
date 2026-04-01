<?= $this->extend('layout/dashboard_template') ?>

<?= $this->section('page_title') ?>
Kelola Profil Web & Halaman
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
        <a href="<?= base_url('admin/profil') ?>" class="nav-item active">🏛️ Kelola Profil Web</a>
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

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    /* FIX MODAL PREVENT LEAK */
    .modal { display: none; z-index: 1055; }

    /* TOP BAR STYLING */
    .top-bar-area { display: flex; justify-content: flex-end; align-items: center; gap: 20px; margin-bottom: 30px; }
    .profile-info { display: flex; align-items: center; gap: 12px; text-align: right; }
    .profile-avatar { width: 45px; height: 45px; border-radius: 50%; background: linear-gradient(135deg, #f59e0b, #ef4444); display: flex; justify-content: center; align-items: center; font-weight: 800; font-size: 1.2rem; color: #fff; border: 2px solid rgba(255,255,255,0.1); }
    .btn-logout-top { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); padding: 10px 20px; border-radius: 10px; font-weight: 700; font-size: 0.9rem; text-decoration: none; transition: 0.3s ease; display: flex; align-items: center; gap: 8px; }
    .btn-logout-top:hover { background: rgba(239, 68, 68, 0.2); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(239, 68, 68, 0.2); color: #ef4444; }

    /* STATS CARD */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .stat-card { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.08); border-radius: 1.2rem; padding: 25px; }
    .stat-card h3 { margin: 0; font-size: 2.5rem; color: #fff; font-weight: 900; }
    .stat-card p { color: #cbd5e1; margin: 5px 0 0 0; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; font-weight: bold;}

    /* TABLE STYLING */
    .glass-card { background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.12); border-radius: 24px; padding: 40px; position: relative; }
    .table-glass { --bs-table-bg: transparent; --bs-table-color: #f8fafc; --bs-table-border-color: rgba(255,255,255,0.05); }
    .table-glass th { color: #94a3b8; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.75rem; padding-bottom: 15px; border-bottom: 2px solid rgba(255,255,255,0.1) !important;}
    .table-glass td { vertical-align: middle; padding: 15px 10px; border-bottom: 1px solid rgba(255,255,255,0.05); font-weight: 500; }
    .table-glass tbody tr:hover { background: rgba(59, 130, 246, 0.05); transform: scale(1.005); transition: 0.3s; }

    /* BUTTONS & FORMS */
    .btn-gradient-purple { background: linear-gradient(135deg, #8b5cf6, #6366f1); color: #fff; border: none; font-weight: bold; border-radius: 12px; padding: 12px 25px; transition: 0.3s; }
    .btn-gradient-purple:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(139, 92, 246, 0.3); color: #fff; }
    
    .input-group-glass { background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; display: flex; align-items: center; transition: 0.3s; }
    .input-group-glass:focus-within { border-color: #8b5cf6; background: rgba(0, 0, 0, 0.4); }
    .input-group-glass .icon-wrapper { padding: 0 15px; color: #64748b; }
    .input-group-glass .form-control, .input-group-glass textarea { background: transparent; border: none; color: #fff; padding: 12px 15px 12px 0; box-shadow: none !important; }

    .action-btn { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: none; transition: 0.2s; }
    .btn-edit-action { background: rgba(59, 130, 246, 0.1); color: #60a5fa; }
    .btn-edit-action:hover { background: #3b82f6; color: #fff; }
    .btn-del-action { background: rgba(239, 68, 68, 0.1); color: #f87171; }
    .btn-del-action:hover { background: #ef4444; color: #fff; }

    /* MODAL GLASSMORPHISM */
    .glass-modal { background: rgba(15, 23, 42, 0.98) !important; backdrop-filter: blur(25px) !important; border: 1px solid rgba(255, 255, 255, 0.1) !important; border-radius: 20px !important; }
    .btn-close-custom { filter: invert(1) grayscale(100%) brightness(200%); opacity: 0.5; }
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
    <a href="<?= base_url('auth/logout') ?>" id="btn-logout-admin" class="btn-logout-top">🚪 Logout</a>
</div>

<div class="stats-grid">
    <div class="stat-card" style="border-top: 3px solid #3b82f6;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3><?= esc($total_halaman) ?></h3>
                <p>Total Halaman Web</p>
            </div>
            <div style="font-size: 3rem; opacity: 0.2;">🏛️</div>
        </div>
    </div>
</div>

<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-4 border-bottom border-secondary border-opacity-25">
        <div>
            <h2 class="fw-bolder text-white mb-1" style="margin-top:0;">Daftar Profil & Halaman Statis</h2>
            <p class="text-secondary mb-0 small">Kelola informasi publik seperti Visi Misi, Sejarah, dan Struktur Organisasi.</p>
        </div>
        <button class="btn-gradient-purple shadow-lg" onclick="openModalHalaman('add')">
            <i class="bi bi-plus-circle me-1"></i> Buat Halaman Baru
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-glass table-hover mb-0">
            <thead>
                <tr>
                    <th scope="col" width="8%">ID</th>
                    <th scope="col" width="35%">Nama Halaman</th>
                    <th scope="col" width="22%">URL Slug</th>
                    <th scope="col" width="20%" class="text-center">Diperbarui</th>
                    <th scope="col" width="15%" class="text-end">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($daftar_halaman)): ?>
                    <?php foreach($daftar_halaman as $hal): ?>
                        <tr id="row-<?= $hal['id_halaman'] ?>">
                            <td class="fw-bold text-secondary">#<?= esc($hal['id_halaman']) ?></td>
                            <td>
                                <div class="fw-bold text-white fs-6"><?= esc($hal['nama_halaman']) ?></div>
                                <div class="small text-secondary">Editor: @<?= esc($hal['admin_editor'] ?? 'System') ?></div>
                            </td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-25 text-info fw-normal" style="font-family: monospace; font-size: 0.85rem;">
                                    /<?= esc($hal['slug_url']) ?>
                                </span>
                            </td>
                            <td class="text-center text-secondary small">
                                <div class="badge bg-dark bg-opacity-50 border border-secondary border-opacity-25 px-3 py-2">
                                    <?= date('d M Y - H:i', strtotime($hal['terakhir_diupdate'])) ?>
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="action-btn-group">
                                    <button class="action-btn btn-edit-action" 
                                        onclick="openModalHalaman('edit', this)"
                                        data-id="<?= $hal['id_halaman'] ?>"
                                        data-nama="<?= esc($hal['nama_halaman']) ?>"
                                        data-slug="<?= esc($hal['slug_url']) ?>"
                                        data-konten="<?= esc($hal['konten_halaman'] ?? '') ?>"
                                        data-bs-toggle="tooltip" title="Edit Konten">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="action-btn btn-del-action" 
                                        onclick="hapusHalaman(<?= $hal['id_halaman'] ?>, '<?= esc($hal['nama_halaman']) ?>')"
                                        data-bs-toggle="tooltip" title="Hapus Halaman">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-secondary">
                            <div style="font-size: 3rem; margin-bottom: 10px;">📄</div>
                            Belum ada Halaman Statis yang dibuat.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalHalaman" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg border-0">
        <div class="modal-content glass-modal border-0 p-2">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-3">
                    <div id="modalHalamanIcon" style="padding: 12px; border-radius: 12px; background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                        <i class="bi bi-file-earmark-text fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bolder text-white mb-0" id="modalHalamanTitle">Form Halaman</h5>
                        <small class="text-secondary" id="modalHalamanSubtitle">Atur konten statis website.</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-custom align-self-start mt-1" data-bs-dismiss="modal"></button>
            </div>
            <form id="formHalaman">
                <input type="hidden" name="id_halaman" id="halaman_id">
                <div class="modal-body px-4 py-4">
                    <div class="row">
                        <div class="col-md-7 mb-4">
                            <label class="text-secondary small fw-bold mb-2 uppercase">Nama Halaman</label>
                            <div class="input-group-glass">
                                <div class="icon-wrapper"><i class="bi bi-type"></i></div>
                                <input type="text" class="form-control" name="nama_halaman" id="halaman_nama" placeholder="Contoh: Visi & Misi" required autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-5 mb-4">
                            <label class="text-secondary small fw-bold mb-2 uppercase">URL Slug</label>
                            <div class="input-group-glass">
                                <div class="icon-wrapper"><i class="bi bi-link-45deg"></i></div>
                                <input type="text" class="form-control" name="slug_url" id="halaman_slug" placeholder="otomatis-generate" required autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="text-secondary small fw-bold mb-2 uppercase">Isi Konten (HTML / Teks)</label>
                            <div class="input-group-glass align-items-start">
                                <div class="icon-wrapper mt-2"><i class="bi bi-code-slash"></i></div>
                                <textarea class="form-control" name="konten_halaman" id="halaman_konten" rows="8" placeholder="Tuliskan isi halaman di sini..." required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-dark text-secondary fw-bold px-4" style="border-radius: 12px;" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-gradient-purple" id="btnSubmitHalaman">
                        <i class="bi bi-cloud-arrow-up fs-5"></i> <span id="btnSubmitText">Simpan Halaman</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, background: '#1e293b', color: '#fff' });
    
    // --- AUTO SLUG GENERATOR ---
    function slugify(text) {
        return text.toString().toLowerCase().trim()
            .replace(/\s+/g, '-')           // Ganti spasi dengan -
            .replace(/[^\w\-]+/g, '')       // Hapus simbol non-word
            .replace(/\-\-+/g, '-')         // Ganti multiple - dengan single -
            .replace(/^-+/, '')             // Hapus - di awal
            .replace(/-+$/, '');            // Hapus - di akhir
    }

    document.getElementById('halaman_nama').addEventListener('input', function() {
        if(document.getElementById('halaman_id').value === '') { // Hanya auto-slug saat "Tambah"
            document.getElementById('halaman_slug').value = slugify(this.value);
        }
    });

    // --- OPEN MODAL UNIFIED ---
    function openModalHalaman(mode, btn = null) {
        const form = document.getElementById('formHalaman'); form.reset();
        const title = document.getElementById('modalHalamanTitle');
        const submitText = document.getElementById('btnSubmitText');

        if(mode === 'add') {
            title.innerText = 'Buat Halaman Baru';
            submitText.innerText = 'Publikasikan';
            document.getElementById('halaman_id').value = '';
            form.setAttribute('data-url', "<?= base_url('admin/profil/add') ?>"); // Sesuaikan URL Controller anda
        } else {
            title.innerText = 'Edit Konten Halaman';
            submitText.innerText = 'Update Perubahan';
            document.getElementById('halaman_id').value = btn.dataset.id;
            document.getElementById('halaman_nama').value = btn.dataset.nama;
            document.getElementById('halaman_slug').value = btn.dataset.slug;
            document.getElementById('halaman_konten').value = btn.dataset.konten;
            form.setAttribute('data-url', "<?= base_url('admin/profil/edit') ?>"); // Sesuaikan URL Controller anda
        }
        new bootstrap.Modal(document.getElementById('modalHalaman')).show();
    }

    // --- AJAX SUBMIT ---
    document.getElementById('formHalaman').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSubmitHalaman');
        btn.disabled = true; btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Memproses...';

        const url = this.getAttribute('data-url');
        const formData = new FormData(this);

        try {
            // NOTE: Bagian ini harus disesuaikan dengan Controller CI4 Anda
            const response = await fetch(url, { method: "POST", body: formData });
            const result = await response.json();

            if(result.success) {
                bootstrap.Modal.getInstance(document.getElementById('modalHalaman')).hide();
                Toast.fire({ icon: 'success', title: 'Halaman berhasil diperbarui! ✨' });
                setTimeout(() => location.reload(), 1500);
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal', text: result.message, background: '#1e293b', color: '#fff' });
            }
        } catch (error) { Toast.fire({ icon: 'error', title: 'Kesalahan server/jaringan.' }); }
        finally { btn.disabled = false; btn.innerHTML = '<i class="bi bi-cloud-arrow-up fs-5"></i> Simpan Halaman'; }
    });

    // --- HAPUS HALAMAN ---
    function hapusHalaman(id, nama) {
        Swal.fire({
            title: 'Hapus Halaman?',
            html: `Yakin ingin menghapus <b>"${nama}"</b>?<br><small class="text-danger">Tindakan ini tidak dapat dibatalkan.</small>`,
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b', confirmButtonText: 'Ya, Hapus!', background: '#1e293b', color: '#fff'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await fetch(`<?= base_url('admin/profil/delete') ?>/${id}`, { method: "DELETE" });
                    const res = await response.json();
                    if(res.success) {
                        Toast.fire({ icon: 'success', title: 'Halaman dihapus.' });
                        document.getElementById(`row-${id}`).remove();
                    }
                } catch (e) { Toast.fire({ icon: 'error', title: 'Gagal menghapus.' }); }
            }
        });
    }

    // LOGOUT
    document.getElementById('btn-logout-admin').addEventListener('click', function(e) {
        e.preventDefault(); const url = this.getAttribute('href');
        Swal.fire({ title: 'Logout?', text: "Pastikan semua konten tersimpan.", icon: 'question', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b', confirmButtonText: 'Ya, Keluar', background: '#1e293b', color: '#fff' }).then((r) => { if (r.isConfirmed) window.location.href = url; });
    });
</script>

<?= $this->endSection() ?>