<div class="modal" id="modalJadwalTambah">
    <div class="modal-dialog">
        <div class="modal-content dark-mode-availables">
            <!-- Modal Header -->
            <div class="modal-header-custom">
                <div class="modal-header-icon">
                    <img src="{{ asset('assets/img/classroom.png') }}" alt="Kelas Icon">
                </div>
                <div class="modal-header-text">
                    <h5>Tambahkan Data Jadwal</h5>
                    <p>Pastikan data yang kamu input benar</p>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="modal-body-custom">
                <form id="formTambahJadwal" action="#" method="POST">
                    @csrf

                    <input type="hidden" name="from_tambah_Jadwal" value="1">

                    <div class="form-grid-kelas">
                        <div class="form-group">
                            <label for="kelas_id">Kelas</label>
                            <select class="form-control kelas-jadwal @error('kelas_id') is-invalid @enderror"
                                name="kelas_id">
                                <option value="" selected hidden>Pilih Kelas</option>
                                @foreach ($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}"
                                        {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
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

                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer-custom">
                <button class="btn-secondary" onclick="closeModal('modalJadwalTambah')">
                    <span class="material-icons-sharp">close</span> Batal
                </button>

                <button class="btn-success-ac" type="submit" form="formTambahJadwal" id="btnTambahJadwal">
                    {{-- <span class="spinner" id="spinnerTambahJadwal" style="display: none; margin-right: 5px;"></span> --}}
                    <span class="material-icons-sharp">add_home</span> <span id="textBtnTambahJadwal">Lanjut</span>
                </button>
            </div>
        </div>
    </div>
</div>
