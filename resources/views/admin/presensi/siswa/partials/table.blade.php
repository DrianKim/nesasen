<table class="data-kelas-table">
    <thead>
        <tr>
            {{-- <th width="3%">
                <input type="checkbox" onclick="toggleAll(this)">
            </th> --}}
            @if (request('lihatBerdasarkan') == 'tanggal')
                <th width="20%" class="sortable" data-column="tanggal">Tanggal<i class="fas fa-sort"
                        style="margin-left: 4px"></i></th>
            @else
                <th width="20%" class="sortable" data-column="kelas">Kelas<i class="fas fa-sort"
                        style="margin-left: 4px"></i></th>
            @endif
            <th width="15%" data-column="total_siswa">Total Siswa</th>
            <th width="15%" data-column="Hadir">Hadir</th>
            <th width="15%" data-column="Izin">Izin</th>
            <th width="15%" data-column="Sakit">Sakit</th>
            <th width="15%" data-column="Sakit">Alpa</th>
            {{-- <th class="text-center" width="12%">Action</th> --}}
        </tr>
    </thead>
    <tbody>
        @forelse ($presensi_siswa as $item)
            <tr>
                {{-- <td>
                    <input type="checkbox" name="selected_presensi_siswa[]" value="{{ $item->id }}">
                </td> --}}
                @if (request('lihatBerdasarkan') == 'tanggal')
                    <td>
                        {{ $item['tanggal'] ? \Carbon\Carbon::parse($item['tanggal'])->translatedFormat('d-m-Y') : '-' }}
                    </td>
                @else
                    <td>{{ $item['kelas'] }}</td>
                @endif
                <td>{{ $item['total_siswa'] ?? '-' }}</td>
                <td>{{ $item['hadir'] ?? '-' }}</td>
                <td>{{ $item['izin'] ?? '-' }}</td>
                <td>{{ $item['sakit'] ?? '-' }}</td>
                <td>{{ $item['alpa'] ?? '-' }}</td>
                {{-- <td class="text-center">
                    <div class="action-buttons">
                        @include('admin.siswa.modal.edit', ['id' => $item->id, 'siswa' => $item])
                        <button class="btn-edit" onclick="openModalEdit('modalSiswaEdit{{ $item->id }}')">
                            <span class="material-icons-sharp">edit</span>
                        </button>
                        <button class="btn-delete" data-id="{{ $item->id }}" data-nama="{{ $item->nama }}">
                            <span class="material-icons-sharp">delete</span>
                        </button>
                    </div>
                </td> --}}
            </tr>
        @empty
            <tr>
                <td colspan="6" class="py-4 text-center">
                    <div class="empty-state">
                        <img src="{{ asset('assets\img\not-found.png') }}" alt="No Data" width="120">
                        <p>Tidak ada data presensi siswa yang ditemukan</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>


{{-- <table class="table table-hover">

    <tbody>
        @forelse ($presensi_siswa as $item)
            <tr>

                <td>{{ $item['kelas'] }}</td>
                <td>{{ $item['total_siswa'] ?? '-' }}</td>
                <td>{{ $item['hadir'] ?? '-' }}</td>
                <td>{{ $item['izin'] ?? '-' }}</td>
                <td>{{ $item['sakit'] ?? '-' }}</td>
                <td class="text-center">
                    <div class="action-buttons">

                        </button>
                    </div>

                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="py-4 text-center">
                    <div class="empty-state">
                        <img src="{{ asset('assets\img\not-found.png') }}" alt="No Data"
                            width="120">
                        <p>Tidak ada data presensi siswa yang ditemukan</p>

                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
 --}}
