@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <!-- Header with Back Button -->
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <h1 class="h3 fw-bold text-primary">
                <i class="fas fa-user-edit me-2"></i> {{ $title }}
            </h1>
            <a href="{{ route('admin_umum_mapel.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <!-- Form Card -->
        <div class="border-0 rounded-lg shadow-sm card">
            <div class="p-4 card-body">
                <form action="{{ route('admin_umum_mapel.update', $mapel->id) }}" method="POST">
                    @csrf

                    <div class="mb-4 border-0 card bg-light rounded-3">
                        <div class="text-white card-header bg-primary">
                            <h5><i class="fas fa-chalkboard me-2"></i> Edit Mapel</h5>
                        </div>
                        <div class="p-3 card-body">
                            <div class="row g-3">

                                {{-- nama mapel --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nama Mapel</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-chalkboard"></i></span>
                                        <input type="text" name="nama_mapel"
                                            class="form-control @error('nama_mapel') is-invalid @enderror"
                                            value="{{ $mapel->nama_mapel }}">
                                    </div>
                                    @error('nama_mapel')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- kode mapel --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Kode Mapel</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-chalkboard"></i></span>
                                        <input type="text" name="kode_mapel"
                                            class="form-control @error('kode_mapel') is-invalid @enderror"
                                            value="{{ $mapel->kode_mapel }}">
                                    </div>
                                    @error('kode_mapel')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
