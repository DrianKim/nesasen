<div class="custom-modal" id="modalPresensiGuruExport">
    <div class="custom-modal-content">
        <div class="custom-modal-header bg-success">
            <h5>
                <span class="material-icons-sharp">file_present</span> Export Data Guru
            </h5>
        </div>

        <div class="custom-modal-body">
            <div class="modal-flex">
                <div class="import-illustration">
                    <span class="material-icons-sharp icon-xl text-success">file_present</span>
                    <span class="material-icons-sharp icon-md text-primary upload-icon">upload</span>
                </div>

                <div class="export-options">
                    <p>Pilih salah satu format sesuai kebutuhan Anda!</p>

                    <div class="export-buttons">
                        <a href="#" onclick="event.preventDefault(); exportPresensiGuruPDF();"
                            class="btn-export pdf">
                            <span class="material-icons-sharp">picture_as_pdf</span> .pdf
                        </a>
                        <a href="#" onclick="event.preventDefault(); exportPresensiGuruXLSX();"
                            class="btn-export excel">
                            <span class="material-icons-sharp">grid_on</span> .xlsx
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="custom-modal-footer">
            <button class="btn-secondary" onclick="closeModalExport('modalPresensiGuruExport')">
                <span class="material-icons-sharp">close</span> Batal
            </button>
        </div>
    </div>
</div>

<script>
    function exportPresensiGuruPDF() {
        const params = new URLSearchParams();

        const rangeTanggal = document.getElementById('range_tanggal')?.value;
        if (rangeTanggal) {
            params.append('range_tanggal', rangeTanggal);
        }

        window.location.href = "{{ route('admin_presensi_guru.export.pdf') }}?" + params.toString();
    }
</script>

<script>
    function exportPresensiGuruXLSX() {
        const params = new URLSearchParams();

        const rangeTanggal = document.getElementById('range_tanggal')?.value;
        if (rangeTanggal) {
            params.append('range_tanggal', rangeTanggal);
        }

        window.location.href = "{{ route('admin_presensi_guru.export.xlsx') }}?" + params.toString();
    }
</script>
