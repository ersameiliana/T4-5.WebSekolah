<?= $this->extend('layout/dashboard_template') ?>

<?= $this->section('page_title') ?>
Tulis Berita Baru
<?= $this->endSection() ?>

<?= $this->section('sidebar_menu') ?>
    <a href="<?= base_url('admin-secret-panel') ?>" class="nav-item">📊 Dashboard Admin</a>
    <a href="<?= base_url('admin/berita') ?>" class="nav-item active">📰 Kelola Berita</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; color: #cbd5e1; font-weight: 600; }
    .form-control {
        width: 100%; padding: 12px 15px; background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 10px;
        color: #fff; font-size: 1rem; box-sizing: border-box;
    }
    .form-control:focus { border-color: #2563EB; outline: none; box-shadow: 0 0 10px rgba(37,99,235,0.3); }
    
    /* Quill Editor Customization for Dark Mode */
    .ql-toolbar.ql-snow {
        background: rgba(255,255,255,0.9);
        border-radius: 10px 10px 0 0;
        border: none;
    }
    .ql-container.ql-snow {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 0 0 10px 10px;
        color: #fff;
        min-height: 250px;
        font-family: 'Inter', sans-serif;
        font-size: 1rem;
    }
    .btn-submit {
        background: var(--primary-gradient); color: #fff; padding: 12px 30px;
        border: none; border-radius: 10px; cursor: pointer; font-weight: bold;
        box-shadow: var(--glow-ruby); margin-top: 20px;
    }
</style>

<div class="glass-card">
    <form action="<?= base_url('admin/berita/store') ?>" method="post" id="form-berita">
        
        <div class="form-group">
            <label>Judul Berita</label>
            <input type="text" name="judul_berita" class="form-control" required placeholder="Contoh: Astryveil Gelar Konferensi AI Global">
        </div>

        <div class="form-group">
            <label>Sub Judul (Opsional)</label>
            <input type="text" name="sub_judul" class="form-control" placeholder="Ringkasan singkat berita...">
        </div>

        <div class="form-group">
            <label>Isi Berita</label>
            <div id="editor-container"></div>
            <input type="hidden" name="konten" id="konten">
        </div>

        <button type="submit" class="btn-submit">🚀 Terbitkan Berita</button>
        <a href="<?= base_url('admin/berita') ?>" style="color: #cbd5e1; margin-left: 15px; text-decoration: none;">Batal</a>
    </form>
</div>

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    // Konfigurasi Tools Editor
    var quill = new Quill('#editor-container', {
        theme: 'snow',
        placeholder: 'Tulis isi berita di sini...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link'],
                ['clean']
            ]
        }
    });

    // Sebelum form di-submit, pindahkan isi HTML dari Quill ke dalam input hidden 'konten'
    var form = document.getElementById('form-berita');
    form.onsubmit = function() {
        var kontenInput = document.getElementById('konten');
        kontenInput.value = quill.root.innerHTML;
        return true;
    };
</script>

<?= $this->endSection() ?>