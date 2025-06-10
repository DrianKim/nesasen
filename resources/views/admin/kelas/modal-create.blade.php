<!-- Modal Create -->
<div class="modal fade" id="modalKelasCreate" tabindex="-1" role="dialog" aria-labelledby="modalKelasCreateTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center"
                style="background-color: #f5f5f5; border-bottom: 1px solid #ddd;">
                <div class="d-flex align-items-center">
                    <div class="mr-3" style="background-color: #e0f7f5; border-radius: 50%; padding: 10px;">
                        <img src="{{ asset('assets\img\classroom.png') }}" alt="Kelas Icon"
                            style="width: 40px; height: 40px;">
                    </div>
                    <div>
                        <h5 class="modal-title" style="font-weight: 500" id="modalKelasCreateTitle">Tambahkan Data
                            Kelas
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
                <form class="form-modal" id="formTambahKelas" action="{{ route('admin_kelas.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="tingkat">Tingkat</label>
                            <select
                                class="border rounded form-control border-opacity-30 @error('tingkat') is-invalid @enderror"
                                name="tingkat">
                                <option value="" disabled>---Pilih Tingkat---</option>
                                <option value="X">X</option>
                                <option value="XI">XI</option>
                                <option value="XII">XII</option>
                            </select>
                            <small>
                                @error('kode_mapel')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="no_kelas">No Kelas</label>
                            <select
                                class="border rounded form-control border-opacity-30 @error('no_kelas') is-invalid @enderror"
                                name="no_kelas">
                                <option value="" disabled>---Pilih No Kelas---</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                            <small>
                                @error('kode_mapel')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>
                    </div>

                    <div class="mb-3 form-group">
                        <label for="jurusan">Jurusan</label>
                        <select name="jurusan_id"
                            class="border rounded form-control border-opacity-30 @error('jurusan_id') is-invalid @enderror"
                            id="jurusan">
                            <option value="" disabled>---Pilih Jurusan---</option>
                            @foreach ($jurusanList as $jurusan)
                                <option value="{{ $jurusan->id }}">
                                    {{ $jurusan->nama_jurusan . ' (' . $jurusan->kode_jurusan . ')' }}
                                </option>
                            @endforeach
                        </select>
                        <small>
                            @error('kelas_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>

                    <div class="mb-3 form-group">
                        <label for="guru">Guru <small class="text-muted">*Silahkan Pilih Guru Untuk Menjadi Wali
                                Kelas (Opsional)</small></label>
                        <select name="guru_id"
                            class="border rounded form-control border-opacity-30 @error('guru_id') is-invalid @enderror"
                            id="guru">
                            <option value="" disabled>---Pilih Guru---</option>
                            @foreach ($guruList as $guru)
                                <option value="{{ $guru->id }}">
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

@if ($errors->any())
    <script>
        $(document).ready(function() {
            $('#modalKelasCreate').modal('show');
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
