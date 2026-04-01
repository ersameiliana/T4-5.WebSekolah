<?= $this->extend('layout/dashboard_template') ?>

<?= $this->section('page_title') ?>
Portal Informasi Wali & Tamu
<?= $this->endSection() ?>

<?= $this->section('sidebar_menu') ?>
    <a href="<?= base_url('dashboard/guest') ?>" class="nav-item active">📊 Dashboard Guest</a>
    <a href="#" class="nav-item">📢 Pengumuman Akademik</a>
    <a href="#" class="nav-item">📈 Pantau Nilai Mahasiswa</a>
    <a href="#" class="nav-item">🏛️ Tur Kampus Virtual</a>
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
        font-size: 1.5rem;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 5px;
    }

    .stat-card p {
        color: #cbd5e1;
        margin: 0;
        font-size: 0.9rem;
    }
</style>

<div class="stats-grid">
    <div class="glass-card stat-card" style="border-top: 3px solid #10b981;">
        <h3>Terverifikasi</h3>
        <p>Status Akun</p>
    </div>
    <div class="glass-card stat-card" style="border-top: 3px solid #2563EB;">
        <h3>Akses Terbatas</h3>
        <p>Hak Akses Portal</p>
    </div>
    <div class="glass-card stat-card" style="border-top: 3px solid #f59e0b;">
        <h3>Informasi Umum</h3>
        <p>Kategori Layanan</p>
    </div>
</div>

<div class="glass-card">
    <h3 style="margin-top:0; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px; color: #fff;">
        Selamat Datang, <?= session()->get('user_name') ?>
    </h3>
    <p style="color: #cbd5e1; line-height: 1.6;">
        Terima kasih telah bergabung di portal Astryveil Academy. Melalui dasbor ini, Anda dapat mengakses informasi publik terbaru, melihat jadwal kegiatan kampus, dan mengunduh brosur akademik kami.
    </p>

    <div style="background: rgba(37, 99, 235, 0.1); border-left: 4px solid #2563EB; padding: 20px; margin-top: 25px; border-radius: 0 10px 10px 0;">
        <h4 style="margin: 0 0 10px 0; color: #fff;">Apakah Anda Orang Tua / Wali Mahasiswa?</h4>
        <p style="color: #cbd5e1; margin: 0; font-size: 0.95rem; line-height: 1.5;">
            Jika akun Anda telah ditautkan dengan NIM Mahasiswa oleh pihak administrasi, Anda dapat memantau perkembangan akademik (KHS) dan riwayat pembayaran melalui menu <strong>Pantau Nilai Mahasiswa</strong> di sebelah kiri.
            <br><br>
            <em>Catatan: Sesuai aturan sistem, akun Tamu Umum yang tidak aktif selama 6 bulan, atau akun Wali yang mahasiswanya telah lulus/DO akan dinonaktifkan secara otomatis.</em>
        </p>
    </div>

    <div style="margin-top: 30px; display: flex; gap: 15px; flex-wrap: wrap;">
        <a href="<?= base_url('berita') ?>" style="display: inline-block; background: #2563EB; color: #fff; text-decoration: none; padding: 12px 25px; border-radius: 8px; font-weight: bold; box-shadow: 0 0 10px rgba(37, 99, 235, 0.4); transition: 0.3s;">
            📰 Baca Berita Terbaru
        </a>
        <a href="<?= base_url('profil') ?>" style="display: inline-block; background: rgba(255,255,255,0.05); color: #fff; text-decoration: none; padding: 12px 25px; border-radius: 8px; font-weight: bold; border: 1px solid rgba(255,255,255,0.2); transition: 0.3s;">
            🦅 Pelajari Profil Astryveil
        </a>
    </div>
</div>

<?= $this->endSection() ?>