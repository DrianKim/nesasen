<?php

namespace App\Exports;

use App\Models\izinGuru;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class izinGuruExport implements FromArray, WithStyles, WithTitle, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $request;
    protected $izinGuru;
    protected $totalIzin;
    protected $exportedAt;

    public function __construct($filters)
    {
        $this->request = $filters;

        $query = izinGuru::select('izin_guru.*')
            ->join('guru', 'izin_guru.guru_id', '=', 'guru.id')
            ->with('guru');

        if (!empty($filters['range_tanggal'])) {
            [$start, $end] = explode(' - ', $filters['range_tanggal']);
            $query->whereBetween('tanggal', [trim($start), trim($end)]);
        }

        if (!empty($filters['search'])) {
            $searchTerm = strtolower(trim($filters['search']));
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('guru', function ($q2) use ($searchTerm) {
                    $q2->whereRaw('LOWER(REPLACE(nama, " ", "")) LIKE ?', ["%$searchTerm%"]);
                });
            });
        }

        $this->izinGuru = $query
            ->orderBy('tanggal', 'asc')
            ->orderByRaw('LOWER(guru.nama) ASC')
            ->get();
        $this->totalIzin = $this->izinGuru->count();
        $this->exportedAt = now()->format('d-m-Y H:i:s');
    }

    public function array(): array
    {
        $data = [];
        $data[] = [
            'No',
            'Tanggal',
            'Nama Guru',
            'Izin',
            'Keterangan',
        ];

        $no = 1;
        foreach ($this->izinGuru as $izin) {
            $data[] = [
                $no++,
                $izin->tanggal,
                $izin->guru->nama ?? '-',
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
            'A1:E1' => [
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
        return 'Data Izin Guru';
    }
}
