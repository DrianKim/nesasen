<?php

namespace App\Exports;

use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class KelasExport implements FromArray, WithStyles, WithTitle, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $kelas;
    protected $totalKelas;
    protected $exportedAt;

    public function __construct()
    {
        $this->kelas = Kelas::with(['jurusan', 'walas.user.guru'])
            ->join('jurusan', 'kelas.jurusan_id', '=', 'jurusan.id')
            ->select('kelas.*')
            ->orderBy('tingkat', 'asc')
            ->orderBy('jurusan.nama_jurusan', 'asc')
            ->orderBy('no_kelas', 'asc')
            ->get();
        $this->totalKelas = $this->kelas->count();
        $this->exportedAt = now()->format('d-m-Y H:i:s');
    }

    public function array(): array
    {
        $data = [];
        $data[] = [
            'No',
            'Kelas',
            'Wali Kelas',
        ];

        $no = 1;
        foreach ($this->kelas as $kelas) {
            $data[] = [
                $no++,
                $kelas ? $kelas->tingkat . ' ' . $kelas->jurusan->kode_jurusan . ' ' . $kelas->no_kelas : '-',
                $kelas->walas->user->guru->nama ?? '-',
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
            'A1:C1' => [
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
            'B' => 20,
            'C' => 20,
        ];
    }

    public function title(): string
    {
        return 'Data Kelas';
    }

    public function collection()
    {
        return Kelas::all();
    }
}
