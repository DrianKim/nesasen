@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <h1 class="h3 fw-bold text-primary">
                <i class="fas fa-user-edit me-2"></i> {{ $title }}
            </h1>
            <a href="{{ route('admin_mapel.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="border-0 rounded-lg shadow-sm card">
            <div class="p-4 card-body">
                <form action="{{ route('admin_mapel.store') }}" method="POST">
                    @csrf

                    {{-- data murid --}}
                    <div class="mb-4 border-0 card bg-light rounded-3">
                        <div class="text-white card-header bg-primary">
                            <h5><i class="fas fa-user me-2"></i> Buat Mapel Baru</h5>
                        </div>
                        <div class="p-3 card-body">
                            <div class="row g-3">

                                {{-- nama mapel --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nama Mapel</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-chalkboard"></i></span>
                                        <input type="text" name="nama_mapel"
                                            class="form-control @error('nama_mapel') is-invalid @enderror">
                                    </div>
                                    @error('nama_mapel')
                                        <small class="text-danger">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>

                                {{-- no mapel --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Kode Mapel</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-chalkboard"></i></span>
                                        <input type="text" name="kode_mapel"
                                            class="form-control @error('kode_mapel') is-invalid @enderror">
                                    </div>
                                    @error('kode_mapel')
                                        <small class="text-danger">
                                            {{ $message }}
                                        </small>
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
