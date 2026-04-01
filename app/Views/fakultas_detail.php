<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<?php
// 1. HELPER LOGIKA DATABASE (SMART MATCHING)
function getPejabat($keyword_unit, $kategori, $pimpinanList) {
    $keyword_alt = str_ireplace(' & ', ' dan ', $keyword_unit);
    
    if (stripos($keyword_unit, 'DKV') !== false || stripos($keyword_unit, 'Desain Komunikasi Visual') !== false) {
        $keyword_alt = 'Desain Komunikasi Visual';
        $keyword_unit = 'DKV';
    }

    if(!empty($pimpinanList)){
        foreach($pimpinanList as $p) {
            if(isset($p['kategori']) && isset($p['nama_unit'])) {
                if(stripos($p['kategori'], $kategori) !== false) {
                    $dbUnit = $p['nama_unit'];
                    if(
                        stripos($dbUnit, $keyword_unit) !== false || 
                        stripos($keyword_unit, $dbUnit) !== false ||
                        stripos($dbUnit, $keyword_alt) !== false || 
                        stripos($keyword_alt, $dbUnit) !== false
                    ) {
                        $gelarDepan = !empty($p['gelar_depan']) ? $p['gelar_depan'] . ' ' : '';
                        $gelarBelakang = !empty($p['gelar_belakang']) ? ', ' . $p['gelar_belakang'] : '';
                        
                        return !empty($p['nama_dosen']) ? ($gelarDepan . $p['nama_dosen'] . $gelarBelakang) : ($p['nama_pejabat_teks'] ?? 'Menunggu SK');
                    }
                }
            }
        }
    }
    return "Menunggu Penetapan SK"; 
}

function getDekan($namaFakultas, $pimpinanList) {
    return getPejabat($namaFakultas, 'Dekanat', $pimpinanList);
}

function getKaprodi($namaProdi, $pimpinanList) {
    return getPejabat($namaProdi, 'Program Studi', $pimpinanList);
}

// 2. HELPER KONTEN (PENYESUAIAN AKREDITASI)
function enrichProdi($namaProdi) {
    $data = [
        // FTI (Unggul)
        'Informatika' => ['icon' => '💻', 'career' => 'Software Engineer, System Architect', 'salary' => 'Prospek Kompetitif (Sektor Tech)', 'matkul' => ['Algoritma & Struktur Data', 'Cloud Architecture', 'Machine Learning Dasar'], 'highlight' => 'Rekayasa Komputasi Modern', 'akreditasi' => 'Unggul', 'color' => '#3b82f6'],
        'Sistem Informasi' => ['icon' => '📊', 'career' => 'IT Business Analyst, Product Manager', 'salary' => 'Prospek Tinggi (Manajerial IT)', 'matkul' => ['Enterprise Architecture', 'Tata Kelola TI', 'Analisis Data Bisnis'], 'highlight' => 'Integrasi Bisnis & Teknologi', 'akreditasi' => 'Unggul', 'color' => '#10b981'],
        'Data Science' => ['icon' => '🧬', 'career' => 'Data Scientist, Data Analyst', 'salary' => 'High-Demand Industry', 'matkul' => ['Statistika Inferensial', 'Pemodelan Prediktif', 'Neural Networks'], 'highlight' => 'Analitik Data Skala Besar', 'akreditasi' => 'Unggul', 'color' => '#8b5cf6'],
        'Rekayasa Perangkat Lunak' => ['icon' => '⚙️', 'career' => 'DevOps Engineer, Full-Stack Developer', 'salary' => 'Prospek Kompetitif Global', 'matkul' => ['Manajemen Proyek PL', 'DevOps CI/CD', 'Keamanan Sistem'], 'highlight' => 'Standar Industri Global', 'akreditasi' => 'Unggul', 'color' => '#f59e0b'],
        
        // FSM (Unggul)
        'Matematika Terapan' => ['icon' => '📐', 'career' => 'Quantitative Analyst, Akademisi', 'salary' => 'Prospek Stabil (Riset/Finansial)', 'matkul' => ['Kriptografi', 'Riset Operasi', 'Matematika Keuangan'], 'highlight' => 'Fondasi Analitis Kuat', 'akreditasi' => 'Unggul', 'color' => '#3b82f6'],
        'Fisika Komputasi' => ['icon' => '🌌', 'career' => 'Research Scientist, Data Modeler', 'salary' => 'Berbasis Riset & Pengembangan', 'matkul' => ['Simulasi Material', 'Fisika Kuantum', 'Dinamika Fluida'], 'highlight' => 'Simulasi Tingkat Lanjut', 'akreditasi' => 'Unggul', 'color' => '#10b981'],
        'Statistika' => ['icon' => '📈', 'career' => 'Aktuaris, Market Researcher', 'salary' => 'High-Demand (Sektor Finansial)', 'matkul' => ['Sains Aktuaria', 'Analisis Risiko', 'Metode Survei'], 'highlight' => 'Keputusan Berbasis Data', 'akreditasi' => 'Unggul', 'color' => '#8b5cf6'],
        
        // FBM (A)
        'Manajemen' => ['icon' => '💼', 'career' => 'Business Strategist, Consultant', 'salary' => 'Prospek Manajerial Eksekutif', 'matkul' => ['Manajemen Strategik', 'Perilaku Organisasi', 'Pemasaran Global'], 'highlight' => 'Fokus Kepemimpinan', 'akreditasi' => 'A', 'color' => '#3b82f6'],
        'Akuntansi' => ['icon' => '🧾', 'career' => 'Auditor Profesional, Tax Analyst', 'salary' => 'Prospek Kompetitif (Sektor Finansial)', 'matkul' => ['Audit Forensik', 'Strategi Perpajakan', 'Sistem Informasi Akuntansi'], 'highlight' => 'Standar Finansial Modern', 'akreditasi' => 'A', 'color' => '#10b981'],
        'Bisnis Digital' => ['icon' => '🚀', 'career' => 'Tech Entrepreneur, Growth Strategist', 'salary' => 'Potensi Bervariasi (Kewirausahaan)', 'matkul' => ['Inkubasi Bisnis', 'Teknologi Finansial', 'Strategi E-Commerce'], 'highlight' => 'Kewirausahaan Digital', 'akreditasi' => 'A', 'color' => '#8b5cf6'],
        
        // FDMK (A)
        'Desain Komunikasi Visual' => ['icon' => '🎨', 'career' => 'Art Director, Brand Designer', 'salary' => 'Estimasi Kompetitif (Industri Kreatif)', 'matkul' => ['Identitas Merek', 'Tipografi Aplikatif', 'Metodologi Desain'], 'highlight' => 'Estetika & Komersial', 'akreditasi' => 'A', 'color' => '#ec4899'],
        'Animasi dan Media Digital' => ['icon' => '🎬', 'career' => '3D Animator, Visual Effect Artist', 'salary' => 'Potensi Bervariasi (Studio Global)', 'matkul' => ['Pemodelan 3D', 'Komposisi VFX', 'Sinematografi Digital'], 'highlight' => 'Standar Studio Internasional', 'akreditasi' => 'A', 'color' => '#8b5cf6'],
        'Desain Produk' => ['icon' => '📱', 'career' => 'UI/UX Designer, Industrial Designer', 'salary' => 'High-Demand (Sektor Kreatif/Tech)', 'matkul' => ['Desain UI/UX', 'Ergonomi', 'HCI (Human-Computer Interaction)'], 'highlight' => 'Desain Berpusat pada Manusia', 'akreditasi' => 'A', 'color' => '#3b82f6'],
    ];
    
    foreach ($data as $key => $val) {
        if (strcasecmp(trim($namaProdi), trim($key)) == 0 || stripos($namaProdi, $key) !== false) return $val;
    }
    return ['icon' => '🎓', 'career' => 'Peluang Karir Global', 'salary' => 'Potensi Pendapatan Kompetitif', 'matkul' => ['Teori Lanjutan', 'Proyek Praktik', 'Metodologi Riset'], 'highlight' => 'Program Keunggulan', 'akreditasi' => 'B', 'color' => '#64748b'];
}

// Penentuan Akreditasi Tingkat Fakultas untuk Info Bar
$akreditasiFakultas = (in_array($fakultas['keyword_db'], ['Teknologi', 'Sains'])) ? 'Unggul' : 'A';
?>

<style>
    /* 🌐 BASE SETTINGS (Clean & Fast) */
    html { scroll-behavior: smooth; }
    body { background-color: #0b0f19; color: #f8fafc; font-family: 'Inter', sans-serif; overflow-x: hidden; }

    /* 🎬 SCROLL REVEAL (Sederhana & Elegan) */
    .reveal { opacity: 0; transform: translateY(30px); transition: 0.8s ease-out; will-change: transform, opacity; }
    .reveal.active { opacity: 1; transform: translateY(0); }

    /* 🌌 HERO SECTION */
    .hero-fak { position: relative; padding: 160px 20px 80px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.05); overflow: hidden; background: linear-gradient(180deg, rgba(15,23,42,0.8) 0%, #0b0f19 100%); }
    .fac-icon-huge { font-size: 4rem; margin-bottom: 15px; }
    .hero-fak h1 { font-size: clamp(2rem, 4vw, 3.5rem); font-weight: 800; color: #fff; margin: 0 0 15px; letter-spacing: -0.5px; }
    .accent-text { color: #8b5cf6; }
    .hero-fak p { font-size: 1.1rem; color: #94a3b8; max-width: 800px; margin: 0 auto; line-height: 1.6; }

    /* 📊 INFO HIGHLIGHT BAR */
    .info-bar { max-width: 900px; margin: -40px auto 80px; background: #0f172a; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; display: grid; grid-template-columns: repeat(3, 1fr); box-shadow: 0 15px 30px rgba(0,0,0,0.2); position: relative; z-index: 10; }
    .info-item { padding: 20px; text-align: center; border-right: 1px solid rgba(255,255,255,0.05); }
    .info-item:last-child { border-right: none; }
    .info-title { color: #64748b; font-size: 0.8rem; text-transform: uppercase; font-weight: 700; letter-spacing: 1px; margin-bottom: 5px; }
    .info-val { color: #e2e8f0; font-size: 1.1rem; font-weight: 700; }
    @media(max-width: 768px) { .info-bar { grid-template-columns: 1fr; margin: 20px 20px 60px; } .info-item { border-right: none; border-bottom: 1px solid rgba(255,255,255,0.05); } }

    .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }

    /* 🏛️ SAMBUTAN DEKAN */
    .dekan-block { display: grid; grid-template-columns: 1fr 3fr; gap: 40px; background: rgba(255,255,255,0.01); border: 1px solid rgba(255,255,255,0.05); padding: 40px; border-radius: 20px; margin-bottom: 80px; align-items: start; }
    .dekan-photo-wrapper { text-align: center; }
    .dekan-photo { width: 100px; height: 100px; margin: 0 auto 15px; background: #1e293b; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; border: 2px solid rgba(255,255,255,0.1); }
    .dekan-quote { font-size: 1.1rem; color: #cbd5e1; line-height: 1.8; font-style: italic; border-left: 3px solid #8b5cf6; padding-left: 20px; text-align: justify; }
    @media(max-width: 768px) { .dekan-block { grid-template-columns: 1fr; text-align: center; } .dekan-quote { border-left: none; padding-left: 0; } }

    /* 📚 PROGRAM STUDI CARDS */
    .section-header { text-align: center; margin-bottom: 50px; }
    .section-title { font-size: 2rem; color: #fff; font-weight: 800; letter-spacing: -0.5px; margin-bottom: 10px; }
    
    .prodi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 30px; margin-bottom: 80px; }
    
    .prodi-card { background: #0f172a; border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; display: flex; flex-direction: column; transition: 0.3s ease; position: relative; overflow: hidden; }
    .prodi-card:hover { border-color: rgba(255,255,255,0.15); transform: translateY(-4px); box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
    
    .prodi-body { padding: 30px; flex: 1; position: relative; z-index: 2; }
    
    .p-header { display: flex; gap: 15px; align-items: flex-start; margin-bottom: 15px; }
    .p-icon { font-size: 2.2rem; line-height: 1; }
    .p-title-wrapper { flex: 1; }
    .p-name { font-size: 1.3rem; color: #fff; font-weight: 700; margin: 0 0 5px; line-height: 1.2; }
    .p-highlight { font-size: 0.8rem; font-weight: 600; color: #94a3b8; }
    
    .p-desc { color: #94a3b8; line-height: 1.6; font-size: 0.95rem; margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.05); }

    .focus-area { margin-bottom: 20px; }
    .fa-title { font-size: 0.8rem; color: #64748b; text-transform: uppercase; font-weight: 700; margin-bottom: 8px; }
    .career-text { color: #e2e8f0; font-weight: 600; font-size: 0.95rem; margin-bottom: 4px; }
    .salary-text { color: #10b981; font-size: 0.85rem; font-weight: 500;}
    
    .matkul-list { list-style: none; padding: 0; margin: 0; }
    .matkul-list li { color: #cbd5e1; font-size: 0.85rem; margin-bottom: 6px; display: flex; align-items: center; gap: 8px; }
    .matkul-list li::before { content: "•"; color: #8b5cf6; font-size: 1.2rem; }

    .prodi-footer { padding: 15px 30px; border-top: 1px solid rgba(255,255,255,0.05); background: rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: space-between; }
    .k-info { display: flex; align-items: center; gap: 12px; }
    .k-ava { width: 35px; height: 35px; background: #1e293b; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem; border: 1px solid rgba(255,255,255,0.1);}
    .k-name { color: #e2e8f0; font-weight: 600; font-size: 0.85rem; }
    .k-role { color: #64748b; font-size: 0.75rem; }

    /* Badge Akreditasi Premium */
    .badge-akreditasi { position: absolute; top: 20px; right: 20px; font-size: 0.7rem; font-weight: 700; padding: 4px 12px; border-radius: 50px; z-index: 5; text-transform: uppercase; letter-spacing: 0.5px; }
    .akr-unggul { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); }
    .akr-a { background: rgba(59, 130, 246, 0.1); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); }

    .cta-funnel { display: grid; grid-template-columns: 1fr 1fr; border-top: 1px solid rgba(255,255,255,0.05); position: relative; z-index: 5;}
    .btn-explore, .btn-apply { padding: 15px; text-align: center; font-size: 0.9rem; font-weight: 600; text-decoration: none; transition: 0.3s; }
    .btn-explore { color: #94a3b8; border-right: 1px solid rgba(255,255,255,0.05); }
    .btn-explore:hover { background: rgba(255,255,255,0.02); color: #fff; }
    .btn-apply { color: #8b5cf6; }
    .btn-apply:hover { background: rgba(139,92,246,0.05); color: #c084fc; }

    /* Partner Styles */
    .partner-section { padding: 60px 0; border-top: 1px solid rgba(255,255,255,0.05); text-align: center; margin-bottom: 80px; }
    .partner-track { display: flex; gap: 40px; justify-content: center; flex-wrap: wrap; margin-top: 30px; filter: grayscale(100%) opacity(0.5); transition: 0.4s; }
    .partner-track:hover { filter: grayscale(0%) opacity(1); }
    .partner-logo { font-size: 1.2rem; font-weight: 800; color: #fff; display: flex; align-items: center; gap: 10px; padding: 12px 25px; background: rgba(255,255,255,0.02); border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); }

</style>

<header class="hero-fak" id="hero-fakultas">
    <div class="reveal reveal-up">
        <div class="fac-icon-huge"><?= $fakultas['icon'] ?></div>
        <h1 class="hero-title">Fakultas <span class="accent-text"><?= $fakultas['nama'] ?></span></h1>
        <p><?= $fakultas['desc'] ?></p>
    </div>
</header>

<div class="info-bar reveal reveal-scale">
    <div class="info-item">
        <div class="info-title">Pendidikan</div>
        <div class="info-val"><?= $fakultas['strata'] ?></div>
    </div>
    <div class="info-item">
        <div class="info-title">Masa Studi (Normal)</div>
        <div class="info-val"><?= $fakultas['masa_studi'] ?></div>
    </div>
    <div class="info-item">
        <div class="info-title">Akreditasi Fakultas</div>
        <div class="info-val" style="color: <?= ($akreditasiFakultas == 'Unggul') ? '#10b981' : '#60a5fa' ?>;">BAN-PT <?= $akreditasiFakultas ?></div>
    </div>
</div>

<main class="container">

    <div class="dekan-block reveal">
        <div class="dekan-photo-wrapper">
            <div class="dekan-photo">👨‍🏫</div>
            <h3 style="color: #fff; margin: 0 0 5px; font-size: 1.1rem;">
                <?= getDekan($fakultas['keyword_db'], $pimpinan) ?>
            </h3>
            <div style="color: #64748b; font-size: 0.8rem; text-transform: uppercase; font-weight: 700;">Dekan Fakultas</div>
        </div>
        <div>
            <div class="dekan-quote"><?= $fakultas['sambutan'] ?></div>
        </div>
    </div>

    <div class="section-header reveal">
        <h2 class="section-title">Program Studi</h2>
        <p style="color: #94a3b8; font-size: 1.05rem;">Disusun secara terstruktur untuk memenuhi standar kompetensi industri global.</p>
    </div>

    <div class="prodi-grid">
        <?php foreach($fakultas['prodi'] as $index => $prodi): 
            $meta = enrichProdi($prodi['nama']); 
            $badgeClass = ($meta['akreditasi'] == 'Unggul') ? 'akr-unggul' : 'akr-a';
        ?>
        <div class="prodi-card reveal" style="transition-delay: <?= ($index % 3) * 0.1 ?>s;">
            <style>.prodi-card:nth-child(<?= $index + 1 ?>) .p-name { color: <?= $meta['color'] ?>; }</style>
            
            <div class="badge-akreditasi <?= $badgeClass ?>">Akreditasi: <?= $meta['akreditasi'] ?></div>

            <div class="prodi-body">
                <div class="p-header">
                    <div class="p-icon"><?= $meta['icon'] ?></div>
                    <div class="p-title-wrapper">
                        <h3 class="p-name"><?= $prodi['nama'] ?></h3>
                        <div class="p-highlight"><?= $meta['highlight'] ?></div>
                    </div>
                </div>
                
                <p class="p-desc"><?= $prodi['desc'] ?></p>
                
                <div class="focus-area">
                    <div class="fa-title">Prospek Lulusan</div>
                    <div class="career-text"><?= $meta['career'] ?></div>
                    <div class="salary-text"><?= $meta['salary'] ?></div>
                </div>
                
                <div class="focus-area">
                    <div class="fa-title">Mata Kuliah Inti</div>
                    <ul class="matkul-list">
                        <?php foreach($meta['matkul'] as $mk): ?>
                            <li><?= $mk ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            
            <div class="prodi-footer">
                <div class="k-info">
                    <div class="k-ava">👤</div>
                    <div>
                        <div class="k-name"><?= getKaprodi($prodi['nama'], $pimpinan) ?></div>
                        <div class="k-role">Kaprodi (<?= $prodi['sks'] ?> SKS)</div>
                    </div>
                </div>
            </div>
            
            <div class="cta-funnel">
                <a href="#kurikulum" class="btn-explore" onclick="alert('Fitur Silabus Segera Hadir.'); return false;">Detail Kurikulum</a>
                <a href="<?= base_url('login') ?>" class="btn-apply">Daftar Sekarang</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="partner-section reveal reveal-up">
        <h3 style="color: #fff; font-size: 1.5rem; margin-bottom: 10px;">Lulusan Kami Telah Bekerja Di</h3>
        <p style="color: #94a3b8;">Kurikulum diakui dan disertifikasi oleh pemimpin industri.</p>
        
        <div class="partner-track">
            <?php if($fakultas['keyword_db'] == 'Teknologi'): ?>
                <div class="partner-logo"><span>🌐</span> Google</div>
                <div class="partner-logo"><span>☁️</span> AWS</div>
                <div class="partner-logo"><span>📱</span> Apple</div>
            <?php elseif($fakultas['keyword_db'] == 'Bisnis'): ?>
                <div class="partner-logo"><span>🏦</span> McKinsey</div>
                <div class="partner-logo"><span>📈</span> Deloitte</div>
                <div class="partner-logo"><span>🛒</span> Shopee</div>
            <?php elseif($fakultas['keyword_db'] == 'Desain'): ?>
                <div class="partner-logo"><span>🎬</span> Pixar</div>
                <div class="partner-logo"><span>🎵</span> Spotify</div>
                <div class="partner-logo"><span>🎮</span> Sony Interactive</div>
            <?php else: ?>
                <div class="partner-logo"><span>🧬</span> BioFarma</div>
                <div class="partner-logo"><span>🔬</span> CERN</div>
                <div class="partner-logo"><span>📊</span> BPS</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="reveal" style="text-align: center; padding: 60px 20px; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 20px; margin-bottom: 100px;">
        <h2 style="color: #fff; font-size: 1.8rem; margin: 0 0 10px; font-weight: 700;">Tertarik Membangun Karir di Bidang Ini?</h2>
        <p style="color: #94a3b8; margin: 0 auto 30px; max-width: 600px; line-height: 1.6;">Akses portal akademik untuk informasi pendaftaran, rincian biaya studi, dan jadwal seleksi penerimaan mahasiswa baru.</p>
        <a href="<?= base_url('login') ?>" style="display: inline-block; padding: 12px 30px; background: #fff; color: #0f172a; text-decoration: none; border-radius: 8px; font-weight: 700; transition: 0.3s;">Menuju Portal Pendaftaran</a>
    </div>

</main>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Clean, simple reveal observer
        const revealObserver = new IntersectionObserver((entries, obs) => {
            entries.forEach(e => { 
                if (e.isIntersecting) { e.target.classList.add('active'); obs.unobserve(e.target); } 
            });
        }, { threshold: 0.1, rootMargin: "0px 0px -50px 0px" });
        
        document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));
    });
</script>

<?= $this->endSection() ?>