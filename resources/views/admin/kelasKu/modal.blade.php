<!-- Modal Delete -->
<div class="modal fade" id="modalKelasKuDestroy{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="text-white modal-header bg-danger">
                <h5 class="modal-title" id="exampleModalLongTitle">Hapus Kelasku Ini?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>

            <div class="text-left modal-body">

                <div class="row">
                    <div class="col-6">
                        KelasKu
                    </div>
                    <div class="col-6">
                        : {{ $item->mata_pelajaran->nama_mapel ?? '' }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        Kelas
                    </div>
                    <div class="col-6">
                        : {{ $item->kelas->tingkat. ' '. $item->kelas->jurusan->kode_jurusan. ' '. $item->kelas->no_kelas ?? '' }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        Guru
                    </div>
                    <div class="col-6">
                        :
                        <span>
                            {{ $item->guru->nama ?? '' }}
                        </span>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                    Tutup
                </button>
                <form action="{{ route('admin_kelas.destroy', $item->id) }}" method="POST">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
