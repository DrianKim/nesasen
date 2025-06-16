<div class="modal" id="modalKelasTambah">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header-custom">
                <div class="modal-header-icon">
                    <img src="images/classroom.png" alt="Kelas Icon" />
                </div>
                <div class="modal-header-text">
                    <h5>Tambahkan Data Kelas</h5>
                    <p>Pastikan data yang kamu input benar</p>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="modal-body-custom">
                <form id="formTambahKelas" action="{{ route('admin_kelas.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="from_tambah_siswa" value="1">

                    <div class="form-grid-kelas">
                        <div class="form-group">
                            <label for="tingkat">Tingkat</label>
                            <select id="tingkat" name="create_tingkat"
                                class="@error('create_tingkat') is-invalid @enderror">
                                <option value="" hidden {{ old('create_tingkat') == '' ? 'selected' : '' }}>Pilih
                                    Tingkat</option>
                                <option value="X" {{ old('create_tingkat') == 'X' ? 'selected' : '' }}>X</option>
                                <option value="XI" {{ old('create_tingkat') == 'XI' ? 'selected' : '' }}>XI</option>
                                <option value="XII" {{ old('create_tingkat') == 'XII' ? 'selected' : '' }}>XII
                                </option>
                            </select>
                            <small>
                                @error('create_tingkat')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="no_kelas">No Kelas</label>
                            <select id="no_kelas" name="create_no_kelas"
                                class="@error('create_no_kelas') is-invalid @enderror"
                                value='{{ old('create_no_kelas') }}'>
                                <option value="" selected hidden>Pilih No Kelas</option>
                                <option value="1" {{ old('create_no_kelas') === '1' ? 'selected' : '' }}>1</option>
                                <option value="2" {{ old('create_no_kelas') === '2' ? 'selected' : '' }}>2</option>
                                <option value="3" {{ old('create_no_kelas') === '3' ? 'selected' : '' }}>3</option>
                                <option value="4" {{ old('create_no_kelas') === '4' ? 'selected' : '' }}>4
                                </option>
                            </select>
                            <small>
                                @error('create_no_kelas')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="jurusan">Jurusan</label>
                        <select id="jurusan" name="create_jurusan_id"
                            class="form-control @error('create_jurusan_id') is-invalid @enderror">
                            <option value="" selected hidden>Pilih Jurusan</option>
                            @foreach ($jurusanList as $jurusan)
                                <option value="{{ $jurusan->id }}"
                                    {{ old('create_jurusan_id') == $jurusan->id ? 'selected' : '' }}>
                                    {{ $jurusan->nama_jurusan }} ({{ $jurusan->kode_jurusan }})
                                </option>
                            @endforeach
                        </select>
                        <small>
                            @error('create_jurusan_id')
                                <div style="color: red">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="guru">
                            Wali Kelas
                            <small class="text-muted">Silahkan pilih guru untuk jadi wali kelas (opsional)</small>
                        </label>
                        <select id="guru" name="create_guru_id"
                            class="form-control @error('create_guru') is-invalid @enderror"
                            value='{{ old('create_guru_id') }}'>
                            <option value="" selected hidden>Pilih Guru...</option>
                            @foreach ($guruListCreate as $guru)
                                <option value="{{ $guru->id }}"
                                    {{ old('create_guru_id') == $guru->id ? 'selected' : '' }}>{{ $guru->nama }}
                                </option>
                            @endforeach
                        </select>
                        <small>
                            @error('create_guru')
                                <div style="color: red">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer-custom">
                <button class="btn-secondary" onclick="closeModal('modalKelasTambah')">
                    <span class="material-icons-sharp">close</span> Batal
                </button>

                <button class="btn-success-ac" type="submit" form="formTambahKelas" id="btnTambahKelas">
                    <span class="spinner" id="spinnerTambahKelas" style="display: none; margin-right: 5px;"></span>
                    <span class="material-icons-sharp">add_home</span> <span id="textBtnTambahKelas">Tambah</span>
                </button>
            </div>
        </div>
    </div>
</div>
