@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <!-- Header -->
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <h1 class="h3 fw-bold text-primary">
                <i class="fas fa-user-edit me-2"></i> {{ $title }}
            </h1>
            <a href="{{ route('admin_umum_kelas.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <!-- Form Card -->
        <div class="border-0 rounded-lg shadow-sm card">
            <div class="p-4 card-body">
                <form action="{{ route('admin_umum_kelas.update', $kelas->id) }}" method="POST">
                    @csrf

                    <div class="mb-4 border-0 card bg-light rounded-3">
                        <div class="text-white card-header bg-primary">
                            <h5><i class="fas fa-chalkboard me-2"></i> Edit Kelas</h5>
                        </div>
                        <div class="p-3 card-body">
                            <div class="row g-3">

                                {{-- Nama Kelas --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Kelas</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-chalkboard"></i></span>
                                        <input type="text" name="nama_kelas" class="form-control" disabled
                                            value="{{ $kelas->tingkat . ' ' . $kelas->jurusan->kode_jurusan . ' ' . $kelas->no_kelas }}">
                                    </div>
                                </div>

                                {{-- Wali Kelas --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Wali Kelas</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-chalkboard-teacher"></i></span>
                                        <select name="guru_id" class="form-control" required>
                                            <option disabled selected value="">--- Pilih Wali Kelas ---</option>
                                            @foreach ($guruList as $guru)
                                                <option value="{{ $guru->id }}"
                                                    {{ $selectedWalas == $guru->id ? 'selected' : '' }}>
                                                    {{ $guru->guru->nama ?? $guru->username }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Simpan --}}
                                <div class="gap-2 mt-4 d-grid d-md-flex justify-content-md-end">
                                    <button type="submit" class="px-4 btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
