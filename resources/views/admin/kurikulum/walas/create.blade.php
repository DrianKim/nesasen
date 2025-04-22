@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <h1 class="h3 fw-bold text-primary">
                <i class="fas fa-user-edit me-2"></i> {{ $title }}
            </h1>
            <a href="{{ route('admin_walas.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="border-0 rounded-lg shadow-sm card">
            <div class="p-4 card-body">
                <form action="{{ route('admin_walas.store') }}" method="POST">
                    @csrf

                    {{-- data walas --}}
                    <div class="mb-4 border-0 card bg-light rounded-3">
                        <div class="text-white card-header bg-primary">
                            <h5><i class="fas fa-user me-2"></i> Masukkan Nama Lengkap Guru Walas</h5>
                        </div>
                        <div class="p-3 card-body">
                            <div class="row g-3">

                                {{-- nama --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Wali Kelas
                                        <small class="text-muted">*Note: Pilih guru yang akan dijadikan wali kelas</small>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <select name="guru_id" class="form-control @error('guru_id') is-invalid @enderror" required>
                                            <option disabled selected>-- Pilih Guru --</option>
                                            @foreach ($guruList as $guru)
                                                <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('guru_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                {{-- <div class="col-md-6">
                                    <label class="form-label fw-bold">Nama Lengkap <small class="text-muted">*Note:
                                            Silahkan Pilih Nama Guru Yang Tersedia</small></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input list="guru_id" name="nama"
                                            class="form-control @error('nama') is-invalid @enderror">
                                        <datalist id='guru_id'>
                                            @foreach ($guruList as $guru)
                                            <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                                            @endforeach
                                        </datalist>
                                    </div>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div> --}}
                            </div>
                        </div>
                    </div>

                    {{-- simpan --}}
                    <div class="gap-2 mt-4 d-grid d-md-flex justify-content-md-end">
                        <button type="submit" class="px-4 btn btn-primary">
                            <i class="fas fa-save me-2"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
