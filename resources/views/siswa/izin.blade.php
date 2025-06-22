{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Nesasen - Learning Management System for SMKN 1 Subang">
    <meta name="author" content="Development Team">
    <meta name="theme-color" content="#4e73df">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Nesasen | {{ $title }}</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}" type="image/x-icon">

    <!-- Custom fonts for this template-->
    <link href="{{ asset('sbadmin2/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('sbadmin2/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- Custom styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <!-- PWA elements -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Nesasen">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-152x152.png') }}">

    <!-- Css Custom -->
    <link rel="stylesheet" href="{{ asset('assets/css/style-izin.css') }}">

</head>

@include('layouts.loading-page')

<body>

    <div class="izin-container">
        <!-- Header with Back Button -->
        <div class="izin-header">
            <div class="back-button">
                <a href="{{ route('siswa.beranda') }}" class="back-button">
                    <i class="fas fa-arrow-left"></i> Izin
                </a>
            </div>
            <div class="history-button">
                <i class="fas fa-history"></i>
            </div>
        </div>

        <!-- Form Izin -->
        <div class="izin-form">
            <form action="{{ route('siswa.izin.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Jenis Izin -->
                <div class="form-group">
                        <label class="form-label @error('jenis_izin') is-invalid @enderror">Jenis Izin</label>
                        <div class="option-grid">
                            <div class="option-item{{ old('jenis_izin', 'Keperluan Keluarga') == 'Keperluan Keluarga' ? ' active' : '' }}">
                                <input type="radio" name="jenis_izin" id="keperluan_keluarga" value="Keperluan Keluarga"
                                    class="option-input" {{ old('jenis_izin', 'Keperluan Keluarga') == 'Keperluan Keluarga' ? 'checked' : '' }}>
                                <label for="keperluan_keluarga" class="option-label">
                                    <span class="ml-2">
                                        Keperluan Keluarga
                                    </span>
                                </label>
                            </div>
                            <div class="option-item{{ old('jenis_izin') == 'Sakit' ? ' active' : '' }}">
                                <input type="radio" name="jenis_izin" id="sakit" value="Sakit" class="option-input" {{ old('jenis_izin') == 'Sakit' ? 'checked' : '' }}>
                                <label for="sakit" class="option-label">
                                    <span class="ml-2">
                                        Sakit
                                    </span>
                                </label>
                            </div>
                            <div class="option-item{{ old('jenis_izin') == 'Keperluan Sekolah' ? ' active' : '' }}">
                                <input type="radio" name="jenis_izin" id="keperluan_sekolah" value="Keperluan Sekolah"
                                    class="option-input" {{ old('jenis_izin') == 'Keperluan Sekolah' ? 'checked' : '' }}>
                                <label for="keperluan_sekolah" class="option-label">
                                    <span class="ml-2">
                                        Keperluan Sekolah
                                    </span>
                                </label>
                            </div>
                            <div class="option-item{{ old('jenis_izin') == 'Lainnya' ? ' active' : '' }}">
                                <input type="radio" name="jenis_izin" id="lainnya" value="Lainnya" class="option-input" {{ old('jenis_izin') == 'Lainnya' ? 'checked' : '' }}>
                                <label for="lainnya" class="option-label">
                                    <span class="ml-2">
                                        Lainnya
                                    </span>
                                </label>
                            </div>
                            <small>
                                @error('jenis_izin')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>
                    </div>

                <!-- Durasi Izin -->
                <div class="form-group">
                    <label class="form-label">Izin lebih dari 1 hari</label>
                    <div class="toggle-switch">
                        <input type="checkbox" id="multi_day" name="multi_day" class="toggle-input">
                        <label for="multi_day" class="toggle-label"></label>
                    </div>
                </div>

                <!-- Tanggal Izin -->
                <div class="form-group">
                    <label class="form-label">Tanggal Izin</label>
                    <div class="date-input" id="assa-date">
                        <input type="text" name="tanggal" id="tanggal" placeholder="Pilih Tanggal"
                            class="form-control date-picker @error('tanggal') is-invalid @enderror" readonly
                            value="{{ old('tanggal') }}">
                        <input type="date" name="tanggal" id="tanggal" placeholder="Pilih Tanggal"
                            class="form-control date-picker @error('tanggal') is-invalid @enderror">
                        <i class="fas fa-calendar-alt date-icon"></i>
                    </div>
                    <small>
                        @error('tanggal')
                            <div style="color: red">{{ $message }}</div>
                        @enderror
                    </small>
                </div>

                <!-- Tanggal Range (Hidden by default) -->
                <div class="hidden form-group" id="date-range">
                    <div class="date-range-container">
                        <div class="date-input half">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="text" name="tanggal_mulai" id="tanggal_mulai"
                                placeholder="Pilih Tanggal" readonly class="form-control date-picker">
                            <i class="mt-2 fas fa-calendar-alt date-icon"></i>
                        </div>
                        <div class="date-input half">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="text" name="tanggal_selesai" id="tanggal_selesai"
                                placeholder="Pilih Tanggal" readonly class="form-control date-picker">
                            <i class="mt-2 fas fa-calendar-alt date-icon"></i>
                        </div>
                    </div>
                </div>

                <!-- Keterangan Tambahan -->
                <div class="form-group">
                    <label class="form-label">Keterangan Tambahan</label>
                    <textarea name="keterangan" id="keterangan" class="form-control textarea @error('keterangan') is-invalid @enderror"
                        rows="4" placeholder="Deskripsi">{{ old('keterangan') }}</textarea>
                    <small>
                        @error('keterangan')
                            <div style="color: red">{{ $message }}</div>
                        @enderror
                    </small>
                </div>

                <!-- Upload Lampiran -->
                <div class="form-group">
                    <label class="form-label">Lampiran <span class="optional">(opsional)</span></label>
                    <div class="upload-container">
                        <input type="file" name="lampiran" id="lampiran" class="file-input"
                            accept="image/*,application/pdf,.doc,.docx"
                            @if (old('lampiran')) data-old="{{ old('lampiran') }}" @endif>
                        <label for="lampiran" class="upload-label">
                            <i class="fas fa-upload"></i> Tambah Lampiran
                        </label>
                        <div id="preview-container" class="hidden preview-container">
                            <img id="image-preview" src="#" alt="Preview" class="hidden image-preview">
                            <div id="file-info" class="hidden file-info">
                                <i class="fas fa-file"></i>
                                <span id="file-name"></span>
                                <span id="file-size"></span>
                            </div>
                            <button type="button" id="remove-image" class="remove-image">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        @if (isset($izinSiswa) && !empty($izinSiswa->lampiran))
                            <div class="mt-3">
                                <p class="text-muted">Lampiran yang sudah diupload:</p>
                                @if (Str::endsWith(strtolower($izinSiswa->lampiran), ['.jpg', '.jpeg', '.png', '.gif', '.bmp']))
                                    <img src="{{ asset('storage/' . $izinSiswa->lampiran) }}" alt="Lampiran"
                                        class="mt-2 img-fluid" style="max-width: 200px;">
                                @else
                                    <div class="file-info-existing">
                                        <i class="fas fa-file-alt"></i>
                                        <span>{{ basename($izinSiswa->lampiran) }}</span>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="form-group">
                    <button type="submit" class="submit-button">AJUKAN IZIN</button>
                </div>
            </form>
        </div>
    </div> --}}

@extends('siswa.layouts.app')

@section('content')
    <main>
        <h2 class="izin-title">Izin</h2>

        <div class="izin-wrapper">

            <form action="{{ route('siswa.izin.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Jenis Izin -->
                <div class="form-group-izin">
                    <label for="jenis_izin" class="@error('jenis_izin') is-invalid @enderror"><b>Jenis Izin</b></label>

                    <div class="izin-type-buttons" id="izinTypeButtons">
                        <button
                            class="izin-btn{{ old('jenis_izin', 'Keperluan Keluarga') == 'Keperluan Keluarga' ? ' active' : '' }}"
                            data-value="Keperluan Keluarga" type="button">Keperluan Keluarga</button>
                        <button class="izin-btn{{ old('jenis_izin') == 'Sakit' ? ' active' : '' }}" data-value="Sakit"
                            type="button">Sakit</button>
                        <button class="izin-btn{{ old('jenis_izin') == 'Keperluan Sekolah' ? ' active' : '' }}"
                            data-value="Keperluan Sekolah" type="button">Keperluan Sekolah</button>
                        <button class="izin-btn{{ old('jenis_izin') == 'Lainnya' ? ' active' : '' }}" data-value="Lainnya"
                            type="button">Lainnya</button>
                    </div>

                    <input type="hidden" name="jenis_izin" id="jenis_izin"
                        value="{{ old('jenis_izin', 'Keperluan Keluarga') }}">

                    <small>
                        @error('jenis_izin')
                            <div style="color: red">{{ $message }}</div>
                        @enderror
                    </small>
                </div>


                <!-- Tanggal Izin -->
                <div class="form-group-izin">
                    <label for="tanggal" class="@error('tanggal') is-invalid @enderror"><b>Tanggal
                            Izin</b></label>
                    <input type="date" id="tanggal" name="tanggal" required value="{{ old('tanggal') }}"
                        min="{{ date('Y-m-d') }}" class="form-control @error('tanggal') is-invalid @enderror" />
                    <small>
                        @error('tanggal')
                            <div style="color: red">{{ $message }}</div>
                        @enderror
                    </small>
                </div>

                <!-- Keterangan Tambahan -->
                <div class="form-group-izin">
                    <label for="keterangan" class="@error('keterangan') is-invalid @enderror"><b>Keterangan</b></label>
                    <textarea id="keterangan" name="keterangan" rows="3" placeholder="Masukkan keterangan..."
                        class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan') }}</textarea>
                    <small>
                        @error('keterangan')
                            <div style="color: red">{{ $message }}</div>
                        @enderror
                    </small>
                </div>

                <!-- Upload File -->
                <div class="form-group-izin" id="lampiranWrapper">
                    <div class="file-dropzone @error('lampiran') is-invalid @enderror" id="dropzoneContainer">
                        <!-- Konten Default -->
                        <div class="file-content" id="fileContentDefault">
                            <span class="material-icons-sharp">upload</span>
                            <p>Tambah Lampiran (Opsional)</p>
                        </div>

                        <!-- Preview File -->
                        <div id="preview-container" class="hidden">
                            <img id="image-preview" class="hidden" style="max-width: 100px; border-radius: 6px;" />
                            <div id="file-info" class="hidden">
                                <i class="fas fa-file"></i>
                                <span id="file-name"></span>
                                <span id="file-size"></span>
                            </div>
                            <!-- Tombol Hapus -->
                            <button type="button" id="remove-image" class="remove-image" style="display: none">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <!-- File input -->
                    <input type="file" id="file_upload" name="lampiran" hidden
                        accept="image/*,application/pdf,.doc,.docx" />

                    <small>
                        @error('lampiran')
                            <div style="color: red">{{ $message }}</div>
                        @enderror
                    </small>
                </div>

                <!-- Button Ajukan -->
                <button class="btn-pengumuman-style btn-izin">
                    <span class="material-icons-sharp">send</span> Ajukan Izin
                </button>
            </form>
        </div>
    </main>
    <!-- End of Main Content -->

    <!-- Right Section -->
    <div class="right-section">
        <div class="nav">
            <button id="menu-btn">
                <span class="material-icons-sharp"> menu </span>
            </button>

            <div class="date-today">
                <span id="tanggal-hari-ini"></span>
            </div>

            <div class="dark-mode">
                <span class="material-icons-sharp active"> light_mode </span>
                <span class="material-icons-sharp"> dark_mode </span>
            </div>

            <div class="profile">
                <div class="info">
                    <p>Hallo, <b>{{ Auth::user()->siswa->nama }}</b></p>
                    <small class="text-muted">Siswa</small>
                </div>
                <div class="profile-photo">
                    <img src="images/profile-1.jpg" />
                </div>
            </div>
        </div>

        <!-- End of Nav -->

        <div class="news">
            <iframe src="https://www.instagram.com/p/DJWiNpzSK2i/embed" width="100%" height="335" frameborder="0"
                scrolling="no" allowtransparency="true">
            </iframe>
        </div>

        <div class="reminders hide">
            <div class="header">
                <h2>Reminders</h2>
            </div>

            <div class="notification">
                <div class="icon">
                    <span class="material-icons-sharp"> volume_up </span>
                </div>
                <div class="content">
                    <div class="info">
                        <h3>Workshop</h3>
                        <small class="text_muted"> 08:00 AM - 12:00 PM </small>
                    </div>
                    <span class="material-icons-sharp"> more_vert </span>
                </div>
            </div>

            <div class="notification deactive">
                <div class="icon">
                    <span class="material-icons-sharp"> edit </span>
                </div>
                <div class="content">
                    <div class="info">
                        <h3>Workshop</h3>
                        <small class="text_muted"> 08:00 AM - 12:00 PM </small>
                    </div>
                    <span class="material-icons-sharp"> more_vert </span>
                </div>
            </div>

            <div class="notification add-reminder">
                <div>
                    <span class="material-icons-sharp"> add </span>
                    <h3>Add Reminder</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- @include('layouts.footer-cr') --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.izin-btn');
            const input = document.getElementById('jenis_izin');

            buttons.forEach(btn => {
                btn.addEventListener('click', () => {
                    // Hapus semua class active
                    buttons.forEach(b => b.classList.remove('active'));

                    // Kasih class active ke yang dipilih
                    btn.classList.add('active');

                    // Update hidden input value
                    input.value = btn.getAttribute('data-value');
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Initialize datepicker
            $(".date-picker").datepicker({
                dateFormat: 'dd-mm-yy',
                minDate: 0,
                showOtherMonths: true,
                selectOtherMonths: true
            });

            // Toggle date range fields based on checkbox
            $("#multi_day").change(function() {
                if ($(this).is(":checked")) {
                    $("#assa-date").addClass("hidden");
                    $("#date-range").removeClass("hidden");
                } else {
                    $("#assa-date").removeClass("hidden");
                    $("#date-range").addClass("hidden");
                }
            });

            // Handle radio button styling
            $(".option-input").change(function() {
                $(".option-item").removeClass("active");
                $(this).closest(".option-item").addClass("active");
            });
        });
    </script>

    <script>
        const dropzone = document.getElementById('dropzoneContainer');
        const fileInput = document.getElementById('file_upload');

        dropzone.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            const previewContainer = document.getElementById('preview-container');
            const imagePreview = document.getElementById('image-preview');
            const fileInfo = document.getElementById('file-info');
            const fileName = document.getElementById('file-name');
            const fileSize = document.getElementById('file-size');
            const removeBtn = document.getElementById('remove-image');
            const fileContentDefault = document.getElementById('fileContentDefault');

            if (file) {
                const sizeInMB = (file.size / 1024 / 1024).toFixed(2);
                fileContentDefault.classList.add('hidden');

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        imagePreview.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                        fileInfo.classList.add('hidden');
                        previewContainer.classList.remove('hidden');
                        removeBtn.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.classList.add('hidden');
                    fileName.textContent = file.name;
                    fileSize.textContent = `${sizeInMB} MB`;
                    fileInfo.classList.remove('hidden');
                    previewContainer.classList.remove('hidden');
                    removeBtn.style.display = 'block';
                }
            }
        });

        document.getElementById('remove-image').addEventListener('click', function(e) {
            e.preventDefault();
            fileInput.value = '';
            document.getElementById('image-preview').classList.add('hidden');
            document.getElementById('file-info').classList.add('hidden');
            document.getElementById('preview-container').classList.add('hidden');
            document.getElementById('fileContentDefault').classList.remove('hidden');
            this.style.display = 'none';
        });
    </script>
@endsection
