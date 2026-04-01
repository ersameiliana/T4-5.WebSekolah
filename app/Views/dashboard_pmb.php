<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<style>
    body { background-color: #0b0f19; color: #f8fafc; font-family: 'Inter', sans-serif; }
    .dashboard-container { max-width: 1100px; margin: 120px auto 80px; padding: 0 20px; }
    
    /* HEADER & PROGRESS BAR GLOBAL */
    .dash-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 25px; }
    .welcome-text { font-size: 2rem; font-weight: 800; color: #fff; margin: 0 0 5px; }
    .prodi-text { color: #8b5cf6; font-weight: 600; font-size: 1.1rem; }
    
    .progress-container { background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 20px; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
    .progress-info { display: flex; justify-content: space-between; font-weight: 700; margin-bottom: 10px; font-size: 0.95rem; }
    .progress-track { width: 100%; height: 10px; background: rgba(255,255,255,0.1); border-radius: 10px; overflow: hidden; }
    .progress-fill { height: 100%; background: linear-gradient(90deg, #3b82f6, #8b5cf6); border-radius: 10px; transition: width 1s cubic-bezier(0.4, 0, 0.2, 1); }

    /* LAYOUT GRID BARU (Sidebar lebih lebar & fungsional) */
    .dash-grid { display: grid; grid-template-columns: 1fr 380px; gap: 30px; }
    @media(max-width: 850px) { .dash-grid { grid-template-columns: 1fr; } }
    
    .card { background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255,255,255,0.08); border-radius: 20px; padding: 30px; margin-bottom: 20px; }
    .card-title { font-size: 1.2rem; font-weight: 800; color: #fff; margin: 0 0 20px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 15px;}

    /* TIMELINE STYLING */
    .timeline { position: relative; padding-left: 30px; margin-top: 10px; }
    .timeline::before { content: ''; position: absolute; top: 0; left: 11px; height: 100%; width: 2px; background: rgba(255,255,255,0.1); }
    .step { position: relative; margin-bottom: 35px; }
    .step-indicator { position: absolute; left: -41px; top: 0; width: 24px; height: 24px; border-radius: 50%; background: #0f172a; border: 2px solid rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: bold; color: #94a3b8; z-index: 2; transition: 0.3s; }
    
    .step.completed .step-indicator { background: #10b981; border-color: #10b981; color: #fff; box-shadow: 0 0 10px rgba(16, 185, 129, 0.4); }
    .step.completed::after { content: ''; position: absolute; top: 24px; left: -30px; height: calc(100% + 11px); width: 2px; background: #10b981; z-index: 1; }
    
    .step.active .step-indicator { background: #0b0f19; border-color: #8b5cf6; box-shadow: 0 0 0 4px rgba(139,92,246,0.2); color: #8b5cf6; }
    .step.active .step-content { background: rgba(139,92,246,0.05); border: 1px solid rgba(139,92,246,0.2); border-radius: 12px; padding: 20px; }
    
    /* REJECTED / ERROR STATE */
    .step.error .step-indicator { background: #ef4444; border-color: #ef4444; color: #fff; box-shadow: 0 0 10px rgba(239, 68, 68, 0.4); }
    .step.error .step-content { background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 12px; padding: 20px; }
    .step.error .step-title-text { color: #ef4444 !important; }

    .step-content { padding-left: 10px; }
    .step-title-text { font-weight: 700; color: #e2e8f0; font-size: 1.05rem; margin-bottom: 5px; }
    .step.completed .step-title-text { color: #94a3b8; text-decoration: line-through; }
    .step.active .step-title-text { color: #8b5cf6; font-size: 1.15rem; }
    .step-desc { color: #94a3b8; font-size: 0.9rem; margin-bottom: 15px; line-height: 1.5; }
    
    /* URGENCY & DEADLINE */
    .deadline-badge { display: inline-flex; align-items: center; gap: 5px; background: rgba(245, 158, 11, 0.1); color: #f59e0b; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; margin-bottom: 10px; border: 1px solid rgba(245, 158, 11, 0.2); }
    
    /* CTAs YANG MENGGIGIT */
    .btn-action { display: inline-block; background: linear-gradient(135deg, #8b5cf6, #3b82f6); color: #fff; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 0.9rem; transition: 0.3s; box-shadow: 0 4px 15px rgba(139,92,246,0.3); }
    .btn-action:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(139,92,246,0.5); }
    .btn-locked { display: inline-block; background: rgba(255,255,255,0.05); color: #64748b; padding: 10px 20px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05); font-weight: 600; font-size: 0.9rem; }
    .btn-success { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); }

    /* SIDEBAR INFO ITEMS */
    .info-item { display: flex; align-items: flex-start; gap: 15px; margin-bottom: 20px; }
    .info-icon { background: rgba(255,255,255,0.05); width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; border: 1px solid rgba(255,255,255,0.1); }
    .info-text h4 { margin: 0 0 4px 0; color: #e2e8f0; font-size: 0.95rem; }
    .info-text p { margin: 0; color: #94a3b8; font-size: 0.85rem; line-height: 1.4; }
</style>

<?php 
    // 🔥 LOGIC LEVEL & PROGRESS GLOBAL 🔥
    $levels = [
        'registered'        => 1,
        'biodata_complete'  => 2,
        'document_uploaded' => 3,
        'waiting_payment'   => 4,
        'verified'          => 5,
        'test_done'         => 6, 
        'accepted'          => 7,
        'rejected'          => 7 
    ];
    $status = $user['status_pmb'];
    $level  = $levels[$status] ?? 1;
    
    // Hitung persentase progress
    $progress = floor(($level / 7) * 100);
    if ($progress > 100) $progress = 100;
?>

<div class="dashboard-container">
    <div class="dash-header">
        <div>
            <h1 class="welcome-text">Selamat Datang, <?= esc($user['nama_lengkap']) ?> 👋</h1>
            <div class="prodi-text">Jalur <?= esc($user['jalur_pendaftaran']) ?> - <?= esc($user['prodi_pilihan']) ?></div>
        </div>
    </div>

    <div class="progress-container">
        <div class="progress-info">
            <span style="color: <?= $status == 'rejected' ? '#ef4444' : '#8b5cf6' ?>;">
                <?= $status == 'rejected' ? 'Pendaftaran Ditolak' : 'Progress Pendaftaran Anda' ?>
            </span>
            <span><?= $progress ?>% Selesai</span>
        </div>
        <div class="progress-track">
            <div class="progress-fill" style="width: <?= $progress ?>%; <?= $status == 'rejected' ? 'background: #ef4444;' : '' ?>"></div>
        </div>
    </div>

    <div class="dash-grid">
        <div class="card">
            <h2 class="card-title"><span>📋</span> Roadmap Pendaftaran</h2>
            <div class="timeline">
                
                <div class="step completed">
                    <div class="step-indicator">✔</div>
                    <div class="step-content">
                        <div class="step-title-text">Registrasi Akun</div>
                    </div>
                </div>

                <div class="step <?= ($level == 1) ? 'active' : 'completed' ?>">
                    <div class="step-indicator"><?= ($level > 1) ? '✔' : '2' ?></div>
                    <div class="step-content">
                        <div class="step-title-text">Kelengkapan Biodata</div>
                        <div class="step-desc">Data ini digunakan untuk pelaporan PDDikti. Harap isi sesuai KK/KTP asli.</div>
                        <?php if($level == 1): ?>
                            <a href="<?= base_url('pmb/biodata') ?>" class="btn-action">Lengkapi Biodata Sekarang 🚀</a>
                        <?php else: ?>
                            <span class="btn-locked btn-success">✔ Biodata Tersimpan</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="step <?= ($level == 2) ? 'active' : ($level > 2 ? 'completed' : '') ?>">
                    <div class="step-indicator"><?= ($level > 2) ? '✔' : '3' ?></div>
                    <div class="step-content">
                        <div class="step-title-text">Unggah Dokumen Wajib</div>
                        <div class="step-desc">Sistem mendeteksi Anda belum mengunggah berkas. Segera unggah untuk masuk antrean verifikasi.</div>
                        <?php if($level == 2): ?>
                            <div class="deadline-badge">⏳ Batas Upload: 3x24 Jam</div><br>
                            <a href="<?= base_url('pmb/upload') ?>" class="btn-action">Upload Berkas Sekarang 📁</a>
                        <?php elseif($level > 2): ?>
                            <span class="btn-locked btn-success">✔ Berkas Terverifikasi Sistem</span>
                        <?php else: ?>
                            <span class="btn-locked">🔒 Selesaikan Biodata Dulu</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="step <?= ($level == 3) ? 'active' : ($level > 3 ? 'completed' : '') ?>">
                    <div class="step-indicator"><?= ($level > 3) ? '✔' : '4' ?></div>
                    <div class="step-content">
                        <div class="step-title-text">Pembayaran Formulir</div>
                        <?php if($level == 3): ?>
                            <div class="step-desc">Bayar biaya pendaftaran (Rp 250.000) agar Anda bisa mengikuti seleksi.</div>
                            <div class="deadline-badge" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border-color: rgba(239, 68, 68, 0.2);">⏰ Tagihan Expired Besok Pukul 23:59</div><br>
                            <a href="<?= base_url('pmb/bayar') ?>" class="btn-action" style="background: #10b981;">Bayar Tagihan Sekarang 💳</a>
                        <?php elseif($level > 3): ?>
                            <span class="btn-locked btn-success">✔ Lunas (Invoice: INV-<?= date('Ymd') ?>-<?= $user['id'] ?>)</span>
                        <?php else: ?>
                            <div class="step-desc">Selesaikan unggah berkas untuk mendapatkan Nomor Virtual Account (VA).</div>
                            <span class="btn-locked">🔒 Terkunci</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="step <?= ($level == 5) ? 'active' : (($level >= 6) ? 'completed' : '') ?>">
                    <div class="step-indicator"><?= ($level >= 6) ? '✔' : '5' ?></div>
                    <div class="step-content">
                        <?php if($level >= 6): ?>
                            <div class="step-title-text">Tahap Seleksi Selesai</div>
                            <span class="btn-locked btn-success">✔ Hasil telah direkam sistem</span>
                        <?php elseif ($user['jalur_pendaftaran'] == 'Reguler'): ?>
                            <div class="step-title-text">Ujian Seleksi CBT</div>
                            <?php if($level == 5): ?>
                                <div class="step-desc">Waktu 90 Menit. Jika terputus, timer akan tetap berjalan. Siapkan diri Anda.</div>
                                <div class="deadline-badge">🔥 Jadwal CBT Anda Aktif Hari Ini!</div><br>
                                <a href="<?= base_url('pmb/cbt') ?>" class="btn-action" style="background: linear-gradient(135deg, #ef4444, #b91c1c);">Mulai Ujian TPA Sekarang 📝</a>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="step-title-text">Proses Evaluasi Admisi</div>
                            <?php if($level == 5): ?>
                                <div class="step-desc">Berkas jalur <?= esc($user['jalur_pendaftaran']) ?> Anda sedang divalidasi manual oleh tim kami. Estimasi selesai: 2 Hari Kerja.</div>
                                <span class="btn-locked" style="color: #60a5fa;">⏳ Sedang Review Dokumen</span>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <?php if($level < 5): ?>
                            <span class="btn-locked">🔒 Selesaikan pembayaran terlebih dahulu</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="step <?= ($status == 'accepted' || $status == 'rejected') ? 'active ' . ($status == 'rejected' ? 'error' : '') : '' ?>">
                    <div class="step-indicator"><?= ($status == 'accepted') ? '🎓' : (($status == 'rejected') ? '❌' : '6') ?></div>
                    <div class="step-content">
                        <div class="step-title-text">Pengumuman Kelulusan</div>
                        
                        <?php if($status == 'accepted'): ?>
                            <div class="step-desc" style="color: #10b981; font-weight: bold; font-size: 1.1rem; margin-bottom: 10px;">
                                🎉 SELAMAT! ANDA DINYATAKAN LULUS!
                            </div>
                            <div class="step-desc">
                                Selamat bergabung menjadi bagian dari Astryveil Academy. Silakan unduh Letter of Acceptance (LoA) Anda.
                            </div>
                            <a href="#" class="btn-action" style="background: #10b981; margin-top: 10px;">Unduh LoA Resmi (PDF) 📥</a>
                            
                            <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
                            <script>
                                setTimeout(() => {
                                    confetti({ particleCount: 150, spread: 70, origin: { y: 0.6 }, colors: ['#8b5cf6', '#3b82f6', '#10b981'] });
                                }, 500);
                            </script>

                        <?php elseif($status == 'rejected'): ?>
                            <div class="step-desc" style="color: #ef4444; font-weight: bold;">
                                Mohon Maaf, Anda Dinyatakan Tidak Lulus pada seleksi kali ini.
                            </div>
                            <div class="step-desc">
                                Jangan patah semangat! Nilai Anda belum memenuhi *passing grade* Prodi <?= esc($user['prodi_pilihan']) ?>. Anda masih bisa mendaftar di Gelombang 2 atau memilih jalur lain.
                            </div>
                            <a href="#" class="btn-action" style="background: #ef4444; margin-top: 10px;">Daftar Ulang Gelombang 2 🔄</a>

                        <?php elseif($level == 6): ?>
                            <div class="step-desc">
                                Pengumuman resmi akan dikirimkan ke Email <strong><?= esc($user['email']) ?></strong>. Harap cek kotak masuk secara berkala.
                            </div>
                            <span class="btn-locked" style="color: #f59e0b; background: rgba(245, 158, 11, 0.1); border-color: rgba(245, 158, 11, 0.3);">⏳ Menunggu Sidang Kelulusan Admin</span>
                        <?php else: ?>
                            <span class="btn-locked">🔒 Terkunci</span>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>

        <div>
            <div class="card" style="padding: 25px;">
                <h2 class="card-title" style="font-size: 1.1rem; border-bottom: none; margin-bottom: 10px;"><span>🔔</span> Status Pendaftaran</h2>
                
                <div class="info-item">
                    <div class="info-icon">👤</div>
                    <div class="info-text">
                        <h4>Data Personal</h4>
                        <p><?= esc($user['email']) ?><br><?= esc($user['no_whatsapp']) ?></p>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">🎯</div>
                    <div class="info-text">
                        <h4>Jalur & Prodi</h4>
                        <p>Jalur <?= esc($user['jalur_pendaftaran']) ?><br><?= esc($user['prodi_pilihan']) ?></p>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">📅</div>
                    <div class="info-text">
                        <h4>Gelombang Aktif</h4>
                        <p>Gelombang 1 Reguler<br><span style="color: #ef4444; font-weight: bold;">Tutup 28 Hari Lagi</span></p>
                    </div>
                </div>
            </div>

            <div class="card" style="padding: 25px; background: rgba(59, 130, 246, 0.05); border-color: rgba(59, 130, 246, 0.2);">
                <h2 class="card-title" style="font-size: 1.1rem; color: #60a5fa; border-bottom: none; margin-bottom: 15px;"><span>🎧</span> Pusat Bantuan Admisi</h2>
                <p style="color: #94a3b8; font-size: 0.85rem; line-height: 1.5; margin-bottom: 15px;">
                    Mengalami kendala gagal upload berkas atau pembayaran tidak terverifikasi? Hubungi kami:
                </p>
                <a href="https://wa.me/6281234567890" target="_blank" style="display: block; background: #10b981; color: white; text-align: center; padding: 10px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.9rem; margin-bottom: 10px;">Chat WA Admin PMB</a>
                <a href="<?= base_url('auth/logout') ?>" style="display: block; text-align: center; color: #ef4444; text-decoration: none; font-size: 0.85rem; font-weight: 600; padding: 10px; border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 8px;">🚪 Simpan & Keluar</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    <?php if(session()->getFlashdata('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= session()->getFlashdata('success') ?>',
            background: '#1e293b', color: '#fff', confirmButtonColor: '#10b981',
            timer: 4000, timerProgressBar: true
        });
    <?php endif; ?>
</script>

<?= $this->endSection() ?>