@include('admin.layouts.header')

<body class="with-right-section">
    <div class="container">
        <!-- Sidebar Section -->
        @include('admin.layouts.sidebar')
        <!-- End of Sidebar Section -->

        <!-- Main Content -->
        <main>
            <h2 class="section-title-form">Buat Pengumuman</h2>

            <div class="feature-card-pengumuman pengumuman-flex">
                <!-- Bagian kiri: vector -->
                <div class="vector-container">
                    <img src="images/guru-vector.png" alt="Vector" />
                </div>

                <!-- Bagian kanan: form -->
                <div class="form-container">
                    <form action="#" method="POST" class="form-pengumuman">
                        <label for="judul">Judul Pengumuman</label>
                        <div class="input-group">
                            <span class="material-icons-sharp">title</span>
                            <input type="text" name="judul" required />
                        </div>

                        <label for="isi">Isi Pengumuman</label>
                        <div class="input-group">
                            <span class="material-icons-sharp">description</span>
                            <textarea name="isi" rows="4" required></textarea>
                        </div>

                        <!-- Wrapper 2 kolom -->
                        <div class="form-grid">
                            <div class="form-group-pengumuman half">
                                <label><b>Tampilkan Selama</b></label>
                                <select name="durasi" class="form-input">
                                    <option value="1">24 Jam</option>
                                    <option value="3">3 Hari</option>
                                    <option value="7">1 Minggu</option>
                                </select>
                            </div>

                            <div class="form-group-pengumuman half">
                                <label for="ditujukan_untuk"><b>Ditujukan Untuk</b></label>
                                <select name="ditujukan_untuk" class="form-input">
                                    <option value="siswa">Siswa</option>
                                    <option value="guru">Guru</option>
                                    <option value="semua">Semua</option>
                                </select>
                            </div>
                        </div>

                        <!-- Simulasi selain admin (hidden input) -->
                        <!-- <input type="hidden" name="ditujukan_untuk" value="siswa"> -->

                        <button type="submit" class="btn-pengumuman-style">
                            <span class="material-icons-sharp">campaign</span>
                            Kirim Pengumuman
                        </button>
                    </form>
                </div>
            </div>

            <!-- Pengumuman Terakhir -->
            <div class="last-announcement">
                <h2>Pengumuman Terakhir</h2>

                <div class="announcement">
                    <div class="announcement-header">
                        <h4>Contoh Judul Pengumuman</h4>
                        <div class="announcement-actions">
                            <!-- Tombol Edit -->
                            <button class="btn-action btn-edit" onclick="openEditModal(1)">
                                <span class="material-icons-sharp">edit</span>
                            </button>

                            <!-- Tombol Hapus -->
                            <button class="btn-action btn-delete" onclick="hapusPengumuman(1)">
                                <span class="material-icons-sharp">delete</span>
                            </button>
                        </div>
                    </div>
                    <div class="announcement-content">
                        <small><i>15 Juni 2025</i></small>
                        <p>
                            Isi pengumuman contoh. Ini bisa berupa apapun dan akan tampil
                            lengkap di sini.
                        </p>
                        <small class="text-muted">Ditujukan untuk: <b>Siswa</b></small><br />
                        <small class="text-muted">Berlaku sampai: <b>18 Juni 2025</b></small>
                    </div>
                </div>

                <!-- Kalau kosong -->
                <!-- <div class="announcement">
    <p>Tidak ada pengumuman yang anda buat.</p></div> -->
            </div>
        </main>
        <!-- Right Section -->
        <div class="right-section">
            <div class="nav-home">
                <button id="menu-btn"></button>
                <div class="dark-mode">
                    <span class="material-icons-sharp active"> light_mode </span>
                    <span class="material-icons-sharp"> dark_mode </span>
                </div>

                <div class="profile">
                    <div class="info">
                        <p>Hallo, <b>Min</b></p>
                        <small class="text-muted">Admin</small>
                    </div>
                    <div class="profile-photo">
                        <img src="{{ asset('assets/img/smeapng.png') }}" />
                    </div>
                </div>
            </div>
            <!-- End of Nav -->

            <div class="news">
                <iframe src="https://www.instagram.com/reel/DHe7asDy6ob/embed" width="100%" height="335"
                    frameborder="0" scrolling="no" allowtransparency="true">
                </iframe>
            </div>

            {{-- <div class="news">
                <iframe src="https://www.instagram.com/p/DJWiNpzSK2i/embed" width="100%" height="335"
                    frameborder="0" scrolling="no" allowtransparency="true">
                </iframe>
            </div> --}}

            <div class="reminders">
                <div class="header">
                    <h2>Reminders</h2>
                </div>

                <div class="notification">
                    <div class="icon">
                        <span class="material-icons-sharp"> volume_up </span>
                    </div>
                    <div class="content">
                        <div class="info">
                            <h3>Workshop</h3>
                            <small class="text_muted"> 08:00 AM - 12:00 PM </small>
                        </div>
                        <span class="material-icons-sharp"> more_vert </span>
                    </div>
                </div>

                <div class="notification deactive">
                    <div class="icon">
                        <span class="material-icons-sharp"> edit </span>
                    </div>
                    <div class="content">
                        <div class="info">
                            <h3>Workshop</h3>
                            <small class="text_muted"> 08:00 AM - 12:00 PM </small>
                        </div>
                        <span class="material-icons-sharp"> more_vert </span>
                    </div>
                </div>

                <div class="notification add-reminder">
                    <div>
                        <span class="material-icons-sharp"> add </span>
                        <h3>Add Reminder</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.layouts.footer')
