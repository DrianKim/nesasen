    <!-- Tab Informasi -->
    <div class="tab-pane fade show active" id="info" role="tabpanel">
        <div class="row">
            <div class="mb-3 col-md-6">
                <div class="mb-3 d-flex align-items-center">
                    <div class="p-2 bg-white shadow-sm rounded-circle me-3">
                        <i class="fas fa-user text-primary"></i>
                    </div>
                    <div class="ml-2">
                        <small class="text-muted d-block">Username</small>
                        <span class="fw-medium">{{ $profil->user->username }}</span>
                    </div>
                </div>
            </div>
            <div class="mb-3 col-md-6">
                <div class="mb-3 d-flex align-items-center">
                    <div class="p-2 bg-white shadow-sm rounded-circle me-3">
                        <i class="fas fa-envelope text-primary"></i>
                    </div>
                    <div class="ml-2">
                        <small class="text-muted d-block">Email</small>
                        <span class="fw-medium">{{ $profil->email }}</span>
                    </div>
                </div>
            </div>
            @if ($profil->user->role_id == 4)
                <div class="mb-3 col-md-6">
                    <div class="mb-3 d-flex align-items-center">
                        <div class="p-2 bg-white shadow-sm rounded-circle me-3">
                            <i class="fas fa-user text-primary"></i>
                        </div>
                        <div class="ml-2">
                            <small class="text-muted d-block">NIS</small>
                            <span class="fw-medium">{{ $profil->nis }}</span>
                        </div>
                    </div>
                </div>
                @else ($profil->user->role_id == 2 || $profil->user->role_id == 3)
                <div class="mb-3 col-md-6">
                    <div class="mb-3 d-flex align-items-center">
                        <div class="p-2 bg-white shadow-sm rounded-circle me-3">
                            <i class="fas fa-user text-primary"></i>
                        </div>
                        <div class="ml-2">
                            <small class="text-muted d-block">NIP</small>
                            <span class="fw-medium">{{ $profil->nip }}</span>
                        </div>
                    </div>
                </div>
            @endif
            <div class="mb-3 col-md-6">
                <div class="mb-3 d-flex align-items-center">
                    <div class="p-2 bg-white shadow-sm rounded-circle me-3">
                        <i class="fas fa-phone text-primary"></i>
                    </div>
                    <div class="ml-2">
                        <small class="text-muted d-block">No HP</small>
                        <span class="fw-medium">{{ $profil->no_hp }}</span>
                    </div>
                </div>
            </div>
            <div class="mb-3 col-md-6">
                <div class="mb-3 d-flex align-items-center">
                    <div class="p-2 bg-white shadow-sm rounded-circle me-3">
                        <i class="fas fa-venus-mars text-primary"></i>
                    </div>
                    <div class="ml-2">
                        <small class="text-muted d-block">Jenis Kelamin</small>
                        <span class="fw-medium">{{ $profil->jenis_kelamin }}</span>
                    </div>
                </div>
            </div>

            <div class="mb-3 col-md-6">
                <div class="mb-3 d-flex align-items-center">
                    <div class="p-2 bg-white shadow-sm rounded-circle me-3">
                        <i class="fas fa-calendar text-primary"></i>
                    </div>
                    <div class="ml-2">
                        <small class="text-muted d-block">Tanggal Lahir</small>
                        <span class="fw-medium">{{ $profil->tanggal_lahir }}</span>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="d-flex align-items-center">
                    <div class="p-2 bg-white shadow-sm rounded-circle me-3">
                        <i class="fas fa-map-marker-alt text-primary"></i>
                    </div>
                    <div class="ml-2">
                        <small class="text-muted d-block">Alamat</small>
                        <span class="fw-medium">{{ $profil->alamat }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
