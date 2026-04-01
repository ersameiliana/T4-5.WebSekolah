<?= $this->extend('layout/dashboard_template') ?>

<?= $this->section('page_title') ?>
Dashboard Akademik Dosen
<?= $this->endSection() ?>

<?= $this->section('sidebar_menu') ?>
    <a href="<?= base_url('dashboard/dosen') ?>" class="nav-item active">📊 Dashboard Dosen</a>
    <a href="<?= base_url('dosen/nilai') ?>" class="nav-item">📝 Input Nilai</a>
    <a href="#" class="nav-item">📚 Unggah Materi</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card h3 {
        margin: 0;
        font-size: 2rem;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .stat-card p {
        color: #cbd5e1;
        margin: 5px 0 0 0;
        font-size: 0.9rem;
    }
</style>

<div class="stats-grid">
    <div class="glass-card stat-card" style="border-top: 3px solid #2563EB;">
        <h3>4</h3>
        <p>Kelas Diampu</p>
    </div>
    <div class="glass-card stat-card" style="border-top: 3px solid #9F1239;">
        <h3>120</h3>
        <p>Total Mahasiswa</p>
    </div>
    <div class="glass-card stat-card" style="border-top: 3px solid #10b981;">
        <h3>Aktif</h3>
        <p>Status Mengajar</p>
    </div>
</div>

<div class="glass-card">
    <h3 style="margin-top:0; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px;">
        Selamat Datang, <?= session()->get('user_name') ?>
    </h3>
    <p style="color: #cbd5e1; line-height: 1.6;">
        Ini adalah pusat kendali akademik Anda. Dari sini, Anda dapat memantau kelas yang Anda ajar, mengunggah materi perkuliahan, serta memberikan penilaian kepada mahasiswa.
    </p>
    
    <div style="margin-top: 25px;">
        <h4 style="color: #fff; margin-bottom: 15px;">Jalan Pintas (Aksi Cepat):</h4>
        
        <a href="<?= base_url('dosen/nilai') ?>" style="display: inline-block; background: #2563EB; color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; box-shadow: 0 0 10px rgba(37, 99, 235, 0.4); margin-right: 10px; transition: 0.3s;">
            📝 Mulai Input Nilai
        </a>
        
        <a href="#" style="display: inline-block; background: rgba(255,255,255,0.05); color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; border: 1px solid rgba(255,255,255,0.2); transition: 0.3s;">
            📚 Kelola Materi Kuliah
        </a>
    </div>
</div>

<?= $this->endSection() ?>