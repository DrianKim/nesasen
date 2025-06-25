<div class="modal" id="modalAdminEdit{{ $item->id }}">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header-custom">
                <div class="modal-header-icon">
                    <img src="{{ asset('assets/img/admin.png') }}" alt="Admin Icon">
                </div>
                <div class="modal-header-text">
                    <h5>Edit Data Admin</h5>
                    <p>Update data admin sesuai kebutuhan</p>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="modal-body-custom">
                <form id="formEditAdmin{{ $item->id }}" action="{{ route('admin_data_admin.update', $item->id) }}"
                    method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="username{{ $item->id }}">Username</label>
                        <input type="text" id="username{{ $item->id }}" name="edit_username"
                            class="@error('edit_username') is-invalid @enderror"
                            value="{{ old('edit_username', $item->username) }}" required>
                        <small>
                            @error('edit_username')
                                <div style="color: red">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="nama_admin{{ $item->id }}">Nama Admin</label>
                        <input type="text" id="nama_admin{{ $item->id }}" name="edit_name_admin"
                            class="@error('edit_name_admin') is-invalid @enderror"
                            value="{{ old('edit_name_admin', $item->name_admin) }}" required>
                        <small>
                            @error('edit_name_admin')
                                <div style="color: red">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="password{{ $item->id }}">Password Baru <small>(Opsional)</small></label>
                        <input type="password" id="password{{ $item->id }}" name="edit_password"
                            class="@error('edit_password') is-invalid @enderror">
                        <small>
                            @error('edit_password')
                                <div style="color: red">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation{{ $item->id }}">Konfirmasi Password Baru
                            <small>(Opsional)</small></label>
                        <input type="password" id="password_confirmation{{ $item->id }}"
                            name="edit_password_confirmation"
                            class="@error('edit_password_confirmation') is-invalid @enderror">
                        <small>
                            @error('edit_password_confirmation')
                                <div style="color: red">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer-custom">
                <button class="btn-secondary" onclick="closeModalEdit('modalAdminEdit{{ $item->id }}')">
                    <span class="material-icons-sharp">close</span> Batal
                </button>

                <button class="btn-success-ac" type="submit" form="formEditAdmin{{ $item->id }}"
                    id="btnEditAdmin{{ $item->id }}">
                    <span class="spinner" id="spinnerEditAdmin{{ $item->id }}"
                        style="display: none; margin-right: 5px;"></span>
                    <span class="material-icons-sharp">add_home</span>
                    <span id="textBtnEditAdmin{{ $item->id }}">Simpan</span>
                </button>
            </div>
        </div>
    </div>
</div>
