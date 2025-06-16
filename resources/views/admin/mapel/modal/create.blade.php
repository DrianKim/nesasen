<div class="modal" id="modalMapelTambah">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header-custom">
                <div class="modal-header-icon">
                    <img src="images/classroom.png" alt="Kelas Icon">
                </div>
                <div class="modal-header-text">
                    <h5>Tambahkan Data Mata Pelajaran</h5>
                    <p>Pastikan data yang kamu input benar</p>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="modal-body-custom">
                <form id="formTambahMapel" action="{{ route('admin_mapel.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="from_tambah_mapel" value="1">

                    <div class="form-grid">
                        <div class="form-group half">
                            <label for="nama">Nama Mapel</label>
                            <input type="text" id="nama_mapel" name="nama_mapel"
                                class="@error('nama_mapel') is-invalid @enderror" value="{{ old('nama_mapel') }}">
                            @error('nama_mapel')
                                <div style="color: red">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group half">
                            <label for="kode">Kode Mapel</label>
                            <input type="text" id="kode_mapel" name="kode_mapel"
                                class="@error('kode_mapel') is-invalid @enderror" value="{{ old('kode_mapel') }}">
                            @error('kode_mapel')
                                <div style="color: red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="info-box">
                        <div class="alert info">
                            <span class="material-icons-sharp">info</span>
                            Yang dimaksud Kode Mapel adalah Nama Mapel yang disingkat,
                            contoh : Bahasa Indonesia = B. Indonesia.
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer-custom">
                <button class="btn-secondary" onclick="closeModal('modalMapelTambah')">
                    <span class="material-icons-sharp">close</span> Batal
                </button>

                <button class="btn-success-ac" type="submit" form="formTambahMapel" id="btnTambahMapel">
                    <span class="spinner" id="spinnerTambahMapel" style="display: none; margin-right: 5px;"></span>
                    <span class="material-icons-sharp">add_home</span> <span id="textBtnTambahMapel">Tambah</span>
                </button>
            </div>
        </div>
    </div>
</div>
