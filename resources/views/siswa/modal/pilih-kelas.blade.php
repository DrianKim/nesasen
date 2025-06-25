<div class="kelas-modal" id="modalPilihKelas">
    <div class="kelas-modal-dialog">
        <div class="kelas-modal-content">
            <h2>Pilih Kelas Anda</h2>

            <input type="text" id="searchKelas" class="kelas-modal-search" placeholder="Cari kelas..."
                onkeyup="filterKelas()">

            <form action="{{ route('siswa.pilihKelas') }}" method="POST" style="padding-bottom: 70px;">
                @csrf
                <div id="kelasList">
                    @foreach ($daftarKelas as $kelas)
                        <label class="kelas-modal-option">
                            <input type="radio" name="kelas_id" value="{{ $kelas->id }}" {{ $siswa->kelas_id == $kelas->id ? 'checked' : '' }}>
                            <span class="material-icons-sharp" style="font-size:24px;">school</span>
                            {{ $kelas->tingkat }} {{ $kelas->jurusan->kode_jurusan }} {{ $kelas->no_kelas }}
                        </label>
                    @endforeach
                </div>

                <div class="kelas-modal-actions">
                    <button type="submit" class="kelas-modal-btn">Simpan</button>
                    <button type="button" class="kelas-modal-btn kelas-modal-btn-cancel"
                        onclick="closeModal('modalPilihKelas')">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    function filterKelas() {
        const input = document.getElementById("searchKelas").value.toLowerCase().trim();
        const kelasItems = document.querySelectorAll("#kelasList .kelas-modal-option");

        kelasItems.forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(input) ? "flex" : "none";
        });
    }
</script>


{{-- <style>
    :root {
        --modal-bg: #fff;
        --modal-text: #222;
        --modal-border: #ccc;
        --modal-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        --modal-btn-bg: #4CAF50;
        --modal-btn-text: #fff;
        --modal-btn-cancel-bg: #ccc;
        --modal-btn-cancel-text: #333;
        --kelas-hover: #f9f9f9;
        --kelas-border: #eee;
        --input-bg: #fff;
        --input-border: #ccc;
        --input-text: #222;
    }

    @media (prefers-color-scheme: dark) {
        :root {
            --modal-bg: #23272f;
            --modal-text: #f1f1f1;
            --modal-border: #444;
            --modal-shadow: 0 0 16px rgba(0, 0, 0, 0.7);
            --modal-btn-bg: #388e3c;
            --modal-btn-text: #fff;
            --modal-btn-cancel-bg: #444;
            --modal-btn-cancel-text: #eee;
            --kelas-hover: #2a2f38;
            --kelas-border: #333;
            --input-bg: #23272f;
            --input-border: #444;
            --input-text: #f1f1f1;
        }
    }

    /* body.modal-open {
        overflow: hidden;
    } */

    /* ===== Modal Pilih Kelas - Custom Class Prefix: kelas-modal- ===== */

</style> --}}
