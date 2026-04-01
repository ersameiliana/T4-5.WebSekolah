<?= $this->extend('layout/dashboard_template') ?>

<?= $this->section('page_title') ?>
Manajemen Penilaian Akademik
<?= $this->endSection() ?>

<?= $this->section('sidebar_menu') ?>
    <a href="<?= base_url('dashboard/dosen') ?>" class="nav-item">📊 Dashboard Dosen</a>
    <a href="<?= base_url('dosen/nilai') ?>" class="nav-item active">📝 Input Nilai</a>
    <a href="#" class="nav-item">📚 Unggah Materi</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; color: #cbd5e1; font-weight: 600; }
    .form-control {
        width: 100%; padding: 12px 15px; background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 10px;
        color: #fff; font-size: 1rem; box-sizing: border-box;
    }
    .form-control:focus { border-color: #9F1239; outline: none; box-shadow: 0 0 15px rgba(159,18,57,0.4); }
    select.form-control option { background: #0b0f19; color: #fff; }
    
    .btn-submit {
        background: var(--primary-gradient); color: #fff; padding: 12px 30px;
        border: none; border-radius: 10px; cursor: pointer; font-weight: bold;
        box-shadow: var(--glow-ruby); width: 100%; font-size: 1.1rem;
    }
    
    .alert-success { background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; color: #10b981; padding: 15px; border-radius: 10px; margin-bottom: 20px; }
    .alert-error { background: rgba(159, 18, 57, 0.2); border: 1px solid #9F1239; color: #ff4d4d; padding: 15px; border-radius: 10px; margin-bottom: 20px; }
    
    .table-glass { width: 100%; border-collapse: collapse; color: #fff; margin-top: 20px; }
    .table-glass th, .table-glass td { padding: 12px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .table-glass th { background: rgba(159, 18, 57, 0.2); color: #cbd5e1; }
</style>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
    
    <div>
        <div class="glass-card">
            <h3 style="margin-top: 0; color: #fff; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px;">Form Input Nilai</h3>
            
            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert-error"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <form action="<?= base_url('dosen/nilai/store') ?>" method="post">
                <div class="form-group">
                    <label>Pilih Kelas & Mata Kuliah</label>
                    <select name="kelas_mk" class="form-control" required>
                        <option value="" disabled selected>-- Kelas yang Anda Ajar --</option>
                        <?php foreach($kelas_dosen as $kd): ?>
                            <option value="<?= $kd['id_kelas'].'|'.$kd['id_mk'] ?>">
                                Kelas <?= $kd['nama_kelas'] ?> - <?= $kd['nama_mk'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>NIM Mahasiswa</label>
                    <input type="number" name="nim" class="form-control" placeholder="Contoh: 20231001" required>
                </div>

                <div class="form-group">
                    <label>Nilai Angka (0 - 100)</label>
                    <input type="number" step="0.01" max="100" min="0" name="nilai_angka" class="form-control" placeholder="Masukkan nilai" required>
                    <small style="color: #aaa;">*Sistem akan otomatis menghitung Nilai Huruf (A/B/C/D/E)</small>
                </div>

                <button type="submit" class="btn-submit">Simpan Nilai</button>
            </form>
        </div>
    </div>

    <div>
        <div class="glass-card">
            <h3 style="margin-top: 0; color: #fff; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px;">Riwayat Input Nilai</h3>
            
            <div style="overflow-x: auto;">
                <table class="table-glass">
                    <thead>
                        <tr>
                            <th>Mahasiswa</th>
                            <th>Mata Kuliah</th>
                            <th>Nilai Angka</th>
                            <th>Predikat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($riwayat_nilai as $rn): ?>
                        <tr>
                            <td>
                                <strong><?= esc($rn['nama_mahasiswa']) ?></strong><br>
                                <small style="color: #aaa;"><?= $rn['nim'] ?></small>
                            </td>
                            <td><?= esc($rn['nama_mk']) ?></td>
                            <td><?= $rn['nilai_angka'] ?></td>
                            <td>
                                <?php 
                                    $color = '#10b981'; // Hijau untuk A/B
                                    if($rn['nilai_huruf'] == 'C') $color = '#f59e0b'; // Kuning
                                    if(in_array($rn['nilai_huruf'], ['D','E'])) $color = '#ff4d4d'; // Merah
                                ?>
                                <span style="background: <?= $color ?>; color: #fff; padding: 3px 10px; border-radius: 5px; font-weight: bold;">
                                    <?= $rn['nilai_huruf'] ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if(empty($riwayat_nilai)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center; color: #aaa;">Belum ada riwayat nilai yang diinput.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>