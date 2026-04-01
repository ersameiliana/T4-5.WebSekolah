<?= $this->extend('layout/dashboard_template') ?>

<?= $this->section('page_title') ?>
Database System Logs
<?= $this->endSection() ?>

<?php 
    $role = session()->get('role_admin') ?? 'Guest'; 
    $nama_admin = session()->get('nama_admin') ?? 'Administrator';
    $inisial = strtoupper(substr($nama_admin, 0, 1));
?>

<?= $this->section('sidebar_menu') ?>
    <style>
        .nav-item { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); display: block; border-radius: 8px; margin-bottom: 5px; }
        .nav-item:hover { transform: translateX(8px); background: rgba(255,255,255,0.05); }
    </style>

    <a href="<?= base_url('admin-secret-panel') ?>" class="nav-item">📊 Dashboard Admin</a>

    <?php if (in_array($role, ['Editing', 'Sistem/Database'])): ?>
        <div style="color: #64748b; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; margin: 20px 0 10px 15px; letter-spacing: 1px;">Konten & Publikasi</div>
        <a href="<?= base_url('admin/profil') ?>" class="nav-item">🏛️ Kelola Profil Web</a>
        <a href="<?= base_url('admin/berita') ?>" class="nav-item">📰 Kelola Berita</a>
    <?php endif; ?>

    <?php if (in_array($role, ['Administrasi', 'Sistem/Database'])): ?>
        <div style="color: #64748b; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; margin: 20px 0 10px 15px; letter-spacing: 1px;">Data Akademik</div>
        <a href="<?= base_url('admin/pengguna') ?>" class="nav-item">👥 Kelola Akun User</a>
        <a href="<?= base_url('admin/jurusan') ?>" class="nav-item">🏢 Kelola Jurusan</a>
    <?php endif; ?>

    <?php if ($role === 'Sistem/Database'): ?>
        <div style="color: #ef4444; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; margin: 20px 0 10px 15px; letter-spacing: 1px;">Superadmin Tools</div>
        <a href="<?= base_url('admin/otorisasi') ?>" class="nav-item">🔐 Cabut Hak Akses</a>
        <a href="<?= base_url('admin/system-logs') ?>" class="nav-item active" style="color: #fca5a5; background: rgba(239, 68, 68, 0.1);">⚙️ Database Logs</a>
    <?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    /* FIX MODAL PREVENT LEAK */
    .modal { display: none; z-index: 1055; }

    /* TOP BAR STYLING */
    .top-bar-area { display: flex; justify-content: flex-end; align-items: center; gap: 20px; margin-bottom: 30px; }
    .profile-info { display: flex; align-items: center; gap: 12px; text-align: right; }
    .profile-avatar { width: 45px; height: 45px; border-radius: 50%; background: linear-gradient(135deg, #f59e0b, #ef4444); display: flex; justify-content: center; align-items: center; font-weight: 800; font-size: 1.2rem; color: #fff; border: 2px solid rgba(255,255,255,0.1); }
    .btn-logout-top { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); padding: 10px 20px; border-radius: 10px; font-weight: 700; font-size: 0.9rem; text-decoration: none; transition: 0.3s ease; display: flex; align-items: center; gap: 8px; }
    .btn-logout-top:hover { background: rgba(239, 68, 68, 0.2); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(239, 68, 68, 0.2); color: #ef4444; }

    /* STATS CARD */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .stat-card { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.08); border-radius: 1.2rem; padding: 25px; position: relative; overflow: hidden; }
    .stat-card h3 { margin: 0; font-size: 2.5rem; color: #fff; font-weight: 900; }
    .stat-card p { color: #cbd5e1; margin: 5px 0 0 0; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; font-weight: bold;}
    .stat-icon { font-size: 4rem; opacity: 0.1; position: absolute; top: 10px; right: 20px; }

    /* TABLE STYLING */
    .glass-card { background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.12); border-radius: 24px; padding: 40px; position: relative; }
    .table-glass { --bs-table-bg: transparent; --bs-table-color: #f8fafc; --bs-table-border-color: rgba(255,255,255,0.05); }
    .table-glass th { color: #94a3b8; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.75rem; padding-bottom: 15px; border-bottom: 2px solid rgba(255,255,255,0.1) !important;}
    .table-glass td { vertical-align: middle; padding: 15px 10px; border-bottom: 1px solid rgba(255,255,255,0.05); font-weight: 500; font-size: 0.95rem; }
    .table-glass tbody tr:hover { background: rgba(59, 130, 246, 0.05); transform: scale(1.005); transition: 0.3s; }

    /* SEARCH BAR */
    .input-group-glass { background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; display: flex; align-items: center; transition: 0.3s; flex: 1; min-width: 250px;}
    .input-group-glass:focus-within { border-color: #64ffda; background: rgba(0, 0, 0, 0.4); box-shadow: 0 0 15px rgba(100, 255, 218, 0.1);}
    .input-group-glass .icon-wrapper { padding: 0 15px; color: #64748b; }
    .input-group-glass .form-control { background: transparent; border: none; color: #fff; padding: 14px 15px 14px 0; box-shadow: none !important; }

    /* BUTTONS */
    .btn-gradient-danger { background: linear-gradient(135deg, #ef4444, #b91c1c); color: #fff; border: none; font-weight: bold; border-radius: 12px; padding: 12px 25px; transition: 0.3s; }
    .btn-gradient-danger:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(239, 68, 68, 0.3); color: #fff; }

    .action-btn { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: none; transition: 0.2s; background: rgba(148, 163, 184, 0.1); color: #94a3b8; }
    .action-btn:hover { background: #64748b; color: #fff; }

    /* PAGINATION */
    .pagination-controls { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; flex-wrap: wrap; gap: 15px;}
    .page-size-selector { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255,255,255,0.1); color: #ccd6f6; border-radius: 8px; padding: 6px 12px; outline: none; }
    .page-size-selector:focus { border-color: #64ffda; }
    .pagination-glass { display: flex; gap: 5px; margin: 0; padding: 0; list-style: none; }
    .page-btn { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #8892b0; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 8px; cursor: pointer; transition: 0.2s; font-size: 0.85rem; font-weight: bold;}
    .page-btn:hover { background: rgba(255,255,255,0.1); color: #fff; }
    .page-btn.active { background: linear-gradient(135deg, #ef4444, #b91c1c); color: #fff; border-color: transparent; }

    /* MODAL GLASSMORPHISM */
    .glass-modal { background: rgba(15, 23, 42, 0.98) !important; backdrop-filter: blur(25px) !important; border: 1px solid rgba(255, 255, 255, 0.1) !important; border-radius: 20px !important; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5) !important;}
    .btn-close-custom { filter: invert(1) grayscale(100%) brightness(200%); opacity: 0.5; transition: 0.3s;}
    .btn-close-custom:hover { opacity: 1; }
    
    mark { background: rgba(239, 68, 68, 0.4); color: #fff; border-radius: 3px; padding: 0 2px; }
</style>

<div class="top-bar-area">
    <div class="profile-info">
        <div>
            <div style="font-weight: 700; color: #fff; font-size: 0.95rem;"><?= esc($nama_admin) ?></div>
            <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600; text-transform: uppercase;"><?= esc($role) ?></div>
        </div>
        <div class="profile-avatar"><?= $inisial ?></div>
    </div>
    <div style="width: 1px; height: 30px; background: rgba(255,255,255,0.1);"></div>
    <a href="<?= base_url('auth/logout') ?>" id="btn-logout-admin" class="btn-logout-top"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<div class="stats-grid">
    <div class="stat-card" style="border-top: 3px solid #f59e0b;">
        <i class="bi bi-server stat-icon" style="color: #f59e0b;"></i>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3><?= esc(count($daftar_log ?? [])) ?></h3>
                <p>Total Aktivitas Tercatat</p>
            </div>
        </div>
    </div>
    <div class="stat-card" style="border-top: 3px solid #10b981;">
        <i class="bi bi-shield-check stat-icon" style="color: #10b981;"></i>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3>Aman</h3>
                <p>Status Database</p>
            </div>
        </div>
    </div>
</div>

<div class="glass-card">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-4 border-bottom border-secondary border-opacity-25 gap-3">
        <div>
            <h2 class="fw-bolder text-white mb-1" style="margin-top:0;">⚙️ System Audit Logs</h2>
            <p class="text-secondary mb-0 small">Seluruh histori aktivitas *Create, Update, Delete* pada sistem terekam di sini secara otomatis.</p>
        </div>
        <div class="input-group-glass" style="max-width: 350px;">
            <div class="icon-wrapper"><i class="bi bi-search"></i></div>
            <input type="text" id="searchLog" class="form-control" placeholder="Cari aktivitas, admin, atau tabel..." autocomplete="off">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-glass table-hover mb-0">
            <thead>
                <tr>
                    <th scope="col" width="18%">Waktu (WIB)</th>
                    <th scope="col" width="20%">Aktor / Admin</th>
                    <th scope="col" width="15%">Aksi</th>
                    <th scope="col" width="37%">Keterangan Aktivitas</th>
                    <th scope="col" width="10%" class="text-end">Rincian</th>
                </tr>
            </thead>
            <tbody id="table-body-logs">
                <?php if(!empty($daftar_log)): ?>
                    <?php foreach($daftar_log as $log): ?>
                        <?php 
                            // Pewarnaan Badge berdasarkan jenis aksi (asumsi nama kolom 'aksi' atau 'aktivitas')
                            $aksi = strtoupper($log['aksi'] ?? $log['aktivitas'] ?? 'INFO');
                            $badgeColor = '#64748b'; // default gray
                            $bgGlow = 'rgba(100, 116, 139, 0.15)';
                            
                            if(strpos($aksi, 'INSERT') !== false || strpos($aksi, 'TAMBAH') !== false) {
                                $badgeColor = '#10b981'; // Green
                                $bgGlow = 'rgba(16, 185, 129, 0.15)';
                            } elseif (strpos($aksi, 'UPDATE') !== false || strpos($aksi, 'EDIT') !== false) {
                                $badgeColor = '#3b82f6'; // Blue
                                $bgGlow = 'rgba(59, 130, 246, 0.15)';
                            } elseif (strpos($aksi, 'DELETE') !== false || strpos($aksi, 'HAPUS') !== false) {
                                $badgeColor = '#ef4444'; // Red
                                $bgGlow = 'rgba(239, 68, 68, 0.15)';
                            } elseif (strpos($aksi, 'LOGIN') !== false) {
                                $badgeColor = '#f59e0b'; // Yellow
                                $bgGlow = 'rgba(245, 158, 11, 0.15)';
                            }

                            // Fallback nama kolom agar kode aman (menyesuaikan struktur tabel Anda)
                            $waktu = $log['waktu'] ?? $log['created_at'] ?? $log['timestamp'] ?? '-';
                            $aktor = $log['admin'] ?? $log['pengguna'] ?? $log['user'] ?? 'System';
                            $keterangan = $log['keterangan'] ?? $log['detail'] ?? $log['deskripsi'] ?? '-';
                        ?>
                        <tr class="data-row" data-search="<?= strtolower(esc($aktor . ' ' . $aksi . ' ' . $keterangan)) ?>">
                            <td class="text-secondary small font-monospace">
                                <?= date('d M Y', strtotime($waktu)) ?><br>
                                <span class="text-white fw-bold"><?= date('H:i:s', strtotime($waktu)) ?></span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar" style="width:28px; height:28px; font-size:0.75rem; background: #334155;"><i class="bi bi-person"></i></div>
                                    <div class="fw-bold text-white search-target">@<?= esc($aktor) ?></div>
                                </div>
                            </td>
                            <td>
                                <span class="badge search-target" style="background: <?= $bgGlow ?>; color: <?= $badgeColor ?>; border: 1px solid <?= $badgeColor ?>40; padding: 6px 12px; letter-spacing: 0.5px;">
                                    <?= esc($aksi) ?>
                                </span>
                            </td>
                            <td>
                                <div class="text-secondary small search-target" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?= esc($keterangan) ?>
                                </div>
                            </td>
                            <td class="text-end">
                                <button class="action-btn" data-bs-toggle="tooltip" title="Lihat Detail"
                                    onclick="openModalDetail(<?= htmlspecialchars(json_encode($log)) ?>)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr id="empty-state">
                        <td colspan="5" class="text-center py-5 text-secondary">
                            <i class="bi bi-inbox fs-1 opacity-50 mb-3 d-block"></i>
                            <h4 class="text-white mb-2">Sistem Bersih</h4>
                            <p>Belum ada aktivitas yang terekam di database.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="pagination-controls mt-4">
        <div class="d-flex align-items-center gap-2 text-secondary small">
            <span>Tampilkan:</span>
            <select id="pageSize" class="page-size-selector" onchange="changePageSize()">
                <option value="10">10</option>
                <option value="25" selected>25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span>baris</span>
        </div>
        <div><ul class="pagination-glass" id="paginationBox"></ul></div>
    </div>
</div>

<div class="modal fade" id="modalLogDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered border-0">
        <div class="modal-content glass-modal border-0 p-2">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-3">
                    <div style="padding: 12px; border-radius: 12px; background: rgba(148, 163, 184, 0.1); color: #94a3b8;">
                        <i class="bi bi-terminal fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bolder text-white mb-0">Detail System Log</h5>
                        <small class="text-secondary" style="font-size: 0.75rem;">Mode Read-Only Audit</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-custom align-self-start mt-1" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 py-4">
                <div class="mb-3">
                    <label class="text-secondary small fw-bold mb-1 uppercase">Aktor Eksekutor</label>
                    <div class="form-control" id="detail_aktor" style="background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.05); color: #fff;" readonly></div>
                </div>
                <div class="mb-3">
                    <label class="text-secondary small fw-bold mb-1 uppercase">Waktu Eksekusi</label>
                    <div class="form-control" id="detail_waktu" style="background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.05); color: #fff;" readonly></div>
                </div>
                <div class="mb-3">
                    <label class="text-secondary small fw-bold mb-1 uppercase">Tipe Aksi</label>
                    <div><span class="badge fs-6 mt-1 px-3 py-2" id="detail_aksi"></span></div>
                </div>
                <div class="mb-2">
                    <label class="text-secondary small fw-bold mb-1 uppercase">Keterangan / Payload Data</label>
                    <textarea class="form-control font-monospace text-info" id="detail_keterangan" rows="6" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.05); font-size: 0.85rem;" readonly></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 pb-4 px-4 d-flex justify-content-end">
                <button type="button" class="btn btn-dark text-white fw-bold px-4" style="border-radius: 12px; padding: 10px 20px;" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Inisialisasi Tooltip
    document.addEventListener("DOMContentLoaded", () => {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (t) { return new bootstrap.Tooltip(t); });
        renderPagination(); 
    });

    // Buka Modal Detail
    function openModalDetail(logData) {
        // Amankan nilai dengan fallback
        const aksi = (logData.aksi || logData.aktivitas || 'INFO').toUpperCase();
        const waktu = logData.waktu || logData.created_at || logData.timestamp || '-';
        const aktor = logData.admin || logData.pengguna || logData.user || 'System';
        const keterangan = logData.keterangan || logData.detail || logData.deskripsi || '-';

        document.getElementById('detail_aktor').innerText = "@" + aktor;
        document.getElementById('detail_waktu').innerText = waktu;
        document.getElementById('detail_keterangan').value = keterangan;
        
        const badge = document.getElementById('detail_aksi');
        badge.innerText = aksi;
        
        // Atur warna badge di modal
        if(aksi.includes('INSERT') || aksi.includes('TAMBAH')) { badge.style.background = '#10b981'; badge.style.color = '#fff'; }
        else if (aksi.includes('UPDATE') || aksi.includes('EDIT')) { badge.style.background = '#3b82f6'; badge.style.color = '#fff'; }
        else if (aksi.includes('DELETE') || aksi.includes('HAPUS')) { badge.style.background = '#ef4444'; badge.style.color = '#fff'; }
        else { badge.style.background = '#64748b'; badge.style.color = '#fff'; }

        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalLogDetail')).show();
    }

    // PAGINATION & LIVE SEARCH SCRIPT
    let currentPage = 1; let rowsPerPage = 25;
    function changePageSize() { rowsPerPage = parseInt(document.getElementById('pageSize').value); currentPage = 1; renderPagination(); }
    
    function renderPagination() {
        const keyword = document.getElementById('searchLog').value.toLowerCase();
        const tbody = document.getElementById('table-body-logs');
        const rows = Array.from(tbody.querySelectorAll('tr.data-row')); 
        let visibleRows = [];
        
        rows.forEach(row => {
            const dataSearch = row.dataset.search || '';
            if (!keyword || dataSearch.includes(keyword)) {
                visibleRows.push(row);
                const targets = row.querySelectorAll('.search-target');
                targets.forEach(target => {
                    if(!target.dataset.original) target.dataset.original = target.innerHTML;
                    if(keyword) target.innerHTML = target.dataset.original.replace(new RegExp(keyword, "gi"), match => `<mark>${match}</mark>`);
                    else target.innerHTML = target.dataset.original;
                });
            } else row.style.display = 'none';
        });

        let emptyTr = tbody.querySelector('#empty-state');
        if(visibleRows.length === 0) {
            if(!emptyTr) tbody.insertAdjacentHTML('beforeend', '<tr id="empty-state"><td colspan="5" class="text-center py-5 text-secondary">Data tidak ditemukan.</td></tr>');
            document.getElementById('paginationBox').innerHTML = ''; return;
        } else if(emptyTr) emptyTr.remove();

        const totalPages = Math.ceil(visibleRows.length / rowsPerPage);
        if(currentPage > totalPages) currentPage = totalPages || 1;
        const start = (currentPage - 1) * rowsPerPage; const end = start + rowsPerPage;
        visibleRows.forEach((row, index) => { row.style.display = (index >= start && index < end) ? '' : 'none'; });

        let paginationHTML = '';
        paginationHTML += `<li class="page-item"><button class="page-btn" ${currentPage === 1 ? 'disabled' : `onclick="goToPage(${currentPage - 1})"`}><i class="bi bi-chevron-left"></i></button></li>`;
        for(let i = 1; i <= totalPages; i++) {
            if(i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                paginationHTML += `<li class="page-item"><button class="page-btn ${currentPage === i ? 'active' : ''}" onclick="goToPage(${i})">${i}</button></li>`;
            } else if (i === currentPage - 2 || i === currentPage + 2) {
                paginationHTML += `<li class="page-item"><span class="page-btn text-secondary border-0" style="background:transparent; cursor:default;">...</span></li>`;
            }
        }
        paginationHTML += `<li class="page-item"><button class="page-btn" ${currentPage === totalPages ? 'disabled' : `onclick="goToPage(${currentPage + 1})"`}><i class="bi bi-chevron-right"></i></button></li>`;
        document.getElementById('paginationBox').innerHTML = paginationHTML;
    }

    function goToPage(page) { currentPage = page; renderPagination(); }
    let searchDebounce;
    document.getElementById('searchLog').addEventListener('input', () => { clearTimeout(searchDebounce); searchDebounce = setTimeout(() => { currentPage = 1; renderPagination(); }, 300); });

    // LOGOUT SCRIPT
    document.getElementById('btn-logout-admin').addEventListener('click', function(e) {
        e.preventDefault(); const logoutUrl = this.getAttribute('href');
        Swal.fire({ title: 'Akhiri Sesi?', icon: 'question', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b', confirmButtonText: 'Ya, Keluar', background: '#1e293b', color: '#fff' }).then((result) => { if (result.isConfirmed) { window.location.href = logoutUrl; } });
    });
</script>

<?= $this->endSection() ?>