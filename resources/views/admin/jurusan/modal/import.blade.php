<div class="custom-modal" id="modalJurusanImport">
    <div class="custom-modal-content">
        <div class="custom-modal-header bg-success">
            <h5>
                <span class="material-icons-sharp">upload_file</span> Impor Data Jurusan
            </h5>
        </div>

        <div class="custom-modal-body">
            <div class="modal-flex">
                <!-- Ilustrasi -->
                <div class="import-illustration">
                    <span class="material-icons-sharp icon-xl text-success">upload_file</span>
                    <span class="material-icons-sharp icon-md text-primary upload-icon">rule_folder</span>
                </div>

                <!-- Konten -->
                <div class="import-info">
                    <p>
                        Disarankan untuk melakukan impor data jurusan dengan format sebagai
                        berikut:
                    </p>

                    <!-- Tabel contoh -->
                    <div class="example-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nama Jurusan</th>
                                    <th>Kode Jurusan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Rekayasa Perangkat Lunak</td>
                                    <td>RPL</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Tombol Download -->
                    <a href="{{ route('admin_jurusan.template') }}" class="btn-outline btn-sm">
                        <span class="material-icons-sharp">download</span> Download Format
                    </a>

                    <!-- Alert info -->
                    <div class="alert info">
                        <span class="material-icons-sharp">info</span>
                        Pastikan Nama Jurusan dan Kode Jurusan tidak ada yang terduplicate.
                    </div>

                    <div class="alert warning">
                        <span class="material-icons-sharp">warning</span>
                        Pastikan file sesuai format yang disediakan (.xlsx).
                    </div>

                    <!-- Form Upload -->
                    <form id="formImportJurusan" action="{{ route('admin_jurusan.import') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="file-upload-group">
                            <label for="file-upload">Pilih File:</label>
                            <input type="file" id="file-upload" name="file" accept=".xlsx,.xls" required />
                        </div>
                        <small class="note"><span class="material-icons-sharp">info</span> Hanya mendukung
                            .xlsx dari Excel</small>
                    </form>
                </div>
            </div>
        </div>

        <div class="custom-modal-footer">
            <button class="btn-secondary" onclick="closeModalImport('modalJurusanImport')">
                <span class="material-icons-sharp">close</span> Batal
            </button>

            <button class="btn-success" type="submit" form="formImportJurusan" id="btnImportJurusan">
                <span class="spinner" id="spinnerImportJurusan" style="display: none; margin-right: 5px;"></span>
                <span class="material-icons-sharp">upload_file</span> <span id="textBtnImportJurusan">Import</span>
            </button>
        </div>
    </div>
</div>

<script>
    document.getElementById('formImportJurusan').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);
        const btn = document.getElementById('btnImportJurusan');
        const isDark = document.body.classList.contains('dark-mode-variables')

        btn.disabled = true;
        btn.innerHTML =
            `<span class="spinner" id="spinnerImportJurusan" style="display: none; margin-right: 5px;"></span> <span class="material-icons-sharp">upload_file</span> <span id="textBtnImportJurusan">Import</span>`;

        fetch("{{ route('admin_jurusan.import') }}", {
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
                    `<span class="material-icons-sharp">upload_file</span> <span id="textBtnImportJurusan">Import</span>`;

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
                    `<span class="material-icons-sharp">upload_file</span> <span id="textBtnImportJurusan">Import</span>`;

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
