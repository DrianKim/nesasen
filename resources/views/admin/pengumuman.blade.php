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
                    <img src="{{ asset('img/atmin-vector.png') }}" alt="Vector" />
                </div>

                <!-- Bagian kanan: form -->
                <div class="form-container">
                    <form action="{{ route('admin_pengumuman.store') }}" method="POST" class="form-pengumuman">
                        @csrf
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
                                <select name="ditujukan_untuk" class="form-input untuk">
                                    <option value="siswa">Siswa</option>
                                    <option value="guru">Guru</option>
                                    <option value="semua">Semua</option>\
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

            @once
                @include('admin.pengumuman.edit')
            @endonce

            <!-- Pengumuman Terakhir -->
            <div class="last-announcement">
                <h2>Pengumuman Terakhir</h2>
                @forelse($pengumumen as $p)
                    <div class="announcement">
                        <div class="announcement-header">
                            <h4>{{ $p->judul }}</h4>
                            <div class="announcement-actions">
                                <!-- Tombol Edit -->
                                <button class="btn-action btn-edit" onclick="openEditModal({{ $p->id }})">
                                    <span class="material-icons-sharp">edit</span>
                                </button>
                                <!-- Tombol Hapus -->
                                <button class="btn-action btn-delete" onclick="hapusPengumuman({{ $p->id }})">
                                    <span class="material-icons-sharp">delete</span>
                                </button>
                            </div>
                        </div>
                        <small>Dibuat pada
                            <i>{{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d F Y') }}</i></small>
                        <p>{{ $p->isi }}</p>
                        <small>Untuk: {{ ucfirst($p->ditujukan_untuk) }}</small><br>
                        <small>Habis:
                            {{ \Carbon\Carbon::parse($p->kadaluarsa_sampai)->translatedFormat('d F Y') }}</small>
                    </div>
                @empty
                    <div class="announcement">
                        <p>Belum ada pengumuman yang kamu buat.</p>
                    </div>
                @endforelse
            </div>
        </main>
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
                        <p>Hallo, <b>Min</b></p>
                        <small class="text-muted">Admin</small>
                    </div>
                    <div class="profile-photo">
                        <img src="images/profile-1.jpg" />
                    </div>
                </div>
            </div>
            <!-- End of Nav -->

            {{-- <div class="news">
                <iframe src="https://www.instagram.com/reel/DHe7asDy6ob/embed" width="100%" height="335"
                    frameborder="0" scrolling="no" allowtransparency="true">
                </iframe>
            </div> --}}

            <div class="news hide">
                <iframe src="https://www.instagram.com/p/DJWiNpzSK2i/embed" width="100%" height="335"
                    frameborder="0" scrolling="no" allowtransparency="true">
                </iframe>
            </div>

            <div class="reminders hide">
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

    <script>
        function openEditModal(id) {
            fetch(`/pengumuman/${id}/edit`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById("edit_judul").value = data.judul;
                    document.getElementById("edit_isi").value = data.isi;
                    document.getElementById("edit_durasi").value = data.durasi;

                    const form = document.getElementById("formEditPengumuman");
                    form.action = `/admin/pengumuman/${id}`;
                    form.dataset.id = id;

                    document.getElementById("modalEditPengumuman").style.display = "block";
                })
                .catch(err => {
                    console.error(err);
                    alert("Gagal mengambil data.");
                });
        }

        function closeModalEdit() {
            document.getElementById("modalEditPengumuman").style.display = "none";
        }
    </script>

    <script>
        function hapusPengumuman(id) {
            // Deteksi dark mode aktif atau enggak
            const isDark = document.body.classList.contains('dark-mode-variables');

            Swal.fire({
                title: 'Yakin mau hapus?',
                text: "Pengumuman akan hilang permanen!",
                icon: 'warning',
                showCancelButton: true,
                iconColor: '#e7586e',
                confirmButtonColor: '#e7586e',
                cancelButtonColor: '#43c6c9',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                background: isDark ? '#181a1e' : '#fff',
                color: isDark ? '#fff' : '#000',
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/pengumuman/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        })
                        .then(res => res.json())
                        .then(res => {
                            if (res.success) {
                                Swal.fire({
                                    title: 'Terhapus!',
                                    text: 'Pengumuman berhasil dihapus.',
                                    icon: 'success',
                                    confirmButtonColor: '#43c6c9',
                                    background: isDark ? '#181a1e' : '#fff',
                                    color: isDark ? '#fff' : '#000',
                                }).then(() => location.reload());
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: 'Pengumuman gagal dihapus.',
                                    icon: 'error',
                                    background: isDark ? '#1e1e2f' : '#fff',
                                    color: isDark ? '#fff' : '#000',
                                });
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            Swal.fire({
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menghapus.',
                                icon: 'error',
                                background: isDark ? '#1e1e2f' : '#fff',
                                color: isDark ? '#fff' : '#000',
                            });
                        });
                }
            });
        }
    </script>

    @include('admin.layouts.footer')
