@extends('admin.layouts.app')

@section('content')

    <!-- Page Heading -->
    {{-- <h1 class="mb-4 text-gray-800 h3">
    {{ $title }}
    @if (auth()->user()->role_id === 1)
        Admin
    @elseif(auth()->user()->role_id === 2)
        Wali Kelas
    @elseif(auth()->user()->role_id === 3)
        Guru
    @elseif(auth()->user()->role_id === 4)
        Murid
    @endif
</h1> --}}

    <!-- Content Row -->
    <div class="row">

        <!-- Sekolah Info Card -->
        <div class="mb-4 col-xl-6 col-md-6">
            <div class="py-2 shadow card border-left-primary h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="mr-2 col">
                            <h4 class="mb-1 font-weight-bold text-primary">SMKN 1 Subang</h4>
                            <p class="mb-1"><i class="text-gray-500 fas fa-map-marker-alt fa-sm fa-fw"></i> Jl. Arief Rahman
                                Hakim No.35, Subang</p>
                            <p class="mb-1"><i class="text-gray-500 fas fa-phone fa-sm fa-fw"></i> (0260) 411975</p>
                            <p class="mb-0"><i class="text-gray-500 fas fa-envelope fa-sm fa-fw"></i>
                                info@smkn1subang.sch.id</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (auth()->user()->role_id === 1)
            <!-- Kartu Info Admin -->
            <div class="mb-4 col-xl-6 col-md-6">
                <div class="row">
                    <div class="mb-4 col-xl-4 col-md-4">
                        <div class="py-2 shadow card border-left-success h-100">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="mr-2 col">
                                        <div class="mb-1 text-xs font-weight-bold text-success text-uppercase">Total Murid
                                        </div>
                                        <div class="mb-0 text-gray-800 h5 font-weight-bold">850</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="text-gray-300 fas fa-graduation-cap fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 col-xl-4 col-md-4">
                        <div class="py-2 shadow card border-left-info h-100">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="mr-2 col">
                                        <div class="mb-1 text-xs font-weight-bold text-info text-uppercase">Total Guru</div>
                                        <div class="mb-0 text-gray-800 h5 font-weight-bold">95</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="text-gray-300 fas fa-chalkboard-teacher fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 col-xl-4 col-md-4">
                        <div class="py-2 shadow card border-left-warning h-100">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="mr-2 col">
                                        <div class="mb-1 text-xs font-weight-bold text-warning text-uppercase">Total Wali
                                            Kelas</div>
                                        <div class="mb-0 text-gray-800 h5 font-weight-bold">28</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="text-gray-300 fas fa-users fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(auth()->user()->role_id === 2 || auth()->user()->role_id === 3)
            <!-- Kartu Info Guru / Wali Kelas -->
            <div class="mb-4 col-xl-6 col-md-6">
                <div class="row">
                    <div class="mb-4 col-xl-6 col-md-6">
                        <div class="py-2 shadow card border-left-success h-100">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="mr-2 col">
                                        <div class="mb-1 text-xs font-weight-bold text-success text-uppercase">Kelas yang
                                            Diajar</div>
                                        <div class="mb-0 text-gray-800 h5 font-weight-bold">8</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="text-gray-300 fas fa-chalkboard fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (auth()->user()->role_id === 2)
                        <div class="mb-4 col-xl-6 col-md-6">
                            <div class="py-2 shadow card border-left-info h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="mr-2 col">
                                            <div class="mb-1 text-xs font-weight-bold text-info text-uppercase">Wali Kelas
                                            </div>
                                            <div class="mb-0 text-gray-800 h5 font-weight-bold">XI RPL 1</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="text-gray-300 fas fa-user-friends fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @elseif(auth()->user()->role_id === 4)
            <!-- Kartu Info Murid -->
            <div class="mb-4 col-xl-6 col-md-6">
                <div class="py-2 shadow card border-left-warning h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="mr-2 col">
                                <div class="mb-1 text-xs font-weight-bold text-warning text-uppercase">Tugas yang Harus
                                    Dikerjakan</div>
                                <div class="mt-3">
                                    <div class="mb-2">
                                        <div class="font-weight-bold">Tugas Matematika - Bab 4</div>
                                        <div class="small"><span class="text-gray-600">Guru: Ibu Siti</span> - <span
                                                class="text-danger">Due: 15 Apr 2025</span></div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="font-weight-bold">Proyek Website - Final</div>
                                        <div class="small"><span class="text-gray-600">Guru: Bapak Rusdi</span> - <span
                                                class="text-danger">Due: 20 Apr 2025</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="text-gray-300 fas fa-tasks fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
    <!-- Pengumuman -->
    <div class="row">
        <div class="mb-4 col-xl-6 col-md-6">
            <div class="mb-4 shadow card">
                <div class="flex-row py-3 card-header d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Pengumuman Terbaru</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h5 class="mb-1 font-weight-bold">Jadwal Ujian Tengah Semester</h5>
                        <small class="text-gray-500">2 hari yang lalu</small>
                        <p class="mb-1">Jadwal UTS sudah tersedia. Silahkan cek di menu Pengumuman untuk info lebih
                            lanjut.</p>
                        <small class="text-primary">Oleh: Admin (Kurikulum)</small>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <h5 class="mb-1 font-weight-bold">Libur Nasional</h5>
                        <small class="text-gray-500">5 hari yang lalu</small>
                        <p class="mb-1">Sekolah akan libur pada tanggal 17 April 2025 dalam rangka Hari Besar Nasional.
                        </p>
                        <small class="text-primary">Oleh: Kepala Sekolah</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
