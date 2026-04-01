<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<style>
    body { background-color: #0b0f19; color: #f8fafc; font-family: 'Inter', sans-serif; }
    
    .form-container { 
        max-width: 800px; margin: 120px auto 80px; padding: 40px;
        background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(25px);
        border: 1px solid rgba(255,255,255,0.08); border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.4);
    }

    .form-header { text-align: center; margin-bottom: 40px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 20px; }
    .form-header h1 { font-size: 1.8rem; font-weight: 800; margin: 0 0 10px; color: #fff; }
    .form-header p { color: #94a3b8; font-size: 0.95rem; margin: 0; }

    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
    @media(max-width: 600px) { .form-row { grid-template-columns: 1fr; gap: 0; } .form-row .form-group { margin-bottom: 20px; } }

    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 0.85rem; font-weight: 600; color: #cbd5e1; margin-bottom: 8px; }
    
    .form-control {
        width: 100%; padding: 14px 15px; border-radius: 12px;
        background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1);
        color: #fff; font-family: 'Inter', sans-serif; font-size: 0.95rem;
        box-sizing: border-box; transition: 0.3s;
    }
    .form-control:focus { outline: none; border-color: #8b5cf6; background: rgba(139,92,246,0.05); box-shadow: 0 0 15px rgba(139,92,246,0.2); }
    textarea.form-control { resize: vertical; min-height: 100px; }

    .btn-submit {
        width: 100%; padding: 16px; border-radius: 12px; margin-top: 20px;
        background: linear-gradient(135deg, #8b5cf6, #3b82f6); border: none;
        color: #fff; font-weight: 800; font-size: 1.05rem; cursor: pointer;
        transition: 0.3s; box-shadow: 0 5px 15px rgba(139,92,246,0.3);
    }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(139,92,246,0.5); }

    .back-link { display: inline-block; color: #64748b; font-size: 0.9rem; font-weight: 600; text-decoration: none; margin-bottom: 20px; transition: 0.3s; }
    .back-link:hover { color: #fff; }
</style>

<div class="form-container">
    <a href="<?= base_url('dashboard/pmb') ?>" class="back-link">← Kembali ke Dashboard</a>
    
    <div class="form-header">
        <h1>Lengkapi Biodata Diri</h1>
        <p>Mohon isi data kependudukan sesuai dengan Kartu Keluarga (KK) atau KTP Anda.</p>
    </div>

    <form action="<?= base_url('pmb/biodata/store') ?>" method="POST">
        <?= csrf_field() ?>
        
        <div class="form-group">
            <label>Nomor Induk Kependudukan (NIK)</label>
            <input type="text" name="nik" class="form-control" placeholder="Masukkan 16 digit NIK" required pattern="\d{16}" title="NIK harus berupa 16 digit angka">
        </div>

        <div class="form-row">
            <div class="form-group" style="margin-bottom: 0;">
                <label>Tempat Lahir</label>
                <input type="text" name="tempat_lahir" class="form-control" placeholder="Kota/Kabupaten kelahiran" required>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label>Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control" required style="color-scheme: dark;">
            </div>
        </div>

        <div class="form-group">
            <label>Alamat Lengkap Sesuai KTP</label>
            <textarea name="alamat_lengkap" class="form-control" placeholder="Jl. Nama Jalan, RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten, Kode Pos" required></textarea>
        </div>

        <div class="form-group">
            <label>Nama Orang Tua / Wali Kandung</label>
            <input type="text" name="nama_ortu" class="form-control" placeholder="Nama ayah/ibu/wali kandung" required>
        </div>

        <button type="submit" class="btn-submit">Simpan Biodata & Lanjutkan 🚀</button>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault(); // Tahan form agar tidak langsung terkirim
        
        Swal.fire({
            title: 'Simpan Biodata?',
            text: "Pastikan NIK dan data lainnya sudah diketik dengan benar sesuai KTP/KK.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6', // Biru
            cancelButtonColor: '#64748b',  // Abu-abu
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Cek Lagi',
            background: '#1e293b',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading animasi saat proses kirim data
                Swal.fire({
                    title: 'Menyimpan Data...',
                    background: '#1e293b', color: '#fff',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                this.submit(); // Lanjutkan kirim form
            }
        });
    });
</script>
    </form>
</div>

<?= $this->endSection() ?>