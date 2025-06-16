<div class="modal" id="modalSiswaTambah">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header-custom">
                <div class="modal-header-icon">
                    <img src="images/student.png" alt="Siswa Icon" />
                </div>
                <div class="modal-header-text">
                    <h5>Tambahkan Data Siswa</h5>
                    <p>Pastikan data yang kamu input benar</p>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="modal-body-custom">
                <form id="formTambahSiswa" action="{{ route('admin_siswa.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="from_tambah_siswa" value="1">

                    <div class="form-grid">
                        <div class="form-group half">
                            <label for="nisn">NISN <small class="text-muted">(Opsional)</small></label>
                            <input type="text" id="nisn" name="create_nisn"
                                class="@error('create_nisn') is-invalid @enderror" value="{{ old('create_nisn') }}">
                            <small>
                                @error('create_nisn')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>

                        <div class="form-group half">
                            <label for="nis">NIS</label>
                            <input type="text" id="nis" name="create_nis"
                                class="@error('create_nis') is-invalid @enderror" value="{{ old('create_nis') }}">
                            <small>
                                @error('create_nis')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nama">Nama Siswa</label>
                        <input type="text" id="nama" name="create_nama"
                            class="@error('create_nama') is-invalid @enderror" value="{{ old('create_nama') }}">
                        <small>
                            @error('create_nama')
                                <div style="color: red">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="create_username"
                            class="@error('create_username') is-invalid @enderror" value="{{ old('create_username') }}">
                        <small>
                            @error('create_username')
                                <div style="color: red">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>

                    <div class="form-grid">
                        <div class="form-group half">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <input type="date" id="tanggal_lahir" name="create_tanggal_lahir"
                                class="@error('create_tanggal_lahir') is-invalid @enderror"
                                value="{{ old('create_tanggal_lahir') }}">
                            <small>
                                @error('create_tanggal_lahir')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>

                        <div class="form-group half">
                            <label for="kelas">Kelas</label>
                            <select id="kelasModal" name="create_kelas_id"
                                class="form-control @error('create_kelas_id') is-invalid @enderror"
                                value="{{ old('create_kelas_id') }}">
                                <option value="" selected hidden>Pilih Kelas</option>
                                @foreach ($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}"
                                        {{ old('create_kelas_id', $siswa->kelas_id ?? '') == $kelas->id ? 'selected' : '' }}>
                                        {{ $kelas->tingkat }} {{ $kelas->jurusan->kode_jurusan }}
                                        {{ $kelas->no_kelas }}
                                    </option>
                                @endforeach
                            </select>
                            <small>
                                @error('create_kelas_id')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group half">
                            <label for="no_hp">No HP</label>
                            <input type="text" id="no_hp" name="create_no_hp" placeholder="08xxxxxxxx"
                                class="@error('create_no_hp') is-invalid @enderror" value="{{ old('create_no_hp') }}">
                            <small>
                                @error('create_no_hp')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>

                        <div class="form-group half">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="create_email" placeholder="xxx@gmail.com"
                                class="@error('create_email') is-invalid @enderror" value="{{ old('create_email') }}">
                            <small>
                                @error('create_email')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>
                    </div>

                    <div class="info-box">
                        <div class="alert info">
                            <span class="material-icons-sharp">info</span>
                            Siswa akan otomatis bisa login dengan username dan password awal
                            berupa tanggal lahir (ddmmyyyy), dan diwajibkan untuk membuat
                            password baru
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer-custom">
                <button class="btn-secondary" onclick="closeModal('modalSiswaTambah')">
                    <span class="material-icons-sharp">close</span> Batal
                </button>

                <button class="btn-success-ac" type="submit" form="formTambahSiswa" id="btnTambahSiswa">
                    <span class="spinner" id="spinnerTambahSiswa" style="display: none; margin-right: 5px;"></span>
                    <span class="material-icons-sharp">add_home</span> <span id="textBtnTambahSiswa">Tambah</span>
                </button>
            </div>
        </div>
    </div>
</div>
