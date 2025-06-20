@extends('admin.layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        {{-- <h1 class="m-0 h4 fw-bold">
            <i class="fas fa-chalkboard">
            </i>
            {{ $title }}
        </h1> --}}
        {{-- <button class="px-3 rounded btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Murid
        </button> --}}
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="mb-1 mr-2">
                    <a href="#" class="btn btn-primary btn-sm">
                        <i class="mr-2 fas fa-plus"></i>
                        Tambah Jadwal
                    </a>
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
                    <input type="text" class="search-input"
                        placeholder="Cari berdasarkan nama jurusan atau kode jurusan...">
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th width="2%">Waktu</th>
                                <th width="20%">Senin</th>
                                <th width="20%">Selasa</th>
                                <th width="20%">Rabu</th>
                                <th width="20%">Kamis</th>
                                <th width="20%">Jumat</th>
                                <th class="text-center" width="2%">
                                    <i class="fas fa-cog"></i>
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr class="text-center">
                                <td class="text-center">07:30</td>
                                <td class="p-2 border">Matematika</td>
                                <td class="p-2 border">Bahasa Sunda</td>
                                <td class="p-2 border">Bahasa Inggris</td>
                                <td class="p-2 border">Bahas Mandarin</td>
                                <td class="p-2 border">Bahasa Jepang</td>
                                <td class="p-2 text-center border">
                                    {{-- <button class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </button> --}}
                                    <a href="#" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="text-center">
                                <td class="text-center">08:10</td>
                                <td class="p-2 border">-</td>
                                <td class="p-2 border">-</td>
                                <td class="p-2 border">-</td>
                                <td class="p-2 border">-</td>
                                <td class="p-2 border">-</td>
                                <td class="p-2 text-center border">
                                    {{-- <button class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </button> --}}
                                    <a href="#" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="text-center">
                                <td class="text-center">08:50</td>
                                <td class="p-2 border">-</td>
                                <td class="p-2 border">-</td>
                                <td class="p-2 border">-</td>
                                <td class="p-2 border">-</td>
                                <td class="p-2 border">-</td>
                                <td class="p-2 text-center border">
                                    {{-- <button class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </button> --}}
                                    <a href="#" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
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
