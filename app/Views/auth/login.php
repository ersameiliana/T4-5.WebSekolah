<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container" style="display: flex; justify-content: center; align-items: center; min-height: 80vh;">
    <div class="glass card-glow" style="padding: 40px; width: 100%; max-width: 400px; text-align: center;">
        <h2 style="margin-bottom: 30px;">Login Portal</h2>
        
        <?php if(session()->getFlashdata('msg')): ?>
            <div style="color: #ff4d4d; margin-bottom: 15px;"><?= session()->getFlashdata('msg') ?></div>
        <?php endif; ?>

        <form action="/login/process" method="POST">
            <input type="text" name="username" placeholder="Username / NIM / NIDN" class="glass" 
                   style="width: 100%; padding: 15px; margin-bottom: 20px; color: white; border: 1px solid rgba(255,255,255,0.3); box-sizing: border-box;" required>
            
            <input type="password" name="password" placeholder="Password" class="glass" 
                   style="width: 100%; padding: 15px; margin-bottom: 30px; color: white; border: 1px solid rgba(255,255,255,0.3); box-sizing: border-box;" required>
            
            <button type="submit" class="btn" style="width: 100%; font-size: 1.1rem;">Secure Login 🚀</button>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>