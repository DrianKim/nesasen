@extends('admin.layouts.app')

@section('content')
    <div class="p-0 container-fluid">
        <!-- Header dengan tombol kembali -->
        <div class="mb-3 d-flex align-items-center">
            <a href="{{ route('siswa.beranda') }}" class="text-decoration-none text-dark">
                <i class="fas fa-arrow-left me-2"></i>
                <span class="fw-medium">Tugasku</span>
            </a>
        </div>

        <!-- Tab Navigation -->
        <div class="mb-4">
            <ul class="nav nav-tabs" id="kelasKuTabs" role="tablist">
                <li class="nav-item flex-fill" role="presentation">
                    <button class="nav-link active w-100" id="mata-pelajaran-tab" data-bs-toggle="tab"
                        data-bs-target="#mata-pelajaran" type="button" role="tab" aria-controls="mata-pelajaran"
                        aria-selected="true">
                        Mata Pelajaran
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Content -->
        <div class="tab-content" id="kelasKuTabsContent">
            <!-- Mata Pelajaran Tab -->
            <div class="tab-pane fade show active" id="mata-pelajaran" role="tabpanel" aria-labelledby="mata-pelajaran-tab">
                {{-- @if (!empty($kelas) && count($kelas) > 0) --}}
                <div class="row g-3">
                    @foreach ($kelasKu as $item)
                        <div class="col-12">
                            <a href="#
                            {{-- {{ route('siswa.kelas.detail', $item->id) }} --}}
                                "
                                class="text-decoration-none">
                                <div class="mb-2 border-0 shadow-sm card">
                                    <div class="card-body">
                                        <!-- Kelas Header -->
                                        <div class="mb-2 d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0 card-title fw-bold text-dark">
                                                {{ $item->mataPelajaran->nama_mapel }}</h5>
                                            <span class="badge bg-primary rounded-pill">
                                                {{ $item->kelas->tingkat }}
                                                {{ $item->kelas->jurusan->kode_jurusan }}
                                                {{ $item->kelas->no_kelas }}
                                            </span>
                                        </div>

                                        <!-- Guru Info -->
                                        <p class="mb-3 card-text text-muted">
                                            <i class="fas fa-user-tie me-2 small"></i>
                                            {{ $item->guru->nama }}
                                        </p>

                                        <!-- Progress Bar -->
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: {{ $item->progress ?? 0 }}%;"
                                                aria-valuenow="{{ $item->progress ?? 0 }}" aria-valuemin="0"
                                                aria-valuemax="100">
                                            </div>
                                        </div>

                                        <!-- Info Footer -->
                                        <div class="mt-2 d-flex justify-content-between">
                                            <small class="text-muted">
                                                Progress: {{ $item->progress ?? 0 }}%
                                                ({{ $item->tugasSelesai ?? 0 }}/{{ $item->jumlahTugas ?? 0 }})
                                            </small>
                                            <small class="text-muted">
                                                <i class="fas fa-clipboard-list me-1"></i>
                                                {{ $item->jumlahTugas ?? 0 }} tugas
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                {{-- @else --}}
                <!-- Empty State untuk Mata Pelajaran -->
                {{-- <div class="py-5 text-center">
                    <img src="{{ asset('assets/img/empty-class.png') }}" alt="Belum ada Kelas" class="mb-3 img-fluid" style="max-height: 200px;">
                    <h5 class="mt-3">Belum ada Kelas</h5>
                    <p class="text-muted">Bergabunglah dengan kelas untuk melihat mata pelajaran</p>
                </div> --}}
                {{-- @endif --}}
            </div>

            <!-- To-Do Tab -->
            {{-- <div class="tab-pane fade" id="todo" role="tabpanel" aria-labelledby="todo-tab">
            @if (!empty($tugas) && count($tugas) > 0)
                <div class="list-group">
                    @foreach ($tugas as $todo)
                    <a href="{{ route('siswa.tugas.detail', $todo->id) }}" class="mb-3 border-0 shadow-sm list-group-item list-group-item-action">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1 fw-bold">{{ $todo->judul ?? 'Judul Tugas' }}</h6>
                                <p class="mb-1 text-muted small">
                                    {{ $todo->kelasKu->mata_pelajaran->nama_mapel ?? 'Mata Pelajaran' }} •
                                    {{ $todo->kelasKu->guru->nama ?? 'Nama Guru' }}
                                </p>
                                <div class="d-flex align-items-center">
                                    <i class="far fa-calendar-alt text-muted me-1 small"></i>
                                    <small class="text-muted">Tenggat: {{ \Carbon\Carbon::parse($todo->deadline)->format('d M Y H:i') }}</small>
                                </div>
                            </div>
                            <div class="d-flex flex-column align-items-end">
                                @php
                                    $status_class = 'bg-warning';
                                    $status_text = 'Belum Selesai';

                                    if($todo->status == 'completed') {
                                        $status_class = 'bg-success';
                                        $status_text = 'Selesai';
                                    } elseif(\Carbon\Carbon::parse($todo->deadline)->isPast()) {
                                        $status_class = 'bg-danger';
                                        $status_text = 'Terlambat';
                                    }
                                @endphp
                                <span class="badge {{ $status_class }} mb-2">{{ $status_text }}</span>
                                <small class="text-muted">{{ $todo->points ?? 0 }} poin</small>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            @else
                <!-- Empty State untuk To-Do -->
                <div class="py-5 text-center">
                    <img src="{{ asset('assets/img/empty-todo.png') }}" alt="Belum ada To-Do" class="mb-3 img-fluid" style="max-height: 200px;">
                    <h5 class="mt-3">Belum ada To-Do</h5>
                    <p class="text-muted">Semua tugas yang perlu diselesaikan akan muncul di sini</p>
                </div>
            @endif
        </div> --}}
        </div>

        <!-- Join Button (Fixed at bottom) -->
        {{-- <div class="bottom-0 p-3 bg-white position-fixed start-0 end-0 border-top">
        <div class="d-grid">
            <button class="py-2 btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#joinKelasModal">BERGABUNG DI KELAS</button>
        </div>
    </div> --}}

        <!-- Join Class Modal -->
        {{-- <div class="modal fade" id="joinKelasModal" tabindex="-1" aria-labelledby="joinKelasModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="joinKelasModalLabel">Bergabung ke Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="kode_kelas" class="form-label">Kode Kelas</label>
                            <input type="text" class="form-control @error('kode_kelas') is-invalid @enderror"
                                id="kode_kelas" name="kode_kelas" placeholder="Masukkan kode kelas">
                            <div class="form-text">Minta kode kelas dari guru Anda</div>
                            @error('kode_kelas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Gabung</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
    </div>
@endsection

@push('styles')
    <style>
        body {
            background-color: #f8f9fa;
            padding-bottom: 80px;
            /* Space for fixed button */
        }

        .nav-tabs {
            border-bottom: none;
        }

        .nav-tabs .nav-link {
            color: #6c757d;
            border: none;
            border-bottom: 3px solid transparent;
            font-weight: 500;
            text-align: center;
        }

        .nav-tabs .nav-link.active {
            color: #20c997;
            border-bottom: 3px solid #20c997;
            background-color: transparent;
        }

        .card {
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-3px);
        }

        .list-group-item {
            transition: transform 0.2s ease;
            border-radius: 8px;
        }

        .list-group-item:hover {
            transform: translateY(-3px);
        }

        .btn-primary {
            background-color: #20c997;
            border-color: #20c997;
        }

        .btn-primary:hover {
            background-color: #1ba37e;
            border-color: #1ba37e;
        }

        .badge.bg-primary {
            background-color: #20c997 !important;
        }
    </style>
@endpush
