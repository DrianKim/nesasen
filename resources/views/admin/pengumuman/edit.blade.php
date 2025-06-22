<!-- Modal Edit Pengumuman -->
<div class="modal-edit-pengumuman" id="modalEditPengumuman">
    <div class="modal-edit-content">
        <!-- Header -->
        <div class="modal-edit-header">
            <span class="material-icons-sharp">edit</span>
            Edit Pengumuman
        </div>

        <!-- Body -->
        <div class="modal-edit-body">
            <form id="formEditPengumuman" method="POST">
                @csrf
                @method('PUT')
                <label for="edit_judul"><b>Judul Pengumuman</b></label>
                <div class="input-group">
                    <span class="material-icons-sharp">title</span>
                    <input type="text" id="edit_judul" name="judul" required />
                </div>

                <label for="edit_isi"><b>Isi Pengumuman</b></label>
                <div class="input-group">
                    <span class="material-icons-sharp">description</span>
                    <textarea id="edit_isi" name="isi" rows="4" required></textarea>
                </div>
                <label for="edit_durasi"><b>Tampilkan Selama</b></label>
                <select id="edit_durasi" name="durasi" class="form-input">
                    <option value="1">24 Jam</option>
                    <option value="3">3 Hari</option>
                    <option value="7">1 Minggu</option>
                </select>
            </form>
        </div>

        <!-- Footer -->
        <div class="modal-edit-footer">
            <button class="btn-secondary" onclick="closeModalEdit()">
                <span class="material-icons-sharp">close</span> Batal
            </button>
            <button class="btn-success" type="submit" form="formEditPengumuman">
                <span class="material-icons-sharp">save</span> Simpan
            </button>
        </div>
    </div>
</div>
