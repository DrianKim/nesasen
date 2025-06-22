@include('admin.layouts.header')

<body class="with-right-section">
    <div class="container">
        <!-- Sidebar Section -->
        @include('admin.layouts.sidebar')
        <!-- End of Sidebar Section -->

        <!-- Main Content -->
        <main>
            <h2 class="analyse-title">Analisis</h2>
            <!-- Analyses -->
            <div class="analyse">
                <div class="sales">
                    <div class="status">
                        <div class="info">
                            <h3>Total Siswa Terdaftar</h3>
                            <h1>{{ $totalSiswa }}</h1>
                        </div>
                        <div class="icon-vector">
                            <img src="{{ asset('img/many-siswa-vector.png') }}" alt="Icon Siswa" />
                        </div>
                    </div>
                </div>
                <div class="visits">
                    <div class="status">
                        <div class="progress-box">
                            <h3>Total Siswa Perempuan</h3>
                            <h1>{{ $totalPerempuan }}</h1>
                            <div class="bar-container">
                                <div class="bar-fill" style="width: {{ $persenPerempuan }}%"></div>
                            </div>
                            <small>{{ round($persenPerempuan) }}%</small>
                        </div>
                    </div>
                </div>
                <div class="searches">
                    <div class="status">
                        <div class="progress-box">
                            <h3>Total Siswa Laki-laki</h3>
                            <h1>{{ $totalLaki }}</h1>
                            <div class="bar-container-laki">
                                <div class="bar-fill-laki" style="width: {{ $persenLaki }}%"></div>
                            </div>
                            <small>{{ round($persenLaki) }}%</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- End of Analyses -->

            <div class="pengumuman">
                <h2>Pengumuman</h2>
                <div class="announcement">
                    <h4>Judul Pengumuman 1</h4>
                    <small>Dibuat pada <i>11 Juni 2025</i></small>
                    <p>Isi pengumuman pertama yang ditampilkan di sini.</p>
                    <small class="text-muted">Ditujukan untuk: <b>Semua</b></small><br />
                    <small class="text-muted">Akan hilang pada <b>15 Juni 2025</b></small>
                </div>
                <div class="announcement">
                    <h4>Judul Pengumuman 2</h4>
                    <small>Dibuat pada <i>10 Juni 2025</i></small>
                    <p>Isi pengumuman kedua yang ditampilkan di sini.</p>
                    <small class="text-muted">Ditujukan untuk: <b>Siswa</b></small><br />
                    <small class="text-muted">Akan hilang pada <b>16 Juni 2025</b></small>
                </div>
                <div class="announcement">
                    <h4>Judul Pengumuman 3</h4>
                    <small>Dibuat pada <i>11 Juni 2025</i></small>
                    <p>Isi pengumuman ketiga yang ditampilkan di sini.</p>
                    <small class="text-muted">Ditujukan untuk: <b>Semua</b></small><br />
                    <small class="text-muted">Akan hilang pada <b>15 Juni 2025</b></small>
                </div>
            </div>
            <!-- End of Recent Orders -->
        </main>
        <!-- End of Main Content -->

        <!-- Right Section -->
        <div class="right-section">
            <div class="nav">
                <button id="menu-btn">
                    <span class="material-icons-sharp"> menu </span>
                </button>
                <div class="dark-mode">
                    <span class="material-icons-sharp active"> light_mode </span>
                    <span class="material-icons-sharp"> dark_mode </span>
                </div>

                <div class="profile">
                    <div class="info">
                        <p>Hallo, <b>{{ Auth::user()->name_admin }}</b></p>
                        <small class="text-muted">Admin</small>
                    </div>
                    <div class="profile-photo">
                        <img src="{{ asset('assets/img/smeapng.png') }}" />
                    </div>
                </div>
            </div>
            <!-- End of Nav -->

            {{-- <div class="news">
                <iframe src="https://www.instagram.com/reel/DHe7asDy6ob/embed" width="100%" height="335"
                    frameborder="0" scrolling="no" allowtransparency="true">
                </iframe>
            </div> --}}

            <div class="news">
                <iframe src="https://www.instagram.com/p/DJWiNpzSK2i/embed" width="100%" height="335"
                    frameborder="0" scrolling="no" allowtransparency="true">
                </iframe>
            </div>

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
