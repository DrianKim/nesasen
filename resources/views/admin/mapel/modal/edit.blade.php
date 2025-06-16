<div class="modal" id="modalMapelEdit{{ $item->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header-custom">
                <div class="modal-header-icon">
                    <img src="images/classroom.png" alt="Mapel Icon">
                </div>
                <div class="modal-header-text">
                    <h5>Edit Data Mata Pelajaran</h5>
                    <p>Perbarui informasi kelas dengan benar</p>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="modal-body-custom">
                <form id="formEditMapel{{ $item->id }}" action="{{ route('admin_mapel.update', $item->id) }}"
                    method="POST">
                    @csrf

                    <input type="hidden" name="from_edit_mapel" value="{{ $item->id }}">

                    <div class="form-grid">
                        <div class="form-group half">
                            <label for="nama">Nama Mapel</label>
                            <input type="text" id="nama_mapel" name="nama_mapel"
                                class="@error('nama_mapel') is-invalid @enderror" value="{{ $item->nama_mapel }}" required>
                            @error('nama_mapel')
                                <div style="color: red">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group half">
                            <label for="kode">Kode Mapel</label>
                            <input type="text" id="kode_mapel" name="kode_mapel"
                                class="@error('kode_mapel') is-invalid @enderror" value="{{ $item->kode_mapel }}" required>
                            @error('kode_mapel')
                                <div style="color: red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer-custom">
                <button class="btn-secondary" onclick="closeModalEdit('modalMapelEdit{{ $item->id }}')">
                    <span class="material-icons-sharp">close</span> Batal
                </button>

                <button class="btn-success-ac" type="submit" form="formEditMapel{{ $id }}"
                    id="btnEditMapel{{ $id }}">
                    <span class="spinner" id="spinnerEditMapel{{ $id }}"
                        style="display: none; margin-right: 5px;"></span>
                    <span class="material-icons-sharp">add_home</span>
                    <span id="textBtnEditMapel{{ $id }}">Simpan</span>
                </button>
            </div>
        </div>
    </div>
</div>
