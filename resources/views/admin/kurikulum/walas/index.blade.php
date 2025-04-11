@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h1 class="m-0 h4 fw-bold">{{ $title }}</h1>
        <button class="px-3 rounded btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Wali Kelas
        </button>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-chalkboard-teacher"></i>
                    Daftar Wali Kelas
                </h2>
                <div class="filter-form">
                    <div class="form-group">
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
                    </div>
                    <button type="button" class="btn btn-primary btn-filter">
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
                                <th width="5%">#</th>
                                <th width="30%">Nama Lengkap</th>
                                <th width="15%">Kelas</th>
                                <th width="15%">Jenis Kelamin</th>
                                <th width="15%">NISN</th>
                                <th width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Muhammad Enza Suryadarmaji</td>
                                <td>X IPA 1</td>
                                <td>
                                    <span class="gender-badge male-badge">
                                        <i class="fas fa-mars"></i> Laki-laki
                                    </span>
                                </td>
                                <td>0087887788</td>
                                <td>
                                    <a href="#" class="action-btn btn-info">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    <a href="#" class="action-btn btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="#" class="action-btn btn-delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Aisyah Putri Ramadhani</td>
                                <td>XI IPS 2</td>
                                <td>
                                    <span class="gender-badge female-badge">
                                        <i class="fas fa-venus"></i> Perempuan
                                    </span>
                                </td>
                                <td>0099887766</td>
                                <td>
                                    <a href="#" class="action-btn btn-info">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    <a href="#" class="action-btn btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="#" class="action-btn btn-delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Budi Santoso</td>
                                <td>X IPA 2</td>
                                <td>
                                    <span class="gender-badge male-badge">
                                        <i class="fas fa-mars"></i> Laki-laki
                                    </span>
                                </td>
                                <td>0087654321</td>
                                <td>
                                    <a href="#" class="action-btn btn-info">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    <a href="#" class="action-btn btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="#" class="action-btn btn-delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Diana Permata Sari</td>
                                <td>XII Bahasa 1</td>
                                <td>
                                    <span class="gender-badge female-badge">
                                        <i class="fas fa-venus"></i> Perempuan
                                    </span>
                                </td>
                                <td>0088776655</td>
                                <td>
                                    <a href="#" class="action-btn btn-info">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    <a href="#" class="action-btn btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="#" class="action-btn btn-delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Faisal Rahman</td>
                                <td>XI IPA 3</td>
                                <td>
                                    <span class="gender-badge male-badge">
                                        <i class="fas fa-mars"></i> Laki-laki
                                    </span>
                                </td>
                                <td>0099112233</td>
                                <td>
                                    <a href="#" class="action-btn btn-info">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    <a href="#" class="action-btn btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="#" class="action-btn btn-delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
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
