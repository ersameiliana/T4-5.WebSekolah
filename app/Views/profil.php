<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<?php
// --- Inisialisasi Variabel & Logika Pemisahan Data Pimpinan ---
$rektor = null; 
$wakil_rektor = [];

// Mengecek apakah data struktur_pimpinan dikirim dari Controller
if(isset($struktur_pimpinan) && !empty($struktur_pimpinan)) {
    foreach($struktur_pimpinan as $pimpinan) {
        if(isset($pimpinan['jabatan'])) {
            $jabatan = strtolower($pimpinan['jabatan']);
            if (strpos($jabatan, 'wakil rektor') !== false) { 
                $wakil_rektor[] = $pimpinan; 
            } elseif (strpos($jabatan, 'rektor') !== false) { 
                $rektor = $pimpinan; 
            }
        }
    }
}

// Helper khusus untuk profil
if (!function_exists('formatNamaPejabat')) {
    function formatNamaPejabat($p) {
        if (!empty($p['nama_dosen'])) { 
            $gelarDepan = !empty($p['gelar_depan']) ? $p['gelar_depan'].' ' : '';
            $gelarBelakang = !empty($p['gelar_belakang']) ? ', '.$p['gelar_belakang'] : '';
            return esc($gelarDepan . $p['nama_dosen'] . $gelarBelakang); 
        }
        return esc($p['nama_pejabat_teks'] ?? 'Menunggu SK');
    }
}
?>

<style>
    /* 🌐 Smooth Scroll & Clean Setup (No 3D Tilt, No Stars) */
    html { scroll-behavior: smooth; }
    body { background-color: #0b0f19; color: #f8fafc; font-family: 'Inter', sans-serif; overflow-x: hidden; }

    /* 🎬 Clean Reveals */
    .reveal { opacity: 0; transform: translateY(30px); transition: 0.8s ease-out; }
    .reveal.active { opacity: 1; transform: translateY(0); }

    /* 🌌 HEADER PROFIL FORMAL */
    .page-header { padding: 160px 20px 80px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.05); background: linear-gradient(180deg, #0f172a 0%, #0b0f19 100%); }
    .page-header h1 { font-size: 3.5rem; font-weight: 800; color: #fff; margin-bottom: 15px; letter-spacing: -1px; }
    .page-header p { color: #94a3b8; font-size: 1.1rem; max-width: 600px; margin: 0 auto; line-height: 1.6; }

    /* 💎 SECTION CONTAINERS */
    .content-section { padding: 80px 20px; max-width: 1050px; margin: 0 auto; }
    .section-title { color: #fff; font-size: 2rem; font-weight: 800; margin-bottom: 25px; border-left: 4px solid #8b5cf6; padding-left: 15px; }
    .text-body { color: #cbd5e1; line-height: 1.8; font-size: 1.05rem; }

    /* Grid Split */
    .split-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; margin-bottom: 80px; }
    @media (max-width: 768px) { .split-grid { grid-template-columns: 1fr; gap: 40px; } }

    /* 🧲 STATIC ELEGANT CARDS (Untuk Nilai Inti) */
    .values-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 80px; }
    .val-card { padding: 30px 20px; border-radius: 16px; background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.05); text-align: center; transition: 0.3s; }
    .val-card:hover { background: rgba(139,92,246,0.05); border-color: rgba(139,92,246,0.3); transform: translateY(-5px); }
    .val-card h3 { color: #fff; font-size: 1.1rem; margin: 15px 0 10px; }
    .val-card p { color: #94a3b8; font-size: 0.9rem; margin: 0; line-height: 1.5; }

    /* 🏛️ STRUKTUR ORGANISASI FORMAL */
    .org-box { background: rgba(15,23,42,0.6); border: 1px solid rgba(255,255,255,0.05); padding: 50px; border-radius: 24px; text-align: center; }
    .org-level-1 { display: flex; justify-content: center; margin-bottom: 40px; }
    .org-level-2 { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; }
    .org-card { padding: 20px; }
    .avatar { width: 80px; height: 80px; border-radius: 50%; background: #1e293b; margin: 0 auto 15px; border: 2px solid #8b5cf6; display: flex; align-items: center; justify-content: center; font-size: 2rem; }
    .org-card h4 { color: #8b5cf6; margin: 0 0 5px 0; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; }
    .org-card h3 { color: #fff; margin: 0; font-size: 1.1rem; font-weight: 700; }

    /* ⏳ TIMELINE SEJARAH REALISTIS */
    .timeline { position: relative; padding-left: 30px; border-left: 2px solid rgba(255,255,255,0.1); margin-top: 20px; }
    .timeline-item { position: relative; margin-bottom: 30px; }
    .timeline-dot { position: absolute; left: -39px; top: 0; width: 16px; height: 16px; border-radius: 50%; background: #0b0f19; border: 3px solid #8b5cf6; }
    .timeline-date { color: #fff; font-weight: 700; font-size: 1.1rem; margin-bottom: 5px; }
    .timeline-content { color: #94a3b8; font-size: 0.95rem; line-height: 1.6; }

    /* QUICK FACTS */
    .facts-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; background: rgba(255,255,255,0.02); padding: 40px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.05); margin-bottom: 60px; text-align: center; }
    .fact-num { font-size: 2.5rem; font-weight: 800; color: #fff; margin-bottom: 5px; }
    .fact-label { font-size: 0.85rem; color: #64748b; text-transform: uppercase; font-weight: 700; }
    @media (max-width: 768px) { .facts-grid { grid-template-columns: 1fr 1fr; } }
</style>

<section class="page-header">
    <div class="reveal">
        <h1>Profil Astryveil Academy</h1>
        <p>Institusi pendidikan tinggi terkemuka yang berdedikasi pada pengembangan ilmu pengetahuan, teknologi, dan karakter manusia unggul.</p>
    </div>
</section>

<section class="content-section">

    <div class="facts-grid reveal">
        <div><div class="fact-num">2008</div><div class="fact-label">Tahun Berdiri</div></div>
        <div><div class="fact-num">4</div><div class="fact-label">Fakultas Utama</div></div>
        <div><div class="fact-num">A</div><div class="fact-label">Akreditasi BAN-PT</div></div>
        <div><div class="fact-num">50+</div><div class="fact-label">Mitra Industri</div></div>
    </div>

    <div class="split-grid">
        <div class="reveal">
            <h2 class="section-title">Sejarah Institusi</h2>
            <p class="text-body">
                Astryveil Academy didirikan pada tahun 2008 dengan komitmen untuk menyelenggarakan pendidikan tinggi yang relevan dengan perkembangan zaman. Berawal dari institut sains kecil, Astryveil telah bertransformasi menjadi universitas komprehensif.
            </p>
            <div class="timeline">
                <div class="timeline-item"><div class="timeline-dot"></div><div class="timeline-date">2008</div><div class="timeline-content">Pendirian resmi Astryveil Academy.</div></div>
                <div class="timeline-item"><div class="timeline-dot"></div><div class="timeline-date">2015</div><div class="timeline-content">Perluasan fakultas dan perolehan Akreditasi A.</div></div>
                <div class="timeline-item"><div class="timeline-dot" style="background:#8b5cf6;"></div><div class="timeline-date">Sekarang</div><div class="timeline-content" style="color:#cbd5e1;">Pusat riset dan inovasi teknologi bereputasi nasional.</div></div>
            </div>
        </div>
        <div class="reveal">
            <h2 class="section-title">Sambutan Rektor</h2>
            <p class="text-body" style="font-style: italic; background: rgba(255,255,255,0.02); padding: 20px; border-radius: 12px; border-left: 3px solid #8b5cf6; text-align: justify;">
                "Pendidikan bukan sekadar proses transfer ilmu, melainkan proses memahami potensi—baik potensi diri sendiri maupun orang lain. Di universitas ini, kami merancang sistem yang memungkinkan setiap mahasiswa berkembang dengan caranya masing-masing, tanpa kehilangan arah. Karena masa depan tidak dibentuk oleh satu jenis kecerdasan, tetapi oleh keberagaman cara berpikir yang saling melengkapi."
            </p>
            <div style="margin-top: 15px;">
                <strong style="color: #fff; font-size: 1.1rem;"><?php echo $rektor ? formatNamaPejabat($rektor) : "Prof. Dr. Nezu, M.Pd."; ?></strong><br>
                <span style="color: #64748b; font-size: 0.9rem;">Rektor Astryveil Academy</span>
            </div>
        </div>
    </div>

    <div class="split-grid" style="margin-bottom: 40px;">
        <div class="reveal">
            <h2 class="section-title">Visi</h2>
            <p class="text-body">Menjadi universitas riset kelas dunia yang mengintegrasikan teknologi, sains, dan bisnis untuk menghasilkan lulusan yang berintegritas dan berdaya saing global pada tahun 2035.</p>
        </div>
        <div class="reveal">
            <h2 class="section-title">Misi Utama</h2>
            <ul style="color: #cbd5e1; line-height: 1.8; padding-left: 20px;">
                <li>Menyelenggarakan Tridharma Perguruan Tinggi yang inovatif dan berkualitas.</li>
                <li>Menghasilkan riset terapan yang memberikan solusi nyata bagi industri.</li>
                <li>Membangun tata kelola universitas yang transparan (Good University Governance).</li>
            </ul>
        </div>
    </div>

    <h2 class="section-title reveal" style="text-align: center; border: none;">Nilai Inti Institusi</h2>
    <div class="values-grid reveal">
        <div class="val-card"><div style="font-size: 2rem;">🛡️</div><h3>Integritas</h3><p>Kejujuran dan etika dalam setiap aktivitas Tridharma.</p></div>
        <div class="val-card"><div style="font-size: 2rem;">💡</div><h3>Inovasi</h3><p>Berpikir kreatif untuk menemukan solusi baru.</p></div>
        <div class="val-card"><div style="font-size: 2rem;">🤝</div><h3>Kolaborasi</h3><p>Sinergi multidisiplin antar sivitas akademika.</p></div>
        <div class="val-card"><div style="font-size: 2rem;">⭐</div><h3>Keunggulan</h3><p>Standar kualitas tinggi dalam pelayanan akademik.</p></div>
    </div>

    <div class="org-box reveal">
        <h2 class="section-title" style="border:none; margin-bottom: 40px;">Struktur Pimpinan Universitas</h2>
        <div class="org-level-1">
            <div class="org-card">
                <div class="avatar">👤</div>
                <h4>Rektor</h4>
                <h3><?php echo $rektor ? formatNamaPejabat($rektor) : "Prof. Dr. Nezu, M.Pd."; ?></h3>
            </div>
        </div>
        <div class="org-level-2">
            <?php if(!empty($wakil_rektor)): ?>
                <?php foreach($wakil_rektor as $wr): ?>
                    <div class="org-card">
                        <div class="avatar" style="width: 60px; height: 60px; font-size: 1.5rem;">👤</div>
                        <h4><?= esc($wr['jabatan'] ?? 'Wakil Rektor') ?></h4>
                        <h3><?= formatNamaPejabat($wr) ?></h3>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="org-card"><div class="avatar" style="width: 60px; height: 60px; font-size: 1.5rem;">👤</div><h4>Wakil Rektor Akademik</h4><h3>Dr. Livia Astrana, M.Ed.</h3></div>
                <div class="org-card"><div class="avatar" style="width: 60px; height: 60px; font-size: 1.5rem;">👤</div><h4>Wakil Rektor Kemahasiswaan</h4><h3>Dr. Marcus Elvarion, Ph.D.</h3></div>
            <?php endif; ?>
        </div>
    </div>

</section>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(e => { if(e.isIntersecting) { e.target.classList.add("active"); obs.unobserve(e.target); } });
        }, { threshold: 0.1, rootMargin: "0px 0px -50px 0px" });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    });
</script>

<?= $this->endSection() ?>