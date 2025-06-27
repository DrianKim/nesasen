<?php

namespace App\Exports;

use App\Models\Guru;
use App\Models\PresensiGuru;
use App\Models\IzinGuru;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class PresensiGuruExport implements FromArray, WithTitle, WithColumnWidths, WithStyles
{
    protected $data;
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;

        // Ambil tanggal range, fallback hari ini
        if (!empty($filters['range_tanggal'])) {
            [$start, $end] = explode(' - ', $filters['range_tanggal']);
        } else {
            $start = $end = now()->format('Y-m-d');
        }

        $guruIds = Guru::pluck('id');

        // Generate semua tanggal di range
        $dates = [];
        $currentDate = Carbon::parse($start);
        $endDate = Carbon::parse($end);
        while ($currentDate->lte($endDate)) {
            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        $this->data = collect();

        foreach ($dates as $tanggal) {
            $hadirCount = PresensiGuru::whereDate('tanggal', $tanggal)
                ->whereIn('guru_id', $guruIds)
                ->whereIn('status_kehadiran', ['hadir', 'terlambat'])
                ->count();

            $izinCount = IzinGuru::whereDate('tanggal', $tanggal)
                ->whereIn('guru_id', $guruIds)
                ->where('jenis_izin', '!=', 'Sakit')
                ->count();

            $sakitCount = IzinGuru::whereDate('tanggal', $tanggal)
                ->whereIn('guru_id', $guruIds)
                ->where('jenis_izin', 'Sakit')
                ->count();

            $alpaCount = PresensiGuru::whereDate('tanggal', $tanggal)
                ->whereIn('guru_id', $guruIds)
                ->where('status_kehadiran', 'alpa')
                ->count();

            $this->data->push([
                'tanggal' => Carbon::parse($tanggal)->format('d-m-Y'),
                'total_guru' => $guruIds->count(),
                'hadir' => $hadirCount,
                'izin' => $izinCount,
                'sakit' => $sakitCount,
                'alpa' => $alpaCount,
            ]);
        }
    }

    public function array(): array
    {
        $rows = [];
        // Header sesuai format rekap per tanggal
        $rows[] = ['Tanggal', 'Total Guru', 'Hadir', 'Izin', 'Sakit', 'Alpa'];

        foreach ($this->data as $rekap) {
            $rows[] = [
                $rekap['tanggal'],
                $rekap['total_guru'],
                $rekap['hadir'],
                $rekap['izin'],
                $rekap['sakit'],
                $rekap['alpa'],
            ];
        }

        // Footer tambahan
        $rows[] = [''];
        $rows[] = ['Dicetak pada: ' . now()->format('d-m-Y H:i:s')];
        $rows[] = ['SMKN 1 Subang'];

        return $rows;
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
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFEEEEEE'],
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
        return 'Presensi Guru';
    }
}
