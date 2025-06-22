@php
    $selectedWalas = $walasMap[$item->id] ?? null;
@endphp

<div class="modal" id="modalKelasKuEdit{{ $item->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header-custom">
                <div class="modal-header-icon">
                    <img src="{{ asset('assets/img/classroom.png') }}" alt="Kelas Icon">
                </div>
                <div class="modal-header-text">
                    <h5>Edit Data KelasKu</h5>
                    <p>Perbarui informasi kelasKu dengan benar</p>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="modal-body-custom">
                <form id="formEditKelasKu{{ $item->id }}" action="{{ route('admin_kelasKu.update', $item->id) }}"
                    method="POST">
                    @csrf

                    <input type="hidden" name="from_edit_kelasKu" value="{{ $item->id }}">

                    <div class="form-grid-kelas">
                        <div class="form-group">
                            <label for="mapel_id">KelasKu</label>
                            <select class="form-control mapel_kelasKu_edit @error('mapel_id') is-invalid @enderror"
                                name="mapel_id" required>
                                <option value="" selected hidden>Pilih Kelasku</option>
                                @foreach ($mapelList as $mapel)
                                    <option value="{{ $mapel->id }}"
                                        {{ $item->mapel_id == $mapel->id ? 'selected' : '' }}>
                                        {{ $mapel->nama_mapel }}
                                    </option>
                                @endforeach
                            </select>
                            <small>
                                @error('mapel_id')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="kelas_id">Kelas</label>
                            <select class="form-control kelas_kelasKu_edit @error('kelas_id') is-invalid @enderror"
                                name="kelas_id" required>
                                <option value="" selected hidden>Pilih Kelas</option>
                                @foreach ($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}"
                                        {{ $item->kelas_id == $kelas->id ? 'selected' : '' }}>
                                        {{ $kelas->tingkat . ' ' . $kelas->jurusan->kode_jurusan . ' ' . $kelas->no_kelas }}
                                    </option>
                                @endforeach
                            </select>
                            <small>
                                @error('kelas_id')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="guru">Guru</label>
                        <select name="guru_id"
                            class="form-control guru_kelasKu_edit @error('guru_id') is-invalid @enderror" required>
                            <option value="" selected hidden>Pilih Guru</option>
                            @foreach ($guruList as $guru)
                                <option value="{{ $guru->id }}"
                                    {{ $item->guru_id == $guru->id ? 'selected' : '' }}>
                                    {{ $guru->nama }}
                                </option>
                            @endforeach
                        </select>
                        <small>
                            @error('guru_id')
                                <div style="color: red">{{ $message }}</div>
                            @enderror
                        </small>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer-custom">
                <button class="btn-secondary" onclick="closeModalEdit('modalKelasKuEdit{{ $item->id }}')">
                    <span class="material-icons-sharp">close</span> Batal
                </button>

                <button class="btn-success-ac" type="submit" form="formEditKelasKu{{ $id }}"
                    id="btnEditKelasKu{{ $id }}">
                    <span class="spinner" id="spinnerEditKelasKu{{ $id }}"
                        style="display: none; margin-right: 5px;"></span>
                    <span class="material-icons-sharp">add_home</span>
                    <span id="textBtnEditKelasKu{{ $id }}">Simpan</span>
                </button>
            </div>
        </div>
    </div>
</div>
