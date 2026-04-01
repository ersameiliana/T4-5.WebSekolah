<?= $this->extend('layout/dashboard_template') ?>

<?= $this->section('page_title') ?>
<?= isset($halaman) ? 'Edit' : 'Tambah' ?> Halaman Profil
<?= $this->endSection() ?>

<?= $this->section('sidebar_menu') ?>
    <a href="<?= base_url('admin-secret-panel') ?>" class="nav-item">📊 Dashboard</a>
    <a href="<?= base_url('admin/profil') ?>" class="nav-item active">🏛️ Profil Web</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<div class="glass-card">
    <h2><?= isset($halaman) ? '✏️ Edit' : '➕ Tambah' ?> Halaman Profil</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form action="<?= isset($halaman) ? base_url('admin/profil/update/' . $halaman['id_halaman']) : base_url('admin/profil/store') ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-group">
            <label>Nama Halaman <span class="text-danger">*</span></label>
            <input type="text" name="nama_halaman" class="form-control" required 
                   value="<?= old('nama_halaman', $halaman['nama_halaman'] ?? '') ?>"
                   placeholder="Contoh: Visi Misi Astryveil Academy">
        </div>

        <div class="form-group">
            <label>Slug URL <span class="text-danger">*</span></label>
            <input type="text" name="slug_url" class="form-control" required 
                   value="<?= old('slug_url', $halaman['slug_url'] ?? '') ?>"
                   placeholder="profil/visi-misi">
            <small>Slug untuk link publik (contoh: profil/visi-misi)</small>
        </div>

        <div class="form-group">
            <label>Konten Halaman <span class="text-danger">*</span></label>
            <div id="editor-container" style="height: 400px;"><?= old('konten_halaman', $halaman['konten_halaman'] ?? '<p>Konten halaman...</p>') ?></div>
            <input type="hidden" name="konten_halaman" id="konten">
        </div>

        <div class="form-group d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">💾 Simpan Halaman</button>
            <a href="<?= base_url('admin/profil') ?>" class="btn btn-secondary px-4">Batal</a>
        </div>
    </form>
</div>

<script>
var quill = new Quill('#editor-container', {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'align': [] }],
            ['blockquote', 'code-block'],
            [{ 'color': [] }, { 'background': [] }],
            [{ 'font': [] }],
            [{ 'size': ['small', false, 'large', 'huge'] }],
            ['link', 'image', 'video'],
            ['clean']
        ]
    }
});

// Save to hidden input on submit
document.querySelector('form').onsubmit = function() {
    document.getElementById('konten').value = quill.root.innerHTML;
};
</script>

