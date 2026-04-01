<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<?php
// --- DATA DOSEN ELIT (DEKAN) DARI DATABASE SQL ---
$dosenElite = [
    [
        'nama' => 'Prof. Dr. Ir. Veritas Ratio, M.Sc.', 
        'bidang' => 'Informatika', 
        'alumni' => 'Ph.D. from MIT', 
        'role' => 'Dekan Fak. Teknologi & Informatika',
        'achievements' => ['Penemu Algoritma Prediktif X', 'Ex-Lead Researcher IBM']
    ],
    [
        'nama' => 'Prof. Dr. Julius Novachrono, M.Sc.', 
        'bidang' => 'Fisika Komputasi', 
        'alumni' => 'Ph.D. from Oxford', 
        'role' => 'Dekan Fak. Sains & Matematika',
        'achievements' => ['Pakar Simulasi Material Nano', 'Penasihat Sains Global']
    ],
    [
        'nama' => 'Prof. Dr. Silco, M.B.A.', 
        'bidang' => 'Manajemen', 
        'alumni' => 'Harvard Business School', 
        'role' => 'Dekan Fak. Bisnis & Manajemen',
        'achievements' => ['Pendiri 3 Startup Unicorn', 'Penasihat Ekonomi Forbes']
    ],
    [
        'nama' => 'Prof. Dr. Rohan Kishibe, M.Sn.', 
        'bidang' => 'Desain Komunikasi Visual', 
        'alumni' => 'Tokyo Univ. of Arts', 
        'role' => 'Dekan Fak. Desain & Media Kreatif',
        'achievements' => ['Pemenang Penghargaan Global', 'Art Director Internasional']
    ]
];
?>

<style>
    /* 🌐 BASE & TYPOGRAPHY HIERARCHY */
    html { scroll-behavior: smooth; }
    body { background-color: #0b0f19; color: #f8fafc; font-family: 'Inter', sans-serif; overflow-x: hidden; }

    .bg-dark { background-color: #0b0f19; }
    .bg-light-dark { background-color: #0f172a; border-top: 1px solid rgba(255,255,255,0.02); border-bottom: 1px solid rgba(255,255,255,0.02); }

    h1.hero-title { font-size: clamp(3rem, 6vw, 5.5rem); font-weight: 900; letter-spacing: -2px; line-height: 1.1; margin-bottom: 20px; color: #fff; }
    h2.section-title { font-size: clamp(2rem, 4vw, 3.5rem); font-weight: 800; letter-spacing: -1px; color: #fff; margin: 0 0 20px; }
    h3.card-title { font-size: 1.4rem; font-weight: 700; color: #fff; margin-bottom: 10px; }
    p.body-text { font-size: 1.1rem; color: #94a3b8; line-height: 1.7; }

    /* ✨ SUBTLE CURSOR GLOW */
    .cursor-glow { position: fixed; top: 0; left: 0; width: 350px; height: 350px; background: radial-gradient(circle, rgba(139, 92, 246, 0.08), transparent 60%); pointer-events: none; transform: translate(-50%, -50%); z-index: 9999; mix-blend-mode: screen; will-change: transform; transition: width 0.3s, height 0.3s; }

    /* 🎬 SCROLL REVEAL */
    .reveal { opacity: 0; transform: translateY(40px); transition: 0.8s cubic-bezier(0.16, 1, 0.3, 1); will-change: transform, opacity; }
    .reveal.active { opacity: 1; transform: translateY(0); }

    /* 🌌 HERO SECTION */
    .hero-about { padding: 220px 20px 150px; text-align: center; position: relative; overflow: hidden; }
    .hero-about::before { content: ""; position: absolute; inset: 0; background: radial-gradient(circle at center, rgba(139,92,246,0.15), transparent 60%); z-index: -1; }
    .accent-text { background: linear-gradient(135deg, #c084fc, #8b5cf6, #3b82f6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

    .section-container { max-width: 1200px; margin: 0 auto; padding: 120px 20px; position: relative; z-index: 2; }

    /* ⚠️ PROBLEM STATEMENT */
    .problem-section { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; }
    .problem-statement { border-left: 4px solid #ef4444; padding-left: 30px; }
    .problem-statement h2 { font-size: 2.5rem; font-weight: 800; color: #fff; margin-bottom: 20px; line-height: 1.2; }
    .problem-solution { background: rgba(37, 99, 235, 0.05); border: 1px solid rgba(37, 99, 235, 0.2); padding: 50px; border-radius: 24px; position: relative; }
    .problem-solution::before { content: "SOLUSI"; position: absolute; top: -15px; left: 40px; background: #2563EB; color: #fff; font-size: 0.8rem; font-weight: 800; padding: 5px 15px; border-radius: 50px; letter-spacing: 1px; }
    @media (max-width: 900px) { .problem-section { grid-template-columns: 1fr; gap: 40px; } }

    /* 🎓 ACADEMIC STRENGTH (Program Studi List) */
    .acad-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-top: 50px; }
    .acad-card { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 30px 25px; border-radius: 20px; transition: 0.4s; position: relative; overflow: hidden; }
    .acad-card:hover { transform: translateY(-8px); border-color: rgba(139,92,246,0.4); box-shadow: 0 20px 40px rgba(0,0,0,0.3); background: rgba(139,92,246,0.05); }
    .acad-icon { font-size: 2.5rem; margin-bottom: 15px; }
    .acad-title { font-size: 1.1rem; color: #fff; font-weight: 800; margin-bottom: 15px; line-height: 1.4; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 15px; }
    .prodi-list { list-style: none; padding: 0; margin: 0; }
    .prodi-list li { color: #cbd5e1; font-size: 0.95rem; margin-bottom: 10px; display: flex; align-items: center; gap: 8px; }
    .prodi-list li::before { content: "▪"; color: #8b5cf6; font-size: 1.2rem; }
    @media (max-width: 992px) { .acad-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 576px) { .acad-grid { grid-template-columns: 1fr; } }

    /* 🍱 BENTO GRID (Facilities) */
    .bento-grid { display: grid; grid-template-columns: repeat(4, 1fr); grid-auto-rows: 280px; gap: 20px; margin-top: 50px; }
    .bento-box { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 24px; padding: 40px; position: relative; overflow: hidden; transition: 0.3s ease; display: flex; flex-direction: column; justify-content: flex-end; }
    .bento-box:hover { transform: scale(1.02); border-color: rgba(139,92,246,0.4); box-shadow: 0 20px 40px rgba(0,0,0,0.3); z-index: 10; }
    .bento-large { grid-column: span 2; grid-row: span 2; }
    .bento-wide { grid-column: span 2; grid-row: span 1; }
    .bento-tall { grid-column: span 1; grid-row: span 2; }
    .bento-small { grid-column: span 1; grid-row: span 1; }
    @media (max-width: 992px) { .bento-grid { grid-template-columns: repeat(2, 1fr); grid-auto-rows: 250px; } .bento-large, .bento-wide { grid-column: span 2; } .bento-tall { grid-row: span 1; grid-column: span 2; } .bento-small { grid-column: span 1; } }
    @media (max-width: 576px) { .bento-grid { grid-template-columns: 1fr; grid-auto-rows: auto; } .bento-box { grid-column: span 1 !important; grid-row: span 1 !important; min-height: 250px; } }

    .bento-icon { font-size: 3.5rem; margin-bottom: auto; filter: drop-shadow(0 0 15px rgba(139,92,246,0.4)); }
    .bento-title { color: #fff; font-size: 1.5rem; font-weight: 800; margin: 0 0 10px; line-height: 1.2; z-index: 2; }
    .bento-desc { color: #94a3b8; font-size: 0.95rem; line-height: 1.6; margin: 0; z-index: 2; }
    .bento-large .bento-title { font-size: 2.2rem; }
    .bento-large .bento-desc { font-size: 1.1rem; max-width: 80%; }

    /* 🧑‍🏫 DOSEN ELITE (Hover Reveal) */
    .dosen-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 30px; margin-top: 50px; }
    .dosen-card { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 20px; padding: 40px 20px; text-align: center; position: relative; overflow: hidden; transition: 0.4s; }
    .dosen-card:hover { border-color: #3b82f6; }
    .dosen-ava { width: 100px; height: 100px; border-radius: 50%; background: #1e293b; margin: 0 auto 20px; border: 3px solid #8b5cf6; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; position: relative; z-index: 2; transition: 0.4s; }
    
    .dosen-overlay { position: absolute; inset: 0; background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(5px); display: flex; flex-direction: column; justify-content: center; align-items: center; opacity: 0; transition: 0.4s; z-index: 5; padding: 20px; }
    .dosen-card:hover .dosen-overlay { opacity: 1; }
    .dosen-overlay ul { list-style: none; padding: 0; margin: 0; text-align: left; }
    .dosen-overlay li { color: #cbd5e1; font-size: 0.9rem; margin-bottom: 10px; display: flex; align-items: center; gap: 8px; }
    .dosen-overlay li::before { content: "✔"; color: #10b981; }

    /* 💬 TESTIMONIALS */
    .testi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-top: 50px; }
    .testi-card { background: linear-gradient(145deg, rgba(255,255,255,0.03), transparent); border: 1px solid rgba(255,255,255,0.05); padding: 40px; border-radius: 24px; position: relative; transition: 0.3s; }
    .testi-card:hover { transform: translateY(-5px); border-color: rgba(139,92,246,0.3); background: rgba(139,92,246,0.05); }
    .testi-card::before { content: "“"; position: absolute; top: 10px; right: 20px; font-size: 6rem; color: rgba(255,255,255,0.05); font-family: serif; line-height: 1; }
    .testi-quote { font-size: 1.1rem; color: #e2e8f0; line-height: 1.7; font-style: italic; margin-bottom: 20px; position: relative; z-index: 2; }
    .testi-author { font-weight: 800; color: #fff; font-size: 1.1rem; }
    .testi-role { color: #8b5cf6; font-size: 0.9rem; font-weight: 600; margin-top: 5px; }

    /* 🌍 MARQUEE (Paused on Hover) */
    .marquee-container { width: 100%; overflow: hidden; background: #0f172a; padding: 30px 0; border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); }
    .marquee-content { display: flex; gap: 60px; width: max-content; animation: scrollText 40s linear infinite; }
    .marquee-container:hover .marquee-content { animation-play-state: paused; }
    .marquee-item { font-size: 1.2rem; font-weight: 700; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 2px; display: flex; align-items: center; gap: 15px; cursor: default; transition: 0.3s; }
    .marquee-item:hover { color: #fff; }
    .marquee-item span { color: #8b5cf6; font-size: 1.5rem; }
    @keyframes scrollText { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
</style>

<header class="hero-about bg-dark" id="hero-section">
    <div class="reveal">
        <h1 class="hero-title">Mendefinisikan Ulang <br><span class="accent-text">Masa Depan Pendidikan</span></h1>
        <p class="body-text" style="max-width: 800px; margin: 0 auto;">Kami tidak sekadar mencetak lulusan yang mencari kerja. Astryveil adalah ekosistem inovasi tempat para visioner dilatih untuk menciptakan industri baru dan memimpin perubahan global.</p>
    </div>
</header>

<section class="bg-light-dark">
    <div class="section-container problem-section">
        <div class="problem-statement reveal">
            <div style="color: #ef4444; font-weight: 800; font-size: 1rem; letter-spacing: 2px; margin-bottom: 10px; text-transform: uppercase;">Realitas Hari Ini</div>
            <h2>Mengapa Pendidikan Konvensional Gagal?</h2>
            <p class="body-text">Mayoritas institusi masih menggunakan kurikulum usang yang dirancang untuk revolusi industri masa lalu. Hasilnya? Lulusan menghabiskan 4 tahun belajar teori, namun gagap saat berhadapan dengan ekosistem teknologi modern dan dinamika bisnis nyata.</p>
        </div>
        <div class="problem-solution reveal">
            <h3 class="card-title" style="font-size: 1.8rem; margin-bottom: 15px;">The Astryveil Advantage</h3>
            <p class="body-text" style="margin-bottom: 20px;">Kami meruntuhkan tembok kelas. Di sini, Anda belajar langsung dari eksekutif Fortune 500, menyelesaikan studi kasus nyata perusahaan multinasional, dan membangun portofolio produk yang siap diluncurkan sebelum Anda menyentuh ijazah.</p>
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px; color: #cbd5e1;"><span style="color: #3b82f6;">✔</span> Kurikulum Adaptif Berbasis AI</li>
                <li style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px; color: #cbd5e1;"><span style="color: #3b82f6;">✔</span> Garansi Magang Industri Global</li>
                <li style="display: flex; align-items: center; gap: 10px; color: #cbd5e1;"><span style="color: #3b82f6;">✔</span> Inkubasi Startup di Kampus</li>
            </ul>
        </div>
    </div>
</section>

<section class="section-container bg-dark">
    <div class="reveal" style="text-align: center;">
        <h2 class="section-title">Kekuatan Akademik Kami</h2>
        <p class="body-text" style="max-width: 700px; margin: 0 auto;">Pilih jalur spesialisasi Anda. Setiap Program Studi dirancang secara presisi untuk menjawab defisit talenta di industri teknologi, sains, dan bisnis global.</p>
    </div>
    
    <div class="acad-grid reveal">
        <div class="acad-card">
            <div class="acad-icon">💻</div>
            <h3 class="acad-title">Fakultas Teknologi & Informatika (FTI)</h3>
            <ul class="prodi-list">
                <li>Sistem Informasi</li>
                <li>Informatika</li>
                <li>Data Science</li>
            </ul>
        </div>
        <div class="acad-card" style="transition-delay: 0.1s;">
            <div class="acad-icon">🔬</div>
            <h3 class="acad-title">Fakultas Sains & Matematika (FSM)</h3>
            <ul class="prodi-list">
                <li>Matematika Terapan</li>
                <li>Fisika Komputasi</li>
                <li>Statistika</li>
            </ul>
        </div>
        <div class="acad-card" style="transition-delay: 0.2s;">
            <div class="acad-icon">📈</div>
            <h3 class="acad-title">Fakultas Bisnis & Manajemen (FBM)</h3>
            <ul class="prodi-list">
                <li>Manajemen</li>
                <li>Akuntansi</li>
                <li>Bisnis Digital</li>
            </ul>
        </div>
        <div class="acad-card" style="transition-delay: 0.3s;">
            <div class="acad-icon">🎨</div>
            <h3 class="acad-title">Fakultas Desain & Media Kreatif (FDMK)</h3>
            <ul class="prodi-list">
                <li>Desain Komunikasi Visual (DKV)</li>
                <li>Desain Produk</li>
                <li>Animasi & Media Digital</li>
            </ul>
        </div>
    </div>
</section>

<section class="bg-light-dark">
    <div class="section-container">
        <h2 class="section-title reveal" style="text-align: center;">Fasilitas Kelas Dunia</h2>
        
        <div class="bento-grid reveal">
            <div class="bento-box bento-large" style="background: linear-gradient(145deg, rgba(139,92,246,0.1), rgba(11,15,25,0.8));">
                <div class="bento-icon">📚</div>
                <h3 class="bento-title">The Starlight Nexus</h3>
                <p class="bento-desc">Perpustakaan interaktif yang dilengkapi dengan pod kedap suara, akses jurnal internasional real-time, dan asisten riset AI berhologram.</p>
            </div>
            <div class="bento-box bento-wide">
                <div class="bento-icon">🔬</div>
                <h3 class="bento-title">Quantum & VR Labs</h3>
                <p class="bento-desc">Fasilitas riset dengan superkomputer dan perangkat Haptic VR untuk simulasi tingkat industri.</p>
            </div>
            <div class="bento-box bento-small">
                <div class="bento-icon">☕</div>
                <h3 class="bento-title">Creative Lounge</h3>
                <p class="bento-desc">Kafe 24/7 dan ruang diskusi santai.</p>
            </div>
            <div class="bento-box bento-small">
                <div class="bento-icon">🏟️</div>
                <h3 class="bento-title">Astry Arena</h3>
                <p class="bento-desc">Pusat kebugaran & stadion e-sports.</p>
            </div>
            <div class="bento-box bento-wide" style="background: linear-gradient(145deg, rgba(37,99,235,0.1), rgba(11,15,25,0.8));">
                <div class="bento-icon">💡</div>
                <h3 class="bento-title">Phoenix Incubator</h3>
                <p class="bento-desc">Ruang kerja eksklusif untuk mengubah ide tugas akhir menjadi startup bernilai miliaran rupiah.</p>
            </div>
        </div>
    </div>
</section>

<section class="section-container bg-dark">
    <div class="reveal" style="text-align: center; margin-bottom: 60px;">
        <h2 class="section-title">Dibimbing oleh Para Pionir</h2>
        <p class="body-text" style="max-width: 800px; margin: 0 auto;">Di Astryveil, Anda akan dibimbing langsung oleh para Dekan Fakultas yang merupakan ilmuwan pemenang penghargaan dan tokoh elit diakui dunia.</p>
    </div>
    
    <div class="dosen-grid reveal">
        <?php foreach($dosenElite as $dosen): ?>
        <div class="dosen-card">
            <div style="position: relative; z-index: 2;">
                <div class="dosen-ava">👤</div>
                <h3 class="card-title" style="font-size: 1.1rem;"><?= $dosen['nama'] ?></h3>
                <div style="color: #8b5cf6; font-size: 0.85rem; font-weight: 700; margin-bottom: 10px; text-transform: uppercase;"><?= $dosen['role'] ?></div>
                <div style="color: #cbd5e1; font-size: 0.9rem;"><?= $dosen['bidang'] ?></div>
            </div>
            
            <div class="dosen-overlay">
                <div style="color: #fff; font-weight: 800; margin-bottom: 15px; font-size: 1.1rem;">Track Record</div>
                <ul>
                    <li>🎓 <?= $dosen['alumni'] ?></li>
                    <?php foreach($dosen['achievements'] as $ach): ?>
                        <li><?= $ach ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="bg-light-dark">
    <div class="section-container">
        <h2 class="section-title reveal" style="text-align: center;">Kisah Mereka yang Mengubah Dunia</h2>
        
        <div class="testi-grid reveal">
            <div class="testi-card">
                <p class="testi-quote">"Kurikulum di Astryveil benar-benar memaksa saya berpikir layaknya seorang CEO sejak semester satu. Saya berhasil mengamankan pendanaan startup $2M bahkan sebelum sidang skripsi."</p>
                <div class="testi-author">Seijuro Akashi</div>
                <div class="testi-role">CEO TechNova (Alumni Manajemen 2021)</div>
            </div>
            <div class="testi-card">
                <p class="testi-quote">"Fasilitas Quantum Lab di kampus ini jauh melampaui universitas negeri manapun. Hasil algoritma tugas akhir saya langsung dilirik dan direkrut oleh tim riset Google HQ."</p>
                <div class="testi-author">Silver Wolf</div>
                <div class="testi-role">AI Engineer @ Google (Alumni Data Science 2022)</div>
            </div>
            <div class="testi-card">
                <p class="testi-quote">"Akses mentorship 1-on-1 dengan dosen praktisi adalah privilege terbesar. Portofolio desain interaktif saya mendapat standar industri Eropa berkat bimbingan ketat mereka."</p>
                <div class="testi-author">Kise Ryota</div>
                <div class="testi-role">Lead UI/UX @ Spotify EU (Alumni DKV 2020)</div>
            </div>
        </div>
    </div>
</section>

<div class="marquee-container reveal">
    <div class="marquee-content">
        <div class="marquee-item"><span>✦</span> 98% Graduate Employment Rate</div>
        <div class="marquee-item"><span>✦</span> Rp 15jt+ Avg Starting Salary</div>
        <div class="marquee-item"><span>✦</span> 50+ Global Industry Partners</div>
        <div class="marquee-item"><span>✦</span> 1% Acceptance Rate</div>
        <div class="marquee-item"><span>✦</span> 98% Graduate Employment Rate</div>
        <div class="marquee-item"><span>✦</span> Rp 15jt+ Avg Starting Salary</div>
        <div class="marquee-item"><span>✦</span> 50+ Global Industry Partners</div>
        <div class="marquee-item"><span>✦</span> 1% Acceptance Rate</div>
    </div>
</div>

<section class="section-container bg-dark" style="padding-top: 100px; padding-bottom: 150px; text-align: center;">
    <div class="reveal" style="background: radial-gradient(circle at center, rgba(139,92,246,0.15), transparent 70%); border: 1px solid rgba(139,92,246,0.3); padding: 80px 20px; border-radius: 40px; position: relative; overflow: hidden;">
        <h2 style="font-size: 2.8rem; color: #fff; margin: 0 0 20px; font-weight: 900; letter-spacing: -1px;">Siap Mengambil Langkah Pertama?</h2>
        <p class="body-text" style="max-width: 600px; margin: 0 auto 40px;">Bergabunglah dengan elit inovator muda yang mendefinisikan ulang masa depan teknologi dan bisnis. Pendaftaran Fast-Track kini dibuka.</p>
        
        <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
            <a href="<?= base_url('login') ?>" style="padding: 16px 40px; background: #8b5cf6; color: #fff; text-decoration: none; font-weight: 700; border-radius: 12px; transition: 0.3s; box-shadow: 0 10px 25px rgba(139,92,246,0.4); font-size: 1.1rem;">Apply Now</a>
            <a href="<?= base_url('akademik') ?>" style="padding: 16px 40px; background: rgba(255,255,255,0.05); color: #fff; border: 1px solid rgba(255,255,255,0.2); text-decoration: none; font-weight: 700; border-radius: 12px; transition: 0.3s; font-size: 1.1rem;">Explore Programs</a>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        // 1. CURSOR GLOW (Soft & Eased)
        const glow = document.createElement("div");
        glow.classList.add("cursor-glow");
        document.body.appendChild(glow);

        let mouseX = window.innerWidth / 2, mouseY = window.innerHeight / 2;
        let curX = mouseX, curY = mouseY;
        document.addEventListener("mousemove", e => { mouseX = e.clientX; mouseY = e.clientY; });

        function animCursor() {
            curX += (mouseX - curX) * 0.1; curY += (mouseY - curY) * 0.1;
            glow.style.transform = `translate(calc(-50% + ${curX}px), calc(-50% + ${curY}px))`;
            requestAnimationFrame(animCursor);
        }
        animCursor();

        // 2. HERO PARALLAX
        const hero = document.getElementById("hero-section");
        window.addEventListener("scroll", () => {
            if(window.scrollY < 800) { hero.style.transform = `translateY(${window.scrollY * 0.15}px)`; }
        });

        // 3. CLEAN SCROLL REVEAL
        const revealObserver = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: "0px 0px -50px 0px" });

        document.querySelectorAll('.reveal').forEach((el) => { revealObserver.observe(el); });
    });
</script>

<?= $this->endSection() ?>