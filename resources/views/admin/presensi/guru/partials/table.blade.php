<table class="data-kelas-table">
    <thead>
        <tr>
            {{-- <th width="3%">
                <input type="checkbox" onclick="toggleAll(this)">
            </th> --}}
            <th width="20%" class="sortable" data-column="tanggal">Tanggal<i class="fas fa-sort"
                    style="margin-left: 4px"></i></th>
            <th width="15%" data-column="total_guru">Total Guru</th>
            <th width="15%" data-column="hadir">Hadir</th>
            <th width="15%" data-column="izin">Izin</th>
            <th width="15%" data-column="sakit">Sakit</th>
            <th width="15%" data-column="alpa">Alpa</th>
            {{-- <th class="text-center" width="12%">Action</th> --}}
        </tr>
    </thead>
    <tbody>
        @forelse ($presensi_guru as $item)
            <tr>
                {{-- <td>
                    <input type="checkbox" name="selected_presensi_guru[]" value="{{ $item->id }}">
                </td> --}}
                <td>
                    {{ $item['tanggal'] ? \Carbon\Carbon::parse($item['tanggal'])->translatedFormat('d-m-Y') : '-' }}
                </td>
                <td>{{ $item['total_guru'] ?? '-' }}</td>
                <td>{{ $item['hadir'] ?? '-' }}</td>
                <td>{{ $item['izin'] ?? '-' }}</td>
                <td>{{ $item['sakit'] ?? '-' }}</td>
                <td>{{ $item['alpa'] ?? '-' }}</td>
                {{-- <td class="text-center">
                    <div class="action-buttons">
                        @include('admin.guru.modal.edit', ['id' => $item->id, 'guru' => $item])
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
                        <p>Tidak ada data presensi guru yang ditemukan</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>


{{-- <table class="table table-hover">

    <tbody>
        @forelse ($presensi_guru as $item)
            <tr>

                <td>{{ $item['kelas'] }}</td>
                <td>{{ $item['total_guru'] ?? '-' }}</td>
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
                        <p>Tidak ada data presensi guru yang ditemukan</p>

                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
 --}}
