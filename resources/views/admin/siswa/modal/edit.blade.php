<div class="modal" id="modalSiswaEdit{{ $item->id }}">
    <div class="modal-dialog-siswa modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header-custom">
                <div class="modal-header-icon">
                    <img src="images/student.png" alt="Siswa Icon">
                </div>
                <div class="modal-header-text">
                    <h5>Edit Data Siswa</h5>
                    <p>Pastikan data yang kamu Edit benar</p>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="modal-body-custom">
                <form id="formEditSiswa{{ $item->id }}" action="{{ route('admin_siswa.update', $item->id) }}"
                    method="POST">
                    @csrf

                    <input type="hidden" name="from_edit_siswa" value="{{ $item->id }}">

                    <div class="form-grid">
                        <div class="form-group half">
                            <label for="nisn">NISN <small class="text-muted">(Opsional)</small></label>
                            <input type="text" id="nisn" name="edit_nisn" value="{{ $item->nisn }}"
                                class="@error('edit_nisn') is-invalid @enderror">
                            <small>
                                @error('edit_nisn')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>

                        <div class="form-group half">
                            <label for="nis">NIS</label>
                            <input type="text" id="nis" name="edit_nis" value="{{ $item->nis }}"
                                class="@error('edit_nis') is-invalid @enderror" required>
                            <small>
                                @error('edit_nis')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nama">Nama Siswa</label>
                        <input type="text" id="nama" name="edit_nama" value="{{ $item->nama }}"
                            class="@error('edit_nama') is-invalid @enderror" required>
                        <small>
                            @error('edit_nama')
                                <div style="color: red">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="edit_username" value="{{ $item->user->username }}"
                            class="@error('edit_username') is-invalid @enderror" required>
                        <small>
                            @error('edit_username')
                                <div style="color: red">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>

                    <div class="form-grid">
                        <div class="form-group half">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <input type="date" id="tanggal_lahir" name="edit_tanggal_lahir"
                                value="{{ $item->tanggal_lahir }}"
                                class="@error('edit_tanggal_lahir') is-invalid @enderror" required>
                            <small>
                                @error('edit_tanggal_lahir')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>

                        <div class="form-group half">
                            <label for="kelas">Kelas</label>
                            <select id="kelas" name="edit_kelas_id"
                                class="form-control select-kelas-edit @error('edit_kelas_id') is-invalid @enderror"
                                required>
                                <option value="" disabled>Pilih Kelas</option>
                                @foreach ($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}"
                                        {{ $item->kelas_id == $kelas->id ? 'selected' : '' }}>
                                        {{ $kelas->tingkat }} {{ $kelas->jurusan->kode_jurusan }}
                                        {{ $kelas->no_kelas }}
                                    </option>
                                @endforeach
                            </select>
                            <small>
                                @error('edit_kelas_id')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group half">
                            <label for="no_hp">No HP</label>
                            <input type="text" id="no_hp" name="edit_no_hp" placeholder="08xxxxxxxx"
                                value="{{ $item->no_hp }}" class="@error('edit_no_hp') is-invalid @enderror" required>
                            <small>
                                @error('edit_no_hp')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>

                        <div class="form-group half">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="edit_email" placeholder="xxx@gmail.com"
                                value="{{ $item->email }}" class="@error('edit_email') is-invalid @enderror" required>
                            <small>
                                @error('edit_email')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>
                    </div>

                    <!-- Tombol Ubah Password -->
                    <div class="form-group half">
                        <button type="button" class="btn-warning-outline"
                            onclick="togglePasswordSection({{ $item->id }})">
                            <span class="material-icons-sharp">key</span> Ubah Password
                        </button>
                    </div>

                    <!-- Form Ganti Password -->
                    <div id="passwordSection{{ $item->id }}" style="display: none">
                        <div class="form-group">
                            <label for="new_password">Password Baru</label>
                            <input type="password" id="new_password" name="new_password"
                                class="@error('new_password') is-invalid @enderror">
                            <small>
                                @error('new_password')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password Baru</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="@error('password_confirmation') is-invalid @enderror">
                            <small>
                                @error('password_confirmation')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer-custom">
                <button class="btn-secondary" onclick="closeModalEdit('modalSiswaEdit{{ $item->id }}')">
                    <span class="material-icons-sharp">close</span> Batal
                </button>

                <button class="btn-success-ac" type="submit" form="formEditSiswa{{ $item->id }}"
                    id="btnEditSiswa{{ $id }}">
                    <span class="spinner" id="spinnerEditSiswa{{ $id }}"
                        style="display: none; margin-right: 5px;"></span>
                    <span class="material-icons-sharp">add_home</span>
                    <span id="textBtnEditSiswa{{ $id }}">Simpan</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePasswordSection(id) {
        const section = document.getElementById("passwordSection" + id);
        section.style.display = section.style.display === "none" ? "block" : "none";
    }
</script>
