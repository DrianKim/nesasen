<aside>
    <div class="toggle">
        <div class="logo">
            <img src="images/ls-logo.png" />
        </div>
        <div class="close" id="close-btn">
            <span class="material-icons-sharp"> close </span>
        </div>
    </div>

    <div class="sidebar">
        <div class="menu-scrollable">
            <a href="{{ route('admin.index') }}">
                <span class="material-icons-sharp"> dashboard </span>
                <h3>Beranda</h3>
            </a>
            <a href="buat-pengumuman.html">
                <span class="material-icons-sharp"> campaign </span>
                <h3>Buat Pengumuman</h3>
            </a>
            <a href="{{ route('admin_kelas.index') }}">
                <span class="material-icons-sharp"> local_library </span>
                <h3>Data Kelas</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp"> co_present </span>
                <h3>KelasKu</h3>
            </a>
            <a href="{{ route('admin_siswa.index') }}">
                <span class="material-icons-sharp"> groups </span>
                <h3>Data Siswa</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp"> school </span>
                <h3>Siswa Lulus</h3>
            </a>
            <a href="{{ route('admin_guru.index') }}">
                <span class="material-icons-sharp"> groups_2 </span>
                <h3>Data Guru</h3>
            </a>
            <a href="{{ route('admin_jadwal_pelajaran.index') }}">
                <span class="material-icons-sharp"> assignment </span>
                <h3>Jadwal Pelajaran</h3>
                <!-- <span class="message-count">27</span> -->
            </a>
            <a href="{{ route('admin_jurusan.index') }}">
                <span class="material-icons-sharp"> work </span>
                <h3>Daftar Jurusan</h3>
            </a>
            <a href="{{ route('admin_mapel.index') }}">
                <span class="material-icons-sharp"> menu_book </span>
                <h3>Daftar Mapel</h3>
            </a>
            <a href="{{ route('admin_presensi_siswa.index') }}">
                <span class="material-icons-sharp"> person </span>
                <h3>Presensi Siswa</h3>
            </a>
            <a href="{{ route('admin_presensi_guru.index') }}">
                <span class="material-icons-sharp"> person_2 </span>
                <h3>Presensi Guru</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp"> badge </span>
                <h3>Presensi Per-Mapel</h3>
            </a>
            <a href="{{ route('admin_izin_siswa.index') }}">
                <span class="material-icons-sharp"> contact_mail </span>
                <h3>Izin Siswa</h3>
            </a>
            <a href="{{ route('admin_izin_guru.index') }}">
                <span class="material-icons-sharp"> contact_phone </span>
                <h3>Izin Guru</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp"> grading </span>
                <h3>Nilai Pelajaran</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp"> history_edu</span>
                <h3>Rata-rata Nilai Kelas</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp"> star </span>
                <h3>Nilai Akhir Semester</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp"> date_range </span>
                <h3>Tahun Ajaran</h3>
            </a>
        </div>

        <a href="{{ route('logout') }}" class="btn-logout">
            <span class="material-icons-sharp"> logout </span>
            <h3>Logout</h3>
        </a>
    </div>
</aside>
