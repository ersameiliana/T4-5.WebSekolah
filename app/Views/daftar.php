<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Form Pendaftaran | Astryveil Academy' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* 🌐 BASE & GLOBAL */
        body { 
            margin: 0; font-family: 'Inter', sans-serif; 
            background: #0b0f19; color: #fff; min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            background-image: radial-gradient(circle at top left, rgba(139,92,246,0.15), transparent 40%),
                              radial-gradient(circle at bottom right, rgba(59,130,246,0.15), transparent 40%);
        }
        
        .register-wrapper {
            width: 100%; max-width: 600px; padding: 20px; box-sizing: border-box;
        }

        /* 🏛️ BRAND & URGENCY */
        .brand-header { text-align: center; margin-bottom: 20px; }
        .brand-logo { font-size: 1.5rem; font-weight: 800; color: #fff; letter-spacing: -0.5px; display: inline-flex; align-items: center; gap: 8px; }
        
        .urgency-banner {
            background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3);
            color: #f59e0b; padding: 10px 15px; border-radius: 12px; font-size: 0.85rem; font-weight: 600;
            text-align: center; margin-bottom: 25px; display: flex; align-items: center; justify-content: center; gap: 8px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4); } 70% { box-shadow: 0 0 0 10px rgba(245, 158, 11, 0); } 100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); } }

        /* 🗂️ FORM CONTAINER */
        .form-container {
            background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(25px); -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255,255,255,0.08); border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4); padding: 40px; position: relative;
        }

        .header-form { text-align: center; margin-bottom: 30px; }
        .header-form h1 { font-size: 1.8rem; font-weight: 800; margin: 0 0 10px; }
        .header-form p { color: #94a3b8; font-size: 0.95rem; margin: 0; line-height: 1.5; }

        /* 🔥 ALERTS NOTIFICATION */
        .alert { padding: 12px 16px; border-radius: 10px; font-size: 0.9rem; font-weight: 500; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .alert-error { background: rgba(220, 38, 38, 0.1); border: 1px solid rgba(220, 38, 38, 0.3); color: #fca5a5; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px; }
        @media(max-width: 500px) { .form-row { grid-template-columns: 1fr; gap: 0; } .form-row .form-group { margin-bottom: 20px; } }

        .form-group { margin-bottom: 20px; position: relative; }
        .form-group label { display: block; font-size: 0.8rem; font-weight: 700; color: #cbd5e1; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
        
        .form-control {
            width: 100%; padding: 14px 15px; border-radius: 12px;
            background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1);
            color: #fff; font-family: 'Inter', sans-serif; font-size: 0.95rem;
            box-sizing: border-box; transition: 0.3s;
        }
        .form-control:focus { outline: none; border-color: #8b5cf6; background: rgba(139,92,246,0.05); box-shadow: 0 0 15px rgba(139,92,246,0.2); }
        .form-control::placeholder { color: #475569; }

        select.form-control { appearance: none; cursor: pointer; }
        select.form-control option { background: #0f172a; color: #fff; padding: 10px; }
        select.form-control optgroup { background: #0b0f19; color: #8b5cf6; font-weight: 700; }

        /* 🔥 PASSWORD STRENGTH METER */
        .pw-strength { display: flex; gap: 5px; margin-top: 10px; }
        .pw-bar { height: 4px; flex: 1; border-radius: 2px; background: rgba(255,255,255,0.1); transition: 0.3s; }
        .pw-text { font-size: 0.75rem; font-weight: 600; text-align: right; margin-top: 5px; transition: 0.3s; }

        /* 🎛️ BUTTONS & NAVIGATION */
        .btn-submit {
            width: 100%; padding: 16px; border-radius: 12px; margin-top: 10px;
            background: linear-gradient(135deg, #8b5cf6, #3b82f6); border: none;
            color: #fff; font-weight: 800; font-size: 1.05rem; cursor: pointer;
            transition: 0.3s; box-shadow: 0 5px 15px rgba(139,92,246,0.3);
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(139,92,246,0.5); }

        /* 🔒 TRUST SIGNALS */
        .trust-signal { text-align: center; margin-top: 25px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.05); }
        .trust-text { color: #64748b; font-size: 0.8rem; font-weight: 500; display: flex; align-items: center; justify-content: center; gap: 6px; margin-bottom: 5px; }
        .trust-subtext { color: #475569; font-size: 0.7rem; }
        
        .back-link-top { display: inline-flex; align-items: center; gap: 5px; color: #64748b; font-size: 0.85rem; font-weight: 600; text-decoration: none; margin-bottom: 20px; transition: 0.3s; }
        .back-link-top:hover { color: #fff; }

    </style>
</head>
<body>

    <div class="register-wrapper">
        
        <div class="brand-header">
            <div class="brand-logo">✨ Astryveil Academy</div>
        </div>

        <div class="urgency-banner">
            <span>⏳</span> Gelombang 1 Pendaftaran Ditutup Dalam 12 Hari!
        </div>

        <a href="<?= base_url('pendaftaran') ?>" class="back-link-top">← Lihat Informasi Jalur Masuk</a>

        <div class="form-container">
            <div class="header-form">
                <h1>Formulir Pendaftaran</h1>
                <p>Lengkapi data di bawah ini untuk membuat akun PMB Astryveil Academy.</p>
            </div>

            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-error">⚠️ <?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <form id="pmbForm" action="<?= base_url('auth/register_pmb') ?>" method="POST" onsubmit="return validateForm()">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label>Nama Lengkap (Sesuai Ijazah)</label>
                    <input type="text" name="nama" class="form-control" id="inputNama" value="<?= old('nama') ?>" placeholder="Contoh: Budi Santoso" required>
                </div>

                <div class="form-row">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Alamat Email Aktif</label>
                        <input type="email" name="email" class="form-control" id="inputEmail" value="<?= old('email') ?>" placeholder="Budi@gmail.com" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Nomor WhatsApp</label>
                        <input type="tel" name="telepon" class="form-control" id="inputWA" value="<?= old('telepon') ?>" placeholder="+62 8..." required oninput="formatWA(this)">
                    </div>
                </div>

                <div class="form-group">
                    <label>Asal Sekolah</label>
                    <input type="text" name="asal_sekolah" class="form-control" id="inputSekolah" value="<?= old('asal_sekolah') ?>" placeholder="SMA/SMK/MA..." required>
                </div>

                <div class="form-group">
                    <label>Jalur Pendaftaran</label>
                    <select name="jalur" class="form-control" id="inputJalur" required>
                        <option value="" disabled <?= !old('jalur') ? 'selected' : '' ?>>-- Pilih Jalur Seleksi --</option>
                        <option value="Reguler" <?= old('jalur') == 'Reguler' ? 'selected' : '' ?>>Jalur Reguler (Tes Online CBT - Semua Siswa)</option>
                        <option value="Prestasi" <?= old('jalur') == 'Prestasi' ? 'selected' : '' ?>>Jalur Prestasi (Tanpa Tes - Rapor/Sertifikat)</option>
                        <option value="RPL" <?= old('jalur') == 'RPL' ? 'selected' : '' ?>>Jalur Kelas Eksekutif/RPL (Khusus Profesional/Karyawan)</option>
                        <option value="Internasional" <?= old('jalur') == 'Internasional' ? 'selected' : '' ?>>Kelas Internasional (Double Degree Program)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Pilihan Program Studi Utama</label>
                    <select name="prodi" class="form-control" id="inputProdi" required>
                        <option value="" disabled <?= !old('prodi') ? 'selected' : '' ?>>-- Pilih Program Studi --</option>
                        <?php 
                        $currentFakultas = '';
                        foreach($prodi_list as $prodi): 
                            if ($currentFakultas != $prodi['fakultas']): 
                                if ($currentFakultas != '') echo '</optgroup>';
                                $currentFakultas = $prodi['fakultas'];
                                echo '<optgroup label="' . esc($currentFakultas) . '">';
                            endif;
                            $isSelected = (old('prodi') == $prodi['nama_prodi']) ? 'selected' : '';
                        ?>
                            <option value="<?= esc($prodi['nama_prodi']) ?>" <?= $isSelected ?>>
                                <?= esc($prodi['nama_prodi']) ?> (<?= esc($prodi['strata']) ?>)
                            </option>
                        <?php endforeach; ?>
                        <?php if($currentFakultas != '') echo '</optgroup>'; ?>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Buat Kata Sandi</label>
                        <input type="password" name="password" class="form-control" id="inputPW" placeholder="Minimal 8 karakter" required onkeyup="checkStrength()">
                        <div class="pw-strength">
                            <div class="pw-bar" id="bar1"></div>
                            <div class="pw-bar" id="bar2"></div>
                            <div class="pw-bar" id="bar3"></div>
                        </div>
                        <div class="pw-text" id="pwStatus" style="color: #64748b;">Kekuatan Sandi</div>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Konfirmasi Kata Sandi</label>
                        <input type="password" class="form-control" id="inputPWConfirm" placeholder="Ulangi kata sandi" required>
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="btnSubmit">Selesaikan Pendaftaran 🚀</button>
            </form>

            <div class="trust-signal">
                <div class="trust-text"><span>🔒</span> Data Anda dilindungi enkripsi SSL 256-bit</div>
                <div class="trust-subtext">Diproses langsung oleh Sistem Akademik Pusat Astryveil Academy</div>
            </div>
        </div>
    </div>

    <script>
        function validateForm() {
            const pw = document.getElementById('inputPW').value;
            const pwc = document.getElementById('inputPWConfirm').value;
            if(pw !== pwc) {
                alert("⚠️ Konfirmasi kata sandi tidak cocok!");
                return false;
            }
            if(pw.length < 8) {
                alert("⚠️ Kata sandi minimal 8 karakter!");
                return false;
            }
            return true;
        }

        // Auto-Format Phone Number (+62)
        function formatWA(input) {
            let val = input.value.replace(/\D/g, ''); // Hapus semua kecuali angka
            if(val.startsWith('0')) val = '62' + val.substring(1); // Ubah 0 jadi 62
            if(val.startsWith('62')) {
                input.value = '+' + val;
            } else {
                input.value = val ? '+' + val : '';
            }
        }

        // Password Strength Meter
        function checkStrength() {
            const pw = document.getElementById('inputPW').value;
            const b1 = document.getElementById('bar1');
            const b2 = document.getElementById('bar2');
            const b3 = document.getElementById('bar3');
            const status = document.getElementById('pwStatus');
            
            let strength = 0;
            if(pw.length >= 8) strength++; // Punya length
            if(pw.match(/[A-Z]/) && pw.match(/[0-9]/)) strength++; // Kombinasi huruf besar & angka
            if(pw.match(/[^a-zA-Z0-9]/)) strength++; // Punya simbol

            // Reset
            b1.style.background = b2.style.background = b3.style.background = 'rgba(255,255,255,0.1)';
            
            if(pw.length === 0) {
                status.innerText = "Kekuatan Sandi";
                status.style.color = "#64748b";
            } else if(strength === 1) {
                b1.style.background = '#ef4444'; // Red (Weak)
                status.innerText = "Lemah";
                status.style.color = "#ef4444";
            } else if(strength === 2) {
                b1.style.background = b2.style.background = '#f59e0b'; // Yellow (Medium)
                status.innerText = "Sedang";
                status.style.color = "#f59e0b";
            } else if(strength === 3) {
                b1.style.background = b2.style.background = b3.style.background = '#10b981'; // Green (Strong)
                status.innerText = "Sangat Kuat";
                status.style.color = "#10b981";
            }
        }
    </script>

</body>
</html>