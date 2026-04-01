<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<style>
    body { background-color: #0b0f19; color: #f8fafc; font-family: 'Inter', sans-serif; }
    .form-container { max-width: 800px; margin: 120px auto 80px; padding: 40px; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(25px); border: 1px solid rgba(255,255,255,0.08); border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.4); }
    .form-header { text-align: center; margin-bottom: 40px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 20px; }
    .form-header h1 { font-size: 1.8rem; font-weight: 800; margin: 0 0 10px; color: #fff; }
    .form-header p { color: #94a3b8; font-size: 0.95rem; margin: 0; line-height: 1.6; }
    .upload-group { background: rgba(0,0,0,0.2); border: 1px dashed rgba(255,255,255,0.15); border-radius: 16px; padding: 25px; margin-bottom: 25px; transition: 0.3s; }
    .upload-group:hover { border-color: #8b5cf6; background: rgba(139,92,246,0.05); }
    .upload-title { font-weight: 700; font-size: 1.05rem; color: #e2e8f0; margin-bottom: 8px; display: flex; align-items: center; gap: 8px; }
    .upload-desc { font-size: 0.85rem; color: #94a3b8; margin-bottom: 15px; }
    input[type="file"] { width: 100%; padding: 12px; background: rgba(255,255,255,0.05); border-radius: 8px; color: #cbd5e1; font-size: 0.9rem; cursor: pointer; }
    input[type="file"]::file-selector-button { background: #1e293b; color: #fff; border: 1px solid rgba(255,255,255,0.1); padding: 8px 16px; border-radius: 6px; cursor: pointer; transition: 0.3s; margin-right: 15px; }
    input[type="file"]::file-selector-button:hover { background: #334155; }
    .btn-submit { width: 100%; padding: 16px; border-radius: 12px; background: linear-gradient(135deg, #8b5cf6, #3b82f6); border: none; color: #fff; font-weight: 800; font-size: 1.05rem; cursor: pointer; transition: 0.3s; box-shadow: 0 5px 15px rgba(139,92,246,0.3); }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(139,92,246,0.5); }
    .back-link { display: inline-block; color: #64748b; font-size: 0.9rem; font-weight: 600; text-decoration: none; margin-bottom: 20px; transition: 0.3s; }
</style>

<?php
    // 🔥 LOGIKA DINAMIS SYARAT BERKAS BERDASARKAN JALUR
    $jalur = $user['jalur_pendaftaran'];
    $labelKhusus = 'Scan Rapor Lengkap';
    $descKhusus  = 'Gabungkan rapor semester 1-5 dalam 1 file PDF. Maksimal ukuran: 5 MB.';
    
    if ($jalur == 'Prestasi') {
        $labelKhusus = 'Scan Rapor & Sertifikat Kejuaraan';
        $descKhusus  = 'Gabungkan rapor dan sertifikat prestasi tertinggi Anda dalam 1 file PDF. Maksimal 5 MB.';
    } elseif ($jalur == 'RPL') {
        $labelKhusus = 'Curriculum Vitae (CV) & Paklaring';
        $descKhusus  = 'Gabungkan CV dan Surat Keterangan Kerja (Paklaring) dalam 1 file PDF. Maksimal 5 MB.';
    } elseif ($jalur == 'Internasional') {
        $labelKhusus = 'Rapor & Sertifikat TOEFL/IELTS';
        $descKhusus  = 'Gabungkan rapor dan sertifikat bahasa Inggris resmi dalam 1 file PDF. Maksimal 5 MB.';
    }
?>

<div class="form-container">
    <a href="<?= base_url('dashboard/pmb') ?>" class="back-link">← Kembali ke Dashboard</a>
    
    <div class="form-header">
        <h1>Unggah Berkas Persyaratan</h1>
        <p>Anda mendaftar melalui <strong>Jalur <?= esc($jalur) ?></strong>. Pastikan berkas sesuai dengan syarat jalur Anda.</p>
    </div>

    <form action="<?= base_url('pmb/upload/store') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        
        <div class="upload-group">
            <div class="upload-title"><span>📸</span> Pas Foto Resmi Terbaru</div>
            <div class="upload-desc">Latar belakang merah/biru. Format: JPG/PNG. Maksimal ukuran: 2 MB.</div>
            <input type="file" name="berkas_foto" accept=".jpg,.jpeg,.png" required>
        </div>

        <div class="upload-group">
            <div class="upload-title"><span>💳</span> Scan KTP / Kartu Keluarga (KK)</div>
            <div class="upload-desc">Format: PDF/JPG/PNG. Maksimal ukuran: 5 MB.</div>
            <input type="file" name="berkas_kk" accept=".pdf,.jpg,.jpeg,.png" required>
        </div>

        <div class="upload-group">
            <div class="upload-title"><span>📄</span> <?= $labelKhusus ?></div>
            <div class="upload-desc"><?= $descKhusus ?></div>
            <input type="file" name="berkas_rapor" accept=".pdf" required>
        </div>

        <button type="submit" class="btn-submit">Unggah Semua Berkas 🚀</button>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Tangkap Error jika file kebesaran/format salah
    <?php if(session()->getFlashdata('error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'Gagal Mengunggah',
            text: '<?= session()->getFlashdata('error') ?>',
            background: '#1e293b', color: '#fff',
            confirmButtonColor: '#ef4444'
        });
    <?php endif; ?>

    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Unggah Dokumen?',
            text: "Pastikan file tidak buram dan ukurannya tidak melebihi batas maksimal.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#8b5cf6', // Ungu
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Unggah!',
            cancelButtonText: 'Batal',
            background: '#1e293b', color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Mengunggah File...',
                    html: 'Mohon tunggu, jangan tutup halaman ini.',
                    background: '#1e293b', color: '#fff',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                this.submit();
            }
        });
    });
</script>
    </form>
</div>
<?= $this->endSection() ?>