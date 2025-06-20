<?php

namespace App\Exports;

use App\Models\izinSiswa;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class izinSiswaExport implements FromArray, WithStyles, WithTitle, WithColumnWidths
{
    protected $request;
    protected $izinSiswa;
    protected $totalIzin;
    protected $exportedAt;

    public function __construct($filters)
    {
        $this->request = $filters;

        $query = izinSiswa::select('izin_siswa.*')
            ->join('siswa', 'izin_siswa.siswa_id', '=', 'siswa.id')
            ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id')
            ->join('jurusan', 'kelas.jurusan_id', '=', 'jurusan.id')
            ->with('siswa.kelas.jurusan');

        if (!empty($filters['kelas'])) {
            $query->where('kelas.id', $filters['kelas']);
        }

        if (!empty($filters['range_tanggal'])) {
            [$start, $end] = explode(' - ', $filters['range_tanggal']);
            $query->whereBetween('izin_siswa.tanggal', [trim($start), trim($end)]);
        }

        if (!empty($filters['search'])) {
            $searchTerm = strtolower(trim($filters['search']));
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(REPLACE(siswa.nama, " ", "")) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(kelas.tingkat) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(kelas.no_kelas) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(jurusan.kode_jurusan) LIKE ?', ["%{$searchTerm}%"]);
            });
        }

        $this->izinSiswa = $query
            ->orderBy('izin_siswa.tanggal', 'asc')
            ->orderByRaw('LOWER(siswa.nama) ASC')
            ->orderBy('kelas.tingkat', 'asc')
            ->orderBy('jurusan.kode_jurusan', 'asc')
            ->orderBy('kelas.no_kelas', 'asc')
            ->get();

        $this->totalIzin = $this->izinSiswa->count();
        $this->exportedAt = now()->format('d-m-Y H:i:s');
    }

    public function array(): array
    {
        $data = [];
        $data[] = [
            'No',
            'Tanggal',
            'Nama Siswa',
            'Kelas',
            'Izin',
            'Keterangan',
        ];

        $no = 1;
        foreach ($this->izinSiswa as $izin) {
            $data[] = [
                $no++,
                $izin->tanggal,
                $izin->siswa->nama ?? '-',
                $izin->siswa->kelas ? $izin->siswa->kelas->tingkat . ' ' . $izin->siswa->kelas->jurusan->kode_jurusan . ' ' . $izin->siswa->kelas->no_kelas : '-',
                $izin->jenis_izin ?? '-',
                $izin->keterangan ?? '-',
            ];
        }

        $data[] = [''];
        $data[] = ['Dicetak pada: ' . $this->exportedAt];
        $data[] = ['SMKN 1 Subang'];

        return $data;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFCCCCCC'],
                ],
            ],
            'B' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            'D' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            'A1:F1' => [
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 15,
            'C' => 30,
            'D' => 20,
            'E' => 25,
            'F' => 25,
        ];
    }

    public function title(): string
    {
        return 'Data Izin Siswa';
    }
}
