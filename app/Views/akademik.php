<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<style>
    /* 🌐 BASE & LAYOUT (NO GLOW) */
    html { scroll-behavior: smooth; }
    body { background-color: #0b0f19; color: #f8fafc; font-family: 'Inter', sans-serif; overflow-x: hidden; }

    /* 🎬 CLEAN SCROLL REVEAL */
    .reveal { opacity: 0; transform: translateY(30px); transition: 0.8s ease-out; will-change: transform, opacity; }
    .reveal.active { opacity: 1; transform: translateY(0); }

    /* 🌌 HERO SECTION */
    .hero-akademik { position: relative; padding: 160px 20px 100px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.05); background: linear-gradient(180deg, rgba(15,23,42,0.8) 0%, #0b0f19 100%); }
    .hero-akademik h1 { font-size: clamp(2.5rem, 5vw, 4rem); font-weight: 800; margin-bottom: 20px; color: #fff; letter-spacing: -1px; }
    .hero-akademik .accent-text { color: #8b5cf6; }
    .hero-akademik p { font-size: 1.15rem; color: #94a3b8; max-width: 700px; margin: 0 auto; line-height: 1.6; }

    /* 🏛️ SECTION CONTAINERS */
    .section-container { max-width: 1100px; margin: 0 auto; padding: 80px 20px; }
    .section-title { font-size: 2.2rem; color: #fff; margin-bottom: 20px; font-weight: 800; text-align: center; }
    .section-subtitle { text-align: center; color: #94a3b8; margin-bottom: 50px; font-size: 1.05rem; max-width: 700px; margin-left: auto; margin-right: auto; }

    /* 🏆 ACADEMIC STATS (Realism) */
    .grid-3 { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; margin-top: -40px; position: relative; z-index: 10; }
    .stat-card { background: #0f172a; border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; padding: 30px; text-align: center; box-shadow: 0 15px 30px rgba(0,0,0,0.2); }
    .stat-number { font-size: 3rem; font-weight: 800; color: #fff; margin-bottom: 5px; }
    .stat-label { color: #8b5cf6; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 0.8rem; }

    /* 🎓 FAKULTAS CLEAN LAYOUT */
    .faculty-block { background: rgba(255, 255, 255, 0.01); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 20px; padding: 40px; margin-bottom: 40px; display: grid; grid-template-columns: 1fr 1.5fr; gap: 40px; transition: 0.3s; }
    .faculty-block:hover { border-color: rgba(139,92,246,0.3); background: rgba(139,92,246,0.02); }
    @media (max-width: 900px) { .faculty-block { grid-template-columns: 1fr; padding: 30px; } }

    .fac-icon { font-size: 3rem; margin-bottom: 15px; }
    .fac-title { font-size: 1.6rem; color: #fff; font-weight: 800; margin: 0 0 10px 0; }
    .fac-desc { color: #94a3b8; font-size: 0.95rem; line-height: 1.6; margin-bottom: 20px; }
    
    .fac-btn { display: inline-flex; align-items: center; font-size: 0.9rem; font-weight: 600; color: #8b5cf6; text-decoration: none; padding: 10px 20px; border-radius: 8px; border: 1px solid rgba(139,92,246,0.3); transition: 0.3s; }
    .fac-btn:hover { background: #8b5cf6; color: #fff; }

    .prodi-list { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
    .prodi-item { background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.03); padding: 20px; border-radius: 12px; }
    .prodi-name { color: #e2e8f0; font-size: 1rem; font-weight: 700; margin: 0 0 5px; }
    .prodi-degree { font-size: 0.75rem; color: #64748b; font-weight: 600; }
    @media (max-width: 576px) { .prodi-list { grid-template-columns: 1fr; } }

    /* 🌍 ACADEMIC EXPERIENCE */
    .exp-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; }
    .exp-card { background: rgba(255,255,255,0.01); border: 1px solid rgba(255,255,255,0.05); padding: 30px; border-radius: 16px; transition: 0.3s; }
    .exp-card:hover { transform: translateY(-5px); border-color: rgba(59,130,246,0.3); background: rgba(59,130,246,0.05); }
    .exp-icon { font-size: 2.5rem; margin-bottom: 15px; }
    .exp-title { color: #fff; font-size: 1.1rem; font-weight: 700; margin-bottom: 10px; }

</style>

<header class="hero-akademik">
    <div class="reveal">
        <h1>Program <span class="accent-text">Akademik</span></h1>
        <p>Kurikulum berbasis riset yang dirancang bersama industri untuk membekali Anda dengan landasan teoritis mendalam dan keahlian praktis.</p>
    </div>
</header>

<div class="section-container" style="padding-top: 0;">
    <div class="grid-3 reveal">
        <div class="stat-card">
            <div class="stat-number">Top 10</div>
            <div class="stat-label">Universitas Swasta Nasional</div>
        </div>
        <div class="stat-card" style="border-top: 2px solid #8b5cf6;">
            <div class="stat-number">85%</div>
            <div class="stat-label">Lulusan Terserap Industri < 6 Bulan</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">A</div>
            <div class="stat-label">Akreditasi Institusi BAN-PT</div>
        </div>
    </div>
</div>

<section class="section-container" style="padding-top: 20px;">
    <div class="reveal">
        <h2 class="section-title">Fakultas & Program Studi</h2>
        <p class="section-subtitle">Struktur akademik Astryveil Academy dirancang untuk menjawab tantangan dan kebutuhan tenaga ahli di era modern.</p>
    </div>

    <div class="faculty-block reveal">
        <div>
            <div class="fac-icon">💻</div>
            <h3 class="fac-title">Fakultas Teknologi & Informatika</h3>
            <p class="fac-desc">Pusat riset dan pengembangan rekayasa komputasi, kecerdasan buatan, dan sains data berskala nasional.</p>
            <a href="<?= base_url('fakultas/teknologi-informatika') ?>" class="fac-btn">Detail Fakultas →</a>
        </div>
        <div class="prodi-list">
            <div class="prodi-item"><h4 class="prodi-name">Teknik Informatika</h4><span class="prodi-degree">S1 • Terakreditasi Unggul</span></div>
            <div class="prodi-item"><h4 class="prodi-name">Sistem Informasi</h4><span class="prodi-degree">S1 • Terakreditasi Unggul</span></div>
            <div class="prodi-item"><h4 class="prodi-name">Data Science</h4><span class="prodi-degree">S1 • Terakreditasi Unggul</span></div>
            <div class="prodi-item"><h4 class="prodi-name">Rekayasa Perangkat Lunak</h4><span class="prodi-degree">S1 • Terakreditasi Unggul</span></div>
        </div>
    </div>

    <div class="faculty-block reveal">
        <div>
            <div class="fac-icon">📈</div>
            <h3 class="fac-title">Fakultas Bisnis & Manajemen</h3>
            <p class="fac-desc">Mendidik calon eksekutif dan wirausahawan melalui pendekatan ekonomi digital dan tata kelola bisnis modern.</p>
            <a href="<?= base_url('fakultas/bisnis-manajemen') ?>" class="fac-btn">Detail Fakultas →</a>
        </div>
        <div class="prodi-list">
            <div class="prodi-item"><h4 class="prodi-name">Manajemen</h4><span class="prodi-degree">S1 • Terakreditasi A</span></div>
            <div class="prodi-item"><h4 class="prodi-name">Akuntansi</h4><span class="prodi-degree">S1 • Terakreditasi A</span></div>
            <div class="prodi-item"><h4 class="prodi-name">Bisnis Digital</h4><span class="prodi-degree">S1 • Terakreditasi A</span></div>
        </div>
    </div>

    <div class="faculty-block reveal">
        <div>
            <div class="fac-icon">🔬</div>
            <h3 class="fac-title">Fakultas Sains & Matematika</h3>
            <p class="fac-desc">Membangun fondasi ilmu pengetahuan murni dan terapan untuk memecahkan kompleksitas fenomena alam dan industri.</p>
            <a href="<?= base_url('fakultas/sains-matematika') ?>" class="fac-btn">Detail Fakultas →</a>
        </div>
        <div class="prodi-list">
            <div class="prodi-item"><h4 class="prodi-name">Matematika Terapan</h4><span class="prodi-degree">S1 • Terakreditasi Unggul</span></div>
            <div class="prodi-item"><h4 class="prodi-name">Fisika Komputasi</h4><span class="prodi-degree">S1 • Terakreditasi Unggul</span></div>
            <div class="prodi-item"><h4 class="prodi-name">Statistika</h4><span class="prodi-degree">S1 • Terakreditasi Unggul</span></div>
        </div>
    </div>

    <div class="faculty-block reveal">
        <div>
            <div class="fac-icon">🎨</div>
            <h3 class="fac-title">Fakultas Desain Kreatif</h3>
            <p class="fac-desc">Titik temu antara estetika seni rupa dan kecanggihan teknologi media digital interaktif.</p>
            <a href="<?= base_url('fakultas/desain-kreatif') ?>" class="fac-btn">Detail Fakultas →</a>
        </div>
        <div class="prodi-list">
            <div class="prodi-item"><h4 class="prodi-name">Desain Komunikasi Visual</h4><span class="prodi-degree">S1 • Terakreditasi A</span></div>
            <div class="prodi-item"><h4 class="prodi-name">Desain Produk</h4><span class="prodi-degree">S1 • Terakreditasi A</span></div>
            <div class="prodi-item"><h4 class="prodi-name">Animasi & Media Digital</h4><span class="prodi-degree">S1 • Terakreditasi A</span></div>
        </div>
    </div>

</section>

<section class="section-container">
    <h2 class="section-title reveal">Standar Pendidikan Astryveil</h2>
    <p class="section-subtitle reveal">Di Astryveil, pendidikan diintegrasikan dengan pengalaman industri untuk menjamin kompetensi lulusan.</p>
    
    <div class="exp-grid reveal">
        <div class="exp-card">
            <div class="exp-icon">🏢</div>
            <h3 class="exp-title">Program Magang Terstruktur</h3>
            <p style="color: #94a3b8; font-size: 0.9rem; line-height: 1.6;">Fasilitasi magang di perusahaan multinasional dan BUMN bersertifikasi untuk pengalaman kerja nyata.</p>
        </div>
        <div class="exp-card">
            <div class="exp-icon">🌍</div>
            <h3 class="exp-title">Kemitraan Global</h3>
            <p style="color: #94a3b8; font-size: 0.9rem; line-height: 1.6;">Kerja sama riset dan pertukaran pelajar dengan universitas mitra di Asia dan Eropa.</p>
        </div>
        <div class="exp-card">
            <div class="exp-icon">🔬</div>
            <h3 class="exp-title">Fokus Publikasi Ilmiah</h3>
            <p style="color: #94a3b8; font-size: 0.9rem; line-height: 1.6;">Dukungan penuh bagi mahasiswa untuk menghasilkan karya ilmiah terindeks nasional dan internasional.</p>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const revealObserver = new IntersectionObserver((entries, obs) => {
            entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('active'); obs.unobserve(e.target); } });
        }, { threshold: 0.1, rootMargin: "0px 0px -50px 0px" });
        document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));
    });
</script>

<?= $this->endSection() ?>