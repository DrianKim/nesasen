<!-- Modal Show -->
<div class="modal fade" id="modalWalasShow{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="text-white modal-header bg-info">
                <h5 class="modal-title" id="exampleModalLongTitle">Detail Akun</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>

            <div class="text-left modal-body">

                <div class="row">
                    <div class="col-6">
                        Nama Lengkap
                    </div>
                    <div class="col-6">
                        : {{ $item->user->guru->nama ?? '-' }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        Username
                    </div>
                    <div class="col-6">
                        :
                        <span>
                            {{ $item->user->username ?? '' }}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        Nip
                    </div>
                    <div class="col-6">
                        :
                        <span>
                            {{ $item->user->guru->nip ?? '-' }}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        Tanggal Lahir
                    </div>
                    <div class="col-6">
                        :
                        <span>
                            {{ $item->user->guru->tanggal_lahir ?? '-' }}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        Jenis Kelamin
                    </div>
                    <div class="col-6">
                        :
                        <span>
                            {{ $item->user->guru->jenis_kelamin ?? '-' }}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        No HP
                    </div>
                    <div class="col-6">
                        :
                        <span>
                            {{ $item->user->guru->no_hp ?? '-' }}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        Email
                    </div>
                    <div class="col-6">
                        :
                        <span>
                            {{ $item->user->guru->email ?? '-' }}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        Alamat
                    </div>
                    <div class="col-6">
                        :
                        <span>
                            {{ $item->user->guru->alamat ?? '-' }}
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="modalWalasDestroy{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="text-white modal-header bg-danger">
                <h5 class="modal-title" id="exampleModalLongTitle">Hapus Akun Ini?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>

            <div class="text-left modal-body">

                <div class="row">
                    <div class="col-6">
                        Nama Lengkap
                    </div>
                    <div class="col-6">
                        : {{ $item->user->guru->nama ?? '-' }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        Mapel
                    </div>
                    <div class="col-6">
                        :
                        <span>
                            {{ $item->user->guru->mapel_kelas->mata_pelajaran->kode_mapel ?? '' }}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        Kelas Yang Diampu
                    </div>
                    <div class="col-6">
                        :
                        <span>
                            {{ $item->kelas->tingkat . ' ' . $item->kelas->jurusan->kode_jurusan . ' ' . $item->kelas->no_kelas ?? '-' }}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        Jenis Kelamin
                    </div>
                    <div class="col-6">
                        :
                        <span>
                            {{ $item->user->guru->jenis_kelamin ?? '-' }}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        Nip
                    </div>
                    <div class="col-6">
                        :
                        <span>
                            {{ $item->user->guru->nip ?? '-' }}
                        </span>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                    Tutup
                </button>
                <form action="{{ route('admin_guru.destroy', $item->id) }}" method="POST">
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
