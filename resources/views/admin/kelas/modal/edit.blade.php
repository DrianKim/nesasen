@php
    $selectedWalas = $walasMap[$item->id] ?? null;
@endphp

<div class="modal" id="modalKelasEdit{{ $item->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header-custom">
                <div class="modal-header-icon">
                    <img src="images/classroom.png" alt="Kelas Icon" />
                </div>
                <div class="modal-header-text">
                    <h5>Edit Data Kelas</h5>
                    <p>Perbarui informasi kelas dengan benar</p>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="modal-body-custom">
                <form id="formEditKelas{{ $item->id }}" action="{{ route('admin_kelas.update', $item->id) }}"
                    method="POST">
                    @csrf

                    <input type="hidden" name="from_edit_kelas" value="{{ $item->id }}">

                    <div class="form-grid-kelas">
                        <div class="form-group">
                            <label for="tingkat">Tingkat</label>
                            <select id="tingkat" name="edit_tingkat"
                                class="@error('edit_tingkat') is-invalid @enderror">
                                <option value="" disabled>Pilih Tingkat</option>
                                <option value="X" {{ $item->tingkat == 'X' ? 'selected' : '' }}>X</option>
                                <option value="XI" {{ $item->tingkat == 'XI' ? 'selected' : '' }}>XI</option>
                                <option value="XII" {{ $item->tingkat == 'XII' ? 'selected' : '' }}>XII</option>
                            </select>
                            <small>
                                @error('edit_tingkat')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="no_kelas">No Kelas</label>
                            <select id="no_kelas" name="edit_no_kelas"
                                class="@error('edit_no_kelas') is-invalid @enderror">
                                <option value="" disabled>Pilih No Kelas</option>
                                <option value="1" {{ $item->no_kelas == '1' ? 'selected' : '' }}>1</option>
                                <option value="2" {{ $item->no_kelas == '2' ? 'selected' : '' }}>2</option>
                                <option value="3" {{ $item->no_kelas == '3' ? 'selected' : '' }}>3</option>
                                <option value="4" {{ $item->no_kelas == '4' ? 'selected' : '' }}>4</option>
                            </select>
                            <small>
                                @error('edit_no_kelas')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="jurusan">Jurusan</label>
                        <select id="jurusan" name="edit_jurusan_id"
                            class="form-control select-jurusan-edit @error('edit_jurusan_id') is-invalid @enderror"
                            required>
                            <option value="" disabled>Pilih Jurusan</option>
                            @foreach ($jurusanList as $jurusan)
                                <option value="{{ $jurusan->id }}"
                                    {{ $item->jurusan_id == $jurusan->id ? 'selected' : '' }}>
                                    {{ $jurusan->nama_jurusan }} ({{ $jurusan->kode_jurusan }})
                                </option>
                            @endforeach
                        </select>
                        <small>
                            @error('edit_jurusan_id')
                                <div style="color: red">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="guru">
                            Wali Kelas
                            <small class="text-muted">Silahkan pilih guru untuk jadi wali kelas (opsional)</small>
                        </label>
                        <select id="guru{{ $item->id }}" name="edit_guru_id"
                            class="form-control select-guru-edit @error('edit_guru_id') is-invalid @enderror">
                            <option value="" hidden>Pilih Guru...</option>
                            @foreach ($guruListEdit as $guru)
                                <option value="{{ $guru->id }}"
                                    {{ $selectedWalas == $guru->user_id ? 'selected' : '' }}>
                                    {{ $guru->nama }}
                                </option>
                            @endforeach
                        </select>
                        <small>
                            @error('edit_guru_id')
                                <div style="color: red">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer-custom">
                <button class="btn-secondary" onclick="closeModalEdit('modalKelasEdit{{ $item->id }}')">
                    <span class="material-icons-sharp">close</span> Batal
                </button>

                <button class="btn-success-ac" type="submit" form="formEditKelas{{ $id }}"
                    id="btnEditKelas{{ $id }}">
                    <span class="spinner" id="spinnerEditKelas{{ $id }}"
                        style="display: none; margin-right: 5px;"></span>
                    <span class="material-icons-sharp">add_home</span>
                    <span id="textBtnEditKelas{{ $id }}">Simpan</span>
                </button>
            </div>
        </div>
    </div>
</div>
