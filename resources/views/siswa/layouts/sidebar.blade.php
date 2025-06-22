@php
    $activePage = $activePage ?? '';
@endphp

<aside>
    <div class="toggle">
        <div class="logo">
            <img src="{{ asset('img/ls-logo.png') }}">
        </div>
        <div class="close" id="close-btn">
            <span class="material-icons-sharp"> close </span>
        </div>
    </div>

    <div class="sidebar">
        <a href="{{ route('siswa.beranda') }}" class="{{ request()->routeIs('siswa.beranda') ? 'active' : '' }}">
            <span class="material-icons-sharp"> dashboard </span>
            <h3>Beranda</h3>
        </a>
        <a href="buat-pengumuman.html">
            <span class="material-icons-sharp"> campaign </span>
            <h3>Buat Pengumuman</h3>
        </a>
        <a href="{{ route('siswa.profil') }}" class="{{ request()->routeIs('siswa.profil') ? 'active' : '' }}">
            <span class="material-icons-sharp"> person_2 </span>
            <h3>Profil</h3>
        </a>
        <a href="{{ route('siswa.presensi') }}" class="{{ request()->routeIs('siswa.presensi') ? 'active' : '' }}">
            <span class="material-icons-sharp"> work_history </span>
            <h3>Presensi</h3>
        </a>
        <a href="{{ route('siswa.izin') }}" class="{{ request()->routeIs('siswa.izin') ? 'active' : '' }}">
            <span class="material-icons-sharp"> forward_to_inbox </span>
            <h3>Izin</h3>
        </a>
        <a href="{{ route('siswa.jadwal') }}" class="{{ request()->routeIs('siswa.jadwal') ? 'active' : '' }}">
            <span class="material-icons-sharp"> today </span>
            <h3>Jadwal</h3>
        </a>
        <a href="#">
            <span class="material-icons-sharp"> notifications </span>
            <h3>Notifikasi</h3>
            <span class="message-count">27</span>
        </a>

        <a href="#" class="btn-logout" id="logout-btn-siswa">
            <span class="material-icons-sharp"> logout </span>
            <h3>Logout</h3>
        </a>
    </div>
</aside>
