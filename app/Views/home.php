<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<style>
    /* Kunci layar agar tidak bisa di-scroll manual (karena scroll = pindah halaman) */
    body { 
        background-color: #0b0f19; 
        color: #f8fafc; 
        font-family: 'Inter', sans-serif; 
        overflow: hidden; /* Mencegah munculnya scrollbar */
        margin: 0; 
        transition: opacity 0.5s ease, transform 0.5s ease;
    }

    /* Efek animasi saat pindah route */
    body.navigating-down {
        opacity: 0;
        transform: translateY(-50px);
    }

    /* ==============================================
       HERO SECTION (100% Tinggi Layar)
       ============================================== */
    .hero-section { height: 100vh; display: flex; align-items: center; justify-content: center; text-align: center; position: relative; width: 100%; }
    .hero-bg { position: absolute; inset: 0; background: radial-gradient(circle at center, rgba(37, 99, 235, 0.15) 0%, rgba(159, 18, 57, 0.05) 50%, #0b0f19 100%); z-index: -4; }
    .bg-grid { position: absolute; inset: 0; background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 40px 40px; z-index: -3; }
    
    .particles span { position: absolute; background: rgba(255,255,255,0.6); border-radius: 50%; animation: floatParticle linear infinite; z-index: -2; }
    @keyframes floatParticle { from { transform: translateY(100vh); opacity: 0; } 50% { opacity: 1; } to { transform: translateY(-10vh); opacity: 0; } }

    .phoenix-wrapper { position: absolute; z-index: -1; transition: transform 0.1s ease-out; }
    .phoenix-silhouette { width: 700px; height: 700px; background: radial-gradient(circle, rgba(159,18,57,0.35), transparent 70%); filter: blur(80px); animation: phoenixPulse 6s infinite alternate ease-in-out; }
    @keyframes phoenixPulse { 0% { transform: scale(0.9); opacity: 0.5; } 100% { transform: scale(1.2); opacity: 0.9; } }

    .hero-content { z-index: 10; padding: 0 20px; }
    .hero-content h1 { font-size: 4.5rem; font-weight: 800; margin-bottom: 10px; background: linear-gradient(to right, #c084fc, #818cf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; opacity: 0; animation: fadeInUp 1s ease forwards; }
    .hero-content p { font-size: 1.2rem; color: #cbd5e1; margin-bottom: 40px; opacity: 0; animation: fadeInUp 1s ease forwards; animation-delay: 0.3s; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

    .btn-hero { display: inline-block; padding: 15px 40px; font-size: 1.1rem; text-decoration: none; color: #fff; font-weight: bold; border-radius: 999px; background: linear-gradient(135deg, #8b5cf6, #6366f1); transition: 0.3s; box-shadow: 0 10px 30px rgba(139,92,246,0.3); opacity: 0; animation: fadeInUp 1s ease forwards; animation-delay: 0.6s; }
    .btn-hero:hover { transform: translateY(-3px) scale(1.05); box-shadow: 0 15px 40px rgba(139,92,246,0.5); }

    .scroll-indicator { position: absolute; bottom: 40px; left: 50%; transform: translateX(-50%); color: rgba(255,255,255,0.5); font-size: 1rem; text-decoration: none; z-index: 20; animation: bounce 2s infinite; display: flex; flex-direction: column; align-items: center; gap: 5px; }
    @keyframes bounce { 0%, 20%, 50%, 80%, 100% { transform: translate(-50%, 0); } 40% { transform: translate(-50%, -15px); } 60% { transform: translate(-50%, -7px); } }

    @media (max-width: 768px) {
        .hero-content h1 { font-size: 2.8rem; }
    }
</style>

<section class="hero-section">
    <div class="hero-bg"></div>
    <div class="bg-grid"></div>
    <div class="particles" id="particles"></div>
    
    <div class="phoenix-wrapper">
        <div class="phoenix-silhouette"></div>
    </div>
    
    <div class="hero-content container">
        <h1>Astryveil Academy</h1>
        <p>“Empowering the Future Through Digital Education”</p>
        <a href="<?= base_url('fakultas') ?>" class="btn-hero">Masuk Portal Kampus</a>
    </div>

    <div class="scroll-indicator">
        <span style="font-size: 0.8rem; letter-spacing: 2px; text-transform: uppercase;">Scroll Down</span>
        <span style="font-size: 1.5rem;">↓</span>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Visual Effect
        const particlesContainer = document.getElementById('particles');
        if(particlesContainer) {
            for(let i=0; i<30; i++) {
                let span = document.createElement('span');
                let size = Math.random() * 3 + 1;
                span.style.width = size + 'px'; span.style.height = size + 'px';
                span.style.left = Math.random() * 100 + 'vw';
                span.style.animationDuration = (Math.random() * 4 + 3) + 's'; 
                span.style.animationDelay = Math.random() * 2 + 's';
                particlesContainer.appendChild(span);
            }
        }

        // ==========================================
        // LOGIKA SCROLL-TO-ROUTE (MOUSE & TOUCH)
        // ==========================================
        let isNavigating = false;
        const nextRoute = "<?= base_url('fakultas') ?>"; // Target Route Berikutnya!

        // Sensor untuk Mouse Wheel (PC/Laptop)
        window.addEventListener("wheel", (e) => {
            if (isNavigating) return;
            
            // Jika user scroll ke bawah (deltaY > 0)
            if (e.deltaY > 20) {
                goToNextRoute();
            }
        });

        // Sensor untuk Swipe Layar (HP/Tablet)
        let touchStartY = 0;
        window.addEventListener("touchstart", (e) => {
            touchStartY = e.changedTouches[0].screenY;
        }, {passive: true});

        window.addEventListener("touchend", (e) => {
            if (isNavigating) return;
            let touchEndY = e.changedTouches[0].screenY;
            
            // Jika user swipe ke atas (artinya scroll ke bawah layar)
            if (touchStartY - touchEndY > 50) { 
                goToNextRoute();
            }
        });

        // Fungsi Eksekusi Pindah Halaman
        function goToNextRoute() {
            isNavigating = true;
            // Tambahkan class animasi fade-out ke atas
            document.body.classList.add('navigating-down');
            
            // Pindah route setelah animasi (500ms)
            setTimeout(() => {
                window.location.href = nextRoute;
            }, 400); // Harus sedikit lebih cepat dari CSS transition
        }
    });
</script>

<?= $this->endSection() ?>