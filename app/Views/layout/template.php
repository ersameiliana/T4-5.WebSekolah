<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Astryveil Academy' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #8b5cf6, #3b82f6);
            --glow-primary: 0 0 20px rgba(139, 92, 246, 0.5);
            --glow-hover: 0 10px 30px rgba(59, 130, 246, 0.6);
        }

        body {
            margin: 0; font-family: 'Inter', sans-serif;
            background-color: #0b0f19; color: #ffffff;
            overflow-x: hidden; scroll-behavior: smooth;
        }

        /* ✨ NAVBAR RE-STRUCTURE (CLEAN & CONVERSION FOCUSED) */
        nav {
            position: fixed; top: 20px; left: 50%; transform: translateX(-50%);
            width: 90%; max-width: 1200px; padding: 10px 25px; border-radius: 50px;
            display: grid; grid-template-columns: auto 1fr auto; align-items: center; gap: 20px;
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            background: rgba(11, 15, 25, 0.75); border: 1px solid rgba(255, 255, 255, 0.08);
            z-index: 9999; transition: 0.4s ease-in-out; box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        nav.scrolled {
            top: 15px; background: rgba(10, 10, 20, 0.95);
            border-color: rgba(139, 92, 246, 0.2); box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
        }

        .brand-logo {
            font-weight: 800; font-size: 1.4rem; text-decoration: none;
            color: #fff; display: flex; align-items: center; gap: 10px; letter-spacing: -0.5px;
        }

        /* 🧠 UX Upgrade: Pill Hover State */
        .nav-links { 
            display: flex; gap: 10px; list-style: none; margin: 0; padding: 0; justify-content: center;
        }
        
        .nav-links a {
            text-decoration: none; color: #cbd5e1; font-weight: 600; font-size: 0.95rem;
            padding: 8px 20px; border-radius: 50px; transition: 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            display: inline-block;
        }
        
        .nav-links a:hover { 
            color: #fff; background: rgba(139, 92, 246, 0.15); 
            box-shadow: inset 0 0 20px rgba(139, 92, 246, 0.1), 0 0 15px rgba(139, 92, 246, 0.2); 
        }

        /* ▾ DROPDOWN MENU UPGRADE */
        .nav-dropdown { position: relative; }
        .dropdown-menu {
            position: absolute; top: 120%; left: 50%; transform: translateX(-50%) translateY(15px) scale(0.95); 
            background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(25px); 
            border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 12px; 
            display: flex; flex-direction: column; min-width: 260px; 
            opacity: 0; visibility: hidden; transition: 0.3s cubic-bezier(0.16, 1, 0.3, 1); 
            box-shadow: 0 20px 40px rgba(0,0,0,0.4); transform-origin: top center;
        }
        .nav-dropdown:hover .dropdown-menu { 
            opacity: 1; visibility: visible; transform: translateX(-50%) translateY(0) scale(1); 
        }
        .dropdown-menu a {
            color: #cbd5e1; padding: 12px 18px; text-decoration: none; border-radius: 12px;
            font-weight: 600; font-size: 0.9rem; transition: 0.3s; display: block; text-align: left;
        }
        .dropdown-menu a:hover { 
            background: rgba(139, 92, 246, 0.2); color: #fff; padding-left: 25px; 
            box-shadow: none;
        }

        /* 🚀 STRONG CTA & LOGIN SPLIT UPGRADE */
        .auth-buttons { 
            display: flex; align-items: center; gap: 20px; justify-content: flex-end; 
        }
        
        /* Subtle Login Link */
        .btn-login {
            color: #cbd5e1; font-weight: 600; font-size: 0.9rem; text-decoration: none; 
            transition: 0.3s; padding: 8px 0;
        }
        .btn-login:hover { 
            color: #fff; text-shadow: 0 0 15px rgba(255,255,255,0.6); 
        }

        /* Primary Admission CTA */
        .btn-cta {
            position: relative; overflow: hidden; border-radius: 50px;
            background: var(--primary-gradient); color: white;
            padding: 10px 24px; font-weight: 700; font-size: 0.95rem; text-decoration: none;
            display: inline-block; transition: 0.3s; box-shadow: var(--glow-primary);
            border: 1px solid rgba(255,255,255,0.2);
        }
        .btn-cta:hover { 
            transform: translateY(-2px); box-shadow: var(--glow-hover); color: #fff;
        }

        /* Mobile View Hide */
        @media (max-width: 992px) {
            nav { display: flex; justify-content: space-between; padding: 12px 20px; }
            .nav-links { display: none; }
            .btn-login { display: none; } /* Sembunyikan login agar mobile tidak sumpek */
        }
    </style>
</head>
<body>

    <nav id="navbar">
        <a href="<?= base_url() ?>" class="brand-logo">✨ Astryveil</a>

        <ul class="nav-links">
            <li><a href="<?= base_url() ?>">Home</a></li>
            
            <li class="nav-dropdown">
                <a href="#" style="cursor: default;">Fakultas ▾</a>
                <div class="dropdown-menu">
                    <a href="<?= base_url('fakultas/teknologi-informatika') ?>">💻 Teknologi & Informatika</a>
                    <a href="<?= base_url('fakultas/sains-matematika') ?>">🔬 Sains & Matematika</a>
                    <a href="<?= base_url('fakultas/bisnis-manajemen') ?>">📈 Bisnis & Manajemen</a>
                    <a href="<?= base_url('fakultas/desain-kreatif') ?>">🎨 Desain & Kreatif</a>
                </div>
            </li>

            <li><a href="<?= base_url('akademik') ?>">Program</a></li>
            <li><a href="<?= base_url('berita') ?>">Berita</a></li>
            <li><a href="<?= base_url('tentang-kami') ?>">Tentang Kami</a></li>
        </ul>

        <div class="auth-buttons">
            <a href="<?= base_url('login') ?>" class="btn-login">Portal Login</a>
            <a href="<?= base_url('pendaftaran') ?>" class="btn-cta">Daftar Sekarang 🚀</a>
        </div>
    </nav>

    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <script>
        // Efek Scrolled Navbar
        window.addEventListener("scroll", () => {
            document.getElementById("navbar").classList.toggle("scrolled", window.scrollY > 50);
        });
    </script>
</body>
</html>