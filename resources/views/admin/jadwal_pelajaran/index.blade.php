@extends('admin.layouts.app')

@section('content')
    <div class="content-wrapper">

        <div id="judul-jadwal">
            @include('admin.jadwal_pelajaran.partials.judul', ['selectedKelas' => null])
        </div>

        <div class="skul-container">
            <div class="content-section">
                {{-- Filter --}}
                <div class="filter-wrapper">
                    <div class="filter-top">
                        <div class="filter-bar">

                            <form action="{{ route('admin_jadwal_pelajaran.index') }}" method="GET">
                                <div class="filter-group">
                                    <label for="kelas">Kelas:</label>
                                    <select id="kelas" name="kelas" class="form-control select-kelas-jadwal">
                                        <option selected value="">Semua Kelas</option>
                                        @foreach ($kelasFilter as $kelas)
                                            <option value="{{ $kelas->id }}"
                                                {{ request('kelas') == $kelas->id ? 'selected' : '' }}>
                                                {{ $kelas->tingkat }} {{ $kelas->jurusan->kode_jurusan }}
                                                {{ $kelas->no_kelas }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                        {{-- @include('admin.jadwal_pelajaran.modal.create') --}}
                        @include('admin.jadwal_pelajaran.modal.import')
                        <div class="right-actions">
                            {{-- <button class="btn-tambah" onclick="openModal('modalJadwalTambah')">
                                <span class="material-icons-sharp">add</span>
                            </button> --}}
                            <button class="btn-import" onclick="openModalImport('modalJadwalImport')">
                                <span class="material-icons-sharp">file_present</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="grid-wrapper-relative" style="position: relative;">

                    <div id="loading-indicator" style="display:none;" class="loading-overlay-table">
                        <div class="my-3 d-flex justify-content-center spinner-wrapper">
                            <div class="spinner-border text-primary" role="status">
                                <span class="loading-text">Loading...</span>
                            </div>
                        </div>
                    </div>

                    <div class="jadwal-grid-wrapper">
                        @include('admin.jadwal_pelajaran.partials.grid')
                    </div>

                </div>

            </div>
        </div>
    </div>

    @foreach ($jadwals as $jadwal)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                setupEditFormListenersJadwal({{ $jadwal->id }});
            });
        </script>
    @endforeach

    <script>
        document.getElementById('formImportJadwal').addEventListener('submit', function() {
            document.getElementById('btnImportJadwal').disabled = true;
            document.getElementById('spinnerImportJadwal').style.display = 'inline-block';
            document.getElementById('textBtnImportJadwal').textContent = 'Mengimport...';
        });

        function setupEditFormListenersJadwal(id) {
            const form = document.getElementById('formEditJadwal' + id);
            const btn = document.getElementById('btnEditJadwal' + id);
            const spinner = document.getElementById('spinnerEditJadwal' + id);
            const text = document.getElementById('textBtnEditJadwal' + id);

            if (form) {
                form.addEventListener('submit', function() {
                    if (btn && spinner && text) {
                        btn.disabled = true;
                        spinner.style.display = 'inline-block';
                        text.textContent = 'Menyimpan...';
                    }
                });
            }
        }
    </script>

    <script>
        function openModal() {
            const modal = document.getElementById("modalJadwalTambah");
            if (modal) {
                modal.style.display = 'block';
                setTimeout(() => {
                    modal.classList.add('show');
                }, 10);

                $('.kelas-jadwal').select2({
                    placeholder: 'Cari kelas...',
                    // dropdownParent: $(modal).find('.modal-content'),
                    width: '100%'
                });
            }
        }

        function openModalImport() {
            const modal = document.getElementById("modalJadwalImport");
            if (modal) {
                modal.style.display = 'flex';
                setTimeout(() => {
                    modal.classList.add('show');
                }, 10);

                $('.kelas-jadwal-import').select2({
                    placeholder: 'Cari kelas...',
                    // dropdownParent: $(modal).find('.modal-content'),
                    width: '100%'
                });
            }
        }

        function closeModalImport() {
            const modal = document.getElementById("modalJadwalImport");
            if (modal) {
                modal.classList.remove('show');
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 300);
            }
        }

        function openModalEdit(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'block';
                setTimeout(() => {
                    modal.classList.add('show');
                }, 10);

                const id = modalId.replace('modalJadwalEdit', '');
                setupEditFormListenersJadwal(id);
            }
        }

        function closeModalEdit(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('show');
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 300);
            }
        }
    </script>

    <script>
        function initEditJadwalModal(id) {
            const jamMulai = document.getElementById("jam_mulai_" + id);
            const jamSelesai = document.getElementById("jam_selesai_" + id);
            const durasiDisplay = document.getElementById("durasiDisplay" + id);
            const form = document.getElementById("formEditJadwal" + id);
            const modalId = "modalEditJadwal" + id;
            const isDark = document.body.classList.contains('dark-mode-variables');

            function updateDurasi() {
                const mulai = jamMulai.value;
                const selesai = jamSelesai.value;

                if (mulai && selesai) {
                    const [h1, m1] = mulai.split(":").map(Number);
                    const [h2, m2] = selesai.split(":").map(Number);
                    const totalMenit = (h2 * 60 + m2) - (h1 * 60 + m1);

                    if (totalMenit <= 0) {
                        durasiDisplay.textContent = "❌ Jam selesai harus setelah jam masuk!";
                        durasiDisplay.style.color = "red";
                    } else {
                        const jam = Math.floor(totalMenit / 60);
                        const menit = totalMenit % 60;
                        durasiDisplay.textContent = `${jam > 0 ? jam + ' jam ' : ''}${menit} menit`;
                        durasiDisplay.style.color = "inherit";
                    }
                } else {
                    durasiDisplay.textContent = "-";
                }
            }

            if (jamMulai && jamSelesai) {
                jamMulai.addEventListener("change", updateDurasi);
                jamSelesai.addEventListener("change", updateDurasi);
                updateDurasi();
            }

            if (form) {
                form.addEventListener("submit", function(e) {
                    e.preventDefault();

                    const formData = new FormData(form);

                    fetch(form.action, {
                            method: "POST",
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message,
                                    timer: 2000,
                                    showConfirmButton: false,
                                    background: isDark ? getComputedStyle(document.body)
                                        .getPropertyValue('--color-background') : '#fff',
                                    color: isDark ? getComputedStyle(document.body).getPropertyValue(
                                        '--color-dark') : '#000',
                                    customClass: {
                                        popup: isDark ? 'swal-dark' : ''
                                    }
                                }).then(() => {
                                    closeModalEdit(modalId);
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: data.type || 'error',
                                    title: data.message || 'Gagal!',
                                    html: data.errors ? Object.values(data.errors).map(err =>
                                        `<p>${err[0]}</p>`).join('') : 'Terjadi kesalahan.',
                                    showConfirmButton: true,
                                    background: isDark ? getComputedStyle(document.body)
                                        .getPropertyValue('--color-background') : '#fff',
                                    color: isDark ? getComputedStyle(document.body).getPropertyValue(
                                        '--color-dark') : '#000',
                                    customClass: {
                                        popup: isDark ? 'swal-dark' : ''
                                    }
                                });
                            }
                        })
                        .catch(err => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops!',
                                text: 'Terjadi kesalahan saat mengirim data.',
                                showConfirmButton: true,
                                background: isDark ? getComputedStyle(document.body).getPropertyValue(
                                    '--color-background') : '#fff',
                                color: isDark ? getComputedStyle(document.body).getPropertyValue(
                                    '--color-dark') : '#000',
                                customClass: {
                                    popup: isDark ? 'swal-dark' : ''
                                }
                            });
                        });
                });
            }
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const kelasSelect = document.querySelector('.select-kelas-jadwal');
            const loadingIndicator = document.getElementById('loading-indicator');
            const jadwalGridWrapper = document.querySelector('.jadwal-grid-wrapper');

            let currentRequest = null;

            function getFilterData() {
                const data = new URLSearchParams();
                if (kelasSelect) data.append('kelas', kelasSelect.value);
                return data;
            }

            function loadData() {
                if (!jadwalGridWrapper) return;

                loadingIndicator && (loadingIndicator.style.display = 'flex');

                if (currentRequest) {
                    currentRequest.abort();
                }

                const filterData = getFilterData();

                currentRequest = new XMLHttpRequest();
                currentRequest.open('GET', '{{ route('admin_jadwal_pelajaran.filter') }}?' + filterData.toString(),
                    true);
                currentRequest.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                currentRequest.onload = function() {
                    loadingIndicator && (loadingIndicator.style.display = 'none');
                    currentRequest = null;

                    if (this.status >= 200 && this.status < 400) {
                        try {
                            const response = JSON.parse(this.response);

                            // Ganti isi grid jadwal dengan view yang dikirim dari controller
                            jadwalGridWrapper.innerHTML = response.grid;

                            document.getElementById('judul-jadwal').innerHTML = response.judul;

                            // 🔥 Tambahan ini bro buat re-inisialisasi modal script-nya
                            document.querySelectorAll("[id^='formEditJadwal']").forEach(form => {
                                const id = form.id.replace("formEditJadwal", "");
                                initEditJadwalModal(id);
                            });

                            updateURL(filterData);
                        } catch (error) {
                            console.error('Gagal parse JSON:', error);
                        }
                    } else {
                        console.error('Gagal ambil data:', this.status);
                    }
                };

                currentRequest.onerror = function() {
                    console.error('Connection error');
                    loadingIndicator && (loadingIndicator.style.display = 'none');
                    currentRequest = null;
                };

                currentRequest.send();
            }

            function updateURL(filterData) {
                const newURL = window.location.protocol + '//' + window.location.host +
                    window.location.pathname + '?' + filterData.toString();
                history.pushState({
                    path: newURL
                }, '', newURL);
            }

            if (typeof $ !== 'undefined' && kelasSelect) {
                $(kelasSelect).select2({
                    placeholder: 'Pilih Kelas...',
                    allowClear: true,
                    width: '100%',
                });

                $(kelasSelect).on('change', function() {
                    loadData();
                });
            }
        });
    </script>
@endsection
