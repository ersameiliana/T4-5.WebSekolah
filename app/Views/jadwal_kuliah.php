<?= $this->extend('layout/dashboard_template') ?>

<?= $this->section('page_title') ?>
Overview Akademik
<?= $this->endSection() ?>

<?= $this->section('sidebar_menu') ?>
    <a href="#" class="nav-item active">📊 Dashboard</a>
    <a href="#" class="nav-item">📅 Jadwal Kuliah</a>
    <a href="#" class="nav-item">📝 Kartu Hasil Studi (KHS)</a>
    <a href="#" class="nav-item">📚 Materi & Tugas</a>
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
        background: linear-gradient(to right, #c084fc, #818cf8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .stat-card p {
        color: #cbd5e1;
        margin: 5px 0 0 0;
        font-size: 0.9rem;
    }

    /* Styling Tambahan untuk KHS & Tabel */
    .table-container { width: 100%; overflow-x: auto; }
    .khs-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    .khs-table th { text-align: left; padding: 12px 15px; color: #94a3b8; border-bottom: 1px solid rgba(255,255,255,0.1); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; }
    .khs-table td { padding: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); color: #f8fafc; font-size: 0.95rem; }
    .khs-table tr:hover { background: rgba(255,255,255,0.02); }

    .badge { padding: 5px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 700; }
    .badge-success { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); }
    .badge-danger { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); }
    .badge-warning { background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3); }
    
    .nilai-huruf { font-weight: 900; font-size: 1.1rem; }
</style>

<div class="stats-grid">
    <div class="glass-card stat-card">
        <h3><?= esc($mahasiswa['total_sks'] ?? '0') ?></h3>
        <p>Total SKS Ditempuh</p>
    </div>
    <div class="glass-card stat-card">
        <h3><?= esc($mahasiswa['ipk'] ?? '0.00') ?></h3>
        <p>IPK Saat Ini</p>
    </div>
    <div class="glass-card stat-card">
        <?php $status_mhs = $mahasiswa['status_akademik'] ?? 'Aktif'; ?>
        <h3 style="<?= ($status_mhs != 'Aktif') ? 'background: #ef4444; -webkit-text-fill-color: #ef4444;' : '' ?>">
            <?= esc($status_mhs) ?>
        </h3>
        <p>Status Akademik</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 30px;">
    
    <div class="glass-card">
        <h3 style="margin-top:0; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px; font-size: 1.2rem;">📅 Jadwal Kuliah Hari Ini</h3>
        
        <?php if(!empty($jadwal_hari_ini)): ?>
            <?php foreach($jadwal_hari_ini as $jadwal): ?>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <div>
                        <div style="font-weight: bold; color: #3b82f6;"><?= esc($jadwal['jam_mulai']) ?> - <?= esc($jadwal['jam_selesai']) ?></div>
                        <div style="color: #cbd5e1; font-size: 0.9rem;"><?= esc($jadwal['ruangan']) ?></div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: bold;"><?= esc($jadwal['mata_kuliah']) ?> (<?= esc($jadwal['sks']) ?> SKS)</div>
                        <div style="color: #aaa; font-size: 0.85rem;">Dosen: <?= esc($jadwal['dosen']) ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="padding: 20px 0; text-align: center; color: #94a3b8;">
                <p>Tidak ada jadwal kuliah untuk hari ini. Waktunya istirahat! ☕</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="glass-card">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px;">
            <h3 style="margin: 0; font-size: 1.2rem;">📝 KHS Semester Ini</h3>
            <span class="badge badge-warning">Semester Genap 2025/2026</span>
        </div>

        <div class="table-container">
            <table class="khs-table">
                <thead>
                    <tr>
                        <th>Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Nilai</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($data_khs)): ?>
                        <?php foreach($data_khs as $mk): ?>
                            <?php 
                                // LOGIKA LULUS / TIDAK LULUS
                                // Menganggap D dan E adalah tidak lulus
                                $nilai = strtoupper($mk['nilai_huruf']);
                                $isLulus = !in_array($nilai, ['D', 'E', 'T']); // T = Tunda/Kosong
                            ?>
                            <tr>
                                <td>
                                    <strong><?= esc($mk['mata_kuliah']) ?></strong><br>
                                    <span style="font-size: 0.8rem; color: #94a3b8;"><?= esc($mk['kode_mk']) ?></span>
                                </td>
                                <td><?= esc($mk['sks']) ?></td>
                                <td><span class="nilai-huruf" style="color: <?= $isLulus ? '#10b981' : '#ef4444' ?>;"><?= esc($nilai) ?></span></td>
                                <td>
                                    <?php if($isLulus): ?>
                                        <span class="badge badge-success">✔ Lulus</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">✖ Mengulang</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 30px; color: #94a3b8;">Data KHS belum tersedia untuk semester ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
</div>

<?= $this->endSection() ?>