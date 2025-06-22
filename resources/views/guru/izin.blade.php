@extends('guru.layouts.app')
@php
    $nama = explode(' ', Auth::user()->guru->nama);
    $namaPendek = implode(' ', array_slice($nama, 0, 1));
@endphp
@section('content')
    <main>
        <h2 class="izin-title">Izin</h2>

        <div class="izin-wrapper">

            <form action="{{ route('guru.izin.store') }}" method="POST" enctype="multipart/form-data">
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
                    <p>Hallo, <b>{{ $namaPendek}}</b></p>
                    <small class="text-muted">Guru</small>
                </div>
                <div class="profile-photo">
                    <img src="{{ asset('assets/img/smeapng.png') }}" />
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
