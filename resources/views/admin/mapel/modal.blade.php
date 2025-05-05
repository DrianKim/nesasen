<!-- Modal Delete -->
<div class="modal fade" id="modalMapelDestroy{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="text-white modal-header bg-danger">
                <h5 class="modal-title" id="exampleModalLongTitle">Hapus Mapel Ini?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>

            <div class="text-left modal-body">

                <div class="row">
                    <div class="col-6">
                        Nama Mapel
                    </div>
                    <div class="col-6">
                        : {{ $item->nama_mapel ?? '-' }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        Kode Mapel
                    </div>
                    <div class="col-6">
                        :
                        <span>
                            {{ $item->kode_mapel ?? '' }}
                        </span>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                    Tutup
                </button>
                <form action="{{ route('admin_mapel.destroy', $item->id) }}" method="POST">
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
