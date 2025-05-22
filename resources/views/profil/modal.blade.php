<!-- Modal Edit Profil -->
<div class="modal fade" id="editProfilModal" tabindex="-1" aria-labelledby="editProfilModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
        <div class="border-0 shadow modal-content rounded-4">
            <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="pb-0 modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold" id="editProfilModalLabel">
                        <i class="mr-2 fas fa-user-edit me-2 text-primary"></i>Edit Profil
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="p-4 modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <div class="row">
                        {{-- email --}}
                        <div class="mb-3 col-md-6">
                            <label class="form-label small text-muted">Email</label>
                            <div class="input-group">
                                <span class="border-0 input-group-text bg-light">
                                    <i class="fas fa-envelope text-primary"></i>
                                </span>
                                <input type="email" name="email" value="{{ $profil->email }}"
                                    class="border-0 form-control bg-light">
                            </div>
                        </div>

                        {{-- no hp --}}
                        <div class="mb-3 col-md-6">
                            <label class="form-label small text-muted">No HP</label>
                            <div class="input-group">
                                <span class="border-0 input-group-text bg-light">
                                    <i class="fas fa-phone text-primary"></i>
                                </span>
                                <input type="text" name="no_hp" value="{{ $profil->no_hp }}"
                                    class="border-0 form-control bg-light">
                            </div>
                        </div>

                        {{-- nis / nip --}}
                        @if ($profil->user->role_id == 4)
                            <div class="mb-3 col-md-6">
                                <label class="form-label small text-muted">NIS</label>
                                <div class="input-group">
                                    <span class="border-0 input-group-text bg-light">
                                        <i class="fas fa-id-card text-primary"></i>
                                    </span>
                                    <input type="text" name="nis" value="{{ $profil->nis }}"
                                        class="border-0 form-control bg-light">
                                </div>
                            </div>
                        @else
                            <div class="mb-3 col-md-6">
                                <label class="form-label small text-muted">NIP</label>
                                <div class="input-group">
                                    <span class="border-0 input-group-text bg-light">
                                        <i class="fas fa-id-badge text-primary"></i>
                                    </span>
                                    <input type="text" name="nip" value="{{ $profil->nip }}"
                                        class="border-0 form-control bg-light">
                                </div>
                            </div>
                        @endif

                        {{-- jenis kelamin --}}
                        <div class="mb-3 col-md-6">
                            <label class="form-label small text-muted">Jenis Kelamin</label>
                            <div class="input-group">
                                <span class="border-0 input-group-text bg-light">
                                    <i class="fas fa-venus-mars text-primary"></i>
                                </span>
                                <select name="jenis_kelamin"
                                    class="border-0 form-control bg-light @error('jenis_kelamin') is-invalid @enderror">
                                    <option disabled>Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki"
                                        {{ $profil->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                                    </option>
                                    <option value="Perempuan"
                                        {{ $profil->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan
                                    </option>
                                </select>
                                {{-- <select name="jenis_kelamin" class="border-0 form-control bg-light">
                                    <option value="{{ $profil->jenis_kelamin }}">{{ $profil->jenis_kelamin }}</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select> --}}
                            </div>
                        </div>

                        {{-- tanggal lahir --}}
                        <div class="mb-3 col-md-6">
                            <label class="form-label small text-muted">Tanggal Lahir</label>
                            <div class="input-group">
                                <span class="border-0 input-group-text bg-light">
                                    <i class="fas fa-calendar text-primary"></i>
                                </span>
                                <input type="date" name="tanggal_lahir" value="{{ $profil->tanggal_lahir }}"
                                    class="border-0 form-control bg-light">
                            </div>
                        </div>

                        {{-- alamat --}}
                        <div class="mb-3 col-12">
                            <label class="form-label small text-muted">Alamat</label>
                            <div class="input-group">
                                <span class="border-0 input-group-text bg-light">
                                    <i class="fas fa-map-marker-alt text-primary"></i>
                                </span>
                                <textarea name="alamat" class="border-0 form-control bg-light" rows="2">{{ $profil->alamat }}</textarea>
                            </div>
                        </div>

                        {{-- password toggle --}}
                        <div class="col-12">
                            <button type="button" class="mb-3 btn btn-sm btn-outline-secondary"
                                onclick="document.getElementById('pwSection').classList.toggle('d-none')">
                                <i class="fas fa-key me-1"></i> Ubah Password
                            </button>
                            <div id="pwSection" class="d-none">

                                <label class="form-label small text-muted">Password Lama</label>
                                <div class="mb-3 input-group">
                                    <span class="border-0 input-group-text bg-light">
                                        <i class="fas fa-lock text-primary"></i>
                                    </span>
                                    <input type="password" name="current_password"
                                        class="border-0 form-control bg-light
                                        @error('password') is-invalid @enderror"
                                        placeholder="Masukkan password lama">
                                    @error('password')
                                        <div class="small text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <label class="form-label small text-muted">Password Baru</label>
                                <div class="mb-3 input-group">
                                    <span class="border-0 input-group-text bg-light">
                                        <i class="fas fa-key text-primary"></i>
                                    </span>
                                    <input type="password" name="new_password" class="border-0 form-control bg-light"
                                        placeholder="Password baru">
                                </div>

                                <label class="form-label small text-muted">Konfirmasi Password Baru</label>
                                <div class="mb-3 input-group">
                                    <span class="border-0 input-group-text bg-light">
                                        <i class="fas fa-key text-primary"></i>
                                    </span>
                                    <input type="password" name="new_password_confirmation"
                                        class="border-0 form-control bg-light" placeholder="Ulangi password baru">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-0 modal-footer border-top-0">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" id="submitBtn" class="px-4 btn btn-primary rounded-pill">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.querySelector('#editProfilModal form').addEventListener('submit', function() {
        document.getElementById('submitBtn').innerHTML =
            '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Menyimpan...';
    });
</script>
