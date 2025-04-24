<!-- Modal Edit Profil -->
<div class="modal fade" id="editProfilModal" tabindex="-1" aria-labelledby="editProfilModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="border-0 shadow modal-content rounded-4">
            <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="pb-0 modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold" id="editProfilModalLabel">
                        <i class="mr-2 fas fa-user-edit me-2 text-primary"></i>Edit Profil
                    </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="p-4 modal-body">
                    <div class="mb-4">
                        <label for="email" class="form-label small text-muted">Email</label>
                        <div class="input-group">
                            <span class="border-0 input-group-text bg-light">
                                <i class="fas fa-envelope text-primary"></i>
                            </span>
                            <input type="email" class="border-0 form-control bg-light" id="email" name="email"
                                value="{{ $profil->email }}">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="no_hp" class="form-label small text-muted">No HP</label>
                        <div class="input-group">
                            <span class="border-0 input-group-text bg-light">
                                <i class="fas fa-phone text-primary"></i>
                            </span>
                            <input type="text" class="border-0 form-control bg-light" id="no_hp" name="no_hp"
                                value="{{ $profil->no_hp }}">
                        </div>
                    </div>

                    @if ($profil->user->role_id == 4)
                    <div class="mb-4">
                        <label for="nis" class="form-label small text-muted">NIS</label>
                        <div class="input-group">
                            <span class="border-0 input-group-text bg-light">
                                <i class="fas fa-user text-primary"></i>
                            </span>
                            <input type="text" class="border-0 form-control bg-light" id="nis" name="nis"
                                value="{{ $profil->nis }}">
                        </div>
                    </div>

                    @else ($profil->user->role_id == 2 || $profil->user->role_id == 3)
                    <div class="mb-4">
                        <label for="nip" class="form-label small text-muted">NIP</label>
                        <div class="input-group">
                            <span class="border-0 input-group-text bg-light">
                                <i class="fas fa-user text-primary"></i>
                            </span>
                            <input type="text" class="border-0 form-control bg-light" id="nip" name="nip"
                                value="{{ $profil->nip }}">
                        </div>
                    </div>
                    @endif

                    <div class="mb-4">
                        <label for="password" class="form-label small text-muted">Password <small class="text-muted">*Note: Kosongkan Jika Tidak Ingin Diubah</small></label>
                        <div class="input-group">
                            <span class="border-0 input-group-text bg-light">
                                <i class="fas fa-key text-primary"></i>
                            </span>
                            <input type="text" class="border-0 form-control bg-light" id="password" name="password"
                                value="">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="jenis_kelamin" class="form-label small text-muted">Jenis Kelamin</label>
                        <div class="input-group">
                            <span class="border-0 input-group-text bg-light">
                                <i class="fas fa-venus-mars text-primary"></i>
                            </span>
                            <select name="jenis_kelamin" class="border-0 form-select bg-light form-control" id="jenis_kelamin">
                                <option value="{{ $profil->jenis_kelamin }}">{{ $profil->jenis_kelamin }}</option>
                                @if ($profil->jenis_kelamin == 'Laki-laki')
                                    <option value="Perempuan">Perempuan</option>
                                @else
                                    <option value="Laki-laki">Laki-laki</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="tanggal_lahir" class="form-label small text-muted">Tanggal Lahir</label>
                        <div class="input-group">
                            <span class="border-0 input-group-text bg-light">
                                <i class="fas fa-calendar text-primary"></i>
                            </span>
                            <input type="date" class="border-0 form-control bg-light" id="tanggal_lahir" name="tanggal_lahir"
                                value="{{ $profil->tanggal_lahir }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label small text-muted">Alamat</label>
                        <div class="input-group">
                            <span class="border-0 input-group-text bg-light">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                            </span>
                            <textarea name="alamat" id="alamat" rows="3" class="border-0 form-control bg-light">{{ $profil->alamat }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="pt-0 modal-footer border-top-0">
                    <button type="button" class="px-4 btn btn-light rounded-pill" data-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="px-4 btn btn-primary rounded-pill">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
