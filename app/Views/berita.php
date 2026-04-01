<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<style>
    .page-header {
        padding: 150px 20px 80px;
        text-align: center;
        background: radial-gradient(circle at top, rgba(159, 18, 57, 0.2) 0%, #0b0f19 70%);
    }

    .page-header h1 {
        font-size: 3rem;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 10px;
    }

    .news-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        padding: 40px 20px;
    }

    .news-card {
        display: flex;
        flex-direction: column;
        border-radius: 20px;
        overflow: hidden;
        transition: 0.4s;
        text-decoration: none;
    }

    .news-card:hover {
        box-shadow: var(--glow-hover);
        transform: translateY(-5px);
    }

    .news-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background-color: #1e293b; /* Fallback color */
    }

    .news-content {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .news-meta {
        font-size: 0.8rem;
        color: #9F1239;
        font-weight: 600;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .news-title {
        color: #fff;
        font-size: 1.25rem;
        margin: 0 0 15px 0;
        line-height: 1.4;
    }

    .news-preview {
        color: #cbd5e1;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 20px;
        flex-grow: 1;
    }

    .read-more {
        color: #3b82f6;
        font-weight: 600;
        text-decoration: none;
        margin-top: auto;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .read-more:hover {
        text-shadow: 0 0 10px #3b82f6;
    }

    .load-more-container {
        text-align: center;
        padding: 20px 0 60px 0;
    }

    /* Loading Spinner */
    .loader {
        border: 4px solid rgba(255,255,255,0.1);
        border-top: 4px solid #2563EB;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
        margin: 0 auto;
        display: none;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<section class="page-header">
    <div class="container">
        <h1>Portal Berita & Update</h1>
        <p style="color: #aaa; font-size: 1.2rem;">Kabar terbaru, riset, dan pengumuman dari Astryveil Academy.</p>
    </div>
</section>

<section class="container">
    <div id="news-container" class="news-grid"></div>

    <div class="load-more-container">
        <div id="loader" class="loader"></div>
        <button id="loadMore" class="btn-login" style="padding: 12px 35px;">Load More</button>
    </div>
</section>

<script>
    let currentPage = 1;
    let isLoading = false;
    const btnLoadMore = document.getElementById("loadMore");
    const container = document.getElementById("news-container");
    const loader = document.getElementById("loader");

    // Karena tabel berita tidak punya kolom gambar, kita pakai gambar placeholder dinamis 
    // bernuansa biru/merah (teknologi/edukasi) agar UI tetap cantik.
    const getPlaceholderImage = (id) => `https://picsum.photos/seed/astryveil${id}/400/250`;

    async function fetchNews() {
        if (isLoading) return;
        isLoading = true;
        
        // Tampilkan animasi loading, sembunyikan tombol
        btnLoadMore.style.display = 'none';
        loader.style.display = 'block';

        try {
            // Hit API backend CI4
            const response = await fetch(`<?= base_url('berita/apiGetNews') ?>?page=${currentPage}`);
            const data = await response.json();

            // Sembunyikan loader
            loader.style.display = 'none';

            if (data.length > 0) {
                // Loop data berita dan buat HTML-nya
                data.forEach(news => {
                    const card = document.createElement('a');
                    card.href = `<?= base_url('berita/baca/') ?>${news.link_url}`;
                    card.className = 'glass news-card';
                    card.innerHTML = `
                        <img src="${getPlaceholderImage(news.id_berita)}" alt="${news.judul_berita}" class="news-image" loading="lazy">
                        <div class="news-content">
                            <div class="news-meta">📅 ${news.tanggal_format} | ✍️ ${news.penulis}</div>
                            <h3 class="news-title">${news.judul_berita}</h3>
                            <div class="news-preview">${news.preview_konten || (news.sub_judul ? news.sub_judul : 'Baca selengkapnya untuk detail berita ini...')}</div>
                            <span class="read-more">Baca Selengkapnya →</span>
                        </div>
                    `;
                    container.appendChild(card);
                });

                // Jika data yang dikembalikan kurang dari 6 (limit), artinya data sudah habis
                if (data.length < 6) {
                    btnLoadMore.style.display = 'none';
                    container.insertAdjacentHTML('afterend', '<p style="text-align:center; color:#64748b; margin-top:20px;">Semua berita telah dimuat.</p>');
                } else {
                    btnLoadMore.style.display = 'inline-block';
                    currentPage++;
                }
            } else {
                if(currentPage === 1) {
                    container.innerHTML = '<p style="color:#fff; grid-column: 1 / -1; text-align:center;">Belum ada berita yang diterbitkan.</p>';
                } else {
                    // Data habis di halaman selanjutnya
                    btnLoadMore.style.display = 'none';
                }
            }
        } catch (error) {
            console.error("Gagal mengambil berita:", error);
            loader.style.display = 'none';
            btnLoadMore.style.display = 'inline-block';
            btnLoadMore.innerText = "Coba Lagi";
        }

        isLoading = false;
    }

    // Event Listener untuk Tombol
    btnLoadMore.addEventListener("click", fetchNews);

    // Otomatis load data pertama kali saat halaman dibuka
    document.addEventListener("DOMContentLoaded", fetchNews);
</script>

<?= $this->endSection() ?>