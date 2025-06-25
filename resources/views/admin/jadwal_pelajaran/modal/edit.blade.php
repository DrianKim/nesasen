<div class="modal" id="modalJadwalEdit{{ $jadwal->id }}">
    <div class="modal-dialog-guru modal-lg">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header-custom">
                <div class="modal-header-icon"> 
                    <img src="{{ asset('assets/img/schedule.png') }}" alt="Jadwal Icon">
                </div>
                <div class="modal-header-text">
                    <h5>Edit Jadwal</h5>
                    <p>Edit hari dan waktu jadwal ini</p>
                </div>
            </div>

            <!-- Body -->
            <div class="modal-body-custom">
                <form id="formEditJadwal{{ $jadwal->id }}"
                    action="{{ route('admin_jadwal_pelajaran.update', $jadwal->id) }}" method="POST">
                    @csrf

                    <!-- Nama Mapel (Readonly) -->
                    <div class="form-group">
                        <label for="mapel">Mata Pelajaran</label>
                        <input type="text" id="mapel"
                            value="{{ $jadwal->mapelKelas->mataPelajaran->nama_mapel }}" readonly>
                    </div>

                    <!-- Kelas (Readonly) -->
                    <div class="form-group">
                        <label for="kelas">Kelas</label>
                        <input type="text" id="kelas"
                            value="{{ $jadwal->mapelKelas->kelas->tingkat ?? '-' }} {{ $jadwal->mapelKelas->kelas->jurusan->kode_jurusan ?? '-' }} {{ $jadwal->mapelKelas->kelas->no_kelas ?? '-' }}"
                            readonly>
                    </div>

                    <!-- Hari -->
                    <div class="form-group">
                        <label for="hari">Hari</label>
                        <select name="hari" id="hari">
                            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                                <option value="{{ $h }}"
                                    {{ ucfirst(\Carbon\Carbon::parse($jadwal->tanggal)->locale('id')->isoFormat('dddd')) == $h ? 'selected' : '' }}>
                                    {{ $h }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jam Masuk -->
                    <div class="form-group">
                        <label for="jam_mulai">Jam Masuk</label>
                        <input type="time" name="jam_mulai" id="jam_mulai_{{ $jadwal->id }}"
                            value="{{ $jadwal->jam_mulai }}">
                    </div>

                    <!-- Jam Selesai -->
                    <div class="form-group">
                        <label for="jam_selesai">Jam Selesai</label>
                        <input type="time" name="jam_selesai" id="jam_selesai_{{ $jadwal->id }}"
                            value="{{ $jadwal->jam_selesai }}">
                    </div>

                    <!-- Preview Durasi -->
                    <div class="form-group">
                        <label>Durasi</label>
                        <p id="durasiDisplay{{ $jadwal->id }}">-</p>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="modal-footer-custom">
                <button class="btn-secondary" onclick="closeModalEdit('modalJadwalEdit{{ $jadwal->id }}')">
                    <span class="material-icons-sharp">close</span> Batal
                </button>

                <button class="btn-success-ac" type="submit" form="formEditJadwal{{ $jadwal->id }}"
                    id="btnEditJadwal{{ $jadwal->id }}">
                    <span class="spinner" id="spinnerEditJadwal{{ $jadwal->id }}"
                        style="display: none; margin-right: 5px;"></span>
                    <span class="material-icons-sharp">add_home</span>
                    <span id="textBtnEditJadwal{{ $jadwal->id }}">Simpan</span>
                </button>
            </div>
        </div>
    </div>
</div>
