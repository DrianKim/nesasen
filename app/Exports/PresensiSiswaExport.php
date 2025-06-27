<?php

namespace App\Exports;

use App\Models\Siswa;
use App\Models\izinSiswa;
use App\Models\presensiSiswa;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PresensiSiswaExport implements FromArray, WithTitle, WithColumnWidths, WithStyles
{
    protected $data;
    protected $filters;
    protected $lihat;

    public function __construct($filters)
    {
        $this->filters = $filters;
        $this->lihat = $filters['lihatBerdasarkan'] ?? 'kelas';

        if ($this->lihat === 'kelas') {
            // Query dengan join ke siswa, kelas, dan jurusan
            $query = presensiSiswa::with('siswa.kelas.jurusan')
                ->join('siswa', 'presensi_siswa.siswa_id', '=', 'siswa.id')
                ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id')
                ->join('jurusan', 'kelas.jurusan_id', '=', 'jurusan.id')
                ->select('presensi_siswa.*');

            // Filter tanggal (jika dipilih)
            if (!empty($this->filters['tanggal'])) {
                $query->whereDate('presensi_siswa.tanggal', $this->filters['tanggal']);
            }

            // Filter kelas (jika dipilih)
            if (!empty($this->filters['kelas'])) {
                $kelasIds = is_array($this->filters['kelas']) ? $this->filters['kelas'] : [$this->filters['kelas']];
                $query->whereIn('siswa.kelas_id', $kelasIds);
            }

            // Urutan: kelas → jurusan → no kelas → nama
            $data = $query
                ->orderBy('kelas.tingkat')
                ->orderBy('jurusan.kode_jurusan')
                ->orderBy('kelas.no_kelas')
                ->orderByRaw('LOWER(siswa.nama) ASC')
                ->get();

            // Format data untuk export
            $this->data = $data->map(function ($item) {
                $kelas = optional($item->siswa->kelas);
                $jurusan = optional($kelas->jurusan);
                $kelasLabel = $kelas ? "{$kelas->tingkat} {$jurusan->kode_jurusan} {$kelas->no_kelas}" : '-';

                return [
                    'nama' => $item->siswa->nama ?? '-',
                    'kelas' => $kelasLabel,
                    'status' => $item->status_kehadiran,
                    'tanggal' => \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y'),
                ];
            });
        } else {
            // Mode rekap per tanggal
            if (!empty($this->filters['range_tanggal'])) {
                [$start, $end] = explode(' - ', $this->filters['range_tanggal']);
            } else {
                $start = $end = now()->format('Y-m-d');
            }

            // Ambil presensi dalam rentang tanggal
            $data = presensiSiswa::whereBetween('tanggal', [$start, $end])->get();

            // Ambil semua siswa aktif
            $siswaIds = Siswa::pluck('id');

            $this->data = $data->groupBy('tanggal')->map(function ($group, $tanggal) use ($siswaIds) {
                return [
                    'tanggal' => \Carbon\Carbon::parse($tanggal)->format('d-m-Y'),
                    'total_siswa' => $siswaIds->count(),
                    'hadir' => $group->whereIn('status_kehadiran', ['hadir', 'terlambat'])->count(),
                    'izin' => izinSiswa::whereDate('tanggal', $tanggal)
                        ->whereIn('siswa_id', $siswaIds)
                        ->where('jenis_izin', '!=', 'Sakit')
                        ->count(),
                    'sakit' => izinSiswa::whereDate('tanggal', $tanggal)
                        ->whereIn('siswa_id', $siswaIds)
                        ->where('jenis_izin', 'Sakit')
                        ->count(),
                    'alpa' => presensiSiswa::whereDate('tanggal', $tanggal)
                        ->whereIn('siswa_id', $siswaIds)
                        ->where('status_kehadiran', 'alpa')
                        ->count(),
                ];
            })->sortKeys();
        }
    }

    public function array(): array
    {
        $rows = [];

        if ($this->lihat === 'kelas') {
            $rows[] = ['Nama Siswa', 'Kelas', 'Status', 'Tanggal'];
            foreach ($this->data as $item) {
                $rows[] = [
                    $item['nama'],
                    $item['kelas'],
                    ucfirst($item['status']),
                    $item['tanggal'],
                ];
            }
        } else {
            $rows[] = ['Tanggal', 'Total Siswa', 'Hadir', 'Izin', 'Sakit', 'Alpa'];
            foreach ($this->data as $rekap) {
                $rows[] = [
                    $rekap['tanggal'],
                    $rekap['total_siswa'],
                    $rekap['hadir'],
                    $rekap['izin'],
                    $rekap['sakit'],
                    $rekap['alpa'],
                ];
            }
        }

        // Tambahan info di bawah
        $rows[] = [''];
        $rows[] = ['Dicetak pada: ' . now()->format('d-m-Y H:i:s')];
        $rows[] = ['SMKN 1 Subang'];

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        // Hitung jumlah kolom berdasarkan mode
        $lastColumn = $this->lihat === 'kelas' ? 'D' : 'F';

        return [
            // Apply style ke baris pertama (header)
            1 => [
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFEEEEEE'], // abu muda
                ],
            ],
        ];
    }


    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 15,
            'C' => 12,
            'D' => 12,
            'E' => 12,
            'F' => 12,
        ];
    }

    public function title(): string
    {
        return 'Presensi Siswa';
    }
}
