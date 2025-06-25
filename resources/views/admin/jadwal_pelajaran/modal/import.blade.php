<div class="custom-modal" id="modalJadwalImport">
    <div class="custom-modal-content">
        <div class="custom-modal-header bg-success">
            <h5>
                <span class="material-icons-sharp">upload_file</span> Impor Data Jadwal
            </h5>
        </div>

        <div class="custom-modal-body">
            <div class="modal-flex">
                <!-- Ilustrasi -->
                <div class="import-illustration">
                    <span class="material-icons-sharp icon-xl text-success">alarm_add</span>
                    <span class="material-icons-sharp icon-md text-primary upload-icon">rule_folder</span>
                </div>

                <!-- Konten -->
                <div class="import-info">
                    <p>
                        Disarankan untuk melakukan impor data per kelas dengan format
                        sebagai berikut:
                    </p>

                    <!-- Tabel contoh -->
                    <div class="example-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Hari</th>
                                    <th>Jam Mulai</th>
                                    <th>Jam Selesai</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Guru</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Senin</td>
                                    <td>08:00</td>
                                    <td>09:20</td>
                                    <td>Bahasa Indonesia</td>
                                    <td>Dedi Purnama</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Tombol Download -->
                    <a href="{{ route('admin_jadwal_pelajaran.template') }}" class="btn-outline btn-sm">
                        <span class="material-icons-sharp">download</span> Download Format
                    </a>

                    <!-- Alert info -->
                    {{-- <div class="alert warning">
                        <span class="material-icons-sharp">warning</span>
                        Format tanggal lahir wajib dd/mm/yyyy. Jika tidak sesuai, data tidak
                        bisa diimpor.
                    </div> --}}

                    <div class="alert info">
                        <span class="material-icons-sharp">info</span>
                        Pastikan Kelasnya sesuai dengan kelas yang sudah terdaftar di data kelasKu.
                    </div>

                    <!-- Form Upload -->
                    <form id="formImportJadwal" action="{{ route('admin_jadwal_pelajaran.import') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- Kelas -->
                            <div class="form-group half">
                                <label for="import_kelas">Kelas:</label>
                                <select name="kelas_id" id="JadwalKelasImport" class="form-control kelas-jadwal-import" required>
                                    <option value="" disabled>Pilih Jadwal</option>
                                    @foreach ($kelasFilter as $kelas)
                                        <option value="{{ $kelas->id }}">
                                            {{ $kelas->tingkat }} {{ $kelas->jurusan->kode_jurusan }}
                                            {{ $kelas->no_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <!-- Upload file -->
                        <div class="file-upload-group" style="margin-top: 1rem;">
                            <label for="import_file">Pilih File:</label>
                            <input type="file" id="import_file" name="file" accept=".xlsx,.xls" required />
                        </div>
                        <small class="note">
                            <span class="material-icons-sharp">info</span>
                            Hanya mendukung file .xlsx dari Microsoft Excel
                        </small>
                    </form>
                </div>
            </div>
        </div>

        <div class="custom-modal-footer">
            <button class="btn-secondary" onclick="closeModalImport('modalJadwalImport')">
                <span class="material-icons-sharp">close</span> Batal
            </button>

            <button class="btn-success" type="submit" form="formImportJadwal" id="btnImportJadwal">
                <span class="spinner" id="spinnerImportJadwal" style="display: none; margin-right: 5px;"></span>
                <span class="material-icons-sharp">upload_file</span> <span id="textBtnImportJadwal">Import</span>
            </button>
        </div>
    </div>
</div>

<script>
    document.getElementById('formImportJadwal').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);
        const btn = document.getElementById('btnImportJadwal');
        const isDark = document.body.classList.contains('dark-mode-variables')

        btn.disabled = true;
        btn.innerHTML =
            `<span class="spinner" id="spinnerImportJadwal" style="display: none; margin-right: 5px;"></span> <span class="material-icons-sharp">upload_file</span> <span id="textBtnImportJadwal">Import</span>`;

        fetch("{{ route('admin_jadwal_pelajaran.import') }}", {
                method: "POST",
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(res => {
                btn.disabled = false;
                btn.innerHTML =
                    `<span class="material-icons-sharp">upload_file</span> <span id="textBtnImportJadwal">Import</span>`;

                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        html: res.message + (res.errors ?
                            `<br><br><strong>Error:</strong><br>${res.errors.join('<br>')}` : ''
                        ),
                        background: isDark ? getComputedStyle(document.body)
                            .getPropertyValue(
                                '--color-background') : '#fff',
                        color: isDark ? getComputedStyle(document.body)
                            .getPropertyValue(
                                '--color-dark') : '#000',
                        customClass: {
                            popup: isDark ? 'swal-dark' : ''
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Import Gagal Sebagian!',
                        html: res.message + (res.errors ?
                            `<br><br><strong>Error:</strong><br>${res.errors.join('<br>')}` : ''
                        ),
                        background: isDark ? getComputedStyle(document.body)
                            .getPropertyValue(
                                '--color-background') : '#fff',
                        color: isDark ? getComputedStyle(document.body)
                            .getPropertyValue(
                                '--color-dark') : '#000',
                        customClass: {
                            popup: isDark ? 'swal-dark' : ''
                        }
                    });
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML =
                    `<span class="material-icons-sharp">upload_file</span> <span id="textBtnImportJadwal">Import</span>`;

                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Error',
                    text: 'Gagal mengimport data. Silakan coba lagi.',
                    background: isDark ? getComputedStyle(document.body)
                        .getPropertyValue(
                            '--color-background') : '#fff',
                    color: isDark ? getComputedStyle(document.body)
                        .getPropertyValue(
                            '--color-dark') : '#000',
                    customClass: {
                        popup: isDark ? 'swal-dark' : ''
                    }
                });

                console.error('Import error:', err);
            });
    });
</script>

