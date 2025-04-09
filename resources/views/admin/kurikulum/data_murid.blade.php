@extends('layouts.app')

@section('content')

<!-- Page Heading -->
<h1 class="mb-4 text-gray-800 h3">{{ $title }} </h1>

<div class="mb-4 shadow card">
    <div class="py-3 card-header">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Murid</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr class="text-center">
                        <th>No</th>
                        <th>Nama</th>
                        {{-- <th>Tanggal Lahir</th> --}}
                        <th>No Hp</th>
                        <th>Nisn</th>
                        <th>Jenis Kelamin</th>
                        {{-- <th>Alamat</th> --}}
                        {{-- <th>Foto</th> --}}
                        <th>
                            <i class="fas fa-cog"></i>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">1</td>
                        <td>Muhammad Enza Suryadarmaji</td>
                        {{-- <td>01-01-2000</td> --}}
                        <td class="text-center">082121212121</td>
                        <td class="text-center">0087887788</td>
                        <td class="text-center">Laki-laki</td>
                        {{-- <td>Gg Rawabadak No Suryadarmaji</td> --}}
                        {{-- <td>Foto</td> --}}
                        <td class="text-center">Show Edit Hapus</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
