@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h1 class="m-0 h4 fw-bold">
            <i class="fas fa-user-graduate">
            </i>
            {{ $title }}
        </h1>
        {{-- <button class="px-3 rounded btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Siswa
        </button> --}}
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="mb-1 mr-2">
                    <a href="{{ route('admin_siswa.create') }}" class="btn btn-primary btn-sm">
                        <i class="mr-2 fas fa-plus"></i>
                        Tambah Siswa
                    </a>
                </div>
                <div class="mb-1 mr-2">
                    <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data"
                        id="importSiswaForm">
                        @csrf
                        <input type="file" name="file" id="fileInput" accept=".xlsx,.xls" style="display: none;"
                            onchange="document.getElementById('importSiswaForm').submit();">

                        <button type="button" class="btn btn-success btn-sm"
                            onclick="document.getElementById('fileInput').click();">
                            <i class="mr-2 fas fa-file-excel"></i>
                            Import Siswa
                        </button>
                    </form>
                </div>

                <div class="filter-form">
                    {{-- <div class="form-group">
                        <select class="form-select">
                            <option value="">Semua Jurusan</option>
                            <option value="1">IPA</option>
                            <option value="2">IPS</option>
                            <option value="3">Bahasa</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select class="form-select">
                            <option value="">Semua Kelas</option>
                            <option value="1">X IPA 1</option>
                            <option value="2">XI IPS 2</option>
                            <option value="3">XII Bahasa 3</option>
                        </select>
                    </div> --}}
                    <button type="button" class="btn btn-primary btn-filter btn-sm">
                        <i class="fas fa-filter"></i>
                        Terapkan Filter
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Cari berdasarkan nama atau NISN...">
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="30%">Nama Lengkap</th>
                                <th width="15%">Kelas</th>
                                <th width="15%">Jenis Kelamin</th>
                                <th width="15%">NIS</th>
                                <th class="text-center" width="2%">
                                    <i class="fas fa-cog"></i>
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($siswa as $item)
                                <tr class="text-center">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="p-2 border">{{ $item->user->siswa->nama ?? '-' }}</td>
                                    <td class="p-2 border">
                                        {{ $item->kelas ? $item->kelas->tingkat . ' ' . $item->kelas->jurusan->kode_jurusan . ' ' . $item->kelas->no_kelas : '-' }}
                                    </td>
                                    <td class="p-2 border">{{ $item->jenis_kelamin ?? '-' }}</td>
                                    <td class="p-2 border">{{ $item->nis ?? '-' }}</td>
                                    <td class="p-2 text-center border">
                                        <button class="btn btn-sm btn-primary" data-toggle="modal"
                                            data-target="#modalSiswaShow{{ $item->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('admin_siswa.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger" data-toggle="modal"
                                            data-target="#modalSiswaDestroy{{ $item->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @include('admin.siswa.modal')
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
