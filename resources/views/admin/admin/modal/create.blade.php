<div class="modal" id="modalAdminTambah">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header-custom">
                <div class="modal-header-icon">
                    <img src="{{ asset('assets/img/admin.png') }}" alt="Admin Icon">
                </div>
                <div class="modal-header-text">
                    <h5>Tambahkan Data Admin</h5>
                    <p>Pastikan data admin yang kamu input valid</p>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="modal-body-custom">
                <form id="formTambahAdmin" action="{{ route('admin_data_admin.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="from_tambah_admin" value="1">

                    <div class="form-group">
                        <label for="create_name_admin">Nama Admin</label>
                        <input type="text" id="create_name_admin" name="create_name_admin"
                            class="@error('create_name_admin') is-invalid @enderror"
                            value="{{ old('create_name_admin') }}">
                        <small>
                            @error('create_name_admin')
                                <div style="color: red">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="create_username">Username</label>
                        <input type="text" id="create_username" name="create_username"
                            class="@error('create_username') is-invalid @enderror"
                            value="{{ old('create_username') }}">
                        <small>
                            @error('create_username')
                                <div style="color: red">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="create_password">Password</label>
                        <input type="password" id="create_password" name="create_password"
                            class="@error('create_password') is-invalid @enderror">
                        <small>
                            @error('create_password')
                                <div style="color: red">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>

                    <div class="info-box">
                        <div class="alert info">
                            <span class="material-icons-sharp">info</span>
                            Admin akan langsung bisa login setelah ditambahkan. Pastikan password kuat & aman.
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer-custom">
                <button class="btn-secondary" onclick="closeModal('modalAdminTambah')">
                    <span class="material-icons-sharp">close</span> Batal
                </button>

                <button class="btn-success-ac" type="submit" form="formTambahAdmin" id="btnTambahAdmin">
                    <span class="spinner" id="spinnerTambahAdmin" style="display: none; margin-right: 5px;"></span>
                    <span class="material-icons-sharp">add</span> <span id="textBtnTambahAdmin">Tambah</span>
                </button>
            </div>
        </div>
    </div>
</div>
