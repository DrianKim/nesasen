@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        {{-- <h1 class="m-0 h4 fw-bold">
            <i class="fas fa-chalkboard">
            </i>
            {{ $title }}
        </h1> --}}
        {{-- <button class="px-3 rounded btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Siswa
        </button> --}}
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="mb-1 mr-2">
                    <a href="{{ route('admin_kelas.create') }}" class="btn btn-primary btn-sm">
                        <i class="mr-2 fas fa-plus"></i>
                        Tambah Kelas
                    </a>
                </div>
                <div class="filter-form">
                    <div class="form-group">
                        <select class="form-select">
                            <option disabled value="">Kelas</option>
                            <option value="1">-</option>
                            <option value="2">-</option>
                            <option value="3">-</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary btn-filter btn-sm">
                        <i class="fas fa-filter"></i>
                        Terapkan Filter
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Cari berdasarkan jurusan atau kelas...">
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th width="1%">No</th>
                                <th width="15%">KelasKu</th>
                                <th width="20%">Kelas</th>
                                <th width="20%">Guru</th>
                                <th class="text-center" width="2%">
                                    <i class="fas fa-cog"></i>
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($kelasKu as $item)
                            <tr class="text-center">
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="p-2 border">{{ $item->mata_pelajaran->nama_mapel ?? '' }}</td>
                                <td class="p-2 border">{{ $item->kelas->tingkat. ' '. $item->kelas->jurusan->kode_jurusan. ' '. $item->kelas->no_kelas ?? '' }}</td>
                                <td class="p-2 border">{{ $item->guru->nama ?? '-' }}</td>
                                <td class="p-2 text-center border">
                                    {{-- <button class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </button> --}}
                                    <a href="{{ route('admin_kelas.edit', $item->id)}}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modalKelasDestroy{{ $item->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    {{-- @include('admin.kelas.modal') --}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="page-info">
                    Menampilkan 1-5 dari 24 data
                </div>

                <div class="pagination">
                    <div class="page-item">
                        <a class="page-link" href="#" aria-label="Previous">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </div>
                    <div class="page-item active">
                        <a class="page-link" href="#">1</a>
                    </div>
                    <div class="page-item">
                        <a class="page-link" href="#">2</a>
                    </div>
                    <div class="page-item">
                        <a class="page-link" href="#">3</a>
                    </div>
                    <div class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
