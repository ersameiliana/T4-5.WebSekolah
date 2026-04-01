<?= $this->extend('layout/dashboard_template') ?>

<?= $this->section('page_title') ?>Kelola Pengguna<?= $this->endSection() ?>

<?= $this->section('sidebar_menu') ?>
    <a href="<?= base_url('admin-secret-panel') ?>" class="nav-item">📊 Dashboard</a>
    <a href="<?= base_url('admin/users') ?>" class="nav-item active">👥 Pengguna</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
.nav-tabs .nav-link { border: none; color: #94a3b8; font-weight: 600; }
.nav-tabs .nav-link.active { color: #3b82f6; background: rgba(59,130,246,0.1); border-bottom: 2px solid #3b82f6; }
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-bottom: 25px; }
.stat-item { text-align: center; padding: 20px; background: rgba(255,255,255,0.03); border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); }
</style>

<div class="glass-card">
    <h2>👥 Kelola Pengguna</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-item">
            <div style="font-size: 1.8rem; font-weight: 800; color: #3b82f6;"><?= $mahasiswa_count ?></div>
            <div>Mahasiswa</div>
        </div>
        <div class="stat-item">
            <div style="font-size: 1.8rem; font-weight: 800; color: #10b981;"><?= $dosen_count ?></div>
            <div>Dosen</div>
        </div>
        <div class="stat-item">
            <div style="font-size: 1.8rem; font-weight: 800; color: #f59e0b;"><?= $guest_count ?></div>
            <div>Guest</div>
        </div>
        <div class="stat-item">
            <div style="font-size: 1.8rem; font-weight: 800; color: #ef4444;"><?= $admin_count ?></div>
            <div>Admin</div>
        </div>
    </div>

    <?php if ($role !== 'Sistem/Database'): ?>
        <div class="alert alert-warning">
            ⚠️ <strong><?= strtoupper($role) ?></strong>: Anda hanya bisa <strong>LIHAT & EDIT</strong>. Hapus data harus persetujuan Superadmin.
        </div>
    <?php endif; ?>

    <ul class="nav nav-tabs" id="userTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="mahasiswa-tab" data-bs-toggle="tab" href="#mahasiswa" role="tab">👨‍🎓 Mahasiswa</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="dosen-tab" data-bs-toggle="tab" href="#dosen" role="tab">👨‍🏫 Dosen</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="guest-tab" data-bs-toggle="tab" href="#guest" role="tab">👪 Guest</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="admin-tab" data-bs-toggle="tab" href="#admin" role="tab">🔐 Admin</a>
        </li>
    </ul>

    <div class="tab-content" id="userTabsContent">
        <!-- Tab placeholders - data loaded via JS or links to detailed views -->
        <div class="tab-pane fade show active" id="mahasiswa" role="tabpanel">
            <p class="text-muted mt-3">Klik <strong>Mahasiswa #NIM</strong> untuk edit detail. <a href="<?= base_url('admin/users/edit/1/mahasiswa') ?>" class="btn btn-sm btn-outline-primary">Contoh Edit Mahasiswa</a></p>
        </div>
        <div class="tab-pane fade" id="dosen" role="tabpanel">
            <p class="text-muted mt-3">Klik <strong>Dosen #NIDN</strong> untuk edit. <a href="<?= base_url('admin/users/edit/111000001/dosen') ?>" class="btn btn-sm btn-outline-primary">Contoh Edit Dosen</a></p>
        </div>
        <div class="tab-pane fade" id="guest" role="tabpanel">
            <p class="text-muted mt-3">Klik <strong>Guest #ID</strong> untuk edit. <a href="<?= base_url('admin/users/edit/1/guest') ?>" class="btn btn-sm btn-outline-primary">Contoh Edit Guest</a></p>
        </div>
        <div class="tab-pane fade" id="admin" role="tabpanel">
            <p class="text-muted mt-3">Admin terkelola via <a href="<?= base_url('admin/otorisasi') ?>">Panel Otorisasi</a></p>
        </div>
    </div>
</div>

<script>
// Placeholder - full list would need AJAX for performance
console.log('User stats loaded: MHS=<?= $mahasiswa_count ?>, DOSEN=<?= $dosen_count ?>');
</script>

<?= $this->endSection() ?> 
