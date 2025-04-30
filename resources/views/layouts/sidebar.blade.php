<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon">
            <i class="fas fa-school"></i>
        </div>
        <div class="mx-2 sidebar-brand-text">M-KELAS</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ $menuDashboard ?? '' }}">
        <a class="py-2 nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Beranda</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Notifikasi -->
    {{-- <li class="nav-item">
        <a class="py-2 nav-link" href="#">
            <i class="fas fa-fw fa-bell"></i>
            <span>Notifikasi</span>
            <span class="ml-2 badge badge-pill badge-danger">3</span>
        </a>
    </li>

    <!-- Nav Item - Pesan -->
    <li class="nav-item">
        <a class="py-2 nav-link" href="#">
            <i class="fas fa-fw fa-envelope"></i>
            <span>Pesan</span>
            <span class="ml-2 badge badge-pill badge-danger">7</span>
        </a>
    </li> --}}


    <!-- ADMIN MENU -->
    @if (auth()->user()->role_id == 1)
        <!-- Heading -->
        <div class="sidebar-heading">
            Menu
        </div>

        {{-- kelas --}}
        <li class="nav-item {{ $menu ?? '' }}">
            <a class="py-2 nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseKelas"
                aria-expanded="false" aria-controls="collapseKelas">
                <i class="fas fa-fw fa-book"></i>
                <span>Daftar Kelas</span>
            </a>
            <div id="collapseKelas" class="collapse" aria-labelledby="headingKelas" data-parent="#accordionSidebar">
                <div class="py-2 bg-white rounded collapse-inner">
                    <a class="collapse-item" href="{{ route('admin_kelas.index') }}">
                        <i class="fas fa-fw fa-angle-right"></i> Seluruh Kelas
                    </a>
                    <a class="collapse-item" href="{{ route('admin_kelasKu.index') }}">
                        <i class="fas fa-fw fa-angle-right"></i> KelasKu
                    </a>
                </div>
            </div>
        </li>

        {{-- data siswa --}}
        <li class="nav-item {{ $menu ?? '' }}">
            <a class="py-2 nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSiswa"
                aria-expanded="false" aria-controls="collapseSiswa">
                <i class="fas fa-fw fa-users"></i>
                <span>Data Siswa</span>
            </a>
            <div id="collapseSiswa" class="collapse" aria-labelledby="headingSiswa" data-parent="#accordionSidebar">
                <div class="py-2 bg-white rounded collapse-inner">
                    <a class="collapse-item" href="{{ route('admin_siswa.index') }}">
                        <i class="fas fa-fw fa-angle-right"></i> Semua siswa
                    </a>
                    <a class="collapse-item" href="#">
                        <i class="fas fa-fw fa-angle-right"></i> Siswa Lulus (Opsional)
                    </a>
                </div>
            </div>
        </li>

        {{-- guru --}}
        <li class="nav-item {{ $menu ?? '' }}#">
            <a class="py-2 nav-link" href="{{ route('admin_guru.index') }}">
                <i class="fas fa-fw fa-chalkboard-teacher"></i>
                <span>Data Guru</span>
            </a>
        </li>

        {{-- pelajaran & jadwal --}}
        <li class="nav-item {{ $menu ?? '' }}">
            <a class="py-2 nav-link collapsed" href="#" data-toggle="collapse"
                data-target="#collapsePelajaranJadwal" aria-expanded="false" aria-controls="collapsePelajaranJadwal">
                <i class="fas fa-fw fa-book"></i>
                <span>Pelajaran & Jadwal</span>
            </a>
            <div id="collapsePelajaranJadwal" class="collapse" aria-labelledby="headingPelajaranJadwal"
                data-parent="#accordionSidebar">
                <div class="py-2 bg-white rounded collapse-inner">
                    <a class="collapse-item" href="{{ route('admin_jadwal_pelajaran.index') }}">
                        <i class="fas fa-fw fa-angle-right"></i> Jadwal Pelajaran
                    </a>
                    <a class="collapse-item" href="{{ route('admin_jurusan.index') }}">
                        <i class="fas fa-fw fa-angle-right"></i> Daftar Jurusan
                    </a>
                    <a class="collapse-item" href="{{ route('admin_mapel.index') }}">
                        <i class="fas fa-fw fa-angle-right"></i> Daftar Mapel
                    </a>
                </div>
            </div>
        </li>

        {{-- kehadiran --}}
        <li class="nav-item {{ $menu ?? '' }}">
            <a class="py-2 nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePresensi"
                aria-expanded="false" aria-controls="collapsePresensi">
                <i class="fas fa-fw fa-calendar-check"></i>
                <span>Presensi</span>
            </a>
            <div id="collapsePresensi" class="collapse" aria-labelledby="headingPresensi"
                data-parent="#accordionSidebar">
                <div class="py-2 bg-white rounded collapse-inner">
                    <a class="collapse-item" href="{{ route('admin_presensi_siswa.index') }}">
                        <i class="fas fa-fw fa-angle-right"></i> Presensi Siswa
                    </a>
                    <a class="collapse-item" href="{{ route('admin_presensi_guru.index') }}">
                        <i class="fas fa-fw fa-angle-right"></i> Presensi Guru
                    </a>
                    <a class="collapse-item" href="{{ route('admin_presensi_per_mapel.index') }}">
                        <i class="fas fa-fw fa-angle-right"></i> Presensi Per-Mapel
                    </a>
                </div>
            </div>
        </li>

        {{-- perizinan --}}
        <li class="nav-item {{ $menu ?? '' }}">
            <a class="py-2 nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePerizinan"
                aria-expanded="false" aria-controls="collapsePerizinan">
                <i class="fas fa-fw fa-envelope-open-text"></i>
                <span>Perizinan</span>
            </a>
            <div id="collapsePerizinan" class="collapse" aria-labelledby="headingPerizinan"
                data-parent="#accordionSidebar">
                <div class="py-2 bg-white rounded collapse-inner">
                    <a class="collapse-item" href="{{ route('admin_izin_siswa.index') }}">
                        <i class="fas fa-fw fa-angle-right"></i> Izin Siswa
                    </a>
                    <a class="collapse-item" href="{{ route('admin_izin_guru.index') }}">
                        <i class="fas fa-fw fa-angle-right"></i> Izin Guru
                    </a>
                </div>
            </div>
        </li>

        {{-- penilaian siswa --}}
        <li class="nav-item {{ $menu ?? '' }}">
            <a class="py-2 nav-link collapsed" href="#" data-toggle="collapse"
                data-target="#collapsePenilaianSiswa" aria-expanded="false" aria-controls="collapsePenilaianSiswa">
                <i class="fas fa-fw fa-clipboard-list"></i>
                <span>Penilaian Siswa</span>
            </a>
            <div id="collapsePenilaianSiswa" class="collapse" aria-labelledby="headingPenilaianSiswa"
                data-parent="#accordionSidebar">
                <div class="py-2 bg-white rounded collapse-inner">
                    <a class="collapse-item" href="#">
                        <i class="fas fa-fw fa-angle-right"></i> Nilai Tugas
                    </a>
                    <a class="collapse-item" href="#">
                        <i class="fas fa-fw fa-angle-right"></i> Nilai Akhir Semester
                    </a>
                    <a class="collapse-item" href="#">
                        <i class="fas fa-fw fa-angle-right"></i> Rata-rata Nilai Kelas
                    </a>
                </div>
            </div>
        </li>

        {{-- rapor --}}
        <li class="nav-item {{ $menu ?? '' }}#">
            <a class="py-2 nav-link" href="#">
                <i class="fas fa-fw fa-clipboard-list"></i>
                <span>Rapor Siswa</span>
            </a>
        </li>

        {{-- tahun ajaran --}}
        <li class="nav-item {{ $menu ?? '' }}#">
            <a class="py-2 nav-link" href="#">
                <i class="fas fa-fw fa-calendar-alt"></i>
                <span>Tahun Ajaran</span>
            </a>
        </li>
    @endif

    <!-- GURU/WALI KELAS MENU -->
    @if (auth()->user()->role_id == 2 || auth()->user()->role_id == 3)
        <div class="sidebar-heading">
            Area Pengajar
        </div>

        <!-- Nav Item - Profil -->
        <li class="nav-item {{ $menuProfil ?? '' }}">
            <a class="py-2 nav-link" href="{{ route('profil.index') }}">
                <i class="fas fa-fw fa-user-circle"></i>
                <span>Profil Saya</span>
            </a>
        </li>

        <!-- Akademik -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAkademik"
                aria-expanded="false" aria-controls="collapseAkademik">
                <i class="fas fa-fw fa-graduation-cap"></i>
                <span>Akademik</span>
            </a>
            <div id="collapseAkademik" class="collapse" data-parent="#accordionSidebar">
                <div class="py-2 bg-white rounded collapse-inner">
                    <a class="collapse-item" href="{{ route('jadwal_mengajar.index') }}">
                        <i class="fas fa-calendar-alt fa-sm me-1"></i> Jadwal Mengajar
                    </a>
                    <a class="collapse-item" href="#">
                        <i class="fas fa-pen fa-sm me-1"></i> Input Nilai
                    </a>
                    <a class="collapse-item" href="#">
                        <i class="fas fa-user-check fa-sm me-1"></i> Presensi Siswa
                    </a>
                </div>
            </div>
        </li>

        <!-- Materi & Tugas -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMateri"
                aria-expanded="false" aria-controls="collapseMateri">
                <i class="fas fa-fw fa-book"></i>
                <span>Materi & Tugas</span>
            </a>
            <div id="collapseMateri" class="collapse" data-parent="#accordionSidebar">
                <div class="py-2 bg-white rounded collapse-inner">
                    <a class="collapse-item" href="#">
                        <i class="fas fa-upload fa-sm me-1"></i> Upload Materi
                    </a>
                    <a class="collapse-item" href="#">
                        <i class="fas fa-tasks fa-sm me-1"></i> Buat Tugas
                    </a>
                    <a class="collapse-item" href="#">
                        <i class="fas fa-star fa-sm me-1"></i> Penilaian Tugas
                    </a>
                </div>
            </div>
        </li>

        <!-- Wali Kelas -->
        @if (auth()->user()->role_id == 2)
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <div class="sidebar-heading">
                Wali Kelas
            </div>
            <!-- Walas -->
            <li class="nav-item {{ $menuWalas ?? '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseWalas"
                    aria-expanded="false" aria-controls="collapseWalas">
                    <i class="fas fa-fw fa-graduation-cap"></i>
                    <span>Kelas X RPL 1</span>
                </a>
                <div id="collapseWalas" class="collapse" data-parent="#accordionSidebar">
                    <div class="py-2 bg-white rounded collapse-inner">
                        <a class="collapse-item" href="{{ route('data_kelas.index') }}">
                            <i class="fas fa-circle fa-sm me-1"></i> Data Kelas
                        </a>
                        <a class="collapse-item" href="{{ route('rekap_presensi.index') }}">
                            <i class="fas fa-pen fa-sm me-1"></i> Rekap Presensi
                        </a>
                        {{-- <a class="collapse-item" href="#">
                        <i class="fas fa-user-check fa-sm me-1"></i> Presensi Siswa
                    </a> --}}
                    </div>
                </div>
            </li>
        @endif
    @endif

    <!-- SISWA MENU -->
    @if (auth()->user()->role_id == 4)
        <!-- Heading -->
        <div class="sidebar-heading">
            Area Siswa
        </div>

        <!-- Nav Item - Profil -->
        <li class="nav-item {{ $menuProfil ?? '' }}">
            <a class="py-2 nav-link" href="{{ route('profil.index') }}">
                <i class="fas fa-fw fa-user-circle"></i>
                <span>Profil Saya</span>
            </a>
        </li>

        <!-- Nav Item - Akademik Siswa -->
        <li class="nav-item">
            <a class="py-2 nav-link collapsed" href="#" data-toggle="collapse"
                data-target="#collapseAkademikSiswa" aria-expanded="false" aria-controls="collapseAkademikSiswa">
                <i class="fas fa-fw fa-graduation-cap"></i>
                <span>Akademik</span>
            </a>
            <div id="collapseAkademikSiswa" class="collapse" aria-labelledby="headingAkademikSiswa"
                data-parent="#accordionSidebar">
                <div class="py-2 bg-white rounded collapse-inner">
                    <a class="collapse-item" href="{{ route('jadwal_pelajaran.index') }}">
                        <i class="fas fa-fw fa-angle-right"></i> Jadwal Pelajaran
                    </a>
                    <a class="collapse-item" href="#">
                        <i class="fas fa-fw fa-angle-right"></i> Nilai
                    </a>
                    <a class="collapse-item" href="#">
                        <i class="fas fa-fw fa-angle-right"></i> Presensi
                    </a>
                </div>
            </div>
        </li>

        <!-- Nav Item - Tugas Siswa -->
        <li class="nav-item">
            <a class="py-2 nav-link collapsed" href="#" data-toggle="collapse"
                data-target="#collapseTugasSiswa" aria-expanded="false" aria-controls="collapseTugasSiswa">
                <i class="fas fa-fw fa-tasks"></i>
                <span>Tugas & Materi</span>
            </a>
            <div id="collapseTugasSiswa" class="collapse" aria-labelledby="headingTugasSiswa"
                data-parent="#accordionSidebar">
                <div class="py-2 bg-white rounded collapse-inner">
                    <a class="collapse-item" href="#">
                        <i class="fas fa-fw fa-angle-right"></i> Materi Pelajaran
                    </a>
                    <a class="collapse-item" href="#">
                        <i class="fas fa-fw fa-angle-right"></i> Tugas
                    </a>
                    <a class="collapse-item" href="#">
                        <i class="fas fa-fw fa-angle-right"></i> Kumpulkan Tugas
                    </a>
                </div>
            </div>
        </li>
    @endif


    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="border-0 rounded-circle" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->
