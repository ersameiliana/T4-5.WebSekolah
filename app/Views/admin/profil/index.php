<?= $this->extend('layout/dashboard_template') ?>

<?= $this->section('page_title') ?>Kelola Profil Web<?= $this->endSection() ?>

<?= $this->section('sidebar_menu') ?>
    <a href="<?= base_url('admin-secret-panel') ?>" class="nav-item">📊 Dashboard</a>
    <a href="<?= base_url('admin/profil') ?>" class="nav-item active">🏛️ Profil Web</a>
    <a href="<?= base_url('admin/berita') ?>" class="nav-item">📰 Berita</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
.btn-add { background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 4px 14px rgba(16,185,129,0.4); }
.btn-edit { background: linear-gradient(135deg, #3b82f6, #2563eb); box-shadow: 0 4px 14px rgba(59,130,246,0.4); }
.btn-delete { background: linear-gradient(135deg, #ef4444, #dc2626); box-shadow: 0 4px 14px rgba(239,68,68,0.4); }
.table-container { overflow-x: auto; }
</style>

<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2>📄 Halaman Profil (<?= count($halaman ?? []) ?>)</h2>
        <a href="<?= base_url('admin/profil/create') ?>" class="btn-action btn-add">+ Tambah Halaman</a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="table-container">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nama Halaman</th>
                    <th>Slug</th>
                    <th>Editor Terakhir</th>
                    <th>Terupdate</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($halaman)): ?>
                    <tr><td colspan="5" class="text-center py-5" style="color:#94a3b8;">Belum ada halaman profil. <a href="<?= base_url('admin/profil/create') ?>">Buat yang pertama!</a></td></tr>
                <?php else: ?>
                    <?php foreach ($halaman as $h): ?>
                    <tr>
                        <td><?= esc($h['nama_halaman']) ?></td>
                        <td><code><?= esc($h['slug_url']) ?></code></td>
                        <td><?= esc($h['admin_editor']) ?></td>
                        <td><?= date('d M Y H:i', strtotime($h['terakhir_diupdate'])) ?></td>
                        <td>
                            <a href="<?= base_url('admin/profil/edit/' . $h['id_halaman']) ?>" class="btn-action btn-edit btn-sm">Edit</a>
                            <a href="<?= base_url('admin/profil/delete/' . $h['id_halaman']) ?>" class="btn-action btn-delete btn-sm" onclick="return confirm('Hapus halaman ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
