<?php

namespace App\Exports;

use App\Models\Guru;
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

class GuruExport implements FromArray, WithStyles, WithTitle, WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $guru;
    protected $totalGuru;
    protected $exportedAt;

    public function __construct()
    {
        $this->guru = Guru::with(['user'])->orderBy('nama', 'asc')->get();
        $this->totalGuru = $this->guru->count();
        $this->exportedAt = now()->format('d-m-Y H:i:s');
    }

    public function array(): array
    {
        $data = [];
        $data[] = [
            'No',
            'NIP',
            'Nama Guru',
            'Tgl. Lahir',
            'No. HP',
            'Email',
            'Jenis Kelamin',
            'Alamat',
        ];

        $no = 1;
        foreach ($this->guru as $guru) {
            $data[] = [
                $no++,
                $guru->nip,
                $guru->nama,
                $guru->tanggal_lahir ? date('d/m/Y', strtotime($guru->tanggal_lahir)) : '-',
                $guru->no_hp ?? '-',
                $guru->email ?? '-',
                $guru->jenis_kelamin ?? '-',
                $guru->alamat ?? '-',
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
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
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
            'A1:H1' => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 15,
            'C' => 30,
            'D' => 15,
            'E' => 15,
            'F' => 25,
            'G' => 15,
            'H' => 40,
        ];
    }

    public function title(): string
    {
        return 'Data Guru';
    }
}
