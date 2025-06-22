<?php

namespace App\Exports;

use App\Models\MapelKelas;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class KelasKuExport implements FromArray, WithStyles, WithTitle, WithColumnWidths
{
    protected $mapelKelas;
    protected $exportedAt;

    public function __construct()
    {
        $this->mapelKelas = MapelKelas::with(['mataPelajaran', 'kelas.jurusan', 'guru'])
            ->get()
            ->sortBy([
                fn($a, $b) => strcmp($a->mataPelajaran->nama_mapel ?? '', $b->mataPelajaran->nama_mapel ?? ''),
                fn($a, $b) => strcmp(
                    ($a->kelas->tingkat ?? '') . ($a->kelas->jurusan->kode_jurusan ?? '') . ($a->kelas->no_kelas ?? ''),
                    ($b->kelas->tingkat ?? '') . ($b->kelas->jurusan->kode_jurusan ?? '') . ($b->kelas->no_kelas ?? '')
                ),
                fn($a, $b) => strcmp($a->guru->nama ?? '', $b->guru->nama ?? ''),
            ]);

        $this->exportedAt = now()->format('d-m-Y H:i:s');
    }

    public function array(): array
    {
        $data = [];
        $data[] = [
            'No',
            'KelasKu',
            'Kelas',
            'Guru',
        ];

        $no = 1;
        foreach ($this->mapelKelas as $item) {
            $kelasLabel = $item->kelas
                ? ($item->kelas->tingkat . ' ' . $item->kelas->jurusan->kode_jurusan . ' ' . $item->kelas->no_kelas)
                : '-';

            $data[] = [
                $no++,
                $item->mataPelajaran->nama_mapel ?? '-',
                $kelasLabel,
                $item->guru->nama ?? '-',
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
            'C' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            'A1:D1' => [
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
            'B' => 25,
            'C' => 20,
            'D' => 25,
        ];
    }

    public function title(): string
    {
        return 'Data KelasKu';
    }
}
