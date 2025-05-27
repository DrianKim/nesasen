<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ketentuan & Kebijakan Privasi - Nesasen</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style-role.css') }}">
    <link href="{{ asset('img/favicon.png') }}" rel="icon">
    <link href="{{ asset('enno/assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">
    <style>
        /* Decorative images - Fixed positioning */
        .decorative-image {
            position: fixed;
            z-index: 1;
            pointer-events: none;
            opacity: 0.3;
        }

        .decorative-image.top-right {
            top: 0;
            right: 0;
            width: 150px;
            height: auto;
        }

        .decorative-image.bottom-left {
            bottom: 0;
            left: 0;
            width: 150px;
            height: auto;
        }

        /* Additional styles for terms and privacy page */
        .content-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin: 20px 0;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border: 2px solid #e8f4fd;
            position: relative;
            z-index: 2;
        }

        .content-section h2 {
            color: #2c5aa0;
            font-size: 1.8em;
            margin-bottom: 20px;
            border-bottom: 3px solid #4a90e2;
            padding-bottom: 10px;
        }

        .content-section h3 {
            text-align: start;
            color: #34495e;
            font-size: 1.3em;
            margin-top: 25px;
            margin-bottom: 15px;
        }

        .content-section h4 {
            text-align: start;
            color: #34495e;
            margin-left: 10px;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .content-section p,
        .content-section li {
            text-align: start;
            line-height: 1.7;
            color: #555;
            font-size: 1rem;
            margin-bottom: 12px;
        }

        .content-section ul,
        .content-section ol {
            text-align: start;
            margin-left: 20px;
            margin-bottom: 20px;
        }

        .content-section li {
            text-align: start;
            margin-bottom: 8px;
        }

        .highlight-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .highlight-box h3 {
            color: white;
            margin-bottom: 10px;
        }

        .highlight-box p {
            color: white;
        }

        .warning-box {
            background: #fff3cd;
            border: 2px solid #ffc107;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }

        .warning-box strong {
            color: #856404;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            margin-bottom: 1px;
            transition: transform 0.3s ease;
            position: relative;
            z-index: 2;
        }

        .back-button:hover {
            transform: translateY(-2px);
            color: white;
        }

        .back-button i {
            margin-right: 8px;
        }

        .container {
            max-width: 1000px;
            position: relative;
            z-index: 2;
            margin: 0 auto;
            padding: 20px;
        }

        .last-updated {
            text-align: center;
            color: #666;
            font-style: italic;
            margin-top: 30px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            position: relative;
            z-index: 2;
        }

        /* Header styling */
        header {
            position: relative;
            z-index: 2;
            text-align: start;
            padding: 20px 0;
        }

        header .logo img {
            // berada di kiri atas
            position: absolute;
            top: 20px;
            left: 20px;
            max-height: 80px;
            width: auto;
        }

        /* Main content */
        main {
            position: relative;
            z-index: 2;
        }

        main h1 {
            text-align: center;
            color: #2c5aa0;
            font-size: 2em !important;
            margin-bottom: 10px;
            font-weight: bold;
        }

        main>p {
            text-align: center;
            color: #666;
            font-size: 1.2em;
            margin-bottom: 30px;
        }

        /* Footer styling */
        footer {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 30px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin-top: 50px;
        }

        footer .social-media {
            margin-bottom: 20px;
        }

        footer .social-media a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: 500;
            transition: opacity 0.3s ease;
        }

        footer .social-media a:hover {
            opacity: 0.8;
            text-decoration: underline;
        }

        footer p {
            margin: 0;
            font-size: 1rem;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        /* Body styling */
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            position: relative;
        }

        /* Back to Top Button - Apply to all screen sizes */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .back-to-top:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        .back-to-top.show {
            display: flex;
            animation: fadeInUp 0.3s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .main-title {
                text-align: center !important;
                font-size: 1.5em !important;
            }

            main>p {
                text-align: center !important;
            }

            .decorative-image.top-right {
                width: 100px;
            }

            .decorative-image.bottom-left {
                width: 100px;
            }

            .content-section {
                padding: 20px;
                margin: 15px 0;
            }

            .content-section h2 {
                font-size: 1.5em;
            }

            .content-section h3 {
                font-size: 1.2em;
            }

            .content-section p,
            .content-section li {
                text-align: start;
                font-size: 0.95rem;
            }

            .container {
                padding: 10px;
            }

            main h1 {
                text-align: center;
                font-size: 1.5em;
            }

            footer .social-media a {
                margin: 0 10px;
                font-size: 0.9rem;
            }

            footer p {
                color: white;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {

            .decorative-image.top-right,
            .decorative-image.bottom-left {
                width: 80px;
            }

            main h1 {
                font-size: 1em;
            }

            main>p {
                font-size: 1em;
            }

            footer .social-media a {
                display: block;
                margin: 5px 0;
            }

            .back-to-top {
                width: 45px;
                height: 45px;
                bottom: 20px;
                right: 20px;
                font-size: 18px;
            }
        }
    </style>
</head>

<body>
    <!-- Hiasan - Fixed positioned decorative elements -->
    <img src="{{ asset('img/corner-right.png') }}" alt="Hiasan Kanan Atas" class="decorative-image top-right" />
    <img src="{{ asset('img/corner-left.png') }}" alt="Hiasan Kiri Bawah" class="decorative-image bottom-left" />

    <!-- Header dengan logo -->
    <header>
        <div class="logo">
            <img src="{{ asset('img/ls-logo.png') }}" alt="Nesasen Logo" />
        </div>
    </header>

    <main class="container">
        <a href="javascript:history.back()" class="back-button">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        <h1 class="main-title">Ketentuan Pengguna & Kebijakan Privasi</h1>
        <p>Aplikasi Nesasen – SMKN 1 Subang</p>

        <div class="highlight-box">
            <h3>Selamat Datang di Nesasen!</h3>
            <p>Dengan mengakses dan menggunakan aplikasi ini, Anda dianggap telah membaca, memahami, dan menyetujui
                seluruh ketentuan serta kebijakan privasi yang berlaku.</p>
        </div>

        <!-- KETENTUAN PENGGUNA -->
        <div class="content-section">
            <h2>📋 Ketentuan Pengguna</h2>

            <h3>1. Persetujuan Penggunaan</h3>
            <p>Dengan menggunakan Nesasen, Anda menyetujui untuk mematuhi seluruh aturan penggunaan aplikasi yang telah
                ditetapkan oleh pihak sekolah.</p>

            <h3>2. Peran & Kewajiban Pengguna</h3>
            <ul>
                <li><strong>Siswa:</strong> Melakukan presensi sesuai jadwal, mengerjakan dan mengumpulkan tugas dari
                    guru melalui aplikasi.</li>
                <li><strong>Guru:</strong> Mengelola presensi siswa, memberikan tugas, dan menilai hasil tugas siswa
                    secara digital.</li>
                <li><strong>Admin:</strong> Mengelola sistem secara keseluruhan, termasuk data pengguna, jadwal
                    pelajaran, dan laporan aktivitas.</li>
            </ul>

            <h3>3. Tanggung Jawab Pengguna</h3>
            <ol>
                <li>Wajib menggunakan akun pribadi dan menjaga kerahasiaannya.</li>
                <li>Memberikan data yang benar saat melakukan presensi dan tugas.</li>
                <li>Presensi hanya diperbolehkan dari area sekolah yang telah ditentukan.</li>
                <li>Foto selfie harus jelas dan sesuai untuk verifikasi kehadiran.</li>
                <li>Dilarang memalsukan lokasi atau informasi apapun di aplikasi.</li>
            </ol>

            <div class="warning-box">
                <strong>Perhatian:</strong> Pelanggaran terhadap ketentuan ini dapat dikenai sanksi sesuai kebijakan
                SMKN 1 Subang.
            </div>

            <h3>4. Batasan Penggunaan</h3>
            <ul>
                <li>Nesasen hanya untuk keperluan kegiatan sekolah.</li>
                <li>Dilarang menggunakan aplikasi untuk kepentingan pribadi yang tidak berkaitan dengan sekolah.</li>
                <li>Dilarang memodifikasi, meretas, atau menyebarkan sistem Nesasen tanpa izin.</li>
            </ul>

            <h3>5. Konsekuensi Pelanggaran</h3>
            <p>Pelanggaran terhadap ketentuan ini dapat berakibat:</p>
            <ul>
                <li>Pembatasan atau pemblokiran akses ke aplikasi.</li>
                <li>Sanksi akademik sesuai kebijakan sekolah.</li>
                <li>Tindakan hukum jika terdapat unsur pelanggaran berat.</li>
            </ul>
        </div>

        <!-- KEBIJAKAN PRIVASI -->
        <div class="content-section">
            <h2>🔒 Kebijakan Privasi</h2>

            <h3>1. Data yang Kami Kumpulkan</h3>

            <h4>a. Data Identitas:</h4>
            <ul>
                <li>Nama lengkap dan NIS/NIP</li>
                <li>Kelas dan jurusan siswa</li>
                <li>Mata pelajaran yang diampu guru</li>
                <li>Kontak seperti email dan nomor HP</li>
            </ul>

            <h4>b. Data Presensi:</h4>
            <ul>
                <li>Jam masuk dan keluar</li>
                <li>Lokasi saat presensi dilakukan</li>
                <li>Foto selfie untuk verifikasi</li>
                <li>Alasan izin atau ketidakhadiran</li>
                <li>Status presensi: hadir, telat, izin, sakit, alfa</li>
            </ul>

            <h4>c. Data Teknis:</h4>
            <ul>
                <li>Alamat IP dan jenis perangkat</li>
                <li>Browser yang digunakan</li>
                <li>Riwayat aktivitas di aplikasi</li>
                <li>Metadata foto selfie</li>
            </ul>

            <h3>2. Penggunaan Informasi</h3>
            <ul>
                <li>Memproses dan mencatat kehadiran</li>
                <li>Menampilkan laporan presensi dan tugas</li>
                <li>Verifikasi identitas melalui foto selfie</li>
                <li>Deteksi penyalahgunaan sistem</li>
                <li>Peningkatan layanan aplikasi</li>
                <li>Notifikasi terkait aktivitas pengguna</li>
            </ul>

            <h3>3. Keamanan & Penyimpanan Data</h3>
            <ul>
                <li>Data disimpan dengan sistem keamanan berlapis</li>
                <li>Hanya pihak berwenang yang dapat mengakses data</li>
                <li>Data sensitif dilindungi dengan enkripsi</li>
                <li>Backup dilakukan secara rutin</li>
            </ul>

            <h3>4. Pembagian Data</h3>
            <p>Kami <strong>TIDAK</strong> membagikan data pribadi tanpa izin, kecuali jika diperlukan untuk:</p>
            <ul>
                <li>Kepentingan internal sekolah</li>
                <li>Informasi kehadiran kepada orang tua/wali</li>
                <li>Proses hukum atau permintaan resmi dari pihak berwenang</li>
            </ul>

            <h3>5. Hak Anda</h3>
            <p>Pengguna berhak untuk:</p>
            <ul>
                <li>Mengakses dan melihat data pribadi yang tersimpan</li>
                <li>Meminta koreksi data jika ada kesalahan</li>
                <li>Mengetahui bagaimana data digunakan</li>
                <li>Menyampaikan keluhan atau pertanyaan terkait privasi</li>
            </ul>

            <div class="warning-box">
                <strong>Catatan:</strong> Foto selfie akan disimpan untuk keperluan verifikasi, dan dapat digunakan
                dalam penyelidikan jika ditemukan ketidaksesuaian data.
            </div>

            <h3>6. Masa Penyimpanan Data</h3>
            <ul>
                <li>Data akan disimpan selama masa aktif sebagai siswa/guru</li>
                <li>Dapat diperpanjang untuk keperluan dokumentasi sekolah</li>
                <li>Foto selfie akan dihapus setelah masa tertentu, kecuali jika dibutuhkan</li>
                <li>Data tidak relevan akan dihapus secara berkala</li>
            </ul>

            <h3>7. Cookies & Teknologi Pelacakan</h3>
            <ul>
                <li>Kami menggunakan cookies untuk menyimpan preferensi pengguna</li>
                <li>Session cookies menjaga login tetap aktif</li>
                <li>Cookies juga digunakan untuk keperluan analitik</li>
                <li>Pengguna bisa mengatur cookies melalui pengaturan browser</li>
            </ul>
        </div>

        <!-- KONTAK DAN PERTANYAAN -->
        <div class="content-section">
            <h2>📞 Kontak dan Pertanyaan</h2>

            <p>Untuk pertanyaan, keluhan, atau bantuan teknis seputar Nesasen, silakan hubungi:</p>

            <h3>Tim IT SMKN 1 Subang</h3>
            <ul>
                <li><strong>Email:</strong> it@smkn1subang.sch.id</li>
                <li><strong>Telepon:</strong> (0260) 411-024</li>
                <li><strong>Alamat:</strong> CQV6+J32, Cigadung, Kec. Subang, Kabupaten Subang, Jawa Barat 41211</li>
            </ul>

            <h3>Jam Layanan</h3>
            <ul>
                <li><strong>Senin - Jumat:</strong> 07:00 - 16:00 WIB</li>
                <li><strong>Sabtu, Minggu, & Hari Libur:</strong> Tutup</li>
            </ul>
        </div>

        <!-- PERUBAHAN KEBIJAKAN -->
        <div class="content-section">
            <h2>📝 Perubahan Ketentuan & Kebijakan</h2>

            <p>SMKN 1 Subang berhak memperbarui ketentuan dan kebijakan Nesasen kapan saja. Perubahan akan diumumkan
                melalui:</p>
            <ul>
                <li>Notifikasi dalam aplikasi</li>
                <li>Pengumuman di website sekolah</li>
                <li>Email ke pengguna terdaftar</li>
            </ul>

            <p>Dengan terus menggunakan Nesasen setelah ada pembaruan, Anda dianggap telah menyetujui perubahan
                tersebut.</p>
        </div>

        <div class="last-updated">
            <p><strong>Terakhir diperbarui:</strong> {{ date('d F Y') }}</p>
            <p><strong>Berlaku efektif:</strong> {{ date('d F Y') }}</p>
        </div>
    </main>


    <!-- Sosial Media Links -->
    <footer>
        <div class="social-media">
            <a href="https://www.youtube.com/@NesasCeren" target="_blank" style="color: #fff;">YouTube</a>
            <a href="https://www.instagram.com/officialsmkn1subang/" target="_blank" style="color: #fff;">Instagram</a>
            <a href="https://www.facebook.com/officialsmkn1subang/" target="_blank" style="color: #fff;">Facebook</a>
        </div>
        <p style="color: white;">&copy; {{ date('Y') }} Made with ❤️ by R & P.</p>
    </footer>

    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop" onclick="scrollToTop()">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script src="{{ asset('sbladmin2/js/sb-admin-2.min.js') }}"></script>
    <script src="{{ asset('sweetalert2/dist/sweetalert2.all.min.js') }}"></script>

    <script>
        // Back to Top functionality
        window.addEventListener('scroll', function() {
            const backToTopButton = document.getElementById('backToTop');
            if (window.pageYOffset > 300) {
                backToTopButton.classList.add('show');
            } else {
                backToTopButton.classList.remove('show');
            }
        });

        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Smooth scroll for back button if it uses anchor
        document.addEventListener('DOMContentLoaded', function() {
            // Add smooth scrolling to all internal links
            const links = document.querySelectorAll('a[href^="#"]');
            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);
                    const targetElement = document.getElementById(targetId);
                    if (targetElement) {
                        targetElement.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>
