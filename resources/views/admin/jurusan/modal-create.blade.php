<!-- Modal Create -->
<div class="modal fade" id="modalJurusanCreate" tabindex="-1" role="dialog" aria-labelledby="modalJurusanCreateTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center"
                style="background-color: #f5f5f5; border-bottom: 1px solid #ddd;">
                <div class="d-flex align-items-center">
                    <div class="mr-3" style="background-color: #e0f7f5; border-radius: 50%; padding: 10px;">
                        <img src="{{ asset('assets\img\book.png') }}" alt="Student Icon"
                            style="width: 40px; height: 40px;">
                    </div>
                    <div>
                        <h5 class="modal-title" style="font-weight: 500" id="modalJurusanCreateTitle">Tambahkan Data
                            Jurusan
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
                <form class="form-modal" id="formTambahJurusan" action="{{ route('admin_jurusan.store') }}"
                    method="POST">
                    @csrf
                    <div class="mb-3 form-group">
                        <label for="nama_jurusan">Nama Jurusan</label>
                        <input type="text"
                            class="border rounded form-control border-opacity-30 @error('nama_jurusan') is-invalid @enderror"
                            id="nama_jurusan" name="nama_jurusan" value="{{ old('nama_jurusan') }}">
                        <small>
                            @error('nama_jurusan')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>

                    <div class="mb-3 form-group">
                        <label for="kode_jurusan">Kode Jurusan</label>
                        <input type="text"
                            class="border rounded form-control border-opacity-30 @error('kode_jurusan') is-invalid @enderror"
                            id="kode_jurusan" name="kode_jurusan" value="{{ old('kode_jurusan') }}">
                        <small>
                            @error('kode_jurusan')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>
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
            $('#modalJurusanCreate').modal('show');
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
            $('#modalJurusanCreate').modal('show');

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
        $('#formTambahJurusan').submit(function(e) {
            e.preventDefault(); // Prevent the form from being submitted traditionally

            let formData = new FormData(this); // Create FormData object for the form
            $.ajax({
                url: '{{ route('admin_jurusan.store') }}', // Route tujuan
                method: 'POST',
                data: formData,
                processData: false, // Don't process the data
                contentType: false, // Don't set content type header
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Jurusan berhasil ditambahkan!',
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
