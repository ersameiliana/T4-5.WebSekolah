<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<style>
    body { background-color: #0b0f19; color: #f8fafc; font-family: 'Inter', sans-serif; }
    
    .form-container { 
        max-width: 500px; margin: 120px auto 80px; padding: 40px; 
        background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(25px); 
        border: 1px solid rgba(255,255,255,0.08); border-radius: 24px; 
        text-align: center; box-shadow: 0 20px 40px rgba(0,0,0,0.4);
    }
    
    .qris-box { 
        background: #fff; padding: 20px; border-radius: 12px; 
        display: inline-block; margin: 20px 0; 
    }
    
    .btn-submit { 
        width: 100%; padding: 16px; border-radius: 12px; 
        background: #10b981; border: none; color: #fff; 
        font-weight: 800; font-size: 1.05rem; cursor: pointer; 
        transition: 0.3s; box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
    }
    .btn-submit:hover { 
        background: #059669; transform: translateY(-2px); 
    }
</style>

<div class="form-container">
    <h1 style="font-size: 1.5rem; margin-bottom: 10px;">Biaya Formulir Pendaftaran</h1>
    <p style="color: #94a3b8; font-size: 0.9rem;">Silakan lakukan pembayaran senilai <strong>Rp 250.000</strong> ke rekening virtual berikut untuk memvalidasi pendaftaran Anda.</p>
    
    <div class="qris-box">
        <div style="width: 200px; height: 200px; border: 2px dashed #ccc; display: flex; align-items: center; justify-content: center; color: #333; font-weight: bold; font-size: 1.2rem; text-align: center;">
            Scan QRIS / VA<br><br>BCA<br>123456789
        </div>
    </div>

    <form action="<?= base_url('pmb/bayar/proses') ?>" method="POST">
        <?= csrf_field() ?>
        <button type="submit" class="btn-submit">SAYA SUDAH MEMBAYAR ✔</button>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Konfirmasi Pembayaran',
            text: "Apakah Anda yakin sudah mentransfer Rp 250.000 ke rekening yang tertera?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#10b981', // Hijau
            cancelButtonColor: '#ef4444',  // Merah
            confirmButtonText: 'Ya, Saya Sudah Bayar!',
            cancelButtonText: 'Belum',
            background: '#1e293b', color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memverifikasi...',
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
    
    <a href="<?= base_url('dashboard/pmb') ?>" style="display: block; margin-top: 25px; color: #64748b; text-decoration: none; font-weight: 600; font-size: 0.9rem; transition: 0.3s;">← Batal & Kembali ke Dashboard</a>
</div>

<?= $this->endSection() ?>