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
        <a href="{{ route('guru.beranda') }}" class="{{ request()->routeIs('guru.beranda') ? 'active' : '' }}">
            <span class="material-icons-sharp"> dashboard </span>
            <h3>Beranda</h3>
        </a>
        <a href="{{ route('guru.pengumuman') }}" class="{{ request()->routeIs('guru.pengumuman') ? 'active' : '' }}">
            <span class="material-icons-sharp"> campaign </span>
            <h3>Buat Pengumuman</h3>
        </a>
        <a href="{{ route('guru.profil') }}" class="{{ request()->routeIs('guru.profil') ? 'active' : '' }}">
            <span class="material-icons-sharp"> person_2 </span>
            <h3>Profil</h3>
        </a>
        <a href="{{ route('guru.presensi') }}" class="{{ request()->routeIs('guru.presensi') ? 'active' : '' }}">
            <span class="material-icons-sharp"> work_history </span>
            <h3>Presensi</h3>
        </a>
        <a href="{{ route('guru.izin') }}" class="{{ request()->routeIs('guru.izin') ? 'active' : '' }}">
            <span class="material-icons-sharp"> forward_to_inbox </span>
            <h3>Izin</h3>
        </a>
        <a href="{{ route('guru.jadwal') }}" class="{{ request()->routeIs('guru.jadwal') ? 'active' : '' }}">
            <span class="material-icons-sharp"> today </span>
            <h3>Jadwal</h3>
        </a>
        {{-- <a href="#">
            <span class="material-icons-sharp"> notifications </span>
            <h3>Notifikasi</h3>
            <span class="message-count">27</span>
        </a> --}}

        <a href="#" class="btn-logout" id="logout-btn-guru">
            <span class="material-icons-sharp"> logout </span>
            <h3>Logout</h3>
        </a>
    </div>
</aside>
