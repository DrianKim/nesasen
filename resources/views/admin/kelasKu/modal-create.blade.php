<!-- Modal Create -->
<div class="modal fade" id="modalKelasKuCreate" tabindex="-1" role="dialog" aria-labelledby="modalKelasKuCreateTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center"
                style="background-color: #f5f5f5; border-bottom: 1px solid #ddd;">
                <div class="d-flex align-items-center">
                    <div class="mr-3" style="background-color: #e0f7f5; border-radius: 50%; padding: 10px;">
                        <img src="assets/img/icons/student-icon.png" alt="Student Icon"
                            style="width: 40px; height: 40px;">
                    </div>
                    <div>
                        <h5 class="modal-title" style="font-weight: 500" id="modalKelasKuCreateTitle">Tambahkan Data
                            KelasKu
                        </h5>
                        <p class="mb-0 text-muted" style="font-size: 0.85rem;">Pastikan data yang kamu input benar</p>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <!-- Form Start -->
                <form class="form-modal" id="formTambahKelasKu" action="{{ route('admin_kelasKu.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="mapel_id">KelasKu</label>
                            <select
                                class="border rounded form-control border-opacity-30 @error('mapel_id') is-invalid @enderror"
                                name="mapel_id">
                                <option value="" disabled>---Pilih Kelasku---</option>
                                @foreach ($mapelList as $mapel)
                                    <option value="{{ $mapel->id }}"
                                        {{ old('mapel_id') == $mapel->id ? 'selected' : '' }}>
                                        {{ $mapel->nama_mapel }}
                                    </option>
                                @endforeach
                            </select>
                            <small>
                                @error('mapel_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="kelas_id">Kelas</label>
                            <select
                                class="border rounded form-control border-opacity-30 @error('kelas_id') is-invalid @enderror"
                                name="kelas_id">
                                <option value="" disabled>---Pilih Kelas---</option>
                                @foreach ($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}"
                                        {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                        {{ $kelas->tingkat . ' ' . $kelas->jurusan->kode_jurusan . ' ' . $kelas->no_kelas }}
                                    </option>
                                @endforeach
                            </select>
                            <small>
                                @error('kelas_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>
                    </div>

                    <div class="mb-3 form-group">
                        <label for="guru">Guru</label>
                        <select name="guru_id"
                            class="border rounded form-control border-opacity-30 @error('guru_id') is-invalid @enderror"
                            id="guru">
                            <option value="" disabled>---Pilih Guru---</option>
                            @foreach ($guruList as $guru)
                                <option value="{{ $guru->id }}"
                                    {{ old('guru_id') == $guru->id ? 'selected' : '' }}>
                                    {{ $guru->nama }}
                                </option>
                            @endforeach
                        </select>
                        <small>
                            @error('guru_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>

                    {{-- <div class="mb-3 form-group">
                        <label for="tingkat">Tingkat</label>
                        <input type="text"
                            class="border rounded form-control border-opacity-30 @error('kode_mapel') is-invalid @enderror"
                            id="kode_mapel" name="kode_mapel" value="{{ old('kode_mapel') }}">
                        <small>
                            @error('kode_mapel')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </small>
                    </div> --}}
            </div>
            <!-- Form End -->

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary"
                    style="background-color: #20B2AA; border-color: #20B2AA;">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>

{{-- Script untuk menampilkan modal saat ada error --}}
@if ($errors->any())
    <script>
        $(document).ready(function() {
            $('#modalKelasKuCreate').modal('show');
        });
    </script>
@endif
@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: 'Cek lagi data yang kamu isi, ada yang salah tuh.',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
@endif

{{-- @if ($errors->any())
    <script>
        $(document).ready(function() {
            // Buka modal kalau ada error validasi
            $('#modalKelasKuCreate').modal('show');

            // Optional: Tampilkan error pake SweetAlert
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#d33',
            });
        });
    </script>
@endif --}}

{{-- @if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: 'Cek lagi data yang kamu isi, ada yang salah tuh.',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
@endif --}}

{{-- <script>
    $(document).ready(function() {
        $('#formTambahKelasKu').submit(function(e) {
            e.preventDefault(); // Prevent the form from being submitted traditionally

            let formData = new FormData(this); // Create FormData object for the form
            $.ajax({
                url: '{{ route('admin_mapel.store') }}', // Route tujuan
                method: 'POST',
                data: formData,
                processData: false, // Don't process the data
                contentType: false, // Don't set content type header
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'KelasKu berhasil ditambahkan!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function() {
                            location.reload(); // Reload page after success
                        });
                    }
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessages = '';
                    $.each(errors, function(key, value) {
                        errorMessages += value[0] + "\n";
                    });

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: errorMessages,
                    });
                }
            });
        });
    });
</script> --}}
