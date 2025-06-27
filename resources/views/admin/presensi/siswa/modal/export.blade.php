<div class="custom-modal" id="modalPresensiSiswaExport">
    <div class="custom-modal-content">
        <div class="custom-modal-header bg-success">
            <h5>
                <span class="material-icons-sharp">file_present</span> Export Data Siswa
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
                        <a href="#" onclick="event.preventDefault(); exportPresensiSiswaPDF();"
                            class="btn-export pdf">
                            <span class="material-icons-sharp">picture_as_pdf</span> .pdf
                        </a>
                        <a href="#" onclick="event.preventDefault(); exportPresensiSiswaXLSX();"
                            class="btn-export excel">
                            <span class="material-icons-sharp">grid_on</span> .xlsx
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="custom-modal-footer">
            <button class="btn-secondary" onclick="closeModalExport('modalPresensiSiswaExport')">
                <span class="material-icons-sharp">close</span> Batal
            </button>
        </div>
    </div>
</div>

<script>
    function exportPresensiSiswaPDF() {
        const params = new URLSearchParams();

        const lihatBerdasarkan = document.getElementById('lihatBerdasarkan')?.value;
        const tanggal = document.getElementById('tanggal')?.value;
        const rangeTanggal = document.getElementById('range_tanggal')?.value;
        const kelas = document.getElementById('kelas_id')?.value;

        if (lihatBerdasarkan) {
            params.append('lihatBerdasarkan', lihatBerdasarkan);
        }

        if (lihatBerdasarkan === 'kelas' && tanggal) {
            params.append('tanggal', tanggal);
        }

        if (lihatBerdasarkan === 'tanggal' && rangeTanggal) {
            params.append('range_tanggal', rangeTanggal);
        }

        if (kelas) {
            params.append('kelas', kelas);
        }

        window.location.href = "{{ route('admin_presensi_siswa.export.pdf') }}?" + params.toString();
    }
</script>

<script>
    function exportPresensiSiswaXLSX() {
        const params = new URLSearchParams();

        const lihatBerdasarkan = document.getElementById('lihatBerdasarkan')?.value;
        const tanggal = document.getElementById('tanggal')?.value;
        const rangeTanggal = document.getElementById('range_tanggal')?.value;
        const kelas = document.getElementById('kelas_id')?.value;

        if (lihatBerdasarkan) {
            params.append('lihatBerdasarkan', lihatBerdasarkan);
            params.append('filter_by', lihatBerdasarkan);
        }

        if (lihatBerdasarkan === 'kelas' && tanggal) {
            params.append('tanggal', tanggal);
        }

        if (lihatBerdasarkan === 'tanggal' && rangeTanggal) {
            params.append('range_tanggal', rangeTanggal);
        }

        if (kelas) {
            params.append('kelas', kelas);
        }

        window.location.href = "{{ route('admin_presensi_siswa.export.xlsx') }}?" + params.toString();
    }
</script>
