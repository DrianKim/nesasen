<!DOCTYPE html>
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
                        <div class="option-item active">
                            <input type="radio" name="jenis_izin" id="keperluan_keluarga" value="Keperluan Keluarga"
                                class="option-input">
                            <label for="keperluan_keluarga" class="option-label">
                                <span class="ml-2">
                                    Keperluan Keluarga
                                </span>
                            </label>
                        </div>
                        <div class="option-item">
                            <input type="radio" name="jenis_izin" id="sakit" value="Sakit" class="option-input">
                            <label for="sakit" class="option-label">
                                <span class="ml-2">
                                    Sakit
                                </span>
                            </label>
                        </div>
                        <div class="option-item">
                            <input type="radio" name="jenis_izin" id="keperluan_sekolah" value="Keperluan Sekolah"
                                class="option-input">
                            <label for="keperluan_sekolah" class="option-label">
                                <span class="ml-2">
                                    Keperluan Sekolah
                                </span>
                            </label>
                        </div>
                        <div class="option-item">
                            <input type="radio" name="jenis_izin" id="lainnya" value="Lainnya" class="option-input">
                            <label for="lainnya" class="option-label">
                                <span class="ml-2">
                                    Lainnya
                                </span>
                            </label>
                        </div>
                        <small>
                            @error('jenis_izin')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>
                </div>

                <!-- Durasi Izin -->
                {{-- <div class="form-group">
                    <label class="form-label">Izin lebih dari 1 hari</label>
                    <div class="toggle-switch">
                        <input type="checkbox" id="multi_day" name="multi_day" class="toggle-input">
                        <label for="multi_day" class="toggle-label"></label>
                    </div>
                </div> --}}

                <!-- Tanggal Izin -->
                <div class="form-group">
                    <label class="form-label">Tanggal Izin</label>
                    <div class="date-input" id="assa-date">
                        <input type="text" name="tanggal" id="tanggal" placeholder="Pilih Tanggal"
                            class="form-control date-picker @error('tanggal') is-invalid @enderror" readonly
                            value="{{ old('tanggal') }}">
                        {{-- <input type="date" name="tanggal" id="tanggal" placeholder="Pilih Tanggal"
                            class="form-control date-picker @error('tanggal') is-invalid @enderror"> --}}
                        <i class="fas fa-calendar-alt date-icon"></i>
                    </div>
                    <small>
                        @error('tanggal')
                            <div class="text-danger">{{ $message }}</div>
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
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </small>
                </div>

                <!-- Upload Lampiran -->
                <div class="form-group">
                    <label class="form-label">Lampiran <span class="optional">(opsional)</span></label>
                    <div class="upload-container">
                        <input type="file" name="lampiran" id="lampiran" class="file-input"
                            accept="image/*,application/pdf,.doc,.docx" value="{{ old('lampiran') }}">
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
    </div>

    @include('layouts.footer-cr')

    @include('layouts.footer')

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

            // File preview functionality (support image and other files)
            $("#lampiran").change(function() {
                const file = this.files[0];
                if (file) {
                    // Get file info
                    const fileName = file.name;
                    const fileSize = (file.size / 1024 / 1024).toFixed(2); // Convert to MB

                    // Check if file is an image
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            $("#image-preview").attr("src", e.target.result);
                            $("#image-preview").removeClass("hidden");
                            $("#file-info").addClass("hidden");
                            $("#preview-container").removeClass("hidden");
                            $(".upload-label").addClass("hidden");
                        }
                        reader.readAsDataURL(file);
                    } else {
                        // For non-image files, show file info
                        $("#image-preview").addClass("hidden");
                        $("#file-name").text(fileName);
                        $("#file-size").text(fileSize + " MB");
                        $("#file-info").removeClass("hidden");
                        $("#preview-container").removeClass("hidden");
                        $(".upload-label").addClass("hidden");
                    }

                    // Debug info - console log untuk troubleshooting
                    console.log("File selected:", fileName);
                    console.log("File type:", file.type);
                    console.log("File size:", fileSize + " MB");
                }
            });

            // Remove file preview
            $("#remove-image").click(function(e) {
                e.preventDefault(); // Prevent any default behavior
                console.log("Remove button clicked");

                $("#lampiran").val("");
                $("#image-preview").attr("src", "");
                $("#image-preview").addClass("hidden");
                $("#file-info").addClass("hidden");
                $("#preview-container").addClass("hidden");
                $(".upload-label").removeClass("hidden");
            });
        });
    </script>
</body>

</html>
