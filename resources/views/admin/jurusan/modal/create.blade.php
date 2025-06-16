<div class="modal" id="modalJurusanTambah">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header-custom">
                <div class="modal-header-icon">
                    <img src="images/classroom.png" alt="Kelas Icon">
                </div>
                <div class="modal-header-text">
                    <h5>Tambahkan Data Jurusan</h5>
                    <p>Pastikan data yang kamu input benar</p>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="modal-body-custom">
                <form id="formTambahJurusan" action="{{ route('admin_jurusan.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="from_tambah_jurusan" value="1">

                    <div class="form-grid">
                        <div class="form-group half">
                            <label for="nama">Nama Jurusan</label>
                            <input type="text" id="nama_jurusan" name="nama_jurusan"
                                class="@error('nama_jurusan') is-invalid @enderror" value="{{ old('nama_jurusan') }}">
                            @error('nama_jurusan')
                                <div style="color: red">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group half">
                            <label for="kode">Kode Jurusan</label>
                            <input type="text" id="kode_jurusan" name="kode_jurusan"
                                class="@error('kode_jurusan') is-invalid @enderror" value="{{ old('kode_jurusan') }}">
                            @error('kode_jurusan')
                                <div style="color: red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="info-box">
                        <div class="alert info">
                            <span class="material-icons-sharp">info</span>
                            Yang dimaksud Kode Jurusan adalah Nama Jurusan yang disingkat,
                            contoh : Rekayasa Perangkat Lunak = RPL.
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer-custom">
                <button class="btn-secondary" onclick="closeModal('modalJurusanTambah')">
                    <span class="material-icons-sharp">close</span> Batal
                </button>

                <button class="btn-success-ac" type="submit" form="formTambahJurusan" id="btnTambahJurusan">
                    <span class="spinner" id="spinnerTambahJurusan" style="display: none; margin-right: 5px;"></span>
                    <span class="material-icons-sharp">add_home</span> <span id="textBtnTambahJurusan">Tambah</span>
                </button>
            </div>
        </div>
    </div>
</div>
