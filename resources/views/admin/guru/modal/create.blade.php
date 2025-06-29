<div class="modal" id="modalGuruTambah">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header-custom">
                <div class="modal-header-icon">
                    <img src="{{ asset('assets/img/teacher.png') }}" alt="Guru Icon">
                </div>
                <div class="modal-header-text">
                    <h5>Tambahkan Data Guru</h5>
                    <p>Pastikan data yang kamu input benar</p>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="modal-body-custom">
                <form id="formTambahGuru" action="{{ route('admin_guru.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="from_tambah_guru" value="1">

                    <div class="form-group">
                        <label for="nip">NIP</label>
                        <input type="text" id="nip" name="create_nip"
                            class="@error('create_nip') is-invalid @enderror" value="{{ old('create_nip') }}">
                        <small>
                            @error('create_nip')
                                <div style="color: red">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="nama">Nama Guru</label>
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

                    <div class="form-group">
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
                            <label for="email">Email <small class="text-muted">(Pastikan Email yang diinput valid dan
                                    aktif)</small></label>
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
                            Guru akan otomatis bisa login dengan username dan password awal
                            berupa tanggal lahir (ddmmyyyy), dan diwajibkan untuk membuat
                            password baru
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer-custom">
                <button class="btn-secondary" onclick="closeModal('modalGuruTambah')">
                    <span class="material-icons-sharp">close</span> Batal
                </button>

                <button class="btn-success-ac" type="submit" form="formTambahGuru" id="btnTambahGuru">
                    <span class="spinner" id="spinnerTambahGuru" style="display: none; margin-right: 5px;"></span>
                    <span class="material-icons-sharp">add_home</span> <span id="textBtnTambahGuru">Tambah</span>
                </button>
            </div>
        </div>
    </div>
</div>
