{{-- resources/views/layouts/bottom-navbar.blade.php --}}
<nav class="bottom-navbar d-md-none">

    @if (auth()->user()->role_id == 4)
        <div class="nav-item">
            <a class="nav-link {{ Request::routeIs('siswa.beranda') ? 'active' : '' }}"
                href="{{ route('siswa.beranda') }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Beranda</span>
            </a>
        </div>

        <div class="nav-item">
            <a class="nav-link {{ Request::routeIs('siswa.presensi') ? 'active' : '' }}"
                href="{{ route('siswa.presensi') }}">
                <i class="fas fa-fw fa-user-check"></i>
                <span>Presensi</span>
            </a>
        </div>

        <div class="nav-item">
            <a class="nav-link {{ Request::routeIs('profil.index') ? 'active' : '' }}"
                href="{{ route('profil.index') }}">
                <i class="fas fa-fw fa-user-circle"></i>
                <span>Profil</span>
            </a>
        </div>
    @elseif(auth()->user()->role_id == 2 || auth()->user()->role_id == 3)
        <div class="nav-item">
            <a class="nav-link
            {{-- {{ Request::routeIs('mengajar.*') ? 'active' : '' }} --}}
            " href="#">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>Mengajar</span>
            </a>
        </div>

        <div class="nav-item">
            <a class="nav-link
            {{-- {{ Request::routeIs('tugas.*') ? 'active' : '' }} --}}
            " href="#">
                <i class="fas fa-tasks"></i>
                <span>Tugas</span>
            </a>
        </div>

        <div class="nav-item">
            <a class="nav-link
            {{-- {{ Request::routeIs('nilai.*') ? 'active' : '' }} --}}
            " href="#">
                <i class="fas fa-chart-line"></i>
                <span>Nilai</span>
            </a>
        </div>

        <div class="nav-item">
            <a class="nav-link
            {{-- {{ Request::routeIs('profil.*') ? 'active' : '' }} --}}
            " href="#">
                <i class="fas fa-user"></i>
                <span>Profil</span>
            </a>
        </div>
    @endif
</nav>
