@extends('layouts.app')

@section('content')

<!-- Page Heading -->
<h1 class="mb-4 text-gray-800 h3">{{ $title }} </h1>

<!-- Content Row -->
<div class="row">
    <!-- Sekolah Info Card -->
    <div class="mb-4 col-xl-6 col-md-6">
        <div class="py-2 shadow card border-left-primary h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="mr-2 col">
                        <h4 class="mb-1 font-weight-bold text-primary">SMKN 1 Subang</h4>
                        <p class="mb-1"><i class="text-gray-500 fas fa-map-marker-alt fa-sm fa-fw"></i>Jl. Arief Rahman Hakim No.35, Pasirkareumbi, Kec. Subang, Kabupaten Subang, Jawa Barat 41211</p>
                        <p class="mb-1"><i class="text-gray-500 fas fa-phone fa-sm fa-fw"></i>(0260) 411975</p>
                        <p class="mb-0"><i class="text-gray-500 fas fa-envelope fa-sm fa-fw"></i>info@smkn1subang.sch.id</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dinamis sesuai role pengguna -->
    {{-- @if(auth()->user()->role == 'admin') --}}
    <!-- Admin Stats Cards -->
    <div class="mb-4 col-xl-6 col-md-6">
        <div class="row">
            <!-- Total Murid Card -->
            <div class="mb-4 col-xl-4 col-md-4">
                <div class="py-2 shadow card border-left-success h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="mr-2 col">
                                <div class="mb-1 text-xs font-weight-bold text-success text-uppercase">
                                    Total Murid</div>
                                <div class="mb-0 text-gray-800 h5 font-weight-bold">850</div>
                            </div>
                            <div class="col-auto">
                                <i class="text-gray-300 fas fa-graduation-cap fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Guru Card -->
            <div class="mb-4 col-xl-4 col-md-4">
                <div class="py-2 shadow card border-left-info h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="mr-2 col">
                                <div class="mb-1 text-xs font-weight-bold text-info text-uppercase">
                                    Total Guru</div>
                                <div class="mb-0 text-gray-800 h5 font-weight-bold">45</div>
                            </div>
                            <div class="col-auto">
                                <i class="text-gray-300 fas fa-chalkboard-teacher fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Wali Kelas Card -->
            <div class="mb-4 col-xl-4 col-md-4">
                <div class="py-2 shadow card border-left-warning h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="mr-2 col">
                                <div class="mb-1 text-xs font-weight-bold text-warning text-uppercase">
                                    Total Wali Kelas</div>
                                <div class="mb-0 text-gray-800 h5 font-weight-bold">25</div>
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
    {{-- @elseif(auth()->user()->role == 'teacher') --}}
    <!-- Guru/Walas Stats Cards -->
    <div class="mb-4 col-xl-6 col-md-6">
        <div class="row">
            <!-- Kelas yang Diajar Card -->
            <div class="mb-4 col-xl-6 col-md-6">
                <div class="py-2 shadow card border-left-success h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="mr-2 col">
                                <div class="mb-1 text-xs font-weight-bold text-success text-uppercase">
                                    Kelas yang Diajar</div>
                                <div class="mb-0 text-gray-800 h5 font-weight-bold">8</div>
                            </div>
                            <div class="col-auto">
                                <i class="text-gray-300 fas fa-chalkboard fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- @if(auth()->user()->is_homeroom_teacher) --}}
            <!-- Wali Kelas Card -->
            <div class="mb-4 col-xl-6 col-md-6">
                <div class="py-2 shadow card border-left-info h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="mr-2 col">
                                <div class="mb-1 text-xs font-weight-bold text-info text-uppercase">
                                    Wali Kelas</div>
                                <div class="mb-0 text-gray-800 h5 font-weight-bold">XI RPL 1</div>
                            </div>
                            <div class="col-auto">
                                <i class="text-gray-300 fas fa-user-friends fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- @endif --}}
        </div>
    </div>
    {{-- @elseif(auth()->user()->role == 'student') --}}
    <!-- Siswa Assignments Card -->
    <div class="mb-4 col-xl-6 col-md-6">
        <div class="py-2 shadow card border-left-warning h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="mr-2 col">
                        <div class="mb-1 text-xs font-weight-bold text-warning text-uppercase">
                            Tugas yang Harus Dikerjakan</div>

                        @if(isset($pendingAssignments) && count($pendingAssignments) > 0)
                            <div class="mt-3">
                                @foreach($pendingAssignments as $assignment)
                                <div class="mb-2">
                                    <div class="font-weight-bold">{{ $assignment->title }}</div>
                                    <div class="small">
                                        <span class="text-gray-600">Guru: {{ $assignment->teacher->name ?? 'Bapak Ahmad' }}</span> -
                                        <span class="text-danger">Due: {{ $assignment->due_date ?? '16 Apr 2025' }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="mt-2">
                                <div class="mb-2">
                                    <div class="font-weight-bold">Tugas Matematika - Bab 4</div>
                                    <div class="small">
                                        <span class="text-gray-600">Guru: Ibu Siti</span> -
                                        <span class="text-danger">Due: 15 Apr 2025</span>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="font-weight-bold">Proyek Website - Final</div>
                                    <div class="small">
                                        <span class="text-gray-600">Guru: Bapak Rudi</span> -
                                        <span class="text-danger">Due: 20 Apr 2025</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-auto">
                        <i class="text-gray-300 fas fa-tasks fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- @endif --}}
</div>

<!-- Content Row -->
<div class="row">
    <!-- Statistik Absensi Chart -->
    <div class="mb-4 col-xl-6 col-md-6">
        <div class="mb-4 shadow card">
            <div class="flex-row py-3 card-header d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Statistik Absensi Murid</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Pengumuman Card -->
    <div class="mb-4 col-xl-6 col-md-6">
        <div class="mb-4 shadow card">
            <div class="flex-row py-3 card-header d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Pengumuman Terbaru</h6>
            </div>
            <div class="card-body">
                @if(isset($announcements) && count($announcements) > 0)
                    @foreach($announcements as $announcement)
                    <div class="mb-3">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1 font-weight-bold">{{ $announcement->title }}</h5>
                            <small class="text-gray-500">{{ $announcement->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1">{{ Str::limit($announcement->content, 100) }}</p>
                        <small class="text-primary">Oleh: {{ $announcement->user->name }} ({{ ucfirst($announcement->user->role) }})</small>
                    </div>
                    <hr>
                    @endforeach
                @else
                    <div class="mb-3">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1 font-weight-bold">Jadwal Ujian Tengah Semester</h5>
                            <small class="text-gray-500">2 hari yang lalu</small>
                        </div>
                        <p class="mb-1">Jadwal UTS sudah tersedia. Silahkan cek di menu Pengumuman untuk informasi lebih lanjut.</p>
                        <small class="text-primary">Oleh: Admin (Kurikulum)</small>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1 font-weight-bold">Persiapan Pekan Ketrampilan Siswa</h5>
                            <small class="text-gray-500">5 hari yang lalu</small>
                        </div>
                        <p class="mb-1">Seluruh siswa diharapkan untuk mempersiapkan materi presentasi untuk Pekan Ketrampilan yang akan diadakan bulan depan.</p>
                        <small class="text-primary">Oleh: Ibu Siti (Wali Kelas)</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('sbadmin2/vendor/chart.js/Chart.min.js') }}"></script>
<script>
// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Attendance Chart
var ctx = document.getElementById("attendanceChart");
var myLineChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"],
        datasets: [{
            label: "Hadir",
            lineTension: 0.3,
            backgroundColor: "rgba(78, 115, 223, 0.05)",
            borderColor: "rgba(78, 115, 223, 1)",
            pointRadius: 3,
            pointBackgroundColor: "rgba(78, 115, 223, 1)",
            pointBorderColor: "rgba(78, 115, 223, 1)",
            pointHoverRadius: 3,
            pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
            pointHoverBorderColor: "rgba(78, 115, 223, 1)",
            pointHitRadius: 10,
            pointBorderWidth: 2,
            data: [95, 92, 93, 94, 90, 92, 91, 93, 94, 90, 0, 0],
        },{
            label: "Sakit",
            lineTension: 0.3,
            backgroundColor: "rgba(246, 194, 62, 0.05)",
            borderColor: "rgba(246, 194, 62, 1)",
            pointRadius: 3,
            pointBackgroundColor: "rgba(246, 194, 62, 1)",
            pointBorderColor: "rgba(246, 194, 62, 1)",
            pointHoverRadius: 3,
            pointHoverBackgroundColor: "rgba(246, 194, 62, 1)",
            pointHoverBorderColor: "rgba(246, 194, 62, 1)",
            pointHitRadius: 10,
            pointBorderWidth: 2,
            data: [3, 5, 4, 3, 6, 5, 5, 4, 3, 5, 0, 0],
        },{
            label: "Alpa",
            lineTension: 0.3,
            backgroundColor: "rgba(231, 74, 59, 0.05)",
            borderColor: "rgba(231, 74, 59, 1)",
            pointRadius: 3,
            pointBackgroundColor: "rgba(231, 74, 59, 1)",
            pointBorderColor: "rgba(231, 74, 59, 1)",
            pointHoverRadius: 3,
            pointHoverBackgroundColor: "rgba(231, 74, 59, 1)",
            pointHoverBorderColor: "rgba(231, 74, 59, 1)",
            pointHitRadius: 10,
            pointBorderWidth: 2,
            data: [2, 3, 3, 3, 4, 3, 4, 3, 3, 5, 0, 0],
        }],
    },
    options: {
        maintainAspectRatio: false,
        layout: {
            padding: {
                left: 10,
                right: 25,
                top: 25,
                bottom: 0
            }
        },
        scales: {
            xAxes: [{
                time: {
                    unit: 'date'
                },
                gridLines: {
                    display: false,
                    drawBorder: false
                },
                ticks: {
                    maxTicksLimit: 7
                }
            }],
            yAxes: [{
                ticks: {
                    maxTicksLimit: 5,
                    padding: 10,
                    // Include a dollar sign in the ticks
                    callback: function(value, index, values) {
                        return value + '%';
                    }
                },
                gridLines: {
                    color: "rgb(234, 236, 244)",
                    zeroLineColor: "rgb(234, 236, 244)",
                    drawBorder: false,
                    borderDash: [2],
                    zeroLineBorderDash: [2]
                }
            }],
        },
        legend: {
            display: true
        },
        tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            titleMarginBottom: 10,
            titleFontColor: '#6e707e',
            titleFontSize: 14,
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            intersect: false,
            mode: 'index',
            caretPadding: 10,
            callbacks: {
                label: function(tooltipItem, chart) {
                    var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                    return datasetLabel + ': ' + tooltipItem.yLabel + '%';
                }
            }
        }
    }
});
</script>
@endsection
