@extends('admin.layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h1 class="m-0 h4 fw-bold">
            <i class="fas fa-user-graduate">
            </i>
            {{ $title }}
        </h1>
        {{-- <button class="px-3 rounded btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah Murid
    </button> --}}
    </div>

    <div class="mb-4 col-xl-4 col-md-6">
        <div class="py-2 shadow card border-left-primary h-100">
            <div class="card-body">
                <h5 class="card-title">Kelas XI RPL 1</h5>
                <p>Jumlah Siswa: 32</p>
                <p>Tingkat: XI</p>
                <p>Tahun Ajaran: 2024/2025</p>
            </div>
        </div>
    </div>

    <div class="mb-4 col-xl col-md-6">
        <div class="py-2 shadow card h-100">
            <div class="card-body">
                <h5 class="card-title">Presensi Hari Ini</h5>
                <div class="mt-4 text-center row">
                    <div class="col">
                        <h6>Hadir</h6>
                        <p>28</p>
                    </div>
                    <div class="col">
                        <h6>Izin</h6>
                        <p>2</p>
                    </div>
                    <div class="col">
                        <h6>Sakit</h6>
                        <p>1</p>
                    </div>
                    <div class="col">
                        <h6>Alfa</h6>
                        <p>1</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-4 shadow card">
        <div class="ml-2 card header">
            Rekap Presensi Mingguan
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Hari</th>
                        <th>Hadir</th>
                        <th>Izin</th>
                        <th>Sakit</th>
                        <th>Alfa</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Senin</td>
                        <td>29</td>
                        <td>2</td>
                        <td>1</td>
                        <td>0</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
