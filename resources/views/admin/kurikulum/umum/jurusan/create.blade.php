@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <h1 class="h3 fw-bold text-primary">
                <i class="fas fa-user-edit me-2"></i> {{ $title }}
            </h1>
            <a href="{{ route('admin_umum_jurusan.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="border-0 rounded-lg shadow-sm card">
            <div class="p-4 card-body">
                <form action="{{ route('admin_umum_jurusan.store') }}" method="POST">
                    @csrf

                    {{-- data murid --}}
                    <div class="mb-4 border-0 card bg-light rounded-3">
                        <div class="text-white card-header bg-primary">
                            <h5><i class="fas fa-user me-2"></i> Buat Jurusan Baru</h5>
                        </div>
                        <div class="p-3 card-body">
                            <div class="row g-3">

                                {{-- nama jurusan --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nama Jurusan</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-chalkboard"></i></span>
                                        <input type="text" name="nama_jurusan"
                                            class="form-control @error('nama_jurusan') is-invalid @enderror">
                                    </div>
                                    @error('nama_jurusan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- kode jurusan --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Kode Jurusan</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-chalkboard"></i></span>
                                        <input type="text" name="kode_jurusan"
                                            class="form-control @error('kode_jurusan') is-invalid @enderror">
                                    </div>
                                    @error('kode_jurusan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
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
