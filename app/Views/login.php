<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<style>
    /* 🌌 Clean & Deep Background */
    body {
        background-color: #0f172a; /* Slate 900 */
        color: #f8fafc;
    }

    .auth-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        position: relative;
        z-index: 1;
    }

    /* Subtle Ambient Glow (Not messy blobs) */
    .auth-wrapper::before {
        content: '';
        position: absolute;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(79, 70, 229, 0.15) 0%, transparent 70%);
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: -1;
        pointer-events: none;
    }

    /* 💎 Neumorphic / Glass Card */
    .auth-card {
        width: 100%;
        max-width: 440px;
        background: rgba(30, 41, 59, 0.6); /* Slate 800 with transparency */
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        animation: cardEnter 0.6s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    @keyframes cardEnter {
        from { opacity: 0; transform: translateY(20px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* Header Typography */
    .auth-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .auth-header h2 {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0 0 8px 0;
        color: #ffffff;
        letter-spacing: -0.025em;
        cursor: default; /* Easter egg trigger */
    }

    .auth-header p {
        color: #94a3b8; /* Slate 400 */
        font-size: 0.95rem;
        margin: 0;
    }

    /* 🎛️ Elegant Tab Switcher */
    .tabs-container {
        display: flex;
        background: #0f172a; /* Slate 900 */
        padding: 4px;
        border-radius: 12px;
        margin-bottom: 25px;
        border: 1px solid rgba(255,255,255,0.05);
    }

    .tab-btn {
        flex: 1;
        padding: 10px;
        text-align: center;
        font-size: 0.9rem;
        font-weight: 600;
        color: #64748b; /* Slate 500 */
        background: transparent;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .tab-btn:hover { color: #cbd5e1; }
    
    .tab-btn.active {
        background: #1e293b; /* Slate 800 */
        color: #ffffff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }

    /* 📝 Clean Input Fields */
    .form-group {
        margin-bottom: 20px;
        text-align: left;
    }

    .form-group label {
        display: block;
        font-size: 0.85rem;
        font-weight: 500;
        color: #cbd5e1;
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        background: #0f172a;
        border: 1px solid #334155;
        border-radius: 10px;
        color: #f8fafc;
        font-size: 0.95rem;
        transition: all 0.2s;
        box-sizing: border-box;
    }

    .form-control::placeholder { color: #475569; }

    .form-control:focus {
        outline: none;
        border-color: #4f46e5; /* Indigo 600 */
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
    }

    /* Role Pills */
    .role-pills {
        display: flex;
        gap: 6px; /* Dikurangi sedikit agar muat 4 tombol */
        margin-bottom: 20px;
    }

    .role-pill {
        flex: 1;
        text-align: center;
        padding: 8px 4px; /* Disesuaikan agar muat di HP */
        background: #0f172a;
        border: 1px solid #334155;
        border-radius: 8px;
        font-size: 0.8rem; /* Dikecilkan sedikit */
        font-weight: 600;
        color: #94a3b8;
        cursor: pointer;
        transition: 0.2s;
        white-space: nowrap; /* Mencegah teks turun ke bawah */
    }

    .role-pill:hover { border-color: #475569; color: #cbd5e1; }
    
    .role-pill.active {
        background: rgba(79, 70, 229, 0.1);
        border-color: #4f46e5;
        color: #4f46e5;
    }

    /* Submit Button */
    .btn-primary {
        width: 100%;
        padding: 14px;
        background: #4f46e5;
        color: white;
        font-weight: 600;
        font-size: 1rem;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s;
        margin-top: 10px;
    }

    .btn-primary:hover {
        background: #4338ca; /* Indigo 700 */
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }

    /* Form Section Toggling */
    .auth-section {
        display: none;
        animation: fadeIn 0.4s ease;
    }
    .auth-section.active { display: block; }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Alerts */
    .alert {
        padding: 12px 16px;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .alert-error { background: rgba(220, 38, 38, 0.1); border: 1px solid rgba(220, 38, 38, 0.3); color: #fca5a5; }
    .alert-success { background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); color: #6ee7b7; }

    /* Admin Mode Override */
    .admin-mode .auth-header h2 { color: #ef4444; }
    .admin-mode .tabs-container, .admin-mode .role-pills { display: none; }
    .admin-mode .btn-primary { background: #dc2626; }
    .admin-mode .btn-primary:hover { background: #b91c1c; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3); }
    .admin-mode .form-control:focus { border-color: #dc2626; box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.2); }
</style>

<div class="auth-wrapper">
    <div class="auth-card" id="auth-card">
        
        <div class="auth-header">
            <h2 id="header-title">Welcome to Astryveil</h2>
            <p id="header-subtitle">Silakan masuk ke portal akademik Anda</p>
        </div>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-error">⚠️ <?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success">✅ <?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <div class="tabs-container" id="tabs-container">
            <button class="tab-btn active" onclick="switchForm('login')">Masuk</button>
            <button class="tab-btn" onclick="switchForm('register')">Daftar Tamu/Wali</button>
        </div>

        <div id="form-login" class="auth-section active">
            <form action="<?= base_url('auth/process') ?>" method="post">
                
                <input type="hidden" name="role" id="role-input" value="mahasiswa">
                
                <div class="role-pills" id="role-pills">
                    <div class="role-pill active" data-role="mahasiswa">Mahasiswa</div>
                    <div class="role-pill" data-role="dosen">Dosen</div>
                    <div class="role-pill" data-role="pendaftar">PMB</div>
                    <div class="role-pill" data-role="guest">Guest</div>
                </div>

                <div class="form-group">
                    <label for="userid" id="userid-label">User ID / Username</label>
                    <input type="text" id="userid" name="userid" class="form-control" placeholder="Masukkan ID Anda" required autocomplete="off">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn-primary" id="btn-login">Masuk ke Sistem</button>
            </form>
        </div>

        <div id="form-register" class="auth-section">
            <form action="<?= base_url('auth/register') ?>" method="post">
                
                <div class="form-group">
                    <label for="jenis_akun">Tujuan Pendaftaran</label>
                    <select id="jenis_akun" name="jenis_akun" class="form-control" required onchange="toggleNimField()">
                        <option value="" disabled selected>-- Pilih Jenis --</option>
                        <option value="Tamu umum">Tamu Umum (Eksplorasi)</option>
                        <option value="Orang tua/Wali Mahasiswa">Wali Mahasiswa (Pantau Nilai)</option>
                    </select>
                </div>

                <div class="form-group" id="nim-group" style="display: none; opacity: 0; transition: opacity 0.3s;">
                    <label for="nim_mahasiswa">NIM Anak / Mahasiswa</label>
                    <input type="number" id="nim_mahasiswa" name="nim_mahasiswa" class="form-control" placeholder="Masukkan NIM mahasiswa terkait">
                </div>

                <div class="form-group">
                    <label for="nama_lengkap">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control" placeholder="Nama sesuai identitas" required>
                </div>

                <div class="form-group">
                    <label for="username">Username Baru</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Untuk login nantinya" required autocomplete="off">
                </div>

                <div class="form-group">
                    <label for="reg-password">Password Baru</label>
                    <input type="password" id="reg-password" name="password" class="form-control" placeholder="Min. 6 karakter (Huruf & Angka)" required minlength="6">
                </div>

                <button type="submit" class="btn-primary">Buat Akun</button>
            </form>
        </div>

    </div>
</div>

<script>
    // 1. Tab Switching Logic (Clean Visibility Toggle)
    function switchForm(target) {
        const btns = document.querySelectorAll('.tab-btn');
        const sections = document.querySelectorAll('.auth-section');

        // Reset state
        btns.forEach(btn => btn.classList.remove('active'));
        sections.forEach(sec => sec.classList.remove('active'));

        // Activate target
        if(target === 'login') {
            btns[0].classList.add('active');
            document.getElementById('form-login').classList.add('active');
        } else {
            btns[1].classList.add('active');
            document.getElementById('form-register').classList.add('active');
        }
    }

    // 2. Role Selector (Pills)
    const pills = document.querySelectorAll('.role-pill');
    const roleInput = document.getElementById('role-input');
    const useridLabel = document.getElementById('userid-label');

    pills.forEach(pill => {
        pill.addEventListener('click', () => {
            pills.forEach(p => p.classList.remove('active'));
            pill.classList.add('active');
            
            const selectedRole = pill.getAttribute('data-role');
            roleInput.value = selectedRole;

            // Micro UX: Ubah label berdasarkan role
            if (selectedRole === 'pendaftar') {
                useridLabel.innerText = 'Email Pendaftaran';
                document.getElementById('userid').placeholder = 'Masukkan Email PMB Anda';
            } else {
                useridLabel.innerText = 'User ID / Username';
                document.getElementById('userid').placeholder = 'Masukkan ID Anda';
            }
        });
    });

    // 3. Register Form: Dynamic NIM Field
    function toggleNimField() {
        const select = document.getElementById('jenis_akun').value;
        const nimGroup = document.getElementById('nim-group');
        const nimInput = document.getElementById('nim_mahasiswa');

        if(select === 'Orang tua/Wali Mahasiswa') {
            nimGroup.style.display = 'block';
            setTimeout(() => { nimGroup.style.opacity = '1'; }, 10);
            nimInput.setAttribute('required', 'true');
        } else {
            nimGroup.style.opacity = '0';
            setTimeout(() => { nimGroup.style.display = 'none'; }, 300);
            nimInput.removeAttribute('required');
            nimInput.value = ''; // clear value
        }
    }

    // 4. 🔐 Hidden Admin Easter Egg
    let adminUnlocked = false;
    let clickCount = 0;
    let clickTimer;

    const unlockAdmin = () => {
        if (adminUnlocked) return;
        adminUnlocked = true;

        const card = document.getElementById('auth-card');
        const title = document.getElementById('header-title');
        const subtitle = document.getElementById('header-subtitle');
        const roleInput = document.getElementById('role-input');
        const btn = document.getElementById('btn-login');

        card.classList.add('admin-mode');
        switchForm('login'); 
        
        title.innerHTML = 'System Override';
        subtitle.innerHTML = 'Administrator access protocol initiated.';
        btn.innerHTML = 'Initialize Session';
        
        roleInput.value = 'admin';
    };

    document.addEventListener("keydown", (e) => {
        if (e.ctrlKey && e.shiftKey && e.key.toLowerCase() === 'a') { unlockAdmin(); }
    });

    document.getElementById('header-title').addEventListener('click', () => {
        clickCount++;
        clearTimeout(clickTimer);
        if (clickCount >= 5) { unlockAdmin(); }
        clickTimer = setTimeout(() => { clickCount = 0; }, 1000);
    });
</script>

<?= $this->endSection() ?>