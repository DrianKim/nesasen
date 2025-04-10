        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                    {{-- <i class="fas fa-laugh-wink"></i> --}}
                <div class="mx-3 sidebar-brand-text">SMKN 1 Subang</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Menu Pengguna
            </div>

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ $menuDashboard ?? '' }}">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-fw fa-house-user"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Nav Item - Profil -->
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Profil</span>
                </a>
            </li>

        @if (auth()->user()->role_id == 1)
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Menu Admin
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            {{-- <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Components</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="py-2 bg-white rounded collapse-inner">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a class="collapse-item" href="buttons.html">Buttons</a>
                        <a class="collapse-item" href="cards.html">Cards</a>
                    </div>
                </div>
            </li> --}}

            <!-- Nav Item - Data Umum -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-layer-group"></i>
                    <span>Data Umum</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="py-2 rounded collapse-inner">
                        <a class="text-white collapse-item" href="#">Kelas</a>
                        <a class="text-white collapse-item" href="#">Semester</a>
                        <a class="text-white collapse-item" href="#">Tahun Ajaran</a>
                        <a class="text-white collapse-item" href="#">Mata Pelajaran</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Data Siswa -->
            <li class="nav-item {{ $menu_admin_data_murid ?? '' }}">
                <a class="nav-link" href="{{ route('kurikulum_data_murid') }}">
                    <i class="fas fa-fw fa-graduation-cap"></i>
                    <span>Data Murid</span></a>
            </li>

            <!-- Nav Item - Data Guru -->
            <li class="nav-item {{ $menu_admin_data_guru ?? '' }}">
                <a class="nav-link" href="{{ route('kurikulum_data_guru') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Data Guru</span></a>
            </li>

            <!-- Nav Item - Data Guru -->
            <li class="nav-item {{ $menu_admin_data_walas ?? '' }}">
                <a class="nav-link" href="{{ route('kurikulum_data_walas') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Data Wali Kelas</span></a>
            </li>

            <!-- Nav Item - Pengumuman -->
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-clipboard"></i>
                    <span>Pengumuman (Opsional)</span></a>
            </li>

            @endif

            @if (auth()->user()->role_id == 2)
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Menu Wali Kelas
            </div>

            <!-- Nav Item - Absensi Siswa -->
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-list"></i>
                    <span>Absensi Siswa</span></a>
            </li>

            <!-- Nav Item - Rekap Presensi -->
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-file"></i>
                    <span>Rekap Presensi</span></a>
            </li>

            <!-- Nav Item - Pengumuman -->
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-clipboard"></i>
                    <span>Catatan Wali Kelas (Opsional)</span></a>
            </li>
            @endif

            @if (auth()->user()->role_id == 3)
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Menu Guru
            </div>

            <!-- Nav Item - Jadwal Mengajar -->
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-list"></i>
                    <span>Jadwal Mengajar</span></a>
            </li>

            <!-- Nav Item - Rekap Presensi -->
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-file"></i>
                    <span>Rekap Presensi</span></a>
            </li>
            @endif

            @if (auth()->user()->role_id == 4)
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Menu Murid
            </div>

            <!-- Nav Item - Data Siswa -->
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-marker"></i>
                    <span>Tugas</span></a>
            </li>

            <!-- Nav Item - Data Guru -->
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-chalkboard"></i>
                    <span>Absensi</span></a>
            </li>

            <!-- Nav Item - Pengumuman -->
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-clipboard"></i>
                    <span>Catatan (Opsional)</span></a>
            </li>
            @endif

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="border-0 rounded-circle" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->
