@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <h1 class="h3 fw-bold text-primary">
                <i class="fas fa-user-edit me-2"></i> {{ $title }}
            </h1>
            <a href="{{ route('admin_siswa.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="border-0 rounded-lg shadow-sm card">
            <div class="p-4 card-body">
                <form action="{{ route('admin_siswa.store') }}" method="POST">
                    @csrf
                    
                    {{-- data siswa --}}
                    <div class="mb-4 border-0 card bg-light rounded-3">
                        <div class="text-white card-header bg-primary">
                            <h5><i class="fas fa-user me-2"></i> Masukkan Nama Lengkap Siswa</h5>
                        </div>
                        <div class="p-3 card-body">
                            <div class="row g-3">

                                {{-- nama --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nama Lengkap <small class="text-muted">*Note: Username
                                            Akan Otomatis Dibuat Berdasarkan Nama Lengkap</small></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" name="nama"
                                            class="form-control @error('nama') is-invalid @enderror">
                                    </div>
                                    @error('nama')
                                        <small class="text-danger">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>

                                {{-- kelas --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Kelas</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-chalkboard"></i></span>
                                        {{-- <input list="kelas_id" > --}}
                                        <select name="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror">
                                            <option disabled value="">---Pilih Kelas---</option>
                                            @foreach ($kelasList as $kelas)
                                                <option value="{{ $kelas->id }}">
                                                    {{ $kelas->tingkat . ' ' . $kelas->jurusan->kode_jurusan . ' ' . $kelas->no_kelas }}
                                                </option>
                                            @endforeach
                                        </select>
                                        {{-- <datalist id='kelas_id'>
                                            @foreach ($kelasList as $kelas)
                                            <option value="{{ $kelas->id }}">{{ $kelas->tingkat . ' ' . $kelas->jurusan->kode_jurusan . ' ' . $kelas->no_kelas }}</option>
                                            @endforeach
                                        </datalist> --}}
                                    </div>
                                    @error('kelas_id')
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
