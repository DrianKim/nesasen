@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h1 class="m-0 h4 fw-bold">
            <i class="fas fa-plus">
            </i>
            {{ $title }}
        </h1>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="mb-1 mr-2">
                    <a href="{{ route('admin_murid.index') }}" class="btn btn-success btn-sm">
                        <i class="mr-2 fas fa-arrow-left"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <div class="card-body">
                <form action="{{ route('admin_murid.store') }}" method="POST">
                    @csrf

                    {{-- nama --}}
                    <div class="mb-2 row">
                        <div class="mb-2 col-xl-6">
                            <label class="form-label">
                                <span class="text-danger">*</span>
                                Nama Lengkap:
                            </label>
                            <input type="text" name="nama"
                                class="form-control
                            @error('nama') is-invalid @enderror"
                                value="{{ old('nama') }}">
                            <small class="text-danger">
                                @error('nama')
                                    {{ $message }}
                                @enderror
                            </small>
                        </div>
                    </div>

                    {{-- submit --}}
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="mr-1 fas fa-save"> Simpan</i>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
