<?php

namespace App\Controllers;

use App\Models\StrukturPimpinanModel;

class Fakultas extends BaseController
{
    // ==========================================
    // DATA FAKULTAS (Dipindah ke atas agar bisa dipakai di Index dan Detail)
    // ==========================================
    private $dataFakultas = [
        'teknologi-informatika' => [
            'nama' => 'Teknologi & Informatika', 'icon' => '💻', 'keyword_db' => 'Teknologi',
            'desc' => 'Pusat riset komputasi dan kecerdasan buatan terdepan yang dirancang untuk menjawab tantangan revolusi industri 5.0.',
            'sambutan' => 'Fakultas Teknologi dan Informatika berkomitmen untuk menjadi pusat unggulan dalam pengembangan ilmu pengetahuan dan teknologi yang adaptif terhadap dinamika global. Kami mendorong sivitas akademika untuk tidak hanya menjadi pengguna teknologi, tetapi juga pencipta solusi berbasis inovasi dan pemikiran kritis. Melalui sinergi antara riset, pendidikan, dan kolaborasi industri, kami percaya bahwa lulusan kami akan mampu memberikan kontribusi nyata bagi masyarakat dan peradaban digital.',
            'strata' => 'S1 & D4 Terapan', 'masa_studi' => '8 Semester', 'gelar' => 'S.Kom / S.Tr.Kom',
            'prodi' => [
                ['nama' => 'Informatika', 'sks' => 144, 'desc' => 'Fokus pada Software Engineering, AI, dan Cloud Architecture.', 'skills' => ['Machine Learning', 'Cloud Native']],
                ['nama' => 'Sistem Informasi', 'sks' => 144, 'desc' => 'Menjembatani kebutuhan bisnis korporat dengan infrastruktur IT.', 'skills' => ['Enterprise Architecture', 'Data Analytics']],
                ['nama' => 'Data Science', 'sks' => 144, 'desc' => 'Analitik big data dan pemodelan prediktif tingkat lanjut.', 'skills' => ['Deep Learning', 'Big Data']],
                ['nama' => 'Rekayasa Perangkat Lunak', 'sks' => 144, 'desc' => 'Praktik industri pengembangan perangkat lunak berskala besar.', 'skills' => ['DevOps', 'Agile Scrum']]
            ]
        ],
        'sains-matematika' => [
            'nama' => 'Sains & Matematika', 'icon' => '🔬', 'keyword_db' => 'Sains',
            'desc' => 'Mengeksplorasi kebenaran fundamental alam semesta melalui pemodelan matematis dan observasi empiris.',
            'sambutan' => 'Fakultas Sains dan Matematika merupakan ruang bagi lahirnya pemikiran fundamental yang menjadi dasar dari berbagai kemajuan ilmu pengetahuan. Kami meyakini bahwa pemahaman yang kuat terhadap konsep dasar sains dan matematika adalah kunci dalam membangun inovasi yang berkelanjutan. Oleh karena itu, kami berkomitmen untuk menciptakan lingkungan akademik yang mendorong eksplorasi, ketelitian, dan integritas ilmiah.',
            'strata' => 'S1 (Sarjana)', 'masa_studi' => '8 Semester', 'gelar' => 'S.Si / S.Stat',
            'prodi' => [
                ['nama' => 'Matematika Terapan', 'sks' => 144, 'desc' => 'Aplikasi ilmu matematika dalam industri, keuangan, dan kriptografi.', 'skills' => ['Cryptography', 'Operations Research']],
                ['nama' => 'Fisika Komputasi', 'sks' => 144, 'desc' => 'Simulasi material tingkat nano dan fenomena fisis kompleks.', 'skills' => ['Quantum Simulation', 'Nanotech']],
                ['nama' => 'Statistika', 'sks' => 144, 'desc' => 'Pengolahan probabilitas dan aktuaria untuk mitigasi risiko bisnis.', 'skills' => ['Actuarial Science', 'Risk Analysis']]
            ]
        ],
        'bisnis-manajemen' => [
            'nama' => 'Bisnis & Manajemen', 'icon' => '📈', 'keyword_db' => 'Bisnis',
            'desc' => 'Mencetak eksekutif, inovator finansial, dan pengusaha muda yang siap mendominasi pasar global.',
            'sambutan' => 'Fakultas Bisnis dan Manajemen berkomitmen untuk mencetak pemimpin yang mampu mengambil keputusan strategis dalam lingkungan yang kompetitif dan dinamis. Kami tidak hanya mengajarkan teori, tetapi juga menanamkan kemampuan analisis, negosiasi, dan eksekusi yang presisi. Di sini, mahasiswa dipersiapkan untuk memahami bagaimana nilai diciptakan, dikelola, dan dikendalikan dalam dunia bisnis modern.',
            'strata' => 'S1 (Sarjana)', 'masa_studi' => '8 Semester', 'gelar' => 'S.M / S.E / S.Bns',
            'prodi' => [
                ['nama' => 'Manajemen', 'sks' => 144, 'desc' => 'Strategi manajerial, kepemimpinan organisasi, dan pemasaran digital.', 'skills' => ['Corporate Strategy', 'Digital Marketing']],
                ['nama' => 'Akuntansi', 'sks' => 144, 'desc' => 'Audit forensik, akuntansi publik, dan sistem perpajakan modern.', 'skills' => ['Forensic Auditing', 'Tax Strategy']],
                ['nama' => 'Bisnis Digital', 'sks' => 144, 'desc' => 'Fokus pada e-commerce, startup unicorn, dan financial technology.', 'skills' => ['Startup Incubation', 'Fintech']]
            ]
        ],
        'desain-kreatif' => [
            'nama' => 'Desain & Media Kreatif', 'icon' => '🎨', 'keyword_db' => 'Desain',
            'desc' => 'Titik temu antara estetika seni rupa murni dan kecanggihan teknologi media digital interaktif.',
            'sambutan' => 'Fakultas Desain dan Media Kreatif merupakan ruang eksplorasi di mana ide, visual, dan narasi bertemu untuk membentuk makna. Kami mendorong mahasiswa untuk tidak hanya menciptakan karya yang estetis, tetapi juga memiliki kedalaman konsep dan relevansi terhadap konteks sosial. Kreativitas, bagi kami, adalah hasil dari observasi yang tajam dan pemahaman yang mendalam.',
            'strata' => 'S1 & D4 Terapan', 'masa_studi' => '8 Semester', 'gelar' => 'S.Ds / S.Tr.Ds',
            'prodi' => [
                ['nama' => 'Desain Komunikasi Visual', 'sks' => 144, 'desc' => 'Eksplorasi mendalam mengenai tipografi, ilustrasi, branding korporat, dan kampanye visual multimedia.', 'skills' => ['Brand Identity', 'Typography']],
                ['nama' => 'Animasi dan Media Digital', 'sks' => 144, 'desc' => 'Fokus pada rekayasa efek visual (VFX), pemodelan 3D, dan sinematografi digital.', 'skills' => ['3D Modeling', 'VFX Compositing']],
                ['nama' => 'Desain Produk', 'sks' => 144, 'desc' => 'Perancangan ergonomi produk industri dan antarmuka UI/UX digital yang berpusat pada manusia.', 'skills' => ['UI/UX Design', 'Industrial Design']]
            ]
        ]
    ];

    // ==========================================
    // HALAMAN UTAMA FAKULTAS
    // ==========================================
    public function index()
    {
        // Lempar semua daftar fakultas ke halaman index, 
        // siapa tahu kamu butuh menampilkan kotak-kotak daftarnya
        $data = [
            'title' => 'Daftar Fakultas | Astryveil',
            'listFakultas' => $this->dataFakultas 
        ];

        return view('fakultas/index', $data); 
    }

    // ==========================================
    // HALAMAN DETAIL FAKULTAS (Yang kodenya kamu kasih tadi)
    // ==========================================
    public function detail($slug)
    {
        $pimpinanModel = new StrukturPimpinanModel();
        
        // Jika slug tidak ditemukan, redirect ke halaman akademik utama
        if (!array_key_exists($slug, $this->dataFakultas)) {
            return redirect()->to('/akademik');
        }

        $data = [
            'title'    => 'Fakultas ' . $this->dataFakultas[$slug]['nama'] . ' | Astryveil',
            
            // INI YANG TADI BIKIN ERROR: Mengirim data 1 fakultas spesifik ke View
            'fakultas' => $this->dataFakultas[$slug], 
            
            // Ambil seluruh data pimpinan dari database untuk dicocokkan di View
            'pimpinan' => $pimpinanModel->getPimpinan() 
        ];

        // Pastikan nama file View-nya benar.
        // Di kode aslimu kamu tulis 'fakultas_detail', 
        // pastikan filenya bernama fakultas_detail.php dan ada di folder Views.
        return view('fakultas_detail', $data);
    }
}