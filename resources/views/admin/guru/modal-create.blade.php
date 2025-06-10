<!-- Modal Create -->
<div class="modal fade" id="modalGuruCreate" tabindex="-1" role="dialog" aria-labelledby="modalGuruCreateTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center"
                style="background-color: #228DFFFF; border-bottom: 1px solid #228DFFFF;">
                <div class="d-flex align-items-center">
                    <div class="mr-3" style="background-color: #228DFFFF; border-radius: 50%; padding: 10px;">
                        <img src="{{ asset('assets\img\teacher.png') }}" alt="Guru Icon"
                            style="width: 40px; height: 40px;">
                    </div>
                    <div>
                        <h5 class="modal-title" style="font-weight: 500; color: white" id="modalGuruCreateTitle">
                            Tambahkan Data Guru
                        </h5>
                        <p class="mb-0" style="font-size: 0.85rem; color: white;">Pastikan data yang kamu input benar
                        </p>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <!-- Form Start -->
                <form class="form-modal" id="formTambahGuru" action="{{ route('admin_guru.store') }}" method="POST">
                    @csrf
                    <div class="mb-3 form-group">
                        <label for="nip">Nip</label>
                        <input type="text"
                            class="border rounded form-control border-opacity-30 @error('nip') is-invalid @enderror"
                            id="nip" name="nip" value="{{ old('nip') }}">
                        <small>
                            @error('nip')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>

                    <div class="mb-3 form-group">
                        <label for="nama">Nama Guru</label>
                        <input type="text"
                            class="border rounded form-control border-opacity-30 @error('nama') is-invalid @enderror"
                            id="nama" name="nama" value="{{ old('nama') }}">
                        <small>
                            @error('nama')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>

                    <div class="mb-3 form-group">
                        <label for="username">Username</label>
                        <input type="text"
                            class="border rounded form-control border-opacity-30 @error('username') is-invalid @enderror"
                            id="username" name="username" value="{{ old('username') }}">
                        <small>
                            @error('username')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>

                    <div class="mb-3 form-group">
                        <label for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="date"
                            class="border rounded form-control border-opacity-30 @error('tanggal_lahir') is-invalid @enderror"
                            id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                        <small>
                            @error('tanggal_lahir')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-md-6 form-group">
                            <label for="no_hp">No HP</label>
                            <input type="text"
                                class="border rounded form-control border-opacity-30 @error('no_hp') is-invalid @enderror"
                                id="no_hp" name="no_hp" placeholder="08xxxxxxxx" value="{{ old('no_hp') }}">
                            <small>
                                @error('no_hp')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="email">Email</label>
                            <input type="email"
                                class="border rounded form-control border-opacity-30 @error('email') is-invalid @enderror"
                                id="email" name="email" placeholder="xxx@gmail.com" value="{{ old('email') }}">
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
                                    Guru dapat langsung login menggunakan username dan password* dan wajib membuat
                                    password
                                    baru.
                                </span>
                            </div>
                            <small class="text-muted">
                                *Password sementara guru adalah tanggal lahir dengan format <strong>ddmmyyyy</strong>
                            </small>
                        </div>
                    </div>
                    <!-- Form End -->
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>
                    Tambah
                </button>
            </div>
            </form>
        </div>
    </div>
</div>
@if ($errors->any())
    <script>
        $(document).ready(function() {
            $('#modalGuruCreate').modal('show');
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
            $('#modalGuruCreate').modal('show');

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
        $('#formTambahGuru').submit(function(e) {
            e.preventDefault(); // Prevent the form from being submitted traditionally

            let formData = new FormData(this); // Create FormData object for the form
            $.ajax({
                url: '{{ route('admin_guru.store') }}', // Route tujuan
                method: 'POST',
                data: formData,
                processData: false, // Don't process the data
                contentType: false, // Don't set content type header
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Guru berhasil ditambahkan!',
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
