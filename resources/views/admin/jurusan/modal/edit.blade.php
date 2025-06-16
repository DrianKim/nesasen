<div class="modal" id="modalJurusanEdit{{ $item->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header-custom">
                <div class="modal-header-icon">
                    <img src="images/classroom.png" alt="Jurusan Icon">
                </div>
                <div class="modal-header-text">
                    <h5>Edit Data Jurusan</h5>
                    <p>Perbarui informasi kelas dengan benar</p>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="modal-body-custom">
                <form id="formEditJurusan{{ $item->id }}" action="{{ route('admin_jurusan.update', $item->id) }}"
                    method="POST">
                    @csrf

                    <input type="hidden" name="from_edit_jurusan" value="{{ $item->id }}">

                    <div class="form-grid">
                        <div class="form-group half">
                            <label for="nama">Nama Jurusan</label>
                            <input type="text" id="nama_jurusan" name="nama_jurusan"
                                class="@error('nama_jurusan') is-invalid @enderror" value="{{ $item->nama_jurusan }}" required>
                            @error('nama_jurusan')
                                <div style="color: red">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group half">
                            <label for="kode">Kode Jurusan</label>
                            <input type="text" id="kode_jurusan" name="kode_jurusan"
                                class="@error('kode_jurusan') is-invalid @enderror" value="{{ $item->kode_jurusan }}" required>
                            @error('kode_jurusan')
                                <div style="color: red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer-custom">
                <button class="btn-secondary" onclick="closeModalEdit('modalJurusanEdit{{ $item->id }}')">
                    <span class="material-icons-sharp">close</span> Batal
                </button>

                <button class="btn-success-ac" type="submit" form="formEditJurusan{{ $id }}"
                    id="btnEditJurusan{{ $id }}">
                    <span class="spinner" id="spinnerEditJurusan{{ $id }}"
                        style="display: none; margin-right: 5px;"></span>
                    <span class="material-icons-sharp">add_home</span>
                    <span id="textBtnEditJurusan{{ $id }}">Simpan</span>
                </button>
            </div>
        </div>
    </div>
</div>
