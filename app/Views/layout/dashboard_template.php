<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard Astryveil' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #2563EB, #9F1239);
            --glow-ruby: 0 0 25px rgba(159, 18, 57, 0.4);
            --bg-dark: #0b0f19;
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: #fff;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* Glass Sidebar */
        .sidebar {
            width: 280px;
            background: rgba(11, 15, 25, 0.8);
            backdrop-filter: blur(20px);
            border-right: 1px solid var(--glass-border);
            display: flex;
            flex-direction: column;
            padding: 20px 0;
            box-shadow: 5px 0 20px rgba(0,0,0,0.5);
        }

        .sidebar-brand {
            padding: 0 20px 20px;
            font-size: 1.5rem;
            font-weight: 800;
            border-bottom: 1px solid var(--glass-border);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-item {
            padding: 15px 25px;
            color: #cbd5e1;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
            display: block;
        }

        .nav-item:hover, .nav-item.active {
            background: rgba(37, 99, 235, 0.1);
            color: #fff;
            border-left: 4px solid #2563EB;
        }

        .logout-btn {
            margin-top: auto;
            color: #ff4d4d;
        }

        .logout-btn:hover {
            background: rgba(159, 18, 57, 0.1);
            border-left: 4px solid #9F1239;
        }

        /* Main Content Area */
        .main-content {
            flex-grow: 1;
            padding: 30px 40px;
            overflow-y: auto;
            background: radial-gradient(circle at top right, rgba(37, 99, 235, 0.1) 0%, var(--bg-dark) 50%);
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            box-shadow: var(--glow-ruby);
        }

        /* Glass Cards for Dashboard Widgets */
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(14px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 25px;
        }
    </style>
</head>
<body>

    <nav class="sidebar">
        <div class="sidebar-brand">
            🦅 Astryveil
        </div>
        
        <?= $this->renderSection('sidebar_menu') ?>

        <a href="<?= base_url('auth/logout') ?>" class="nav-item logout-btn">🚪 Keluar (Logout)</a>
    </nav>

    <main class="main-content">
        <div class="topbar">
            <h2><?= $this->renderSection('page_title') ?></h2>
            <div class="user-info">
                <div style="text-align: right;">
                    <div style="font-weight: bold;"><?= session()->get('user_name') ?></div>
                    <div style="font-size: 0.8rem; color: #cbd5e1; text-transform: capitalize;"><?= session()->get('role') ?></div>
                </div>
                <div class="avatar">
                    <?= strtoupper(substr(session()->get('user_name'), 0, 1)) ?>
                </div>
            </div>
        </div>

        <?= $this->renderSection('content') ?>
    </main>

</body>
</html>