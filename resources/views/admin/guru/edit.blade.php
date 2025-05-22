@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <h1 class="h3 fw-bold text-primary">
                <i class="fas fa-user-edit me-2"></i> {{ $title }}
            </h1>
            <a href="{{ route('admin_guru.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="border-0 rounded-lg shadow-sm card">
            <div class="p-4 card-body">
                <form action="{{ route('admin_guru.update', $guru->id) }}" method="POST">
                    @csrf

                    {{-- data guru --}}
                    <div class="mb-4 border-0 card bg-light rounded-3">
                        <div class="text-white card-header bg-primary">
                            <h5><i class="fas fa-user-chalkboard me-2"></i> Data Guru</h5>
                        </div>
                        <div class="p-3 card-body">
                            <div class="row g-3">

                                {{-- nama --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nama Lengkap</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" name="nama"
                                            class="form-control @error('nama') is-invalid @enderror"
                                            value="{{ $guru->nama }}">
                                    </div>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- nip --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">NIP</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" name="nip"
                                            class="form-control @error('nip') is-invalid @enderror"
                                            value="{{ $guru->nip }}">
                                    </div>
                                    @error('nip')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- tanggal lahir --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Tanggal Lahir</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                        <input type="date" name="tanggal_lahir"
                                            class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                            value="{{ $guru->tanggal_lahir }}">
                                    </div>
                                    @error('tanggal_lahir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- jenip kelamin --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Jenip Kelamin</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                        <select name="jenis_kelamin"
                                            class="form-control @error('jenip_kelamin') is-invalid @enderror">
                                            <option disabled>Pilih Jenis Kelamin</option>
                                            <option value="Laki-laki"
                                                {{ $guru->jenip_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                                            </option>
                                            <option value="Perempuan"
                                                {{ $guru->jenip_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan
                                            </option>
                                        </select>
                                    </div>
                                    @error('jenip_kelamin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- no hp --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">No HP</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="text" name="no_hp"
                                            class="form-control @error('no_hp') is-invalid @enderror"
                                            value="{{ $guru->no_hp }}">
                                    </div>
                                    @error('no_hp')
                                        <div class="small text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- email --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ $guru->email }}">
                                    </div>
                                    @error('email')
                                        <div class="small text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- alamat --}}
                                <div class="col-12">
                                    <label class="form-label fw-bold">Alamat</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-pin"></i></span>
                                        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3">{{ $guru->alamat }}</textarea>
                                    </div>
                                    @error('alamat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- akun user --}}
                    <div class="border-0 card bg-light rounded-3">
                        <div class="text-white card-header bg-primary">
                            <h5 class="mb-0"><i class="fas fa-lock me-2"></i> Akun User</h5>
                        </div>

                        {{-- username --}}
                        <div class="p-3 card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input disabled type="text" name="username"
                                            class="form-control @error('username') is-invalid @enderror"
                                            value="{{ $guru->user->username }} ">
                                    </div>
                                    @error('username')
                                        <div class="small text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- password lama --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Password Lama <small class="text-muted">(Kosongkan
                                            jika
                                            tidak ingin diubah)</small></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Masukkan Password Lama">
                                    </div>
                                    @error('password')
                                        <div class="small text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- password baru --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Password Baru</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        <input type="password" name="new_password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Password Baru">
                                    </div>
                                    @error('password')
                                        <div class="small text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- password baru konfirmasi --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Konfirmasi Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        <input type="password" name="new_password_confirmation"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Ulangi Password Baru">
                                    </div>
                                    @error('password')
                                        <div class="small text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="gap-2 mt-4 d-grid d-md-flex justify-content-md-end">
                        <button type="submit" class="px-4 btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
