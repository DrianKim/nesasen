@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="mb-4 d-flex justify-content-between align-jadwals-center">
        <h1 class="m-0 h4 fw-bold">
            <i class="fas fa-user-graduate">
            </i>
            {{ $title }}
        </h1>
        {{-- <button class="px-3 rounded btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah Murid
    </button> --}}
    </div>

    <div class="container">
        <table class="table table-bordered table-hover">
            <thead class="text-white bg-primary">
                <tr>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Mapel</th>
                    <th>Kelas</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jadwalList as $jadwal)
                <tr>
                    <td>{{ $jadwal->hari }}</td>
                    <td>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</td>
                    <td>{{ $jadwal->mapelKelas->mapel->nama_mapel }}</td>
                    <td>{{ $jadwal->mapelKelas->kelas->tingkat. ' '. $jadwal->mapelKelas->kelas->jurusan->kode_jurusan. ' '. $jadwal->mapelKelas->kelas->no_kelas ?? '' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Belum ada jadwal mengajar</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
