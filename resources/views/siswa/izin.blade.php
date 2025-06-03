@extends('layouts.app')

@section('content')
    <div class="izin-container">
        <!-- Header with Back Button -->
        <div class="izin-header">
            <div class="back-button">
                <a href="{{ route('siswa.beranda') }}">
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
                            <input type="text" name="tanggal_mulai" id="tanggal_mulai" placeholder="Pilih Tanggal"
                                readonly class="form-control date-picker">
                            <i class="mt-2 fas fa-calendar-alt date-icon"></i>
                        </div>
                        <div class="date-input half">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="text" name="tanggal_selesai" id="tanggal_selesai" placeholder="Pilih Tanggal"
                                readonly class="form-control date-picker">
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

    <style>
        .izin-container {
            display: flex;
            flex-direction: column;
            background-color: #f0f7ff;
            min-height: 100vh;
        }

        .izin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background-color: #fff;
        }

        .back-button {
            display: flex;
            align-items: center;
            font-size: 16px;
            color: #333;
            text-decoration: none;
        }

        .back-button i {
            margin-right: 10px;
        }

        .history-button {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .izin-form {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 16px;
            font-weight: 500;
            color: #333;
            margin-bottom: 10px;
        }

        .option-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .option-item {
            position: relative;
            height: 50px;
        }

        .option-input {
            opacity: 0;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            margin: 0;
            cursor: pointer;
            z-index: 3;
        }

        .option-label {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            width: 100%;
            border-radius: 8px;
            background-color: #fff;
            color: #666;
            font-size: 14px;
            font-weight: 500;
            border: 1px solid #ddd;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .option-input:checked+.option-label {
            background: linear-gradient(to right, #ff7e5f, #feb47b);
            color: #fff;
            border: none;
        }

        @media (max-width: 768px) {
            .option-item {
                /* center the text */
                display: flex;
                justify-content: center;
                align-items: center;
                text-align: center;

                position: relative;
                height: 50px;
            }
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .toggle-input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-label {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .toggle-label:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 2px;
            bottom: 2px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        .toggle-input:checked+.toggle-label {
            background-color: #4CAF50;
        }

        .toggle-input:checked+.toggle-label:before {
            transform: translateX(26px);
        }

        .form-control {
            width: 100%;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            background-color: #f5f5f5;
        }

        .date-input {
            position: relative;
        }

        .date-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .textarea {
            resize: none;
            min-height: 100px;
        }

        .date-range-container {
            display: flex;
            gap: 10px;
        }

        .half {
            flex: 1;
        }

        .optional {
            font-size: 12px;
            color: #999;
            font-weight: normal;
        }

        .upload-container {
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 40px 20px;
            text-align: center;
            background-color: #f9f9f9;
            position: relative;
        }

        .file-input {
            position: absolute;
            width: 0;
            height: 0;
            opacity: 0;
        }

        .upload-label {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            color: #666;
        }

        .upload-label i {
            font-size: 20px;
        }

        .preview-container {
            margin-top: 15px;
            position: relative;
            display: inline-block;
        }

        .image-preview {
            max-width: 100%;
            max-height: 200px;
            border-radius: 8px;
            display: block;
        }

        .remove-image {
            position: absolute;
            top: -10px;
            right: -10px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background-color: #ff5252;
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
        }

        .file-info {
            background-color: #f9f9fa;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            text-align: center;
            margin-top: 10px;
            width: 100%;
            max-width: 300px;
        }

        .file-info i {
            font-size: 20px;
            color: #6c757d;
            margin-bottom: 8px;
            display: block;
        }

        .file-info #file-name {
            display: block;
            font-weight: 500;
            margin-bottom: 5px;
            word-break: break-all;
        }

        .hidden {
            display: none !important;
        }

        .submit-button {
            width: 100%;
            padding: 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .submit-button:hover {
            background-color: #45a049;
        }
    </style>

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
@endsection
