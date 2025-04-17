@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <!-- Header with Back Button -->
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <h1 class="h3 fw-bold text-primary">
                <i class="fas fa-user-edit me-2"></i> {{ $title }}
            </h1>
            <a href="{{ route('admin_murid.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <!-- Form Card -->
        <div class="border-0 rounded-lg shadow-sm card">
            <div class="p-4 card-body">
                <form action="{{ route('admin_murid.update', $murid->id) }}" method="POST">
                    @csrf

                    <!-- Student Data Section -->
                    <div class="mb-4 border-0 card bg-light rounded-3">
                        <div class="text-white card-header bg-primary">
                            <h5><i class="fas fa-user me-2"></i> Data Murid</h5>
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
                                            value="{{ $murid->nama }}">
                                    </div>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- nis --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">NIS</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" name="nis"
                                            class="form-control @error('nis') is-invalid @enderror"
                                            value="{{ $murid->nis }}">
                                    </div>
                                    @error('nis')
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
                                            value="{{ $murid->tanggal_lahir }}">
                                    </div>
                                    @error('tanggal_lahir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- jenis kelamin --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Jenis Kelamin</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                        <select name="jenis_kelamin"
                                            class="form-control @error('jenis_kelamin') is-invalid @enderror">
                                            <option disabled>Pilih Jenis Kelamin</option>
                                            <option value="Laki-laki"
                                                {{ $murid->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                                            </option>
                                            <option value="Perempuan"
                                                {{ $murid->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan
                                            </option>
                                        </select>
                                    </div>
                                    @error('jenis_kelamin')
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
                                            value="{{ $murid->no_hp }}">
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
                                            value="{{ $murid->email }}">
                                    </div>
                                    @error('email')
                                        <div class="small text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- kelas --}}
                                <div class="col-12">
                                    <label class="form-label fw-bold">Kelas</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-chalkboard"></i></span>
                                        <select name="kelas_id" class="form-control">
                                            @foreach ($kelasList as $kelas)
                                                <option
                                                    value="{{ $kelas->id }}"
                                                    {{ $murid->kelas_id == $kelas->id ? 'selected' : '' }}>
                                                    {{ $kelas->tingkat . ' ' . $kelas->jurusan->kode_jurusan . ' ' . $kelas->no_kelas ?? '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- alamat --}}
                                <div class="col-12">
                                    <label class="form-label fw-bold">Alamat</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-pin"></i></span>
                                        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3">{{ $murid->alamat }}</textarea>
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
                                            value="{{ $murid->user->username }} ">
                                    </div>
                                    @error('username')
                                        <div class="small text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- password --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Password <small class="text-muted">(Kosongkan jika
                                            tidak ingin diubah)</small></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        <input type="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror">
                                    </div>
                                    @error('password')
                                        <div class="small text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- simpan --}}
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
