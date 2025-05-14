<!-- Modal Create -->
<div class="modal fade" id="modalSiswaCreate" tabindex="-1" role="dialog" aria-labelledby="modalSiswaCreateTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center"
                style="background-color: #f5f5f5; border-bottom: 1px solid #ddd;">
                <div class="d-flex align-items-center">
                    <div class="mr-3" style="background-color: #e0f7f5; border-radius: 50%; padding: 10px;">
                        <img src="{{ asset('assets\img\student.png') }}" alt="Siswa Icon"
                            style="width: 40px; height: 40px;">
                    </div>
                    <div>
                        <h5 class="modal-title" style="font-weight: 500" id="modalSiswaCreateTitle">Tambahkan Data Siswa
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
                <form class="form-modal" id="formTambahSiswa" action="{{ route('admin_siswa.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="nisn">NISN</label>
                            <input type="text" class="border rounded form-control border-opacity-30 @error('nisn') is-invalid @enderror" id="nisn"
                                name="nisn" value="{{ old('nisn') }}">
                            <small>
                                @error('nisn')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="nis">NIS <small class="text-muted">(Opsional)</small></label>
                            <input type="text" class="border rounded form-control border-opacity-30 @error('nis') is-invalid @enderror" id="nis"
                                name="nis" value="{{ old('nis') }}">
                            <small>
                                @error('nis')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>
                    </div>

                    <div class="mb-3 form-group">
                        <label for="nama">Nama Siswa</label>
                        <input type="text" class="border rounded form-control border-opacity-30 @error('nama') is-invalid @enderror" id="nama"
                            name="nama" value="{{ old('nama') }}">
                        <small>
                            @error('nama')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>

                    <div class="mb-3 form-group">
                        <label for="username">Username</label>
                        <input type="text" class="border rounded form-control border-opacity-30 @error('username') is-invalid @enderror" id="username"
                            name="username" value="{{ old('username') }}">
                        <small>
                            @error('username')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <input type="date" class="border rounded form-control border-opacity-30 @error('tanggal_lahir') is-invalid @enderror"
                                id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                            <small>
                                @error('tanggal_lahir')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="kelas">Kelas</label>
                            {{-- <select class="border rounded form-control border-opacity-30" id="kelas" name="kelas_id"
                                required>
                                <option value="">Pilih Kelas</option>
                                @foreach ($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}">
                                        {{ $kelas->tingkat . ' ' . $kelas->jurusan->kode_jurusan . ' ' . $kelas->no_kelas }}
                                    </option>
                                @endforeach
                            </select> --}}
                            <select name="kelas_id" class="border rounded form-control border-opacity-30 @error('kelas_id') is-invalid @enderror"
                                id="kelas">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach ($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}"
                                        {{ old('kelas_id', $siswa->kelas_id ?? '') == $kelas->id ? 'selected' : '' }}>
                                        {{ $kelas->tingkat }} {{ $kelas->jurusan->kode_jurusan }}
                                        {{ $kelas->no_kelas }}
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

                    <div class="mb-3 row">
                        <div class="col-md-6 form-group">
                            <label for="no_hp">No HP</label>
                            <input type="text" class="border rounded form-control border-opacity-30 @error('no_hp') is-invalid @enderror" id="no_hp"
                                name="no_hp" placeholder="08xxxxxxxx" value="{{ old('no_hp') }}">
                            <small>
                                @error('no_hp')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="email">Email</label>
                            <input type="email" class="border rounded form-control border-opacity-30 @error('email') is-invalid @enderror" id="email"
                                name="email" placeholder="xxx@gmail.com" value="{{ old('email') }}">
                            <small>
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>
                    </div>

                    <div class="alert alert-info d-flex align-items-start" role="alert">
                        <div class="pt-1 me-3">
                            <i class="fas fa-info-circle fa-lg"></i>
                        </div>
                        <div class="ml-2">
                            <div class="mb-1">
                                <span>
                                    Siswa dapat langsung login menggunakan username dan password* dan wajib membuat
                                    password
                                    baru.
                                </span>
                            </div>
                            <small class="text-muted">
                                *Password sementara siswa adalah tanggal lahir dengan format <strong>ddmmyyyy</strong>
                            </small>
                        </div>
                    </div>
                    <!-- Form End -->
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary"
                    style="background-color: #20B2AA; border-color: #20B2AA;">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>
@if ($errors->any())
    <script>
        $(document).ready(function() {
            $('#modalSiswaCreate').modal('show');
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

{{-- <script>
    $(document).ready(function() {
        $('#formTambahSiswa').submit(function(e) {
            e.preventDefault(); // Prevent the form from being submitted traditionally

            let formData = new FormData(this); // Create FormData object for the form
            $.ajax({
                url: '{{ route('admin_siswa.store') }}', // Route tujuan
                method: 'POST',
                data: formData,
                processData: false, // Don't process the data
                contentType: false, // Don't set content type header
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Siswa berhasil ditambahkan!',
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
