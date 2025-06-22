<div class="custom-modal" id="modalKelasKuExport">
    <div class="custom-modal-content">
        <div class="custom-modal-header bg-success">
            <h5>
                <span class="material-icons-sharp">file_present</span> Export Data KelasKu
            </h5>
        </div>

        <div class="custom-modal-body">
            <div class="modal-flex">
                <!-- Ilustrasi -->
                <div class="import-illustration">
                    <span class="material-icons-sharp icon-xl text-success">file_present</span>
                    <span class="material-icons-sharp icon-md text-primary upload-icon">upload</span>
                </div>

                <!-- Konten Export -->
                <div class="export-options">
                    <p>Pilih salah satu format sesuai kebutuhan Anda!</p>

                    <div class="export-buttons">
                        <a href="{{ route('admin_kelasKu.export.pdf') }}" class="btn-export pdf">
                            <span class="material-icons-sharp">picture_as_pdf</span> .pdf
                        </a>
                        <a href="{{ route('admin_kelasKu.export.xlsx') }}" class="btn-export excel">
                            <span class="material-icons-sharp">grid_on</span> .xlsx
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="custom-modal-footer">
            <button class="btn-secondary" onclick="closeModalExport('modalKelasKuExport')">
                <span class="material-icons-sharp">close</span> Batal
            </button>
        </div>
    </div>
</div>
