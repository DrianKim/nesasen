<div class="modal" id="modalEditProfilGuru">
    <div class="modal-dialog-guru modal-md">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header-custom">
                <div class="modal-header-icon">
                    <img src="{{ asset('assets/img/teacher.png') }}" alt="User Icon">
                </div>
                <div class="modal-header-text">
                    <h5>Edit Profil</h5>
                    <p>Update data kamu di sini ya!</p>
                </div>
            </div>

            <!-- Body -->
            <div class="modal-body-custom">
                <form id="formEditProfilGuru" action="{{ route('guru.profil.update') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="nip">NIP <small class="text-muted">(Opsional)</small></label>
                        <input type="text" id="nip" name="nip" value="{{ $guru->nip }}">
                    </div>

                    <div class="form-group">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" value="{{ $guru->nama }}" required>
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="{{ $guru->user->username }}"
                            required>
                    </div>

                    <div class="form-grid">
                        <div class="form-group half">
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="jenis_kelamin">
                                <option value="Laki-laki" {{ $guru->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>
                                    Laki-laki</option>
                                <option value="Perempuan" {{ $guru->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>
                                    Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group half">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                                value="{{ $guru->tanggal_lahir }}">
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="no_hp">No HP</label>
                        <input type="text" id="no_hp" name="no_hp" value="{{ $guru->no_hp }}">
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ $guru->email }}">
                    </div>

                    <!-- Password Toggle -->
                    <div class="form-group half">
                        <button type="button" class="btn-warning-outline" onclick="togglePasswordSection('user')">
                            <span class="material-icons-sharp">key</span> Ganti Password
                        </button>
                    </div>

                    <!-- Password Section -->
                    <div id="passwordSectionuser" style="display: none;">
                        <div class="form-group">
                            <label for="new_password">Password Baru</label>
                            <input type="password" name="new_password">
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation">
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="modal-footer-custom">
                <button type="button" class="btn-secondary" onclick="closeModal('modalEditProfilGuru')">
                    <span class="material-icons-sharp">close</span> Batal
                </button>
                <button type="submit" class="btn-success-ac" form="formEditProfilGuru" id="btnEditProfilGuru">
                    <span class="spinner" id="spinnerEditProfilGuru" style="display: none; margin-right: 5px;"></span>
                    <span class="material-icons-sharp">add_home</span>
                    <span id="textBtnEditProfilGuru">Simpan</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePasswordSection(id) {
        const section = document.getElementById('passwordSection' + id);
        section.style.display = section.style.display === 'none' ? 'block' : 'none';
    }
</script>

<script>
    document.getElementById('formEditProfilGuru').addEventListener('submit', function() {
        document.getElementById('btnEditProfilGuru').disabled = true;
        document.getElementById('spinnerEditProfilGuru').style.display = 'inline-block';
        document.getElementById('textBtnEditProfilGuru').textContent = 'Menyimpan...';
    });
</script>
