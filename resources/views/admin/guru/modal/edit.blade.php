<div class="modal" id="modalGuruEdit{{ $item->id }}">
    <div class="modal-dialog-guru modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header-custom">
                <div class="modal-header-icon">
                    <img src="{{ asset('assets/img/teacher.png') }}" alt="Guru Icon">
                </div>
                <div class="modal-header-text">
                    <h5>Edit Data Guru</h5>
                    <p>Pastikan data yang kamu Edit benar</p>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="modal-body-custom">
                <form id="formEditGuru{{ $item->id }}" action="{{ route('admin_guru.update', $item->id) }}"
                    method="POST">
                    @csrf



                    <div class="form-group">
                        <label for="nip">NIP</label>
                        <input type="text" id="nip" name="edit_nip"
                            class="@error('edit_nip') is-invalid @enderror" value="{{ $item->nip }}">
                        <small>
                            @error('edit_nip')
                                <div style="color: red">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="nama">Nama Guru</label>
                        <input type="text" id="nama" name="edit_nama"
                            class="@error('edit_nama') is-invalid @enderror" value="{{ $item->nama }}">
                        <small>
                            @error('edit_nama')
                                <div style="color: red">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="edit_username"
                            class="@error('edit_username') is-invalid @enderror" value="{{ $item->user->username }}">
                        <small>
                            @error('edit_username')
                                <div style="color: red">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="date" id="tanggal_lahir" name="edit_tanggal_lahir"
                            class="@error('edit_tanggal_lahir') is-invalid @enderror"
                            value="{{ $item->tanggal_lahir }}">
                        <small>
                            @error('edit_tanggal_lahir')
                                <div style="color: red">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>

                    <div class="form-grid">
                        <div class="form-group half">
                            <label for="no_hp">No HP</label>
                            <input type="text" id="no_hp" name="edit_no_hp" placeholder="08xxxxxxxx"
                                class="@error('edit_no_hp') is-invalid @enderror" value="{{ $item->no_hp }}">
                            <small>
                                @error('edit_no_hp')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>

                        <div class="form-group half">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="edit_email" placeholder="xxx@gmail.com"
                                class="@error('edit_email') is-invalid @enderror" value="{{ $item->email }}">
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
                <button class="btn-secondary" onclick="closeModalEdit('modalGuruEdit{{ $item->id }}')">
                    <span class="material-icons-sharp">close</span> Batal
                </button>

                <button class="btn-success-ac" type="submit" form="formEditGuru{{ $id }}"
                    id="btnEditGuru{{ $id }}">
                    <span class="spinner" id="spinnerEditGuru{{ $id }}"
                        style="display: none; margin-right: 5px;"></span>
                    <span class="material-icons-sharp">add_home</span>
                    <span id="textBtnEditGuru{{ $id }}">Simpan</span>
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
