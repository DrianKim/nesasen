<!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
    Menu Admin
</div>  

<!-- Nav Item - Data Umum -->
<li class="nav-item {{ $menu_admin_umum_kelas ?? $menu_admin_umum_mapel ?? $menu_admin_umum_jurusan ?? '' }}">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"
        aria-controls="collapseTwo">
        <i class="fas fa-fw fa-layer-group"></i>
        <span>Data Umum</span>
    </a>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="py-2 rounded collapse-inner">
            <a class="text-white collapse-item" href="{{ route('admin_umum_jurusan.index') }}">Jurusan</a>
            <a class="text-white collapse-item" href="{{ route('admin_umum_kelas.index') }}">Kelas</a>
            <a class="text-white collapse-item" href="{{ route('admin_umum_mapel.index') }}">Mata Pelajaran</a>
            {{-- <a class="text-white collapse-item" href="#">Semester</a>
            <a class="text-white collapse-item" href="#">Tahun Ajaran</a> --}}
        </div>
    </div>
</li>

<!-- Nav Item - Data Siswa -->
<li class="nav-item {{ $menu_admin_data_murid ?? '' }}">
    <a class="nav-link" href="{{ route('admin_murid.index') }}">
        <i class="fas fa-fw fa-graduation-cap"></i>
        <span>Data Murid</span></a>
</li>

<!-- Nav Item - Data Guru -->
<li class="nav-item {{ $menu_admin_data_guru ?? '' }}">
    <a class="nav-link" href="{{ route('admin_guru.index') }}">
        <i class="fas fa-fw fa-chalkboard-teacher"></i>
        <span>Data Guru</span></a>
</li>

<!-- Nav Item - Data Guru -->
<li class="nav-item {{ $menu_admin_data_walas ?? '' }}">
    <a class="nav-link" href="{{ route('admin_walas.index') }}">
        <i class="fas fa-fw fa-chalkboard-teacher"></i>
        <span>Data Wali Kelas</span></a>
</li>

<!-- Nav Item - Pengumuman -->
<li class="nav-item">
    <a class="nav-link" href="#">
        <i class="fas fa-fw fa-clipboard"></i>
        <span>Pengumuman (Opsional)</span></a>
</li>
