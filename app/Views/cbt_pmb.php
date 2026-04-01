<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<style>
    body { background-color: #0f172a; color: #f8fafc; font-family: 'Inter', sans-serif; }
    .cbt-container { max-width: 800px; margin: 100px auto 80px; padding: 40px; background: rgba(30, 41, 59, 0.8); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; box-shadow: 0 20px 50px rgba(0,0,0,0.5); }
    
    .cbt-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px dashed rgba(255,255,255,0.1); padding-bottom: 20px; margin-bottom: 30px; }
    .cbt-title { font-size: 1.5rem; font-weight: 800; color: #fff; margin: 0; display: flex; align-items: center; gap: 10px; }
    .timer { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; padding: 10px 20px; border-radius: 12px; font-weight: 800; font-size: 1.2rem; font-family: monospace; }

    .soal-box { margin-bottom: 30px; }
    .soal-text { font-size: 1.1rem; font-weight: 600; color: #e2e8f0; margin-bottom: 15px; line-height: 1.6; }
    
    .opsi-label { display: block; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1); padding: 15px 20px; border-radius: 12px; margin-bottom: 10px; cursor: pointer; transition: 0.3s; color: #cbd5e1; font-weight: 500; }
    .opsi-label:hover { background: rgba(139, 92, 246, 0.1); border-color: rgba(139, 92, 246, 0.5); }
    input[type="radio"] { margin-right: 15px; transform: scale(1.2); accent-color: #8b5cf6; }

    .btn-submit { width: 100%; padding: 18px; border-radius: 12px; background: linear-gradient(135deg, #ef4444, #b91c1c); border: none; color: #fff; font-weight: 800; font-size: 1.1rem; cursor: pointer; transition: 0.3s; margin-top: 20px; }
    .btn-submit:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(239, 68, 68, 0.4); }
</style>

<div class="cbt-container">
    <div class="cbt-header">
        <h1 class="cbt-title"><span>📝</span> Ujian Seleksi TPA</h1>
        <div class="timer" id="countdown">89:59</div>
    </div>

    <form action="<?= base_url('pmb/cbt/proses') ?>" method="POST">
        <?= csrf_field() ?>
        
        <div class="soal-box">
            <div class="soal-text">1. Jika semua mahasiswa rajin belajar, maka mereka lulus ujian. Budi adalah mahasiswa yang rajin belajar. Kesimpulan yang tepat adalah...</div>
            <label class="opsi-label"><input type="radio" name="soal_1" value="A"> A. Budi mungkin lulus ujian.</label>
            <label class="opsi-label"><input type="radio" name="soal_1" value="B"> B. Budi pasti lulus ujian.</label>
            <label class="opsi-label"><input type="radio" name="soal_1" value="C"> C. Budi tidak lulus ujian.</label>
            <label class="opsi-label"><input type="radio" name="soal_1" value="D"> D. Belum tentu Budi lulus ujian.</label>
        </div>

        <div class="soal-box">
            <div class="soal-text">2. Berapakah hasil dari 25% dari 2.000 + 500?</div>
            <label class="opsi-label"><input type="radio" name="soal_2" value="A"> A. 750</label>
            <label class="opsi-label"><input type="radio" name="soal_2" value="B"> B. 800</label>
            <label class="opsi-label"><input type="radio" name="soal_2" value="C"> C. 1.000</label>
            <label class="opsi-label"><input type="radio" name="soal_2" value="D"> D. 1.500</label>
        </div>

        <button type="submit" class="btn-submit" onclick="return confirm('Apakah Anda yakin ingin mengumpulkan jawaban sekarang?')">KUMPULKAN JAWABAN ✔</button>
    </form>
</div>

<script>
    // Script simulasi countdown timer
    let time = 5399; // 89 menit 59 detik
    const timerEl = document.getElementById('countdown');
    
    setInterval(() => {
        let minutes = Math.floor(time / 60);
        let seconds = time % 60;
        seconds = seconds < 10 ? '0' + seconds : seconds;
        timerEl.innerHTML = `${minutes}:${seconds}`;
        time--;
        if (time < 0) { time = 0; alert('Waktu Habis!'); document.forms[0].submit(); }
    }, 1000);
</script>

<?= $this->endSection() ?>