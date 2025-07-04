<?php

namespace App\Exports;

use App\Models\Siswa;
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

class SiswaExport implements FromArray, WithStyles, WithTitle, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $siswa;
    protected $totalSiswa;
    protected $exportedAt;

    public function __construct()
    {
        $this->siswa = Siswa::with(['user', 'kelas.jurusan'])
            ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id')
            ->join('jurusan', 'kelas.jurusan_id', '=', 'jurusan.id')
            ->orderBy('kelas.tingkat', 'asc')
            ->orderBy('jurusan.nama_jurusan', 'asc')
            ->orderBy('siswa.nama', 'asc')
            ->select('siswa.*')
            ->get();
        $this->totalSiswa = $this->siswa->count();
        $this->exportedAt = now()->format('d-m-Y H:i:s');
    }

    public function array(): array
    {
        $data = [];
        $data[] = [
            'No',
            'NISN',
            'NIS',
            'Kelas',
            'Nama Siswa',
            'Tgl. Lahir',
            'No. HP',
            'Email',
            'Jenis Kelamin',
            'Alamat',
        ];

        $no = 1;
        foreach ($this->siswa as $siswa) {
            $data[] = [
                $no++,
                $siswa->nisn,
                $siswa->nis,
                $siswa->nama,
                $siswa->kelas ? ($siswa->kelas->tingkat . ' ' . $siswa->kelas->jurusan->kode_jurusan . ' ' . $siswa->kelas->no_kelas) : '-',
                $siswa->tanggal_lahir ? date('d/m/Y', strtotime($siswa->tanggal_lahir)) : '-',
                $siswa->no_hp ?? '-',
                $siswa->email ?? '-',
                $siswa->jenis_kelamin ?? '-',
                $siswa->alamat ?? '-',
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
            'C' => 15,
            'D' => 20,
            'E' => 30,
            'F' => 15,
            'G' => 15,
            'H' => 25,
            'I' => 15,
            'J' => 30,
        ];
    }

    public function title(): string
    {
        return 'Data Siswa';
    }
}
