<!-- Modal Import Siswa -->
<div class="modal fade" id="modalSiswaImport" tabindex="-1" role="dialog" aria-labelledby="modalSiswaImportLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="text-white modal-header bg-success">
                <h5 class="modal-title" id="modalSiswaImportLabel">
                    <i class="fas fa-file-import me-2"></i>
                    Impor Data Siswa
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <!-- Left side - Illustration -->
                    <div class="col-md-4 d-flex align-items-center justify-content-center">
                        <div class="import-illustration">
                            <div class="illustration-container">
                                <i class="mb-3 fas fa-file-excel fa-5x text-success"></i>
                                <div class="upload-icon">
                                    <i class="fas fa-upload fa-2x text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right side - Content -->
                    <div class="col-md-8">
                        <p class="mb-4">Disarankan untuk melakukan impor data per kelas dengan format sebagai berikut
                        </p>

                        <!-- Example Format -->
                        <div class="mb-4">
                            <h6>Contoh:</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-success">
                                        <tr>
                                            <th class="text-center">NIS</th>
                                            <th class="text-center">Nama Siswa</th>
                                            <th class="text-center">Tgl. Lahir</th>
                                            <th class="text-center">No. HP</th>
                                            <th class="text-center">Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">1234567</td>
                                            <td>Gyatrox</td>
                                            <td class="text-center">22/09/2000</td>
                                            <td class="text-center">08123456789</td>
                                            <td>gyatrox@gmail.com</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Download Template Link -->
                        <div class="mb-4">
                            <a href="{{ route('admin_siswa.template') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-download me-1"></i>
                                Download Format
                            </a>
                        </div>

                        <!-- Info Alerts -->
                        <div class="mb-3 alert alert-warning alert-sm">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Format tanggal lahir dd/mm/yyyy</strong><br>
                            <small>Jika format tanggal lahir tidak sesuai maka data siswa tidak dapat diimpor.</small>
                        </div>

                        <div class="mb-4 alert alert-info alert-sm">
                            <i class="fas fa-key me-2"></i>
                            <strong>Siswa dapat langsung login menggunakan username dan password* dan wajib membuat
                                password baru</strong><br>
                            <small>*Password sementara siswa adalah tanggal lahir dengan format ddmmyyyy</small>
                        </div>

                        <!-- Form Import -->
                        <form id="formImportSiswa" action="{{ route('admin_siswa.import') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Class and Academic Year Selection -->
                            <div class="mb-3 row">
                                <div class="col-md-6">
                                    <label for="import_kelas" class="form-label">Kelas:</label>
                                    <select class="form-select" id="import_kelas" name="kelas_id" required>
                                        <option value="" disabled>Pilih Kelas</option>
                                        @foreach ($kelasFilter as $kelas)
                                            <option value="{{ $kelas->id }}">
                                                {{ $kelas->tingkat }} {{ $kelas->jurusan->kode_jurusan }}
                                                {{ $kelas->no_kelas }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="import_tahun_ajaran" class="form-label">Tahun Ajaran:</label>
                                    <select class="form-select" id="import_tahun_ajaran" name="tahun_ajaran">
                                        @foreach ($tahunAjaranFilter as $tahun)
                                            <option value="{{ $tahun }}"
                                                {{ $tahun == date('Y') . '-' . (date('Y') + 1) ? 'selected' : '' }}>
                                                {{ $tahun }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- File Upload -->
                            <div class="mb-3">
                                <label for="import_file" class="form-label">File:</label>
                                <div class="file-upload-container">
                                    <input type="file" class="form-control" id="import_file" name="file"
                                        accept=".xlsx,.xls" required>
                                    <div class="mt-2 file-upload-info">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Hanya mendukung format .xlsx dari Microsoft Excel
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Batal
                </button>
                <button type="submit" form="formImportSiswa" class="btn btn-success" id="btnImport">
                    <i class="fas fa-file-import me-1"></i>
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .import-illustration {
        text-align: center;
        position: relative;
    }

    .illustration-container {
        position: relative;
        display: inline-block;
    }

    .upload-icon {
        position: absolute;
        bottom: -10px;
        right: -20px;
        background: white;
        border-radius: 50%;
        padding: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .alert-sm {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }

    .file-upload-container {
        border: 2px dashed #dee2e6;
        border-radius: 0.375rem;
        padding: 1rem;
        transition: border-color 0.15s ease-in-out;
    }

    .file-upload-container:hover {
        border-color: #dee2e6;
    }

    .file-upload-container.dragover {
        border-color: #0d6efd;
        background-color: #f8f9fa;
    }

    .table-success th {
        background-color: #198754 !important;
        color: white !important;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .modal-lg {
        max-width: 900px;
    }

    /* Loading state for import button */
    .btn-loading {
        position: relative;
    }

    .btn-loading::after {
        content: "";
        position: absolute;
        width: 16px;
        height: 16px;
        margin: auto;
        border: 2px solid transparent;
        border-top-color: #ffffff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const importForm = document.getElementById('formImportSiswa');
        const importButton = document.getElementById('btnImport');
        const fileInput = document.getElementById('import_file');
        const fileUploadContainer = document.querySelector('.file-upload-container');

        // Handle form submission
        importForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Validate form
            if (!importForm.checkValidity()) {
                e.stopPropagation();
                importForm.classList.add('was-validated');
                return;
            }

            // Show loading state
            importButton.disabled = true;
            importButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Mengimpor...';
            importButton.classList.add('btn-loading');

            // Create FormData
            const formData = new FormData(importForm);

            // Submit via AJAX
            fetch(importForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Success
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message || 'Data siswa berhasil diimpor',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        // Close modal and reload page
                        $('#modalSiswaImport').modal('hide');
                        setTimeout(() => {
                            location.reload();
                        }, 2000);

                    } else {
                        // Error
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message || 'Terjadi kesalahan saat mengimpor data',
                            html: data.errors ? '<ul class="text-start">' + data.errors.map(
                                err => `<li>${err}</li>`).join('') + '</ul>' : undefined
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan sistem, silakan coba lagi'
                    });
                })
                .finally(() => {
                    $('#page-loading-overlay').hide;

                    // Reset button state
                    importButton.disabled = false;
                    importButton.innerHTML = '<i class="fas fa-file-import me-1"></i> Simpan';
                    importButton.classList.remove('btn-loading');
                });
        });

        // File input change handler
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                // Validate file type
                const allowedTypes = [
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/vnd.ms-excel'
                ];

                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Format File Tidak Valid',
                        text: 'Silakan pilih file Excel (.xlsx atau .xls)'
                    });
                    this.value = '';
                    return;
                }

                // Validate file size (max 5MB)
                const maxSize = 5 * 1024 * 1024; // 5MB
                if (file.size > maxSize) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'File Terlalu Besar',
                        text: 'Ukuran file maksimal 5MB'
                    });
                    this.value = '';
                    return;
                }
            }
        });

        // Drag and drop functionality
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileUploadContainer.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            fileUploadContainer.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            fileUploadContainer.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            fileUploadContainer.classList.add('dragover');
        }

        function unhighlight(e) {
            fileUploadContainer.classList.remove('dragover');
        }

        fileUploadContainer.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files.length > 0) {
                fileInput.files = files;
                fileInput.dispatchEvent(new Event('change'));
            }
        }

        // Reset form when modal is closed
        $('#modalSiswaImport').on('hidden.bs.modal', function() {
            importForm.reset();
            importForm.classList.remove('was-validated');
        });
    });
</script>
