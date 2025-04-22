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
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Area Pengguna
    </div>

    <!-- Nav Item - Profil -->
    <li class="nav-item">
        <a class="py-2 nav-link" href="#">
            <i class="fas fa-fw fa-user-circle"></i>
            <span>Profil Saya</span>
        </a>
    </li>

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

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- ADMIN MENU -->
    @if(auth()->user()->role_id == 1)
    <!-- Heading -->
    <div class="sidebar-heading">
        Manajemen Admin
    </div>

    <!-- Nav Item - Kurikulum Collapse Menu -->
    <li class="nav-item">
        <a class="py-2 nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseKurikulum"
            aria-expanded="false" aria-controls="collapseKurikulum">
            <i class="fas fa-fw fa-book"></i>
            <span>Kurikulum</span>
        </a>
        <div id="collapseKurikulum" class="collapse" aria-labelledby="headingKurikulum" data-parent="#accordionSidebar">
            <div class="py-2 bg-white rounded collapse-inner">
                <a class="collapse-item" href="{{ route('admin_umum_jurusan.index') }}">
                    <i class="fas fa-fw fa-angle-right"></i> Jurusan
                </a>
                <a class="collapse-item" href="{{ route('admin_umum_kelas.index') }}">
                    <i class="fas fa-fw fa-angle-right"></i> Kelas
                </a>
                <a class="collapse-item" href="{{ route('admin_umum_mapel.index') }}">
                    <i class="fas fa-fw fa-angle-right"></i> Mata Pelajaran
                </a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Pengguna Collapse Menu -->
    <li class="nav-item">
        <a class="py-2 nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePengguna"
            aria-expanded="false" aria-controls="collapsePengguna">
            <i class="fas fa-fw fa-users"></i>
            <span>Pengguna</span>
        </a>
        <div id="collapsePengguna" class="collapse" aria-labelledby="headingPengguna" data-parent="#accordionSidebar">
            <div class="py-2 bg-white rounded collapse-inner">
                {{-- <a class="collapse-item" href="#">
                    <i class="fas fa-fw fa-angle-right"></i> Admin
                </a> --}}
                <a class="collapse-item" href="{{ route('admin_guru.index') }}">
                    <i class="fas fa-fw fa-angle-right"></i> Guru
                </a>
                <a class="collapse-item" href="{{ route('admin_walas.index') }}">
                    <i class="fas fa-fw fa-angle-right"></i> Wali Kelas
                </a>
                <a class="collapse-item" href="{{ route('admin_murid.index') }}">
                    <i class="fas fa-fw fa-angle-right"></i> Murid
                </a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Laporan -->
    <li class="nav-item">
        <a class="py-2 nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLaporan"
            aria-expanded="false" aria-controls="collapseLaporan">
            <i class="fas fa-fw fa-chart-bar"></i>
            <span>Laporan</span>
        </a>
        <div id="collapseLaporan" class="collapse" aria-labelledby="headingLaporan" data-parent="#accordionSidebar">
            <div class="py-2 bg-white rounded collapse-inner">
                <a class="collapse-item" href="#">
                    <i class="fas fa-fw fa-angle-right"></i> Akademik
                </a>
                <a class="collapse-item" href="#">
                    <i class="fas fa-fw fa-angle-right"></i> Presensi
                </a>
                <a class="collapse-item" href="#">
                    <i class="fas fa-fw fa-angle-right"></i> Nilai
                </a>
            </div>
        </div>
    </li>
    @endif

    <!-- GURU/WALI KELAS MENU -->
    @if(auth()->user()->role_id == 2 || auth()->user()->role_id == 3)
    <!-- Heading -->
    <div class="sidebar-heading">
        Area Pengajar
    </div>

    <!-- Nav Item - Akademik -->
    <li class="nav-item">
        <a class="py-2 nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAkademik"
            aria-expanded="false" aria-controls="collapseAkademik">
            <i class="fas fa-fw fa-graduation-cap"></i>
            <span>Akademik</span>
        </a>
        <div id="collapseAkademik" class="collapse" aria-labelledby="headingAkademik" data-parent="#accordionSidebar">
            <div class="py-2 bg-white rounded collapse-inner">
                <a class="collapse-item" href="#">
                    <i class="fas fa-fw fa-angle-right"></i> Jadwal Mengajar
                </a>
                <a class="collapse-item" href="#">
                    <i class="fas fa-fw fa-angle-right"></i> Input Nilai
                </a>
                <a class="collapse-item" href="#">
                    <i class="fas fa-fw fa-angle-right"></i> Presensi Siswa
                </a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Materi & Tugas -->
    <li class="nav-item">
        <a class="py-2 nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMateriTugas"
            aria-expanded="false" aria-controls="collapseMateriTugas">
            <i class="fas fa-fw fa-book-open"></i>
            <span>Materi & Tugas</span>
        </a>
        <div id="collapseMateriTugas" class="collapse" aria-labelledby="headingMateriTugas" data-parent="#accordionSidebar">
            <div class="py-2 bg-white rounded collapse-inner">
                <a class="collapse-item" href="#">
                    <i class="fas fa-fw fa-angle-right"></i> Upload Materi
                </a>
                <a class="collapse-item" href="#">
                    <i class="fas fa-fw fa-angle-right"></i> Buat Tugas
                </a>
                <a class="collapse-item" href="#">
                    <i class="fas fa-fw fa-angle-right"></i> Penilaian Tugas
                </a>
            </div>
        </div>
    </li>
    @endif

    <!-- SISWA MENU -->
    @if(auth()->user()->role_id == 4)
    <!-- Heading -->
    <div class="sidebar-heading">
        Area Siswa
    </div>

    <!-- Nav Item - Akademik Siswa -->
    <li class="nav-item">
        <a class="py-2 nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAkademikSiswa"
            aria-expanded="false" aria-controls="collapseAkademikSiswa">
            <i class="fas fa-fw fa-graduation-cap"></i>
            <span>Akademik</span>
        </a>
        <div id="collapseAkademikSiswa" class="collapse" aria-labelledby="headingAkademikSiswa" data-parent="#accordionSidebar">
            <div class="py-2 bg-white rounded collapse-inner">
                <a class="collapse-item" href="#">
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
        <a class="py-2 nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTugasSiswa"
            aria-expanded="false" aria-controls="collapseTugasSiswa">
            <i class="fas fa-fw fa-tasks"></i>
            <span>Tugas & Materi</span>
        </a>
        <div id="collapseTugasSiswa" class="collapse" aria-labelledby="headingTugasSiswa" data-parent="#accordionSidebar">
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
