<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<style>
    body { background-color: #0b0f19; color: #f8fafc; font-family: 'Inter', sans-serif; overflow-x: hidden; }
    
    .header-fakultas { text-align: center; padding: 150px 20px 60px; position: relative; }
    .header-fakultas h1 { font-size: 3.5rem; font-weight: 800; margin-bottom: 15px; color: #fff; }
    .header-fakultas p { color: #94a3b8; font-size: 1.1rem; max-width: 600px; margin: 0 auto; }
    .accent { color: #8b5cf6; }

    .fakultas-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; max-width: 1200px; margin: 0 auto 100px; padding: 0 20px; }
    
    .fakultas-card { 
        background: #0f172a; 
        border: 1px solid rgba(255,255,255,0.05); 
        border-radius: 16px; 
        padding: 40px 30px; 
        text-align: center; 
        transition: 0.4s; 
        text-decoration: none; 
        display: flex; 
        flex-direction: column;
        align-items: center;
    }
    .fakultas-card:hover { 
        transform: translateY(-10px); 
        border-color: rgba(139,92,246,0.4); 
        box-shadow: 0 20px 40px rgba(0,0,0,0.4); 
        background: rgba(139,92,246,0.02);
    }
    
    .f-icon { font-size: 4rem; margin-bottom: 20px; }
    .f-name { color: #fff; font-size: 1.5rem; font-weight: 700; margin-bottom: 15px; line-height: 1.3; }
    .f-desc { color: #94a3b8; font-size: 0.95rem; line-height: 1.6; margin-bottom: 25px; flex: 1; }
    
    .f-btn { 
        color: #8b5cf6; 
        font-weight: 600; 
        font-size: 0.95rem; 
        border: 1px solid rgba(139,92,246,0.3); 
        padding: 10px 25px; 
        border-radius: 50px; 
        transition: 0.3s;
    }
    .fakultas-card:hover .f-btn { background: #8b5cf6; color: #fff; }
</style>

<div class="header-fakultas">
    <h1>Jelajahi <span class="accent">Fakultas</span></h1>
    <p>Temukan program studi yang sesuai dengan passion dan aspirasi karir masa depanmu di Astryveil Academy.</p>
</div>

<div class="fakultas-grid">
    <?php foreach ($listFakultas as $slug => $fak): ?>
        <a href="<?= base_url('fakultas/' . $slug) ?>" class="fakultas-card">
            <div class="f-icon"><?= $fak['icon'] ?></div>
            <h2 class="f-name"><?= $fak['nama'] ?></h2>
            <p class="f-desc"><?= $fak['desc'] ?></p>
            <div class="f-btn">Lihat Program Studi &rarr;</div>
        </a>
    <?php endforeach; ?>
</div>

<?= $this->endSection() ?>