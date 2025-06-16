<div class="custom-modal" id="modalGuruImport">
    <div class="custom-modal-content">
        <div class="custom-modal-header bg-success">
            <h5>
                <span class="material-icons-sharp">upload_file</span> Impor Data Guru
            </h5>
        </div>

        <div class="custom-modal-body">
            <div class="modal-flex">
                <!-- Ilustrasi -->
                <div class="import-illustration">
                    <span class="material-icons-sharp icon-xl text-success">person_add</span>
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
                                    <th>NIS</th>
                                    <th>Nama Guru</th>
                                    <th>Tgl. Lahir</th>
                                    <th>No. HP</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1234567</td>
                                    <td>Gyatrox</td>
                                    <td>22/09/2000</td>
                                    <td>08123456789</td>
                                    <td>gyatrox@gmail.com</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Tombol Download -->
                    <a href="{{ route('admin_guru.template') }}" class="btn-outline btn-sm">
                        <span class="material-icons-sharp">download</span> Download Format
                    </a>

                    <!-- Alert info -->
                    <div class="alert warning">
                        <span class="material-icons-sharp">warning</span>
                        Format tanggal lahir wajib dd/mm/yyyy. Jika tidak sesuai, data tidak
                        bisa diimpor.
                    </div>

                    <div class="alert info">
                        <span class="material-icons-sharp">info</span>
                        Guru akan otomatis bisa login dengan username dan password awal
                        berupa tanggal lahir (ddmmyyyy).
                    </div>

                    <!-- Form Upload -->
                    <form id="formImportGuru" action="{{ route('admin_guru.import') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <!-- Upload file -->
                        <div class="file-upload-group">
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
            <button class="btn-secondary" onclick="closeModalImport('modalGuruImport')">
                <span class="material-icons-sharp">close</span> Batal
            </button>

            <button class="btn-success" type="submit" form="formImportGuru" id="btnImportGuru">
                <span class="spinner" id="spinnerImportGuru" style="display: none; margin-right: 5px;"></span>
                <span class="material-icons-sharp">upload_file</span> <span id="textBtnImportGuru">Import</span>
            </button>
        </div>
    </div>
</div>

<script>
    document.getElementById('formImportGuru').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);
        const btn = document.getElementById('btnImportGuru');

        btn.disabled = true;
        btn.innerHTML =
            `<span class="spinner" id="spinnerImportGuru" style="display: none; margin-right: 5px;"></span> <span class="material-icons-sharp">upload_file</span> <span id="textBtnImportGuru">Import</span>`;

        fetch("{{ route('admin_guru.import') }}", {
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
                    `<span class="material-icons-sharp">upload_file</span> <span id="textBtnImportGuru">Import</span>`;

                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        html: res.message + (res.errors ?
                            `<br><br><strong>Error:</strong><br>${res.errors.join('<br>')}` : ''
                        )
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Import Gagal Sebagian!',
                        html: res.message + (res.errors ?
                            `<br><br><strong>Error:</strong><br>${res.errors.join('<br>')}` : ''
                        )
                    });
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML =
                    `<span class="material-icons-sharp">upload_file</span> <span id="textBtnImportGuru">Import</span>`;

                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Error',
                    text: 'Gagal mengimport data. Silakan coba lagi.',
                });

                console.error('Import error:', err);
            });
    });
</script>
