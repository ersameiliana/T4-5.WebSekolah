<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<style>
    /* 🌐 BASE SETTINGS */
    html { scroll-behavior: smooth; }
    body { background-color: #0b0f19; color: #f8fafc; font-family: 'Inter', sans-serif; overflow-x: hidden; }

    /* 🌌 HERO SECTION */
    .hero-daftar { position: relative; padding: 160px 20px 60px; text-align: center; overflow: hidden; border-bottom: 1px solid rgba(255,255,255,0.05); background: linear-gradient(180deg, rgba(15,23,42,0.8) 0%, #0b0f19 100%); }
    .hero-daftar h1 { font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 900; color: #fff; margin: 0 0 15px; letter-spacing: -1px; }
    .hero-daftar p { font-size: 1.1rem; color: #94a3b8; max-width: 600px; margin: 0 auto; line-height: 1.6; }

    /* 🗂️ JALUR PENDAFTARAN CARDS */
    .jalur-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 30px; max-width: 1100px; margin: 60px auto 100px; padding: 0 20px; }
    @media (max-width: 768px) { .jalur-grid { grid-template-columns: 1fr; } }
    
    .jalur-card { background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255,255,255,0.05); border-radius: 20px; padding: 40px; display: flex; flex-direction: column; transition: 0.3s ease; position: relative; overflow: hidden; }
    .jalur-card:hover { transform: translateY(-5px); border-color: rgba(139,92,246,0.3); box-shadow: 0 15px 35px rgba(0,0,0,0.2); background: rgba(15, 23, 42, 0.9); }
    
    /* Header Card (Badge & Icon) */
    .jc-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; }
    .jalur-icon { font-size: 2.8rem; line-height: 1; }
    
    .badge-group { display: flex; flex-direction: column; gap: 8px; align-items: flex-end; }
    .badge { padding: 4px 12px; border-radius: 50px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .b-status { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); }
    .b-diff-umum { background: rgba(59, 130, 246, 0.1); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); }
    .b-diff-kompetitif { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); }
    .b-diff-terbatas { background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3); }

    /* Content Card */
    .jalur-title { font-size: 1.5rem; color: #fff; font-weight: 800; margin: 0 0 10px; }
    .jalur-desc { color: #94a3b8; font-size: 0.95rem; line-height: 1.6; margin-bottom: 20px; }
    
    /* Micro UX (Selling Points) */
    .micro-list { list-style: none; padding: 0; margin: 0 0 25px 0; flex: 1; }
    .micro-list li { color: #cbd5e1; font-size: 0.9rem; margin-bottom: 8px; display: flex; align-items: center; gap: 10px; }
    .micro-list li::before { content: "✔"; color: #8b5cf6; font-weight: bold; }

    /* Deadline Box */
    .deadline-box { background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.03); border-radius: 12px; padding: 15px; margin-bottom: 25px; display: flex; align-items: center; gap: 15px; }
    .dl-icon { font-size: 1.5rem; }
    .dl-text { font-size: 0.85rem; color: #94a3b8; }
    .dl-date { font-weight: 700; color: #e2e8f0; font-size: 0.95rem; }

    /* Specific CTAs */
    .btn-jalur { display: block; text-align: center; padding: 15px; border-radius: 12px; font-weight: 700; font-size: 0.95rem; text-decoration: none; transition: 0.3s; }
    .btn-primary { background: #8b5cf6; color: #fff; }
    .btn-primary:hover { background: #7c3aed; box-shadow: 0 5px 15px rgba(139,92,246,0.3); }
    .btn-outline { background: transparent; color: #8b5cf6; border: 1px solid #8b5cf6; }
    .btn-outline:hover { background: rgba(139,92,246,0.1); }

    /* 🎬 ANIMATION */
    .reveal { opacity: 0; transform: translateY(30px); transition: 0.8s ease-out; }
    .reveal.active { opacity: 1; transform: translateY(0); }
</style>

<header class="hero-daftar reveal">
    <h1 style="margin-top: 20px;">Penerimaan Mahasiswa Baru</h1>
    <p>Pilih jalur pendaftaran yang sesuai dengan kualifikasi dan kebutuhan Anda. Bergabunglah menjadi bagian dari Astryveil Academy.</p>
</header>

<main>
    <div class="jalur-grid">
        
        <div class="jalur-card reveal">
            <div class="jc-header">
                <div class="jalur-icon">📝</div>
                <div class="badge-group">
                    <span class="badge b-status">Dibuka</span>
                    <span class="badge b-diff-umum">🟢 Kuota Umum</span>
                </div>
            </div>
            <h2 class="jalur-title">Jalur Reguler (Tes Online)</h2>
            <p class="jalur-desc">Jalur utama penerimaan mahasiswa baru melalui Computer Based Test (CBT) yang dapat diikuti oleh seluruh lulusan SMA/SMK/Sederajat.</p>
            
            <ul class="micro-list">
                <li>Tes dilakukan 100% online dari rumah</li>
                <li>Terbuka untuk semua tahun lulusan</li>
                <li>Pilihan hingga 3 program studi</li>
            </ul>

            <div class="deadline-box">
                <div class="dl-icon">⏳</div>
                <div>
                    <div class="dl-text">Gelombang 1 Berakhir pada:</div>
                    <div class="dl-date">30 Mei 2026</div>
                </div>
            </div>

            <a href="<?= base_url('daftar') ?>" target="_blank" class="btn-jalur btn-primary">Ikuti Tes Sekarang</a>
        </div>

        <div class="jalur-card reveal" style="transition-delay: 0.1s;">
            <div class="jc-header">
                <div class="jalur-icon">🏆</div>
                <div class="badge-group">
                    <span class="badge b-status">Dibuka</span>
                    <span class="badge b-diff-kompetitif">🔥 Sangat Kompetitif</span>
                </div>
            </div>
            <h2 class="jalur-title">Jalur Prestasi & Beasiswa</h2>
            <p class="jalur-desc">Jalur khusus tanpa tes bagi siswa berprestasi akademik (rapor) maupun non-akademik (olahraga, seni, hafidz).</p>
            
            <ul class="micro-list">
                <li>Tanpa tes tertulis (Seleksi berkas)</li>
                <li>Potensi beasiswa kuliah hingga 100%</li>
                <li>Bebas biaya formulir pendaftaran</li>
            </ul>

            <div class="deadline-box">
                <div class="dl-icon">📅</div>
                <div>
                    <div class="dl-text">Batas Akhir Pengajuan:</div>
                    <div class="dl-date">15 April 2026</div>
                </div>
            </div>

            <a href="<?= base_url('daftar') ?>" target="_blank" class="btn-jalur btn-outline">Ajukan Berkas Kelayakan</a>
        </div>

        <div class="jalur-card reveal">
            <div class="jc-header">
                <div class="jalur-icon">💼</div>
                <div class="badge-group">
                    <span class="badge b-status">Dibuka</span>
                    <span class="badge b-diff-terbatas">⭐ Kuota Terbatas</span>
                </div>
            </div>
            <h2 class="jalur-title">Kelas Eksekutif / RPL</h2>
            <p class="jalur-desc">Program Rekognisi Pembelajaran Lampau (RPL) khusus bagi profesional yang ingin melanjutkan studi dengan mengonversi pengalaman kerja.</p>
            
            <ul class="micro-list">
                <li>Jadwal kuliah fleksibel (Malam/Akhir Pekan)</li>
                <li>Konversi pengalaman kerja menjadi SKS</li>
                <li>Masa studi jauh lebih cepat</li>
            </ul>

            <div class="deadline-box">
                <div class="dl-icon">🔄</div>
                <div>
                    <div class="dl-text">Sistem Penerimaan:</div>
                    <div class="dl-date">Rolling Admission (Sepanjang Tahun)</div>
                </div>
            </div>

            <a href="<?= base_url('daftar') ?>" target="_blank" class="btn-jalur btn-outline">Konsultasi Penyetaraan SKS</a>
        </div>

        <div class="jalur-card reveal" style="transition-delay: 0.1s;">
            <div class="jc-header">
                <div class="jalur-icon">🌍</div>
                <div class="badge-group">
                    <span class="badge b-status" style="background: rgba(255,255,255,0.05); color:#94a3b8; border-color: rgba(255,255,255,0.1);">Segera Hadir</span>
                    <span class="badge" style="background: rgba(236, 72, 153, 0.1); color: #ec4899; border: 1px solid rgba(236, 72, 153, 0.3);">💎 Eksklusif</span>
                </div>
            </div>
            <h2 class="jalur-title">Kelas Internasional</h2>
            <p class="jalur-desc">Program Double Degree atau Student Exchange bekerjasama dengan universitas mitra di Asia, Eropa, dan Australia.</p>
            
            <ul class="micro-list">
                <li>Pengantar kuliah 100% Bahasa Inggris</li>
                <li>Kesempatan lulus dengan dua gelar (Double Degree)</li>
                <li>Wajib memiliki sertifikat TOEFL/IELTS</li>
            </ul>

            <div class="deadline-box">
                <div class="dl-icon">✈️</div>
                <div>
                    <div class="dl-text">Intake Perkuliahan:</div>
                    <div class="dl-date">Fall Semester (Agustus 2026)</div>
                </div>
            </div>

            <a href="<?= base_url('daftar') ?>" target="_blank" class="btn-jalur btn-outline">Lihat Persyaratan</a>
        </div>

    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const revealObserver = new IntersectionObserver((entries, obs) => {
            entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('active'); obs.unobserve(e.target); } });
        }, { threshold: 0.1, rootMargin: "0px 0px -50px 0px" });
        document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));
    });
</script>

<?= $this->endSection() ?>