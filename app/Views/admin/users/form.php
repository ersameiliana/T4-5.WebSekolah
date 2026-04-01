<?= $this->extend('layout/dashboard_template') ?>

<?= $this->section('page_title') ?>Edit <?= ucfirst($type) ?> | Admin Panel<?= $this->endSection() ?>

<?= $this->section('sidebar_menu') ?>
    <a href="<?= base_url('admin-secret-panel') ?>" class="nav-item">📊 Dashboard</a>
    <a href="<?= base_url('admin/pengguna') ?>" class="nav-item active">👥 Kelola Pengguna</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="glass-card">
    <h2><?= $is_edit ? 'Edit' : 'Tambah' ?> <?= ucfirst($type) ?></h2>
    
    <form action="<?= $form_action ?>" method="post">
        <input type="hidden" name="id" value="<?= $data['id'] ?? '' ?>">
        <input type="hidden" name="type" value="<?= $type ?>">
        
        <?php if ($type == 'admin'): ?>
            <div class="form-group">
                <label>User ID <span class="required">*</span></label>
                <input type="text" name="user_id" value="<?= old('user_id', $data['user_id'] ?? '') ?>" required class="form-control">
            </div>
            <div class="form-group">
                <label>Nama Admin</label>
                <input type="text" name="nama" value="<?= old('nama', $data['nama'] ?? '') ?>" class="form-control">
            </div>
            <div class="form-group">
                <label>Password <span class="required">*</span></label>
                <input type="password" name="password" value="<?= old('password') ?>" placeholder="Min 6 char alfanum" required class="form-control">
            </div>
            <div class="form-group">
                <label>Jenis Admin <span class="required">*</span></label>
                <select name="jenis_admin" class="form-control" required>
                    <option value="">Pilih Jenis</option>
                    <option value="Editing" <?= old('jenis_admin', $data['jenis_admin'] ?? '') == 'Editing' ? 'selected' : '' ?>>Editing (Profil/Berita)</option>
                    <option value="Administrasi" <?= old('jenis_admin', $data['jenis_admin'] ?? '') == 'Administrasi' ? 'selected' : '' ?>>Administrasi (Users)</option>
                    <option value="Sistem/Database" <?= old('jenis_admin', $data['jenis_admin'] ?? '') == 'Sistem/Database' ? 'selected' : '' ?>>Database (Full)</option>
                </select>
            </div>
        <?php elseif ($type == 'mahasiswa'): ?>
            <div class="form-group">
                <label>NIM <span class="required">*</span></label>
                <input type="number" name="nim" value="<?= old('nim', $data['nim'] ?? '') ?>" required class="form-control">
            </div>
            <div class="form-group">
                <label>Nama Mahasiswa <span class="required">*</span></label>
                <input type="text" name="nama_mahasiswa" value="<?= old('nama_mahasiswa', $data['nama_mahasiswa'] ?? '') ?>" required class="form-control">
            </div>
            <div class="form-group">
                <label>Fakultas</label>
                <select name="fakultas" class="form-control">
                    <option value="Fakultas Teknologi dan Informatika" <?= old('fakultas', $data['fakultas'] ?? '') == 'Fakultas Teknologi dan Informatika' ? 'selected' : '' ?>>Teknologi & Informatika</option>
                    <option value="Fakultas Sains dan Matematika" <?= old('fakultas', $data['fakultas'] ?? '') == 'Fakultas Sains dan Matematika' ? 'selected' : '' ?>>Sains & Matematika</option>
                    <option value="Fakultas Bisnis dan Manajemen" <?= old('fakultas', $data['fakultas'] ?? '') == 'Fakultas Bisnis dan Manajemen' ? 'selected' : '' ?>>Bisnis & Manajemen</option>
                    <option value="Fakultas Desain dan Media Kreatif" <?= old('fakultas', $data['fakultas'] ?? '') == 'Fakultas Desain dan Media Kreatif' ? 'selected' : '' ?>>Desain & Kreatif</option>
                </select>
            </div>
            <div class="form-group">
                <label>Prodi</label>
                <input type="text" name="prodi" value="<?= old('prodi', $data['prodi'] ?? '') ?>" class="form-control">
            </div>
            <div class="form-group">
                <label>Password <span class="required">*</span></label>
                <input type="password" name="password" value="<?= old('password') ?>" placeholder="Min 6 char" required class="form-control">
            </div>
        <?php elseif ($type == 'dosen'): ?>
            <!-- Similar fields for dosen: nidn, nama_dosen, fakultas, prodi, password etc -->
            <div class="form-group">
                <label>NIDN <span class="required">*</span></label>
                <input type="number" name="nidn" value="<?= old('nidn', $data['nidn'] ?? '') ?>" required class="form-control">
            </div>
            <div class="form-group">
                <label>Nama Dosen <span class="required">*</span></label>
                <input type="text" name="nama_dosen" value="<?= old('nama_dosen', $data['nama_dosen'] ?? '') ?>" required class="form-control">
            </div>
            <div class="form-group">
                <label>Password <span class="required">*</span></label>
                <input type="password" name="password" value="<?= old('password') ?>" placeholder="Min 6 char" required class="form-control">
            </div>
        <?php elseif ($type == 'guest'): ?>
            <!-- Guest fields -->
            <div class="form-group">
                <label>Nama Lengkap <span class="required">*</span></label>
                <input type="text" name="nama_lengkap" value="<?= old('nama_lengkap', $data['nama_lengkap'] ?? '') ?>" required class="form-control">
            </div>
            <div class="form-group">
                <label>Username <span class="required">*</span></label>
                <input type="text" name="username" value="<?= old('username', $data['username'] ?? '') ?>" required class="form-control">
            </div>
            <div class="form-group">
                <label>Password <span class="required">*</span></label>
                <input type="password" name="password" value="<?= old('password') ?>" placeholder="Min 6 char alfanum" required class="form-control">
            </div>
            <div class="form-group">
                <label>Jenis Akun</label>
                <select name="jenis_akun" class="form-control">
                    <option value="Tamu umum" <?= old('jenis_akun', $data['jenis_akun'] ?? '') == 'Tamu umum' ? 'selected' : '' ?>>Tamu Umum</option>
                    <option value="Orang tua/Wali Mahasiswa" <?= old('jenis_akun', $data['jenis_akun'] ?? '') == 'Orang tua/Wali Mahasiswa' ? 'selected' : '' ?>>Wali Mahasiswa</option>
                </select>
            </div>
            <div class="form-group">
                <label>NIM Mahasiswa (opsional)</label>
                <input type="number" name="nim_mahasiswa" value="<?= old('nim_mahasiswa', $data['nim_mahasiswa'] ?? '') ?>" class="form-control">
            </div>
        <?php endif; ?>

        <div style="display: flex; gap: 10px; margin-top: 25px;">
            <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
            <a href="<?= base_url('admin/pengguna?type=' . $type) ?>" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?> 
