<?php

namespace App\Http\Controllers;

use Svg\Tag\Rect;
use Carbon\Carbon;
use App\Models\Guru;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\walas;
use App\Models\Jurusan;
use App\Models\kelasKu;
use App\Models\izinGuru;
use Barryvdh\DomPDF\PDF;
use App\Models\izinSiswa;
use App\Models\MapelKelas;
use App\Exports\GuruExport;
use Illuminate\Support\Str;
use App\Exports\KelasExport;
use App\Exports\SiswaExport;
use App\Imports\SiswaImport;
use App\Models\presensiGuru;
use Illuminate\Http\Request;
use App\Models\MataPelajaran;
use App\Models\presensiSiswa;
use App\Exports\JurusanExport;
use App\Exports\KelasKuExport;
use App\Exports\izinGuruExport;
use App\Exports\izinSiswaExport;
use Database\Seeders\GuruSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exports\MataPelajaranExport;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Psy\CodeCleaner\FunctionContextPass;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use function PHPUnit\Framework\returnSelf;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminController extends Controller
{

    // walas
    // public function index_walas()
    // {
    //     $data = array(
    //         'title' => 'Daftar Wali Kelas',
    //         'menuPengguna' => 'active',
    //         // 'menu_admin_index_walas' => 'active',
    //         'walas' => Walas::with('guru')->get(),
    //     );
    //     return view('admin.walas.index', $data);
    // }

    // public function create_walas()
    // {
    //     $data = array(
    //         'title' => 'Tambah Wali Kelas',
    //         'menuPengguna' => 'active',
    //         // 'menu_admin_index_walas' => 'active',
    //         'walas' => Walas::with('guru')->get(),
    //         'guruList' => Guru::all(),
    //     );
    //     return view('admin.walas.create', $data);
    // }

    // public function store_walas() {}

    private function parseExcelDate($dateString)
    {
        try {
            // Try to parse dd/mm/yyyy format
            if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $dateString, $matches)) {
                $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                $year = $matches[3];

                return Carbon::createFromFormat('d/m/Y', "{$day}/{$month}/{$year}");
            }

            // Try to parse if it's Excel serial date
            if (is_numeric($dateString)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateString);
            }

            // Try Carbon parse
            return Carbon::parse($dateString);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function generateUsernameFromName($nama)
    {
        $parts = explode(' ', strtolower($nama));

        $username = '';
        foreach ($parts as $part) {
            $username .= substr($part, 0, 1);
        }

        $username .= substr(end($parts), -2);

        $baseUsername = $username;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }


    // umum kelas
    public function index_kelas(Request $request)
    {
        $kelasId = $request->input('kelas');
        $tahunAjaran = $request->input('tahun_ajaran');
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10);
        $sortBy = $request->input('sort_by', null);
        $sortDirection = $request->input('sort_direction', 'asc');

        $query = Kelas::with(['jurusan', 'walas.user.guru', 'siswa']);

        if ($kelasId) {
            $query->where('id', $kelasId);
        }

        if ($tahunAjaran) {
            $query->where('tahun_ajaran', $tahunAjaran);
        }

        if ($search) {
            $searchTerm = strtolower(trim($search));
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(tingkat) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(no_kelas) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereHas('jurusan', function ($q2) use ($searchTerm) {
                        $q2->whereRaw('LOWER(nama_jurusan) LIKE ?', ["%{$searchTerm}%"])
                            ->orWhereRaw('LOWER(kode_jurusan) LIKE ?', ["%{$searchTerm}%"]);
                    })
                    ->orWhereHas('walas.user.guru', function ($q3) use ($searchTerm) {
                        $q3->whereRaw('LOWER(nama) LIKE ?', ["%{$searchTerm}%"]);
                    });
            });
        }

        if ($sortBy) {
            switch ($sortBy) {
                case 'Nama Kelas':
                    $query->with('jurusan')
                        ->orderBy('tingkat', $sortDirection)
                        ->orderBy(Jurusan::select('nama_jurusan')
                            ->whereColumn('jurusan.id', 'kelas.jurusan_id'), $sortDirection)
                        ->orderBy('no_kelas', $sortDirection);
                    break;

                case 'Wali Kelas':
                    $query->join('walas', 'kelas.id', '=', 'walas.kelas_id')
                        ->join('users', 'walas.user_id', '=', 'users.id')
                        ->join('guru', 'guru.user_id', '=', 'users.id')
                        ->orderBy("guru.nama", $sortDirection)
                        ->select('kelas.*');
                    break;

                case 'Jumlah Siswa':
                    $query->withCount('siswa')->orderBy('siswa_count', $sortDirection);
                    break;

                default:
                    $query->orderBy('tingkat', $sortDirection);
                    break;
            }
        } else {
            $query->orderBy('tingkat', 'asc');
        }

        $kelas = $query->paginate($perPage)->withQueryString();

        $tahunAjaranFilter = Kelas::select('tahun_ajaran')->distinct()->pluck('tahun_ajaran');
        $guruWalasIds = Walas::pluck('user_id')->toArray();
        $walasMap = Walas::pluck('user_id', 'kelas_id')->toArray();

        $guruListCreate = Guru::whereHas('user', function ($query) use ($guruWalasIds) {
            $query->whereNotIn('id', $guruWalasIds);
        })->get();

        $currentWalasGuruIds = collect($kelas->items())->map(function ($kelasItem) {
            return $kelasItem->walas?->user_id;
        })->filter()->unique()->toArray();

        $excludedUserIds = array_diff($guruWalasIds, $currentWalasGuruIds);

        $guruListEdit = Guru::whereHas('user', function ($query) use ($excludedUserIds) {
            $query->whereNotIn('id', $excludedUserIds);
        })->get();

        $data = [
            'title' => 'Daftar Kelas',
            'menuAdmin' => 'active',
            'jurusanList' => Jurusan::all(),
            'guruListCreate' => $guruListCreate,
            'guruListEdit' => $guruListEdit,
            'kelas' => $kelas,
            'tahunAjaranFilter' => $tahunAjaranFilter,
            'walasMap' => $walasMap,
        ];

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.kelas.partials.table', $data)->render(),
                'pagination' => view('admin.kelas.partials.pagination', ['kelas' => $kelas])->render(),
            ]);
        }

        return view('admin.kelas.index', $data);
    }

    public function create_kelas()
    {

        $data = array(
            'title' => 'Tambah Kelas',
            'menuAdmin' => 'active',
            // 'menu_admin_index_kelas' => 'active',
            'jurusanList' => Jurusan::all(),
            'guruList' => Guru::all(),
        );
        return view('admin.kelas.create', $data);
    }

    public function store_kelas(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'create_tingkat' => 'required|in:X,XI,XII',
            'create_jurusan_id' => 'required|exists:jurusan,id',
            'create_no_kelas' => 'required',
            'create_guru_id' => 'nullable|exists:guru,id',
        ], [
            'create_tingkat.required' => 'Tingkat Tidak Boleh Kosong',
            'create_tingkat.in' => 'Tingkat Tidak Valid',
            'create_jurusan_id.required' => 'Jurusan Tidak Boleh Kosong',
            'create_no_kelas.required' => 'No Kelas Tidak Boleh Kosong',
        ]);

        try {
            $kelas = Kelas::create([
                'tingkat' => $request->create_tingkat,
                'jurusan_id' => $request->create_jurusan_id,
                'no_kelas' => $request->create_no_kelas,
            ]);

            if ($request->create_guru_id) {
                $guru = Guru::findOrFail($request->create_guru_id);

                $user = $guru->user;

                if ($user && $user->role_id == 3) {
                    Walas::create([
                        'user_id' => $user->id,
                        'kelas_id' => $kelas->id,
                    ]);

                    $user->role_id = 2;
                    $user->save();
                }
            }

            return redirect()->route('admin_kelas.index')->with('success', 'Kelas Berhasil Ditambahkan');
        } catch (\Exception $e) {
            return back()->withErrors(['general' => 'Terjadi kesalahan, coba lagi.'])->withInput();
        }
    }

    public function edit_kelas($id)
    {
        $kelas = Kelas::findOrFail($id);

        $data = array(
            'title' => 'Edit Kelas',
            'menuAdmin' => 'active',
            'kelas' => $kelas,
            'jurusanList' => Jurusan::all(),
            'guruList' => User::where('role_id', 3)->with('guru')->get(),
        );

        return view('admin.kelas.edit', $data);
    }

    public function update_kelas(Request $request, $id)
    {
        $request->validate([
            'edit_tingkat' => 'required|in:X,XI,XII',
            'edit_jurusan_id' => 'required|exists:jurusan,id',
            'edit_no_kelas' => 'required|in:1,2,3,4',
            'edit_guru_id' => 'nullable|exists:users,id',
        ], [
            'edit_tingkat.required' => 'Tingkat Tidak Boleh Kosong',
            'edit_tingkat.in' => 'Tingkat Tidak Valid',
            'edit_jurusan_id.required' => 'Jurusan Tidak Boleh Kosong',
            'edit_no_kelas.required' => 'No Kelas Tidak Boleh Kosong',
        ]);

        DB::transaction(function () use ($request, $id) {
            $kelas = Kelas::findOrFail($id);

            $kelas->update([
                'tingkat' => $request->edit_tingkat,
                'jurusan_id' => $request->edit_jurusan_id,
                'no_kelas' => $request->edit_no_kelas,
            ]);

            $walasLama = Walas::where('kelas_id', $id)->first();

            if ($request->edit_guru_id) {
                if ($walasLama && $walasLama->user_id != $request->edit_guru_id) {
                    $userLama = User::find($walasLama->user_id);
                    if ($userLama) {
                        $userLama->role_id = 3;
                        $userLama->save();
                    }
                }

                Walas::updateOrCreate(
                    ['kelas_id' => $id],
                    ['user_id' => $request->edit_guru_id]
                );

                $userBaru = User::find($request->edit_guru_id);
                if ($userBaru) {
                    $userBaru->role_id = 2;
                    $userBaru->save();
                }
            } else {
                if ($walasLama) {
                    $userLama = User::find($walasLama->user_id);
                    if ($userLama) {
                        $userLama->role_id = 3;
                        $userLama->save();
                    }
                    $walasLama->delete();
                }
            }
        });

        return redirect()->route('admin_kelas.index')->with('success', 'Kelas berhasil diperbarui!');
    }

    public function destroy_kelas($id)
    {
        $kelas = Kelas::findOrFail($id);

        $walas = Walas::where('kelas_id', $kelas->id)->first();

        if ($walas) {
            $user = User::find($walas->user_id);
            if ($user) {
                $user->role_id = 3;
                $user->save();
            }
            $walas->delete();
        }

        $kelas->delete();

        return redirect()->route('admin_kelas.index')->with('success', 'Kelas Berhasil Dihapus');
    }

    public function bulkAction_kelas(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data yang dipilih!'
            ]);
        }

        try {
            $deletedCount = 0;

            foreach ($ids as $id) {
                $kelas = Kelas::find($id);

                if ($kelas) {
                    $walas = Walas::where('kelas_id', $kelas->id)->first();

                    if ($walas) {
                        $user = User::find($walas->user_id);
                        if ($user) {
                            $user->role_id = 3;
                            $user->save();
                        }
                        $walas->delete();
                    }

                    $kelas->delete();
                    $deletedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil menghapus {$deletedCount} data Kelas."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
            ]);
        }
    }

    public function download_template_kelas()
    {
        $fileName = 'template_import_kelas_' . date('Y-m-d') . '.xlsx';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getProperties()
            ->setCreator('SMKN 1 Subang')
            ->setTitle('Template Import Kelas')
            ->setDescription('Template untuk mengimpor data kelas ke dalam sistem.');

        $headersStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => '198754'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '0000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];

        $headers = [
            'Tingkat',
            'Nama Jurusan',
            'Kode Jurusan',
            'No Kelas',
            'Wali Kelas',
        ];

        $sheet->fromArray(($headers), null, 'A1');
        $sheet->getStyle('A1:E1')->applyFromArray($headersStyle);

        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->getColumnDimension('E')->setWidth(20);

        $exampleData = [
            ['X', 'Teknik Komputer Jaringan', 'TKJ', '1', 'Giga ngga'],
            ['X', 'Rekayasa Perangkat Lunak', 'RPL', '2', 'Mi bombo'],
        ];

        $sheet->fromArray($exampleData, null, 'A2');
        $sheet->getStyle('A2:E3')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '0000000'],
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'f8f9fa'],
            ],
        ]);

        $sheet->setCellValue('A5', 'Petunjuk:');
        $sheet->setCellValue('A6', '1. Isi kolom "Tingkat" dengan X, XI, atau XII.');
        $sheet->setCellValue('A7', '2. Isi kolom "Nama Jurusan" dengan nama jurusan yang sesuai.');
        $sheet->setCellValue('A8', '3. Isi kolom "Kode Jurusan" dengan kode jurusan yang sesuai.');
        $sheet->setCellValue('A9', '4. Isi kolom "No Kelas" dengan nomor kelas yang sesuai.');
        $sheet->setCellValue('A10', '5. Kolom "Wali Kelas" bersifat opsional, isi dengan nama guru yang sudah terdaftar di sistem.');

        $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle('A6:A10')->getFont()->setItalic(true);

        $sheet->getRowDimension(1)->setRowHeight(25);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Last-Modified' => gmdate('D, d M Y H:i:s') . ' GMT',
            'Cache-Control' => 'cache, must-revalidate',
            'Pragma' => 'public',
        ]);
    }

    public function import_kelas(Request $request)
    {
        try {
            // dd($request->all());
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
            ], [
                'file.required' => 'File Tidak Boleh Kosong',
                'file.file' => 'File Harus Berupa File',
                'file.mimes' => 'File Harus Berformat XLSX, XLS, atau CSV',
                'file.max' => 'Ukuran File Maksimal 2MB',
            ]);

            $file = $request->file('file');

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            $headers = array_shift($rows);

            $requiredHeaders = [
                'Tingkat',
                'Nama Jurusan',
                'Kode Jurusan',
                'No Kelas',
            ];
            $optionalHeaders = [
                'Wali Kelas',
            ];
            $headerMap = [];

            foreach ($requiredHeaders as $required) {
                $found = false;
                foreach ($headers as $index => $header) {
                    if (trim(strtoupper($header)) === strtoupper(($required))) {
                        $headerMap[$required] = $index;
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    return response()->json([
                        'success' => false,
                        'message' => "Kolom {$required} tidak ditemukan dalam file."
                    ]);
                }
            }

            foreach ($optionalHeaders as $optional) {
                $found = false;
                foreach ($headers as $index => $header) {
                    if (trim(strtoupper($header)) === strtoupper(($optional))) {
                        $headerMap[$optional] = $index;
                        $found = true;
                        break;
                    }
                }
            }

            $successCount = 0;
            $errorRows = [];

            DB::beginTransaction();

            foreach ($rows as $rowIndex => $row) {
                $rowNumber = $rowIndex + 2;

                try {

                    if (empty(array_filter($row))) {
                        continue;
                    }

                    $tingkat = trim($row[$headerMap['Tingkat']] ?? '');
                    $namaJurusan = trim($row[$headerMap['Nama Jurusan']] ?? '');
                    $kodeJurusan = trim($row[$headerMap['Kode Jurusan']] ?? '');
                    $noKelas = trim($row[$headerMap['No Kelas']] ?? '');

                    $waliNama = isset($headerMap['Wali Kelas']) ? trim($row[$headerMap['Wali Kelas']] ?? '') : null;

                    // dd($tingkat, $namaJurusan, $kodeJurusan, $noKelas, $waliNama);

                    if (!$tingkat || !$namaJurusan || !$kodeJurusan || !$noKelas) {
                        $missing = [];
                        if (!$tingkat) $missing[] = "Tingkat";
                        if (!$namaJurusan) $missing[] = "Nama Jurusan";
                        if (!$kodeJurusan) $missing[] = "Kode Jurusan";
                        if (!$noKelas) $missing[] = "No Kelas";
                        $errorRows[] = "Baris {$rowNumber}: Kolom " . implode(', ', $missing) . " tidak boleh kosong.";
                        continue;
                    }

                    if (!in_array($tingkat, ['X', 'XI', 'XII'])) {
                        $errorRows[] = "Baris {$rowNumber}: Tingkat harus X, XI, atau XII.";
                        continue;
                    }

                    if (!preg_match('/^[1-9]$/', $noKelas)) {
                        $errorRows[] = "Baris {$rowNumber}: No Kelas harus 1, 2, 3, dst.";
                        continue;
                    }

                    $jurusan = Jurusan::where('nama_jurusan', $namaJurusan)
                        ->orWhere('kode_jurusan', $kodeJurusan)
                        ->first();

                    if ($jurusan && $jurusan->nama_jurusan !== $namaJurusan) {
                        $errorRows[] = "Baris {$rowNumber}: Jurusan dengan nama '{$namaJurusan}' sudah ada dengan kode '{$jurusan->kode_jurusan}'.";
                        continue;
                    }

                    // cek jika nama guru wali kelas sudah ada
                    if ($waliNama) {
                        $guru = Guru::where('nama', 'LIKE', '%' . $waliNama . '%')->first();
                        if (!$guru) {
                            $errorRows[] = "Baris {$rowNumber}: Guru dengan nama '{$waliNama}' tidak ditemukan.";
                            continue;
                        }
                        // Cek jika guru sudah menjadi wali kelas di kelas lain
                        $sudahWali = User::where('id', $guru->user_id)
                            ->where('role_id', 2)
                            ->exists();
                        // Cek jika guru sudah menjadi wali kelas di kelas lain
                        $sudahWalii = User::where('id', $guru->user_id)
                            ->where('role_id', 2)
                            ->exists();
                        if ($sudahWali || $sudahWalii) {
                            $errorRows[] = "Baris {$rowNumber}: Guru dengan nama '{$waliNama}' sudah menjadi wali kelas di kelas lain.";
                            continue;
                        }
                    }

                    $kelasExists = Kelas::where([
                        'tingkat' => $tingkat,
                        'jurusan_id' => $jurusan->id,
                        'no_kelas' => $noKelas,
                        'tahun_ajaran' => date('Y'),
                    ])->exists();

                    if ($kelasExists) {
                        $errorRows[] = "Baris {$rowNumber}: Kelas {$tingkat} {$kodeJurusan} {$noKelas} sudah ada.";
                        continue;
                    }


                    $jurusan = Jurusan::firstOrCreate(
                        ['nama_jurusan' => $namaJurusan],
                        ['kode_jurusan' => $kodeJurusan],
                    );

                    $kelas = Kelas::create([
                        'tingkat' => $tingkat,
                        'jurusan_id' => $jurusan->id,
                        'no_kelas' => $noKelas,
                        'tahun_ajaran' => date('Y'),
                    ]);

                    if ($waliNama) {
                        $guru = Guru::where('nama', 'LIKE', '%' . $waliNama . '%')->first();
                        if ($guru) {
                            Walas::create([
                                'user_id' => $guru->user_id,
                                'kelas_id' => $kelas->id,
                            ]);

                            $guru->user->update([
                                'role_id' => 2,
                            ]);
                        } else {
                            $errorRows[] = "Baris {$rowNumber}: Guru dengan nama '{$waliNama}' tidak ditemukan.";
                        }
                    }

                    $successCount++;
                } catch (\Exception $e) {
                    $errorRows[] = "Baris {$rowNumber}: Terjadi kesalahan saat memproses data - " . $e->getMessage();
                }
            }

            if ($successCount > 0) {
                DB::commit();

                $message = "Berhasil mengimpor {$successCount} data kelas.";
                $errors = array_merge($errorRows);

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'errors' => !empty($errors) ? $errors : null,
                ]);
            } else {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data yang berhasil diimpor.',
                    'errors' => array_merge($errorRows),
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan:' . $e->getMessage(),
            ]);
        }
    }

    public function export_kelas_pdf()
    {
        try {
            $kelas = Kelas::with(['jurusan', 'walas.user.guru'])
                ->join('jurusan', 'kelas.jurusan_id', '=', 'jurusan.id')
                ->select('kelas.*')
                ->orderBy('tingkat', 'asc')
                ->orderBy('jurusan.nama_jurusan', 'asc')
                ->orderBy('no_kelas', 'asc')
                ->get();

            $data = [
                'title' => 'Data Kelas',
                'kelas' => $kelas,
                'exported_at' => Carbon::now()->format('d-m-Y'),
                'exported_at_time' => Carbon::now()->format('d-m-Y H:i:s'),
                'total_kelas' => $kelas->count(),
            ];

            $pdf = app('dompdf.wrapper');
            $pdf->loadView('admin.kelas.export.pdf', $data);

            $fileName = 'data_kelas_' . date('Y-m-d_H-i-s') . '.pdf';

            return $pdf->download($fileName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengekspor data ke pdf: ' . $e->getMessage());
        }
    }

    public function export_kelas_xlsx()
    {
        try {
            $fileName = 'data_kelas_' . date('Y-m-d_H-i-s') . '.xlsx';

            return Excel::download(new KelasExport(), $fileName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengekspor data ke Excel: ' . $e->getMessage());
        }
    }

    // umum kelasKu
    public function index_kelasKu(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10);
        $sortBy = $request->input('sort_by');
        $sortDirection = $request->input('sort_direction', 'asc');

        $query = MapelKelas::with(['kelas.jurusan', 'mataPelajaran', 'guru']);

        // Search Logic
        if ($search) {
            $searchTerm = strtolower(str_replace(' ', '', trim($search))); // Hilangkan spasi dari search term juga

            $query->where(function ($q) use ($searchTerm) {
                // Search di mata pelajaran
                $q->whereHas('mataPelajaran', function ($q2) use ($searchTerm) {
                    $q2->whereRaw('LOWER(REPLACE(nama_mapel, " ", "")) LIKE ?', ["%$searchTerm%"])
                        ->orWhereRaw('LOWER(REPLACE(kode_mapel, " ", "")) LIKE ?', ["%$searchTerm%"]);
                })
                    // Search di guru
                    ->orWhereHas('guru', function ($q3) use ($searchTerm) {
                        $q3->whereRaw('LOWER(REPLACE(nama, " ", "")) LIKE ?', ["%$searchTerm%"]);
                    })
                    // Search di kelas (optional tambahan)
                    ->orWhereHas('kelas', function ($q4) use ($searchTerm) {
                        $q4->whereRaw('LOWER(CONCAT(tingkat, (SELECT kode_jurusan FROM jurusan WHERE jurusan.id = kelas.jurusan_id), no_kelas)) LIKE ?', ["%$searchTerm%"]);
                    });
            });
        }

        // Sorting Logic
        if ($sortBy) {
            switch ($sortBy) {
                case 'KelasKu':
                    $query->leftJoin('mata_pelajaran', 'mapel_kelas.mata_pelajaran_id', '=', 'mata_pelajaran.id')
                        ->orderBy('mata_pelajaran.nama_mapel', $sortDirection)
                        ->select('mapel_kelas.*');
                    break;

                case 'Kelas':
                    $query->leftJoin('kelas', 'mapel_kelas.kelas_id', '=', 'kelas.id')
                        ->leftJoin('jurusan', 'kelas.jurusan_id', '=', 'jurusan.id')
                        ->orderByRaw("CONCAT(kelas.tingkat, jurusan.kode_jurusan, kelas.no_kelas) $sortDirection")
                        ->select('mapel_kelas.*');
                    break;

                case 'Guru':
                    $query->leftJoin('guru', 'mapel_kelas.guru_id', '=', 'guru.id')
                        ->orderBy('guru.nama', $sortDirection)
                        ->select('mapel_kelas.*');
                    break;

                default:
                    $query->orderBy('mapel_kelas.id', 'asc');
                    break;
            }
        } else {
            $query->orderBy('mapel_kelas.id', 'asc');
        }

        $kelasKu = $query->paginate($perPage)->withQueryString();

        $data = [
            'title' => 'Kelasku',
            'menuAdmin' => 'active',
            'kelasKu' => $kelasKu,
            'mapelList' => MataPelajaran::all(),
            'guruList' => Guru::all(),
            'kelasList' => Kelas::with('jurusan')->get(),
        ];

        // AJAX Response
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'table' => view('admin.kelasKu.partials.table', $data)->render(),
                'pagination' => view('admin.kelasKu.partials.pagination', ['kelasKu' => $kelasKu])->render(),
            ]);
        }

        return view('admin.kelasKu.index', $data);
    }

    public function store_kelasKu(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'guru_id' => 'required|exists:guru,id',
        ], [
            'kelas_id.required' => 'Kelas Tidak Boleh Kosong',
            'mapel_id.required' => 'Mata Pelajaran Tidak Boleh Kosong',
            'guru_id.required' => 'Guru Tidak Boleh Kosong',
        ]);

        try {
            MapelKelas::create([
                'kelas_id' => $request->kelas_id,
                'mapel_id' => $request->mapel_id,
                'guru_id' => $request->guru_id,
            ]);

            return redirect()->route('admin_kelasKu.index')->with('success', 'KelasKu Berhasil Ditambahkan');
        } catch (\Exception $e) {
            return back()->withErrors(['general' => 'Terjadi kesalahan, coba lagi.'])->withInput();
        }
    }

    public function update_kelasKu(Request $request, $id)
    {
        try {
            $request->validate([
                'mapel_id' => 'required|exists:mata_pelajaran,id',
                'kelas_id' => 'required|exists:kelas,id',
                'guru_id' => 'required|exists:guru,id',
            ], [
                'mapel_id.required' => 'Mapel wajib dipilih.',
                'kelas_id.required' => 'Kelas wajib dipilih.',
                'guru_id.required' => 'Guru wajib dipilih.',
            ]);

            $mapelKelas = MapelKelas::findOrFail($id);
            $mapelKelas->update([
                'mapel_id' => $request->mapel_id,
                'kelas_id' => $request->kelas_id,
                'guru_id' => $request->guru_id,
            ]);

            return redirect()->back()->with('success', 'Data KelasKu berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update data: ' . $e->getMessage());
        }
    }

    public function destroy_kelasKu($id)
    {
        $kelasKu = MapelKelas::findOrFail($id);

        $kelasKu->delete();

        return redirect()->route('admin_kelasKu.index')->with('success', 'KelasKu Berhasil Dihapus');
    }

    public function bulkAction_kelasKu(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data yang dipilih!'
            ]);
        }

        try {
            $deletedCount = 0;

            foreach ($ids as $id) {
                $kelasKu = MapelKelas::find($id);

                if ($kelasKu) {
                    $kelasKu->delete();
                    $deletedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil menghapus {$deletedCount} data KelasKu."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
            ]);
        }
    }


    public function download_template_kelasKu()
    {
        $fileName = 'template_import_kelasKu_' . date('Y-m-d') . '.xlsx';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getProperties()
            ->setCreator('SMKN 1 Subang')
            ->setTitle('Template Import KelasKu')
            ->setDescription('Template untuk mengimpor data kelas ke dalam sistem.');

        $headersStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => '198754'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '0000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];

        $headers = [
            'KelasKu',
            'Kelas',
            'Guru',
        ];

        $sheet->fromArray(($headers), null, 'A1');
        $sheet->getStyle('A1:C1')->applyFromArray($headersStyle);

        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(25);

        $exampleData = [
            ['Bahasa Indonesia', 'X RPL 1', 'Mi Bombo'],
            ['Bahasa Jepang', 'X TKJ 1', 'Buyungkai'],
        ];

        $sheet->fromArray($exampleData, null, 'A2');
        $sheet->getStyle('A2:C3')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '0000000'],
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'f8f9fa'],
            ],
        ]);

        $sheet->setCellValue('A5', 'Petunjuk:');
        $sheet->setCellValue('A6', '1. Isi kolom "KelasKu" dengan nama mapel yang sesuai.');
        $sheet->setCellValue('A7', '2. Isi kolom "Kelas" dengan nama kelas yang sesuai.');
        $sheet->setCellValue('A8', '3. Isi kolom "Guru" dengan nama guru yang sesuai.');

        $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle('A6:A8')->getFont()->setItalic(true);

        $sheet->getRowDimension(1)->setRowHeight(25);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Last-Modified' => gmdate('D, d M Y H:i:s') . ' GMT',
            'Cache-Control' => 'cache, must-revalidate',
            'Pragma' => 'public',
        ]);
    }

    public function import_kelasKu(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
            ], [
                'file.required' => 'File tidak boleh kosong.',
                'file.mimes' => 'File harus berformat: XLSX, XLS, atau CSV.',
                'file.max' => 'Ukuran file maksimal 2MB.',
            ]);

            $file = $request->file('file');
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            $headers = array_shift($rows);

            $requiredHeaders = ['KelasKu', 'Kelas', 'Guru'];
            $headerMap = [];

            foreach ($requiredHeaders as $required) {
                $found = false;
                foreach ($headers as $index => $header) {
                    if (trim(strtolower($header)) === strtolower($required)) {
                        $headerMap[$required] = $index;
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    return response()->json([
                        'success' => false,
                        'message' => "Kolom '{$required}' tidak ditemukan dalam file.",
                    ]);
                }
            }

            $successCount = 0;
            $errorRows = [];

            DB::beginTransaction();

            foreach ($rows as $rowIndex => $row) {
                $rowNumber = $rowIndex + 2;

                try {
                    if (empty(array_filter($row))) {
                        continue;
                    }

                    $namaMapel = trim($row[$headerMap['KelasKu']] ?? '');
                    $namaKelas = trim($row[$headerMap['Kelas']] ?? '');
                    $namaGuru  = trim($row[$headerMap['Guru']] ?? '');

                    if (!$namaMapel || !$namaKelas || !$namaGuru) {
                        $errorRows[] = "Baris {$rowNumber}: Kolom wajib tidak boleh kosong.";
                        continue;
                    }

                    $mapel = MataPelajaran::where('nama_mapel', $namaMapel)->first();
                    if (!$mapel) {
                        $errorRows[] = "Baris {$rowNumber}: Mata pelajaran '{$namaMapel}' tidak ditemukan.";
                        continue;
                    }

                    // Pecah Kelas, contoh: X RPL 1
                    preg_match('/^(X|XI|XII)\s+([A-Z]+)\s+(\d+)$/i', $namaKelas, $match);
                    if (!$match) {
                        $errorRows[] = "Baris {$rowNumber}: Format kelas '{$namaKelas}' tidak valid. Gunakan format: X RPL 1.";
                        continue;
                    }

                    [$full, $tingkat, $kodeJurusan, $noKelas] = $match;

                    $jurusan = Jurusan::where('kode_jurusan', strtoupper($kodeJurusan))->first();
                    if (!$jurusan) {
                        $errorRows[] = "Baris {$rowNumber}: Jurusan '{$kodeJurusan}' tidak ditemukan.";
                        continue;
                    }

                    $kelas = Kelas::where([
                        'tingkat' => $tingkat,
                        'jurusan_id' => $jurusan->id,
                        'no_kelas' => $noKelas,
                        // 'tahun_ajaran' => date('Y'),
                    ])->first();

                    if (!$kelas) {
                        $errorRows[] = "Baris {$rowNumber}: Kelas '{$namaKelas}' tidak ditemukan.";
                        continue;
                    }

                    $guru = Guru::where('nama', 'LIKE', '%' . $namaGuru . '%')->first();
                    if (!$guru) {
                        $errorRows[] = "Baris {$rowNumber}: Guru '{$namaGuru}' tidak ditemukan.";
                        continue;
                    }

                    $exists = MapelKelas::where([
                        'mapel_id' => $mapel->id,
                        'kelas_id' => $kelas->id,
                        'guru_id' => $guru->id,
                    ])->exists();

                    if ($exists) {
                        $errorRows[] = "Baris {$rowNumber}: Data mapel '{$namaMapel}', kelas '{$namaKelas}', dan guru '{$namaGuru}' sudah ada.";
                        continue;
                    }

                    MapelKelas::create([
                        'mapel_id' => $mapel->id,
                        'kelas_id' => $kelas->id,
                        'guru_id' => $guru->id,
                    ]);

                    // Di controller
                    if ($request->ajax()) {
                        Log::debug('Ini AJAX Request');
                    } else {
                        Log::debug('INI BUKAN AJAX');
                    }

                    $successCount++;
                } catch (\Exception $e) {
                    $errorRows[] = "Baris {$rowNumber}: Error - " . $e->getMessage();
                }
            }

            if ($successCount > 0) {
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => "Berhasil mengimpor {$successCount} data mapel kelas.",
                    'errors' => $errorRows,
                ]);
            } else {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => "Tidak ada data yang berhasil diimpor.",
                    'errors' => $errorRows,
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    }

    public function export_kelasKu_pdf()
    {
        try {
            $kelasKu = MapelKelas::with(['mataPelajaran', 'kelas.jurusan', 'guru'])
                ->get()
                ->sortBy([
                    fn($a, $b) => strcmp($a->mataPelajaran->nama_mapel ?? '', $b->mataPelajaran->nama_mapel ?? ''),
                    fn($a, $b) => strcmp(
                        ($a->kelas->tingkat ?? '') . ($a->kelas->jurusan->kode_jurusan ?? '') . ($a->kelas->no_kelas ?? ''),
                        ($b->kelas->tingkat ?? '') . ($b->kelas->jurusan->kode_jurusan ?? '') . ($b->kelas->no_kelas ?? '')
                    ),
                    fn($a, $b) => strcmp($a->guru->nama ?? '', $b->guru->nama ?? ''),
                ]);

            $data = [
                'title' => 'Data KelasKu',
                'kelasKu' => $kelasKu,
                'exported_at' => Carbon::now()->format('d-m-Y'),
                'exported_at_time' => Carbon::now()->format('d-m-Y H:i:s'),
                'total_data' => $kelasKu->count(),
            ];

            $pdf = app('dompdf.wrapper');
            $pdf->loadView('admin.kelasKu.export.pdf', $data);

            $fileName = 'data_kelasKu_' . date('Y-m-d_H-i-s') . '.pdf';

            return $pdf->download($fileName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengekspor KelasKu: ' . $e->getMessage());
        }
    }

    public function export_kelasKu_xlsx()
    {
        try {
            $fileName = 'data_kelasKu_' . date('Y-m-d_H-i-s') . '.xlsx';

            return Excel::download(new KelasKuExport(), $fileName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengekspor data ke Excel: ' . $e->getMessage());
        }
    }

    // siswa
    protected function mapSortColumn($label)
    {
        return match ($label) {
            'NIS' => 'nis',
            'Nama Siswa' => 'nama',
            'No. HP' => 'no_hp',
            'Kelas' => 'kelas_id',
            'default' => 'id',
        };
    }

    public function index_siswa(Request $request)
    {
        $kelasId = $request->input('kelas');
        $tahunAjaran = $request->input('tahun_ajaran');
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10);
        $sortBy = $request->input('sort_by');
        $sortDirection = $request->input('sort_direction', 'asc');

        $query = Siswa::with(['user.siswa', 'kelas.jurusan']);

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        if ($tahunAjaran) {
            $query->whereHas('kelas', function ($q) use ($tahunAjaran) {
                $q->where('tahun_ajaran', $tahunAjaran);
            });
        }

        if ($search) {
            $searchTerm = strtolower(trim($search));

            $query->whereHas('user.siswa', function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(REPLACE(nama, " ", "")) LIKE ?', ['%' . $searchTerm . '%']);
            })->orWhere(function ($q) use ($searchTerm) {
                $q->orWhere('nis', 'like', "%{$searchTerm}%")
                    ->orWhere('no_hp', 'like', "%{$searchTerm}%");
            });
        }

        if ($sortBy) {
            switch ($sortBy) {
                case 'Nisn':
                    $query->orderBy('nisn', $sortDirection);
                    break;

                case 'Nis':
                    $query->orderBy('nis', $sortDirection);
                    break;

                case 'Nama Siswa':
                    $query->orderBy('nama', $sortDirection);
                    break;

                case 'No. HP':
                    $query->orderBy('no_hp', $sortDirection);
                    break;

                case 'Kelas':
                    $query->join('kelas', 'siswa.kelas_id', '=', 'kelas.id')
                        ->join('jurusan', 'kelas.jurusan_id', '=', 'jurusan.id')
                        ->orderByRaw("tingkat $sortDirection")
                        ->orderBy('jurusan.nama_jurusan', $sortDirection)
                        ->orderByRaw("no_kelas $sortDirection")
                        ->select('siswa.*');
                    break;

                default:
                    $query->orderBy('kelas_id', 'asc')->orderBy('nama', 'asc');
                    break;
            }
        }
        // if ($sortBy) {
        //     $columnMap = [
        //         // 'NISN' => 'nisn',
        //         'NIS' => 'nis',
        //         'Nama Siswa' => 'nama',
        //         'No. HP' => 'no_hp',
        //         'Kelas' => 'kelas_id'
        //     ];

        //     if (isset($columnMap[$sortBy])) {
        //         $query->orderBy($columnMap[$sortBy], $sortDirection);
        //     } else {
        //         $query->orderBy('kelas_id', 'asc')->orderBy('nama', 'asc');
        //     }
        // }

        $siswa = $query->paginate($perPage)->withQueryString();

        $kelasFilter = Kelas::with('jurusan')->orderBy('tingkat')->orderBy('jurusan_id')->orderBy('no_kelas')->get();
        $tahunAjaranFilter = Kelas::select('tahun_ajaran')->distinct()->pluck('tahun_ajaran');

        $data = [
            'title' => 'Data Siswa',
            'kelasList' => Kelas::all(),
            'siswa' => $siswa,
            'kelasFilter' => $kelasFilter,
            'tahunAjaranFilter' => $tahunAjaranFilter,
        ];

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.siswa.partials.table', $data)->render(),
                'pagination' => view('admin.siswa.partials.pagination', ['siswa' => $siswa])->render(),
            ]);
        }

        return view('admin.siswa.index', $data);
    }

    public function create_siswa()
    {
        $data = array(
            'title' => 'Tambah Siswa',
            'menuPengguna' => 'active',
            // 'menu_admin_index_siswa' => 'active',
            'kelasList' => Kelas::all(),
        );
        return view('admin.siswa.create', $data);
    }

    public function store_siswa(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'create_nisn' => 'nullable|numeric|digits_between:8,10|unique:siswa,nisn',
            'create_nis' => 'required|numeric|digits_between:4,10|unique:siswa,nis',
            'create_nama' => 'required|string|max:255',
            'create_username' => 'required|unique:users,username',
            'create_tanggal_lahir' => 'required|date',
            'create_kelas_id' => 'required|exists:kelas,id',
            'create_no_hp' => 'required|digits_between:10,15',
            'create_email' => 'required|email|max:255',
        ], [
            'create_nisn.required' => 'NISN Tidak Boleh Kosong',
            'create_nisn.numeric' => 'NISN Harus Angka',
            'create_nisn.digits_between' => 'NISN Harus Antara 8-10 Digit',
            'create_nisn.unique' => 'NISN Sudah Digunakan',
            'create_nis.required' => 'NIS Tidak Boleh Kosong',
            'create_nis.numeric' => 'NIS Harus Angka',
            'create_nis.digits_between' => 'NIS Harus Antara 4-10 Digit',
            'create_nis.unique' => 'NIS Sudah Digunakan',
            'create_nama.required' => 'Nama Tidak Boleh Kosong',
            'create_username.required' => 'Username Tidak Boleh Kosong',
            'create_username.unique' => 'Username Sudah Digunakan',
            'create_tanggal_lahir.required' => 'Tanggal Lahir Tidak Boleh Kosong',
            'create_tanggal_lahir.date' => 'Tanggal Lahir Tidak Valid',
            'create_kelas_id.required' => 'Kelas Tidak Boleh Kosong',
            'create_kelas_id.exists' => 'Kelas Tidak Valid',
            'create_no_hp.required' => 'No HP Tidak Boleh Kosong',
            'create_no_hp.digits_between' => 'No HP Harus Antara 10-15 Digit',
            'create_email.required' => 'Email Tidak Boleh Kosong',
            'create_email.email' => 'Email Tidak Valid',
            'create_email.max' => 'Email Terlalu Panjang',
        ]);

        try {
            $password = date('dmY', strtotime($validated['create_tanggal_lahir']));

            $user = User::create([
                'username' => $request->create_username,
                'role_id' => 4,
                'password' => Hash::make($password),
            ]);

            Siswa::create([
                'user_id' => $user->id,
                'nisn' => $request->create_nisn,
                'nis' => $request->create_nis,
                'nama' => $request->create_nama,
                'kelas_id' => $request->create_kelas_id,
                'tanggal_lahir' => $request->create_tanggal_lahir,
                'no_hp' => $request->create_no_hp,
                'email' => $request->create_email,
            ]);

            return redirect()->route('admin_siswa.index')->with('success', 'Siswa berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withErrors(['general' => 'Terjadi kesalahan, coba lagi.'])->withInput();
        }
    }

    public function inline_update_siswa(Request $request, $id)
    {
        try {
            $request->validate([
                'nisn' => 'required|string',
                'nis' => 'required|string',
                'nama' => 'required|string',
                'no_hp' => 'required|string',
            ]);

            $siswa = Siswa::findOrFail($id);

            // Only update the fields we expect to be editable
            $allowedFields = ['nisn', 'nis', 'nama', 'no_hp'];

            foreach ($allowedFields as $field) {
                if ($request->has($field)) {
                    $siswa->$field = $request->$field;
                }
            }

            $siswa->save();

            return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit_siswa($id)
    {
        $data = array(
            'title' => 'Edit Siswa',
            'menuPengguna' => 'active',
            // 'menu_admin_index_siswa' => 'active',
            'siswa' => Siswa::with('user', 'kelas.jurusan')->findOrFail($id),
            'kelasList' => Kelas::all(),
        );
        return view('admin.siswa.edit', $data);
    }

    public function update_siswa(Request $request, $id)
    {
        $siswa = Siswa::with('user')->findOrFail($id);

        $user = $siswa->user;

        // dd($request->all());

        $request->validate([
            'edit_nisn' => 'nullable|numeric|digits_between:8,10|unique:siswa,nisn,' . $id,
            'edit_nis' => 'required|numeric|digits_between:4,10|unique:siswa,nis,' . $id,
            'edit_nama' => 'required|string|max:255',
            'edit_username' => 'required|unique:users,username,' . ($user ? $user->id : 'NULL'),
            'edit_tanggal_lahir' => 'required|date',
            'edit_kelas_id' => 'required|exists:kelas,id',
            'edit_no_hp' => 'required|digits_between:10,15',
            'edit_email' => 'required|email|max:255',
            'edit_jenis_kelamin' => 'nullable|in:L,P',
            'edit_alamat' => 'nullable|string|max:255',
            // 'current_password' => 'nullable|required_with:new_password|current_password:web',
            'new_password' => 'nullable|min:5|same:password_confirmation',
            'password_confirmation' => 'nullable|required_with:new_password|same:new_password',
        ], [
            'edit_nisn.required' => 'NISN Tidak Boleh Kosong',
            'edit_nisn.numeric' => 'NISN Harus Angka',
            'edit_nisn.digits_between' => 'NISN Harus Antara 8-10 Digit',
            'edit_nisn.unique' => 'NISN Sudah Digunakan',
            'edit_nis.required' => 'NIS Tidak Boleh Kosong',
            'edit_nis.numeric' => 'NIS Harus Angka',
            'edit_nis.digits_between' => 'NIS Harus Antara 4-10 Digit',
            'edit_nis.unique' => 'NIS Sudah Digunakan',
            'edit_nama.required' => 'Nama Tidak Boleh Kosong',
            'edit_username.required' => 'Username Tidak Boleh Kosong',
            'edit_username.unique' => 'Username Sudah Digunakan',
            'edit_tanggal_lahir.required' => 'Tanggal Lahir Tidak Boleh Kosong',
            'edit_tanggal_lahir.date' => 'Tanggal Lahir Tidak Valid',
            'edit_kelas_id.required' => 'Kelas Tidak Boleh Kosong',
            'edit_kelas_id.exists' => 'Kelas Tidak Valid',
            'edit_no_hp.required' => 'No HP Tidak Boleh Kosong',
            'edit_no_hp.digits_between' => 'No HP Harus Antara 10-15 Digit',
            'edit_email.required' => 'Email Tidak Boleh Kosong',
            'edit_email.email' => 'Email Tidak Valid',
            'edit_email.max' => 'Email Terlalu Panjang',
            'current_password.required_with' => 'Password lama harus diisi jika ingin mengganti password.',
            'current_password.current_password' => 'Password lama tidak sesuai.',
            'new_password.min' => 'Password baru minimal 5 karakter.',
            'new_password.different' => 'Password baru tidak boleh sama dengan password lama.',
            'new_password.same' => 'Konfirmasi password tidak cocok.',
            'password_confirmation.required_with' => 'Konfirmasi password harus diisi.',
            'password_confirmation.same' => 'Konfirmasi password tidak cocok.',
        ]);

        $siswa->update(array_filter([
            'nama' => $request->edit_nama,
            'kelas_id' => $request->edit_kelas_id,
            'nis' => $request->edit_nis,
            'nisn' => $request->edit_nisn,
            'tanggal_lahir' => $request->edit_tanggal_lahir,
            'no_hp' => $request->edit_no_hp,
            'email' => $request->edit_email,
            'jenis_kelamin' => $request->edit_jenis_kelamin,
            'alamat' => $request->edit_alamat,
        ]));

        if ($user) {
            $userData = [
                'username' => $request->edit_username,
            ];

            if ($request->filled('new_password')) {
                $userData['password'] = Hash::make($request->new_password);
            }

            $user->update($userData);
        }

        return redirect()->route('admin_siswa.index')->with('success', 'Data Siswa Berhasil Diedit');
    }

    public function destroy_siswa($id)
    {
        $siswa = Siswa::with('user')->findOrFail($id);

        if ($siswa->user) {
            $siswa->user->delete();
        }

        $siswa->delete();

        return redirect()->route('admin_siswa.index')->with('success', 'Data Siswa Berhasil Dihapus');
    }

    public function bulkAction_siswa(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data yang dipilih!'
            ]);
        }

        try {
            $deletedCount = 0;

            foreach ($ids as $id) {
                $siswa = Siswa::with('user')->find($id);

                if ($siswa) {
                    if ($siswa->user) {
                        $siswa->user->delete();
                    }

                    $siswa->delete();
                    $deletedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil menghapus {$deletedCount} data Siswa."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
            ]);
        }
    }

    public function download_template_siswa()
    {
        $fileName = 'template_import_siswa_' . date('Y-m-d') . '.xlsx';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getProperties()
            ->setCreator('SMKN 1 Subang')
            ->setTitle('Template Import Siswa')
            ->setDescription('Template untuk mengimpor data siswa');

        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '198754'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];

        $headers = [
            'NIS',
            'Nama Siswa',
            'Tgl. Lahir',
            'No. HP',
            'Email',
        ];

        $sheet->fromArray($headers, null, 'A1');

        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

        $sheet->getColumnDimension('A')->setWidth(15);  // nis
        $sheet->getColumnDimension('B')->setWidth(25);  // nama siswa
        $sheet->getColumnDimension('C')->setWidth(15);  // tgl lahir
        $sheet->getColumnDimension('D')->setWidth(20);  // no hp
        $sheet->getColumnDimension('E')->setWidth(30);  // email

        $exampleData = [
            ['123456', 'sigma', '01/01/2000', '08123456789', 'sigma@gmail.com'],
            ['123457', 'gyatrox', '01/01/2000', '08123456789', 'gyatrox@gmail.com'],
        ];

        $sheet->fromArray($exampleData, null, 'A2');

        $sheet->getStyle('A2:E3')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'f8f9fA'],
            ],
        ]);

        $sheet->setCellValue('A5', 'Petunjuk:');
        $sheet->setCellValue('A6', '1. Nis adalah Nomor Induk Siswa yang unik untuk setiap siswa');
        $sheet->setCellValue('A7', '2. Nama Siswa harus diisi dengan nama lengkap siswa');
        $sheet->setCellValue('A8', '3. Tanggal Lahir harus diisi dengan format dd/mm/yyyy (contoh: 22/09/2000)');
        $sheet->setCellValue('A9', '4. No. HP harus diisi dengan nomor handphone yang valid');
        $sheet->setCellValue('A10', '5. Email harus diisi dengan alamat email yang valid');
        $sheet->setCellValue('A11', '6. Username akan otomatis dibuat berdasarkan nama siswa');
        $sheet->setCellValue('A12', '7. Password akan otomatis dibuat berdasarkan tanggal lahir siswa (ddmmyyyy)');

        $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle('A6:A12')->getFont()->setItalic(true);

        $sheet->getRowDimension(1)->setRowHeight(25);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Last-Modified' => gmdate('D, d M Y H:i:s') . ' GMT',
            'Cache-Control' => 'cache, must-revalidate',
            'Pragma' => 'public',
        ]);
    }

    public function import_siswa(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
                'kelas_id' => 'required|exists:kelas,id',
            ], [
                'file.required' => 'File Tidak Boleh Kosong',
                'file.mimes' => 'File Harus Berformat XLSX, XLS, atau CSV',
                'file.max' => 'Ukuran File Maksimal 2MB',
                'kelas_id.required' => 'Kelas Tidak Boleh Kosong',
                'kelas_id.exists' => 'Kelas Tidak Valid',
            ]);

            $file = $request->file('file');
            $kelasId = $request->kelas_id;

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            $headers = array_shift($rows);

            $requiredHeaders = ['NIS', 'Nama Siswa', 'Tgl. Lahir', 'No. HP', 'Email'];
            $headerMap = [];

            foreach ($requiredHeaders as $required) {
                $found = false;
                foreach ($headers as $index => $header) {
                    if (trim(strtoupper($header)) === strtoupper($required)) {
                        $headerMap[$required] = $index;
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    return response()->json([
                        'success' => false,
                        'message' => "Kolom '{$required}' tidak ditemukan di file."
                    ]);
                }
            }

            $successCount = 0;
            $errorRows = [];
            $duplicateNIS = [];

            DB::beginTransaction();

            foreach ($rows as $rowIndex => $row) {
                $rowNumber = $rowIndex + 2;

                try {

                    if (empty(array_filter($row))) {
                        continue;
                    }

                    $nis = trim($row[$headerMap['NIS']] ?? '');
                    $nama = trim($row[$headerMap['Nama Siswa']] ?? '');
                    $tanggalLahir = trim($row[$headerMap['Tgl. Lahir']] ?? '');
                    $noHp = trim($row[$headerMap['No. HP']] ?? '');
                    $email = trim($row[$headerMap['Email']] ?? '');

                    if (!$nis || !$nama || !$tanggalLahir || !$noHp || !$email) {
                        $errorRows[] = "Baris {$rowNumber}: Semua kolom wajib diisi.";
                        continue;
                    }

                    if (Siswa::where('nis', $nis)->exists()) {
                        $duplicateNIS[] = "Baris {$rowNumber} NIS '{$nis}' sudah terdaftar.";
                        continue;
                    }

                    // Validasi NIS harus angka
                    if (!is_numeric($nis)) {
                        $errorRows[] = "Baris {$rowNumber}: NIS harus berupa angka";
                        continue;
                    }

                    // Validasi nomor HP
                    if (!preg_match('/^[0-9+\-\s]+$/', $noHp)) {
                        $errorRows[] = "Baris {$rowNumber}: Format nomor HP tidak valid";
                        continue;
                    }

                    $parsedDate = $this->parseExcelDate($tanggalLahir);
                    if (!$parsedDate) {
                        $errorRows[] = "Baris {$rowNumber}: Format tanggal lahir tidak valid (gunakan dd/mm/yyyy)";
                        continue;
                    }

                    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $errorRows[] = "Baris {$rowNumber}: Format email tidak valid";
                        continue;
                    }

                    $username = $this->generateUsernameFromName($nama);
                    $password = $parsedDate->format('dmY');

                    $user = User::create([
                        'username' => $username,
                        'role_id' => 4,
                        'password' => Hash::make($password),
                    ]);

                    Siswa::create([
                        'user_id' => $user->id,
                        'nis' => $nis,
                        'nama' => $nama,
                        'kelas_id' => $kelasId,
                        'tanggal_lahir' => $parsedDate,
                        'no_hp' => $noHp,
                        'email' => $email,
                    ]);

                    $successCount++;
                } catch (\Exception $e) {
                    $errorRows[] = "Baris {$rowNumber}: Terjadi kesalahan saat menyimpan data - " . $e->getMessage();
                }
            }

            if ($successCount > 0) {
                DB::commit();

                $message = "Berhasil mengimpor {$successCount} data siswa.";
                $errors = array_merge($errorRows, $duplicateNIS);

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'imported_count' => $successCount,
                    'errors' => !empty($errors) ? $errors : null,
                ]);
            } else {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data yang berhasil diimpor.',
                    'errors' => array_merge($errorRows,  $duplicateNIS),
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    }

    public function export_siswa_pdf()
    {
        try {
            $siswa = Siswa::with(['user.siswa', 'kelas.jurusan'])->orderBy('kelas_id', 'asc')->orderBy('nama', 'asc')->get();

            $data = [
                'title' => 'Data Siswa',
                'siswa' => $siswa,
                'exported_at' => now()->format('d-m-Y'),
                'exported_at_time' => now()->format('d-m-Y H:i:s'),
                'total_siswa' => $siswa->count(),
            ];

            $pdf = app('dompdf.wrapper');
            $pdf->loadView('admin.siswa.export.pdf', $data);

            $fileName = 'data_siswa_' . date('Y-m-d_H-i-s') . '.pdf';

            return $pdf->download($fileName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengekspor data ke pdf: ' . $e->getMessage());
        }
    }

    public function export_siswa_xlsx()
    {
        try {
            $filename = 'data_siswa_' . date('Y-m-d_H-i-s') . '.xlsx';

            return Excel::download(new SiswaExport, $filename);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengekspor data ke excel: ' . $e->getMessage());
        }
    }

    // guru
    public function index_guru(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10);
        $sortBy = $request->input('sort_by');
        $sortDirection = $request->input('sort_direction', 'asc');

        $query = Guru::with('user');

        if ($search) {
            $searchTerm = strtolower(trim($search));

            $query->whereHas('user.guru', function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(REPLACE(nama, " ", "")) LIKE ?', ['%' . $searchTerm . '%']);
            })->orWhere(function ($q) use ($searchTerm) {
                $q->orWhere('nip', 'like', "%{$searchTerm}%")
                    ->orWhere('no_hp', 'like', "%{$searchTerm}%");
            });
        }

        if ($sortBy) {
            $columnMap = [
                'NIP' => 'nip',
                'Nama Guru' => 'nama',
                'No. HP' => 'no_hp',
            ];

            if (isset($columnMap[$sortBy])) {
                $query->orderBy($columnMap[$sortBy], $sortDirection);
            } else {
                $query->orderBy('nama', 'asc');
            }
        }


        $guru = $query->paginate($perPage)->withQueryString();

        $data = [
            'title' => 'Data Guru',
            'guru' => $guru,
        ];

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.guru.partials.table', $data)->render(),
                'pagination' => view('admin.guru.partials.pagination', ['guru' => $guru])->render(),
            ]);
        }

        return view('admin.guru.index', $data);
    }

    public function create_guru()
    {
        $data = array(
            'title' => 'Tambah Guru',
            'menuPengguna' => 'active',
            // 'menu_admin_index_guru' => 'active',
        );
        return view('admin.guru.create', $data);
    }


    public function store_guru(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'create_nip' => 'required|string|unique:guru,nip',
            'create_nama' => 'required|string|max:255',
            'create_username' => 'required|unique:users,username',
            'create_tanggal_lahir' => 'required|date',
            'create_no_hp' => 'required|digits_between:10,15',
            'create_email' => 'required|email|max:255',
        ], [
            'create_nip.required' => 'NIP Tidak Boleh Kosong',
            'create_nip.unique' => 'NIP Sudah Digunakan',

            'create_nama.required' => 'Nama Tidak Boleh Kosong',

            'create_username.required' => 'Username Tidak Boleh Kosong',
            'create_username.unique' => 'Username Sudah Digunakan',

            'create_tanggal_lahir.required' => 'Tanggal Lahir Tidak Boleh Kosong',
            'create_tanggal_lahir.date' => 'Tanggal Lahir Tidak Valid',

            'create_no_hp.required' => 'No HP Tidak Boleh Kosong',
            'create_no_hp.digits_between' => 'No HP Harus Antara 10-15 Digit',

            'create_email.required' => 'Email Tidak Boleh Kosong',
            'create_email.email' => 'Email Tidak Valid',
        ]);

        try {

            $password = date('dmY', strtotime($request->create_tanggal_lahir));

            $user = User::create([
                'username' => $request->create_username,
                'role_id' => 3,
                'password' => Hash::make($password),
            ]);

            Guru::create([
                'user_id' => $user->id,
                'nip' => $request->create_nip,
                'nama' => $request->create_nama,
                'tanggal_lahir' => $request->create_tanggal_lahir,
                'no_hp' => $request->create_no_hp,
                'email' => $request->create_email,
            ]);

            return redirect()->route('admin_guru.index')->with('success', 'Guru berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withErrors(['general' => 'Terjadi kesalahan, coba lagi.'])->withInput();
        }
    }

    public function edit_guru($id)
    {
        $data = array(
            'title' => 'Edit Guru',
            'menuPengguna' => 'active',
            // 'menu_admin_index_guru' => 'active',
            'guru' => Guru::with('user')->findOrFail($id),
            'kelasList' => Kelas::all(),
        );
        return view('admin.guru.edit', $data);
    }


    public function update_guru(Request $request, $id)
    {
        $guru = Guru::with('user')->findOrFail($id);
        $user = $guru->user;

        $request->validate([
            'edit_nip' => 'required|string|unique:guru,nip,' . $id,
            'edit_nama' => 'required|string|max:255',
            'edit_username' => 'required|unique:users,username,' . ($user ? $user->id : 'NULL'),
            'edit_tanggal_lahir' => 'required|date',
            'edit_no_hp' => 'required|digits_between:10,15',
            'edit_email' => 'required|email|max:255',
            'edit_jenis_kelamin' => 'nullable|in:L,P',
            'edit_alamat' => 'nullable|string|max:255',
            // 'current_password' => 'nullable|required_with:new_password|current_password:web',
            'new_password' => 'nullable|min:5|same:password_confirmation',
            'password_confirmation' => 'nullable|required_with:new_password|same:new_password',
        ], [
            'edit_nip.required' => 'NIP Tidak Boleh Kosong',
            'edit_nip.unique' => 'NIP Sudah Digunakan',
            'edit_nama.required' => 'Nama Tidak Boleh Kosong',
            'edit_username.required' => 'Username Tidak Boleh Kosong',
            'edit_username.unique' => 'Username Sudah Digunakan',
            'edit_tanggal_lahir.required' => 'Tanggal Lahir Tidak Boleh Kosong',
            'edit_tanggal_lahir.date' => 'Tanggal Lahir Tidak Valid',
            'edit_no_hp.required' => 'No HP Tidak Boleh Kosong',
            'edit_no_hp.digits_between' => 'No HP Harus Antara 10-15 Digit',
            'edit_email.required' => 'Email Tidak Boleh Kosong',
            'edit_email.email' => 'Email Tidak Valid',
            'current_password.required_with' => 'Password lama harus diisi jika ingin mengganti password.',
            'current_password.current_password' => 'Password lama tidak sesuai.',
            'new_password.min' => 'Password baru minimal 5 karakter.',
            'new_password.different' => 'Password baru tidak boleh sama dengan password lama.',
            'new_password.same' => 'Konfirmasi password tidak cocok.',
            'password_confirmation.required_with' => 'Konfirmasi password harus diisi.',
            'password_confirmation.same' => 'Konfirmasi password tidak cocok.',
        ]);

        $guru->update(array_filter([
            'nama' => $request->edit_nama,
            'nip' => $request->edit_nip,
            'tanggal_lahir' => $request->edit_tanggal_lahir,
            'no_hp' => $request->edit_no_hp,
            'email' => $request->edit_email,
            'jenis_kelamin' => $request->edit_jenis_kelamin,
            'alamat' => $request->edit_alamat,
        ]));

        if ($user) {
            $userData = [
                'username' => $request->edit_username,
            ];

            if ($request->filled('new_password')) {
                $userData['password'] = Hash::make($request->new_password);
            }

            $user->update($userData);
        }

        return redirect()->route('admin_guru.index')->with('success', 'Data Guru Berhasil Diedit');
    }

    public function destroy_guru($id)
    {
        $guru = Guru::with('user')->findOrFail($id);

        if ($guru->user) {
            $guru->user->delete();
        }

        $guru->delete();

        return redirect()->route('admin_guru.index')->with('success', 'Data Guru Berhasil Dihapus');
    }

    public function bulkAction_guru(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data yang dipilih!'
            ]);
        }

        try {
            $deletedCount = 0;

            foreach ($ids as $id) {
                $guru = Guru::with('user')->find($id);

                if ($guru) {
                    if ($guru->user) {
                        $guru->user->delete();
                    }

                    $guru->delete();
                    $deletedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil menghapus {$deletedCount} data Guru."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
            ]);
        }
    }

    public function download_template_guru()
    {
        $fileName = 'template_import_guru_' . date('Y-m-d') . '.xlsx';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getProperties()
            ->setCreator('SMKN 1 Subang')
            ->setTitle('Template Import Guru')
            ->setDescription('Template untuk mengimpor data guru');

        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '198754'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];

        $headers = [
            'NIP',
            'Nama Guru',
            'Tgl. Lahir',
            'No. HP',
            'Email',
        ];

        $sheet->fromArray($headers, null, 'A1');

        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

        $sheet->getColumnDimension('A')->setWidth(15);  // nip
        $sheet->getColumnDimension('B')->setWidth(25);  // nama guru
        $sheet->getColumnDimension('C')->setWidth(15);  // tgl lahir
        $sheet->getColumnDimension('D')->setWidth(20);  // no hp
        $sheet->getColumnDimension('E')->setWidth(30);  // email

        $exampleData = [
            ['123456', 'sigma', '01/01/2000', '08123456789', 'sigma@gmail.com'],
            ['123457', 'gyatrox', '01/01/2000', '08123456789', 'gyatrox@gmail.com'],
        ];

        $sheet->fromArray($exampleData, null, 'A2');

        $sheet->getStyle('A2:E3')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'f8f9fA'],
            ],
        ]);

        $sheet->setCellValue('A5', 'Petunjuk:');
        $sheet->setCellValue('A6', '1. Nip adalah Nomor Induk Pegawai yang unik untuk setiap guru');
        $sheet->setCellValue('A7', '2. Nama Guru harus diisi dengan nama lengkap guru');
        $sheet->setCellValue('A8', '3. Tanggal Lahir harus diisi dengan format dd/mm/yyyy (contoh: 22/09/2000)');
        $sheet->setCellValue('A9', '4. No. HP harus diisi dengan nomor handphone yang valid');
        $sheet->setCellValue('A10', '5. Email harus diisi dengan alamat email yang valid');
        $sheet->setCellValue('A11', '6. Username akan otomatis dibuat berdasarkan nama guru');
        $sheet->setCellValue('A12', '7. Password akan otomatis dibuat berdasarkan tanggal lahir guru (ddmmyyyy)');

        $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle('A6:A12')->getFont()->setItalic(true);

        $sheet->getRowDimension(1)->setRowHeight(25);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Last-Modified' => gmdate('D, d M Y H:i:s') . ' GMT',
            'Cache-Control' => 'cache, must-revalidate',
            'Pragma' => 'public',
        ]);
    }

    public function import_guru(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
            ], [
                'file.required' => 'File Tidak Boleh Kosong',
                'file.mimes' => 'File Harus Berformat XLSX, XLS, atau CSV',
                'file.max' => 'Ukuran File Maksimal 2MB',
            ]);

            $file = $request->file('file');

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            $headers = array_shift($rows);

            $requiredHeaders = ['NIP', 'Nama Guru', 'Tgl. Lahir', 'No. HP', 'Email'];
            $headerMap = [];

            foreach ($requiredHeaders as $required) {
                $found = false;
                foreach ($headers as $index => $header) {
                    if (trim(strtoupper($header)) === strtoupper($required)) {
                        $headerMap[$required] = $index;
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    return response()->json([
                        'success' => false,
                        'message' => "Kolom '{$required}' tidak ditemukan di file."
                    ]);
                }
            }

            $successCount = 0;
            $errorRows = [];
            $duplicateNIP = [];

            DB::beginTransaction();

            foreach ($rows as $rowIndex => $row) {
                $rowNumber = $rowIndex + 2;

                try {

                    if (empty(array_filter($row))) {
                        continue;
                    }

                    $nip = trim($row[$headerMap['NIP']] ?? '');
                    $nama = trim($row[$headerMap['Nama Guru']] ?? '');
                    $tanggalLahir = trim($row[$headerMap['Tgl. Lahir']] ?? '');
                    $noHp = trim($row[$headerMap['No. HP']] ?? '');
                    $email = trim($row[$headerMap['Email']] ?? '');

                    if (!$nip || !$nama || !$tanggalLahir || !$noHp || !$email) {
                        $errorRows[] = "Baris {$rowNumber}: Semua kolom wajib diisi.";
                        continue;
                    }

                    if (Guru::where('nip', $nip)->exists()) {
                        $duplicateNIP[] = "Baris {$rowNumber} NIP '{$nip}' sudah terdaftar.";
                        continue;
                    }

                    // Validasi NIS harus angka
                    if (!is_numeric($nip)) {
                        $errorRows[] = "Baris {$rowNumber}: NIP harus berupa angka";
                        continue;
                    }

                    // Validasi nomor HP
                    if (!preg_match('/^[0-9+\-\s]+$/', $noHp)) {
                        $errorRows[] = "Baris {$rowNumber}: Format nomor HP tidak valid";
                        continue;
                    }

                    $parsedDate = $this->parseExcelDate($tanggalLahir);
                    if (!$parsedDate) {
                        $errorRows[] = "Baris {$rowNumber}: Format tanggal lahir tidak valid (gunakan dd/mm/yyyy)";
                        continue;
                    }

                    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $errorRows[] = "Baris {$rowNumber}: Format email tidak valid";
                        continue;
                    }

                    $username = $this->generateUsernameFromName($nama);
                    $password = $parsedDate->format('dmY');

                    $user = User::create([
                        'username' => $username,
                        'role_id' => 3,
                        'password' => Hash::make($password),
                    ]);

                    Guru::create([
                        'user_id' => $user->id,
                        'nip' => $nip,
                        'nama' => $nama,
                        'tanggal_lahir' => $parsedDate,
                        'no_hp' => $noHp,
                        'email' => $email,
                    ]);

                    $successCount++;
                } catch (\Exception $e) {
                    $errorRows[] = "Baris {$rowNumber}: Terjadi kesalahan saat menyimpan data - " . $e->getMessage();
                }
            }

            if ($successCount > 0) {
                DB::commit();

                $message = "Berhasil mengimpor {$successCount} data guru.";
                $errors = array_merge($errorRows, $duplicateNIP);

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'imported_count' => $successCount,
                    'errors' => !empty($errors) ? $errors : null,
                ]);
            } else {
                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data yang berhasil diimpor.',
                    'errors' => array_merge($errorRows,  $duplicateNIP),
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    }

    public function export_guru_pdf()
    {
        try {
            $guru = Guru::with(['user'])->orderBy('nama', 'asc')->get();

            $data = [
                'title' => 'Data Guru SMKN 1 Subang',
                'guru' => $guru,
                'exported_at' => now()->format('d-m-Y'),
                'exported_at_time' => now()->format('d-m-Y H:i:s'),
                'total_guru' => $guru->count(),
            ];

            $pdf = app('dompdf.wrapper');
            $pdf->loadView('admin.guru.export.pdf', $data);

            $filename = 'data_guru_' . date('Y-m-d_H-i-s') . '.pdf';

            return $pdf->download($filename);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengekspor data ke pdf: ' . $e->getMessage());
        }
    }

    public function export_guru_xlsx()
    {
        try {
            $filename = 'data_guru_' . date('Y-m-d_H-i-s') . '.xlsx';

            return Excel::download(new GuruExport, $filename);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengekspor data ke excel: ' . $e->getMessage());
        }
    }

    // umum jurusan
    public function index_jadwal_pelajaran()
    {
        $data = array(
            'title' => 'Jadwal Pelajaran',
            'menuAdmin' => 'active',
            // 'menu_admin_index_jurusan' => 'active',
            // 'jurusan' => Jurusan::orderby('nama_jurusan', 'asc')->get(),
        );
        return view('admin.jadwal_pelajaran.index', $data);
    }

    // umum jurusan
    public function index_jurusan(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10);
        $sortBy = $request->input('sort_by');
        $sortDirection = $request->input('sort_direction', 'asc');

        $query = Jurusan::query();

        if ($search) {
            $searchTerm = strtolower(trim($search));

            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(REPLACE(nama_jurusan, " ", "")) LIKE ?', ['%' . $searchTerm . '%'])
                    ->orWhereRaw('LOWER(REPLACE(kode_jurusan, " ", "")) LIKE ?', ['%' . $searchTerm . '%']);
            });
        }

        if ($sortBy) {
            switch ($sortBy) {
                case 'Nama Jurusan':
                    $query->orderBy('nama_jurusan', $sortDirection);
                    break;

                case 'Kode Jurusaan':
                    $query->orderBy('kode_jurusan', $sortDirection);
                    break;

                default:
                    $query->orderBy('nama_jurusan', 'asc')->orderBy('kode_jurusan', 'asc');
                    break;
            }
        }

        $jurusan = $query->paginate($perPage)->withQueryString();

        $data = [
            'title' => 'Daftar Jurusan',
            // 'menuAdmin' => 'active',
            'jurusan' => $jurusan,
        ];

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.jurusan.partials.table', $data)->render(),
                'pagination' => view('admin.jurusan.partials.pagination', ['jurusan' => $jurusan])->render(),
            ]);
        }

        return view('admin.jurusan.index', $data);
    }

    public function create_jurusan()
    {
        $data = array(
            'title' => 'Tambah Jurusan',
            'menuAdmin' => 'active',
            // 'menu_admin_index_jurusan' => 'active',
        );
        return view('admin.jurusan.create', $data);
    }

    public function store_jurusan(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nama_jurusan' => 'required|string|max:255|unique:jurusan,nama_jurusan',
            'kode_jurusan' => 'required|string|max:255|unique:jurusan,kode_jurusan',
        ], [
            'nama_jurusan.required' => 'Nama Jurusan Tidak Boleh Kosong',
            'nama_jurusan.unique' => 'Nama Jurusan Sudah Ada',

            'kode_jurusan.required' => 'Kode Jurusan Tidak Boleh Kosong',
            'kode_jurusan.unique' => 'Kode Jurusan Sudah Ada',
        ]);

        try {
            Jurusan::create([
                'nama_jurusan' => $request->nama_jurusan,
                'kode_jurusan' => $request->kode_jurusan,
            ]);

            return redirect()->route('admin_jurusan.index')->with('success', 'Jurusan Berhasil Ditambahkan');
        } catch (\Exception $e) {
            return back()->withErrors(['general' => 'Terjadi kesalahan, coba lagi.'])->withInput();
        }
    }

    public function edit_jurusan($id)
    {
        $data = array(
            'title' => 'Edit Jurusan',
            'menuAdmin' => 'active',
            // 'menu_admin_index_jurusan' => 'active',
            'jurusan' => Jurusan::findOrFail($id),
        );
        return view('admin.jurusan.edit', $data);
    }

    public function update_jurusan(Request $request, $id)
    {
        $jurusan = Jurusan::findorfail($id);

        $request->validate([
            'nama_jurusan' => 'required|string',
            'kode_jurusan' => 'required|string',
        ], [
            'nama_jurusan.required' => 'Nama Jurusan Tidak Boleh Kosong',
            'kode_jurusan.required' => 'Kode Jurusan Tidak Boleh Kosong',
        ]);

        $jurusan->update(array_filter([
            'nama_jurusan' => $request->nama_jurusan,
            'kode_jurusan' => $request->kode_jurusan,
        ]));

        return redirect()->route('admin_jurusan.index')->with('success', 'Jurusan Berhasil Diedit');

        // $user->save();
        // $request->validate([
        //     'username' => 'nullable|min:3',
        //     'password' => 'nullable|min:5',
        // ], [
        //     'username.min' => 'Minimal 3 Karakter',
        //     'password.min' => 'Minimal 5 Karakter',
        // ]);
    }

    public function destroy_jurusan($id)
    {
        $jurusan = Jurusan::findOrFail($id);

        $jurusan->delete();

        return redirect()->route('admin_jurusan.index')->with('success', 'Jurusan Berhasil Dihapus');
    }

    public function bulkAction_jurusan(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data yang dipilih!'
            ]);
        }

        try {
            $deletedCount = 0;

            foreach ($ids as $id) {
                $jurusan = Jurusan::find($id);

                if ($jurusan) {
                    $jurusan->delete();
                    $deletedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil menghapus {$deletedCount} data Jurusan."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
            ]);
        }
    }

    public function download_template_jurusan()
    {
        $fileName = 'template_import_jurusan_' . date('Y-m-d') . '.xlsx';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getProperties()
            ->setCreator('SMKN 1 Subang')
            ->setTitle('Template Import Jurusan')
            ->setDescription('Template untuk mengimpor data jurusan ke dalam sistem.');

        $headersStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => '198754'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '0000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];

        $headers = [
            'Nama Jurusan',
            'Kode Jurusan',
        ];

        $sheet->fromArray(($headers), null, 'A1');
        $sheet->getStyle('A1:B1')->applyFromArray($headersStyle);

        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(15);

        $exampleData = [
            ['Teknik Komputer Jaringan', 'TKJ'],
            ['Rekayasa Perangkat Lunak', 'RPL'],
        ];

        $sheet->fromArray($exampleData, null, 'A2');
        $sheet->getStyle('A2:B3')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '0000000'],
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'f8f9fa'],
            ],
        ]);

        $sheet->getStyle('B2:B3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A5', 'Petunjuk:');
        $sheet->setCellValue('A6', '2. Isi kolom "Nama Jurusan" dengan nama jurusan yang sesuai.');
        $sheet->setCellValue('A7', '3. Isi kolom "Kode Jurusan" dengan kode jurusan yang sesuai.');

        $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle('A6:A7')->getFont()->setItalic(true);

        $sheet->getRowDimension(1)->setRowHeight(25);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Last-Modified' => gmdate('D, d M Y H:i:s') . ' GMT',
            'Cache-Control' => 'cache, must-revalidate',
            'Pragma' => 'public',
        ]);
    }

    public function import_jurusan(Request $request)
    {
        try {
            // dd($request->all());
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
            ], [
                'file.required' => 'File Tidak Boleh Kosong',
                'file.file' => 'File Harus Berupa File',
                'file.mimes' => 'File Harus Berformat XLSX, XLS, atau CSV',
                'file.max' => 'Ukuran File Maksimal 2MB',
            ]);

            $file = $request->file('file');

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            $headers = array_shift($rows);

            $requiredHeaders = [
                'Nama Jurusan',
                'Kode Jurusan',
            ];

            $headerMap = [];

            foreach ($requiredHeaders as $required) {
                $found = false;
                foreach ($headers as $index => $header) {
                    if (trim(strtoupper($header)) === strtoupper(($required))) {
                        $headerMap[$required] = $index;
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    return response()->json([
                        'success' => false,
                        'message' => "Kolom {$required} tidak ditemukan dalam file."
                    ]);
                }
            }

            $successCount = 0;
            $errorRows = [];

            DB::beginTransaction();

            foreach ($rows as $rowIndex => $row) {
                $rowNumber = $rowIndex + 2;

                try {

                    if (empty(array_filter($row))) {
                        continue;
                    }

                    $namaJurusan = trim($row[$headerMap['Nama Jurusan']] ?? '');
                    $kodeJurusan = trim($row[$headerMap['Kode Jurusan']] ?? '');

                    if (!$namaJurusan || !$kodeJurusan) {
                        $missing = [];
                        if (!$namaJurusan) $missing[] = "Nama Jurusan";
                        if (!$kodeJurusan) $missing[] = "Kode Jurusan";
                        $errorRows[] = "Baris {$rowNumber}: Kolom " . implode(', ', $missing) . " tidak boleh kosong.";
                        continue;
                    }

                    $jurusan = Jurusan::where('nama_jurusan', $namaJurusan)
                        ->orWhere('kode_jurusan', $kodeJurusan)
                        ->first();

                    if ($jurusan && $jurusan->nama_jurusan !== $namaJurusan) {
                        $errorRows[] = "Baris {$rowNumber}: Jurusan dengan nama '{$namaJurusan}' sudah ada dengan kode '{$jurusan->kode_jurusan}'.";
                        continue;
                    }

                    if ($jurusan && $jurusan->kode_jurusan !== $kodeJurusan) {
                        $errorRows[] = "Baris {$rowNumber}: Kode jurusan '{$kodeJurusan}' sudah digunakan untuk jurusan '{$jurusan->nama_jurusan}'.";
                        continue;
                    }

                    $jurusan = Jurusan::create([
                        'nama_jurusan' => $namaJurusan,
                        'kode_jurusan' => $kodeJurusan,
                    ]);

                    $successCount++;
                } catch (\Exception $e) {
                    $errorRows[] = "Baris {$rowNumber}: Terjadi kesalahan saat memproses data - " . $e->getMessage();
                }
            }

            if ($successCount > 0) {
                DB::commit();

                $message = "Berhasil mengimpor {$successCount} data jurusan.";
                $errors = array_merge($errorRows);

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'errors' => !empty($errors) ? $errors : null,
                ]);
            } else {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data yang berhasil diimpor.',
                    'errors' => array_merge($errorRows),
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan:' . $e->getMessage(),
            ]);
        }
    }

    public function export_jurusan_pdf()
    {
        try {
            $jurusan = Jurusan::all();

            $data = [
                'title' => 'Data Jurusan',
                'jurusan' => $jurusan,
                'exported_at' => Carbon::now()->format('d-m-Y'),
                'exported_at_time' => Carbon::now()->format('d-m-Y H:i:s'),
                'total_jurusan' => $jurusan->count(),
            ];

            $pdf = app('dompdf.wrapper');
            $pdf->loadView('admin.jurusan.export.pdf', $data);

            $fileName = 'data_jurusan_' . date('Y-m-d_H-i-s') . '.pdf';

            return $pdf->download($fileName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengekspor data ke pdf: ' . $e->getMessage());
        }
    }

    public function export_jurusan_xlsx()
    {
        try {
            $fileName = 'data_jurusan_' . date('Y-m-d_H-i-s') . '.xlsx';

            return Excel::download(new JurusanExport(), $fileName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengekspor data ke Excel: ' . $e->getMessage());
        }
    }

    // umum mapel
    public function index_mapel(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10);
        $sortBy = $request->input('sort_by');
        $sortDirection = $request->input('sort_direction', 'asc');

        $query = MataPelajaran::query();

        if ($search) {
            $searchTerm = strtolower(trim($search));

            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(REPLACE(nama_mapel, " ", "")) LIKE ?', ['%' . $searchTerm . '%'])
                    ->orWhereRaw('LOWER(REPLACE(kode_mapel, " ", "")) LIKE ?', ['%' . $searchTerm . '%']);
            });
        }

        if ($sortBy) {
            $columnMap = [
                'Nama Mapel' => 'nama_mapel',
                'Kode Mapel' => 'kode_mapel',
            ];

            // dd($sortBy, $columnMap[$sortBy] ?? null);

            if (isset($columnMap[$sortBy])) {
                $query->orderBy($columnMap[$sortBy], $sortDirection);
            } else {
                $query->orderBy('nama_mapel', 'asc')->orderBy('kode_mapel', 'asc');
            }
        }

        $mapel = $query->paginate($perPage)->withQueryString();

        $data = [
            'title' => 'Daftar Mapel',
            'menuAdmin' => 'active',
            // 'menu_admin_index_mapel' => 'active',
            'mapel' => $mapel,
        ];

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.mapel.partials.table', $data)->render(),
                'pagination' => view('admin.mapel.partials.pagination', ['mapel' => $mapel])->render(),
            ]);
        }

        return view('admin.mapel.index', $data);
    }

    public function create_mapel()
    {
        $data = array(
            'title' => 'Tambah Mapel',
            'menuAdmin' => 'active',
            // 'menu_admin_index_mapel' => 'active',
        );
        return view('admin.mapel.create', $data);
    }

    public function store_mapel(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|string|unique:mata_pelajaran,nama_mapel',
            'kode_mapel' => 'required|string|unique:mata_pelajaran,kode_mapel',
        ], [
            'nama_mapel.required' => 'Nama Mapel Tidak Boleh Kosong',
            'nama_mapel.unique' => 'Nama Mapel Sudah Ada',

            'kode_mapel.required' => 'Kode Mapel Tidak Boleh Kosong',
            'kode_mapel.unique' => 'Kode Mapel Sudah Ada',
        ]);

        try {
            MataPelajaran::create([
                'nama_mapel' => $request->nama_mapel,
                'kode_mapel' => $request->kode_mapel,
            ]);

            return redirect()->route('admin_mapel.index')->with('success', 'Mata Pelajaran berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withErrors(['general' => 'Terjadi kesalahan, coba lagi.'])->withInput();
        }
    }

    public function edit_mapel($id)
    {
        $data = array(
            'title' => 'Daftar Mapel',
            'menuAdmin' => 'active',
            'mapel' => MataPelajaran::findorfail($id),
            // 'mapel' => $mapel,
            // 'menu_admin_index_mapel' => 'active',
        );
        return view('admin.mapel.edit', $data);
    }

    public function update_mapel(Request $request, $id)
    {
        $mapel = MataPelajaran::findorfail($id);

        $request->validate([
            'nama_mapel' => 'required|string',
            'kode_mapel' => 'required|string',
        ], [
            'nama_mapel.required' => 'Nama Mapel Tidak Boleh Kosong',
            'kode_mapel.required' => 'Kode Mapel Tidak Boleh Kosong',
        ]);

        $mapel->update(array_filter([
            'nama_mapel' => $request->nama_mapel,
            'kode_mapel' => $request->kode_mapel,
        ]));

        return redirect()->route('admin_mapel.index')->with('success', 'Mapel Berhasil Diedit');
    }

    public function destroy_mapel($id)
    {
        $mapel = MataPelajaran::findorfail($id);

        $mapel->delete();

        return redirect()->route('admin_mapel.index')->with('success', 'Mapel Berhasil Dihapus');
    }

    public function bulkAction_mapel(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data yang dipilih!'
            ]);
        }

        try {
            $deletedCount = 0;

            foreach ($ids as $id) {
                $mapel = MataPelajaran::find($id);

                if ($mapel) {
                    $mapel->delete();
                    $deletedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil menghapus {$deletedCount} data Mapel."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
            ]);
        }
    }

    public function download_template_mapel()
    {
        $fileName = 'template_import_mapel_' . date('Y-m-d') . '.xlsx';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getProperties()
            ->setCreator('SMKN 1 Subang')
            ->setTitle('Template Import Mapel')
            ->setDescription('Template untuk mengimpor data mapel ke dalam sistem.');

        $headersStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => '198754'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '0000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];

        $headers = [
            'Nama Mapel',
            'Kode Mapel',
        ];

        $sheet->fromArray(($headers), null, 'A1');
        $sheet->getStyle('A1:B1')->applyFromArray($headersStyle);

        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(15);

        $exampleData = [
            ['Bahasa Indonesia', 'B. Indonesia'],
            ['Projek Penguatan Profil Pelajar Pancasila', 'P5'],
        ];

        $sheet->fromArray($exampleData, null, 'A2');
        $sheet->getStyle('A2:B3')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '0000000'],
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'f8f9fa'],
            ],
        ]);

        $sheet->getStyle('B2:B3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A5', 'Petunjuk:');
        $sheet->setCellValue('A6', '2. Isi kolom "Nama Mapel" dengan nama jurusan yang sesuai.');
        $sheet->setCellValue('A7', '3. Isi kolom "Kode Mapel" dengan kode jurusan yang sesuai.');

        $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle('A6:A7')->getFont()->setItalic(true);

        $sheet->getRowDimension(1)->setRowHeight(25);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Last-Modified' => gmdate('D, d M Y H:i:s') . ' GMT',
            'Cache-Control' => 'cache, must-revalidate',
            'Pragma' => 'public',
        ]);
    }

    public function import_mapel(Request $request)
    {
        try {
            // dd($request->all());
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
            ], [
                'file.required' => 'File Tidak Boleh Kosong',
                'file.file' => 'File Harus Berupa File',
                'file.mimes' => 'File Harus Berformat XLSX, XLS, atau CSV',
                'file.max' => 'Ukuran File Maksimal 2MB',
            ]);

            $file = $request->file('file');

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            $headers = array_shift($rows);

            $requiredHeaders = [
                'Nama Mapel',
                'Kode Mapel',
            ];

            $headerMap = [];

            foreach ($requiredHeaders as $required) {
                $found = false;
                foreach ($headers as $index => $header) {
                    if (trim(strtoupper($header)) === strtoupper(($required))) {
                        $headerMap[$required] = $index;
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    return response()->json([
                        'success' => false,
                        'message' => "Kolom {$required} tidak ditemukan dalam file."
                    ]);
                }
            }

            $successCount = 0;
            $errorRows = [];

            DB::beginTransaction();

            foreach ($rows as $rowIndex => $row) {
                $rowNumber = $rowIndex + 2;

                try {

                    if (empty(array_filter($row))) {
                        continue;
                    }

                    $namaMapel = trim($row[$headerMap['Nama Mapel']] ?? '');
                    $kodeMapel = trim($row[$headerMap['Kode Mapel']] ?? '');

                    if (!$namaMapel || !$kodeMapel) {
                        $missing = [];
                        if (!$namaMapel) $missing[] = "Nama Mapel";
                        if (!$kodeMapel) $missing[] = "Kode Mapel";
                        $errorRows[] = "Baris {$rowNumber}: Kolom " . implode(', ', $missing) . " tidak boleh kosong.";
                        continue;
                    }

                    $mapel = MataPelajaran::where('nama_mapel', $namaMapel)
                        ->orWhere('kode_mapel', $kodeMapel)
                        ->first();

                    if ($mapel && $mapel->nama_mapel !== $namaMapel) {
                        $errorRows[] = "Baris {$rowNumber}: Mapel dengan nama '{$namaMapel}' sudah ada dengan kode '{$mapel->kode_mapel}'.";
                        continue;
                    }

                    if ($mapel && $mapel->kode_mapel !== $kodeMapel) {
                        $errorRows[] = "Baris {$rowNumber}: Kode mapel '{$kodeMapel}' sudah digunakan untuk mapel '{$mapel->nama_mapel}'.";
                        continue;
                    }

                    $mapel = MataPelajaran::create([
                        'nama_mapel' => $namaMapel,
                        'kode_mapel' => $kodeMapel,
                    ]);

                    $successCount++;
                } catch (\Exception $e) {
                    $errorRows[] = "Baris {$rowNumber}: Terjadi kesalahan saat memproses data - " . $e->getMessage();
                }
            }

            if ($successCount > 0) {
                DB::commit();

                $message = "Berhasil mengimpor {$successCount} data mapel.";
                $errors = array_merge($errorRows);

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'errors' => !empty($errors) ? $errors : null,
                ]);
            } else {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data yang berhasil diimpor.',
                    'errors' => array_merge($errorRows),
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan:' . $e->getMessage(),
            ]);
        }
    }

    public function export_mapel_pdf()
    {
        try {
            $mapel = MataPelajaran::all();

            $data = [
                'title' => 'Data Mapel',
                'mapel' => $mapel,
                'exported_at' => Carbon::now()->format('d-m-Y'),
                'exported_at_time' => Carbon::now()->format('d-m-Y H:i:s'),
                'total_mapel' => $mapel->count(),
            ];

            $pdf = app('dompdf.wrapper');
            $pdf->loadView('admin.mapel.export.pdf', $data);

            $fileName = 'data_mapel_' . date('Y-m-d_H-i-s') . '.pdf';

            return $pdf->download($fileName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengekspor data ke pdf: ' . $e->getMessage());
        }
    }

    public function export_mapel_xlsx()
    {
        try {
            $fileName = 'data_mapel_' . date('Y-m-d_H-i-s') . '.xlsx';

            return Excel::download(new MataPelajaranExport(), $fileName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengekspor data ke Excel: ' . $e->getMessage());
        }
    }

    // presensi siswa
    public function index_presensi_siswa(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $sortBy = $request->input('sort_by');
        $filterBy = $request->input('filter_by');
        $sortDirection = $request->input('sort_direction', 'asc');

        $query = presensiSiswa::with('siswa.kelas.jurusan');

        // Filter
        // if ($request->filled('kelas')) {
        //     $query->whereHas('siswa', function ($q) use ($request) {
        //         $q->where('kelas_id', $request->input('kelas'));
        //     });
        // }

        if ($filterBy === 'kelas' && $request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->input('tanggal'));
        }

        // if ($filterBy === 'tanggal' && $request->filled('range_tanggal')) {
        //     [$start, $end] = explode(' - ', $request->range_tanggal);
        //     $query->whereBetween('tanggal', [$start, $end]);
        // }

        if ($request->filled('range_tanggal')) {
            [$start, $end] = explode(' - ', $request->range_tanggal);
            $start = trim($start);
            $end = trim($end);
            $query->whereBetween('tanggal', [$start, $end]);
        }

        // Sorting manual tanpa join dulu
        $columnMap = [
            'Tanggal' => 'tanggal',
            'Kelas' => 'kelas',
            'Total Siswa' => 'total_siswa',
            'Hadir' => 'hadir',
            'Izin' => 'izin',
            'Sakit' => 'sakit',
            'Alpa' => 'alpa',
        ];

        if (isset($columnMap[$sortBy])) {
            $query->orderBy($columnMap[$sortBy], $sortDirection);
        } else {
            $query->orderBy('tanggal', 'asc');
        }

        $presensi_raw = $query->paginate($perPage)->withQueryString();

        $lihatBerdasarkan = $request->input('lihatBerdasarkan', 'kelas');

        if ($lihatBerdasarkan === 'tanggal') {
            $presensi_grouped = $presensi_raw->getCollection()->groupBy('tanggal')->map(function ($group, $tanggal) {
                // $kelasIds = $group->pluck('siswa.kelas.id')->unique();
                $siswaIds = Siswa::pluck('id');

                $totalSiswa = count($siswaIds);

                $izinCount = izinSiswa::whereDate('tanggal', $tanggal)
                    ->whereIn('siswa_id', $siswaIds)
                    ->where('jenis_izin', '!=', 'Sakit')
                    ->count();

                $sakitCount = izinSiswa::whereDate('tanggal', $tanggal)
                    ->whereIn('siswa_id', $siswaIds)
                    ->where('jenis_izin', 'Sakit')
                    ->count();

                $alpaCount = presensiSiswa::whereDate('tanggal', $tanggal)
                    ->whereIn('siswa_id', $siswaIds)
                    ->where('status_kehadiran', 'alpa')
                    ->count();

                $hadirCount = $group->where('status_kehadiran', 'hadir')->count();

                return [
                    'tanggal' => $tanggal,
                    'total_siswa' => $totalSiswa,
                    'hadir' => $hadirCount,
                    'izin' => $izinCount,
                    'sakit' => $sakitCount,
                    'alpa' => $alpaCount,
                ];
            })->values();
        } else {
            $presensi_grouped = $presensi_raw->getCollection()->groupBy(function ($item) {
                $kelas = optional($item->siswa->kelas);
                $jurusan = optional($kelas->jurusan);
                return $kelas ? $kelas->tingkat . ' ' . $jurusan->kode_jurusan . ' ' . $kelas->no_kelas : '-';
            })->map(function ($group, $kelasNama) {
                $kelasId = optional(optional($group->first()->siswa)->kelas)->id;
                $tanggal = optional($group->first())->tanggal;

                $totalSiswa = Siswa::where('kelas_id', $kelasId)->count();

                $siswaIds = $group->pluck('siswa_id')->unique();

                $izinCount = izinSiswa::whereIn('siswa_id', $siswaIds)
                    ->where('jenis_izin', '!=', 'Sakit')
                    ->count();

                $sakitCount = izinSiswa::whereIn('siswa_id', $siswaIds)
                    ->where('jenis_izin', 'Sakit')
                    ->count();

                $alpaCount = presensiSiswa::whereDate('tanggal', $tanggal)
                    ->whereIn('siswa_id', $siswaIds)
                    ->where('status_kehadiran', 'alpa')
                    ->count();

                $hadirCount = $group->where('status_kehadiran', 'hadir')->count();

                return [
                    'kelas' => $kelasNama,
                    'total_siswa' => $totalSiswa,
                    'hadir' => $hadirCount,
                    'izin' => $izinCount,
                    'sakit' => $sakitCount,
                    'alpa' => $alpaCount,
                    'ids' => $group->pluck('id')->toArray(),
                ];
            })->values();
        }

        $presensi_siswa = new LengthAwarePaginator(
            $presensi_grouped,
            $presensi_raw->total(),
            $presensi_raw->perPage(),
            $presensi_raw->currentPage(),
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $kelasFilter = Kelas::with('jurusan')->orderBy('tingkat')->orderBy('jurusan_id')->orderBy('no_kelas')->get();
        $tanggalFilter = presensiSiswa::select('tanggal')->distinct()->orderBy('tanggal', 'desc')->pluck('tanggal');

        $data = [
            'title' => 'Presensi Siswa',
            'presensi_siswa' => $presensi_siswa,
            'kelasFilter' => $kelasFilter,
            'tanggalFilter' => $tanggalFilter,
        ];

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.presensi.siswa.partials.table', $data)->render(),
                'pagination' => view('admin.presensi.siswa.partials.pagination', ['presensi_siswa' => $presensi_siswa])->render(),
            ]);
        }

        return view('admin.presensi.siswa.index', $data);
    }

    // presensi guru
    public function index_presensi_guru(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $sortBy = $request->input('sort_by');
        $sortDirection = $request->input('sort_direction', 'asc');

        $query = presensiGuru::with('guru');

        // Filter
        if ($request->filled('tanggal')) {
            $query->where('tanggal', $request->input('tanggal'));
        }

        if ($request->filled('range_tanggal')) {
            [$start, $end] = explode(' - ', $request->range_tanggal);
            $start = trim($start);
            $end = trim($end);
            $query->whereBetween('tanggal', [$start, $end]);
        }

        // Sorting manual tanpa join dulu
        $columnMap = [
            'tanggal' => 'tanggal',
            'Hadir' => 'hadir',
            'Izin' => 'izin',
            'Sakit' => 'sakit',
        ];

        if (isset($columnMap[$sortBy])) {
            $query->orderBy($columnMap[$sortBy], $sortDirection);
        } else {
            $query->orderBy('tanggal', 'asc');
        }

        $presensi_raw = $query->paginate($perPage)->withQueryString();

        // Map data
        $presensi_guru = $presensi_raw->getCollection()->groupBy('tanggal')->map(function ($group, $tanggal) {

            $guruIds = Guru::pluck('id');

            $totalGuru = count($guruIds);

            $izinCount = izinGuru::whereDate('tanggal', $tanggal)
                ->whereIn('guru_id', $guruIds)
                ->where('jenis_izin', '!=', 'Sakit')
                ->count();

            $sakitCount = izinGuru::whereDate('tanggal', $tanggal)
                ->whereIn('guru_id', $guruIds)
                ->where('jenis_izin', 'Sakit')
                ->count();

            $alpaCount = presensiGuru::whereDate('tanggal', $tanggal)
                ->whereIn('guru_id', $guruIds)
                ->where('status_kehadiran', 'alpa')
                ->count();

            $hadirCount = $group->where('status_kehadiran', 'hadir')->count();

            return [
                'tanggal' => $tanggal,
                'total_guru' => $totalGuru,
                'hadir' => $hadirCount,
                'izin' => $izinCount,
                'sakit' => $sakitCount,
                'alpa' => $alpaCount,
            ];
        })->values();
        if ($sortBy) {
            $presensi_guru = $presensi_guru->sortBy($sortBy, SORT_REGULAR, $sortDirection === 'desc')->values();
        }


        $presensi_guru = new \Illuminate\Pagination\LengthAwarePaginator(
            $presensi_guru,
            $presensi_raw->total(),
            $presensi_raw->perPage(),
            $presensi_raw->currentPage(),
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $tanggalFilter = presensiGuru::select('tanggal')->distinct()->orderBy('tanggal', 'desc')->pluck('tanggal');

        $data = [
            'title' => 'Presensi Siswa',
            'presensi_guru' => $presensi_guru,
            'tanggalFilter' => $tanggalFilter,
        ];

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.presensi.guru.partials.table', $data)->render(),
                'pagination' => view('admin.presensi.guru.partials.pagination', ['presensi_guru' => $presensi_guru])->render(),
            ]);
        }

        return view('admin.presensi.guru.index', $data);
    }

    // presensi per mapel
    public function index_presensi_per_mapel(Request $request)
    {
        $data = [
            'title' => 'Presensi Per-Mapel',

        ];
        return view('admin.presensi.per_mapel.index', $data);
    }


    // izin siswa
    public function index_izin_siswa(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10);
        $sortBy = $request->input('sort_by');
        $sortDirection = $request->input('sort_direction', 'asc');

        $query = izinSiswa::with('siswa.kelas.jurusan');

        // Filter
        if ($request->filled('kelas')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('kelas_id', $request->input('kelas'));
            });
        }

        if ($request->filled('tanggal')) {
            $query->where('tanggal', $request->input('tanggal'));
        }

        if ($request->filled('range_tanggal')) {
            [$start, $end] = explode(' - ', $request->range_tanggal);
            $start = trim($start);
            $end = trim($end);
            $query->whereBetween('tanggal', [$start, $end]);
        }

        if ($search) {
            $searchTerm = strtolower(trim($search));

            $query->where(function ($q) use ($searchTerm) {
                // Search by siswa.nama
                $q->whereHas('siswa', function ($q2) use ($searchTerm) {
                    $q2->whereRaw('LOWER(REPLACE(nama, " ", "")) LIKE ?', ['%' . $searchTerm . '%'])
                        ->orWhereHas('kelas', function ($q3) use ($searchTerm) {
                            $q3->whereRaw('LOWER(tingkat) LIKE ?', ["%{$searchTerm}%"])
                                ->orWhereRaw('LOWER(no_kelas) LIKE ?', ["%{$searchTerm}%"])
                                ->orWhereHas('jurusan', function ($q4) use ($searchTerm) {
                                    $q4->whereRaw('LOWER(kode_jurusan) LIKE ?', ["%{$searchTerm}%"]);
                                });
                        });
                });
            });
        }

        if ($sortBy === 'kelas') {
            $query->join('siswa', 'izin_siswa.siswa_id', '=', 'siswa.id')
                ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id')
                ->join('jurusan', 'kelas.jurusan_id', '=', 'jurusan.id')
                ->orderBy('kelas.tingkat', $sortDirection)
                ->orderBy('jurusan.kode_jurusan', $sortDirection)
                ->orderBy('kelas.no_kelas', $sortDirection)
                ->select('izin_siswa.*');
        } elseif ($sortBy === 'siswa') {
            $query->join('siswa', 'izin_siswa.siswa_id', '=', 'siswa.id')
                ->orderBy('siswa.nama', $sortDirection)
                ->select('izin_siswa.*');
        } elseif ($sortBy === 'tanggal') {
            $query->orderBy('tanggal', $sortDirection);
        } else {
            $query->orderBy('tanggal', 'asc');
        }

        // Sorting manual tanpa join dulu
        $columnMap = [
            'tanggal' => 'tanggal',
            'siswa' => 'siswa_id',
            'kelas_id' => 'kelas_id',
            'jenis_izin' => 'jenis_izin',
            'keterangan' => 'keterangan',
            'lampiran' => 'lampiran',
        ];

        // if (isset($columnMap[$sortBy])) {
        //     $query->orderBy($columnMap[$sortBy], $sortDirection);
        // } else {
        //     $query->orderBy('tanggal', 'asc');
        // }

        $izin_siswa = $query->paginate($perPage)->withQueryString();

        $kelasFilter = Kelas::with('jurusan')->orderBy('tingkat')->orderBy('jurusan_id')->orderBy('no_kelas')->get();
        $tanggalFilter = izinSiswa::select('tanggal')->distinct()->orderBy('tanggal', 'desc')->pluck('tanggal');

        $data = [
            'title' => 'Izin Siswa',
            'izin_siswa' => $izin_siswa,
            'kelasFilter' => $kelasFilter,
            'tanggalFilter' => $tanggalFilter,
        ];

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.izin.siswa.partials.table', $data)->render(),
                'pagination' => view('admin.izin.siswa.partials.pagination', ['izin_siswa' => $izin_siswa])->render(),
            ]);
        }
        return view('admin.izin.siswa.index', $data);
    }

    public function download_lampiran_siswa($filename)
    {
        $path = storage_path('app/public/lampiran_izin/' . $filename); // Atau storage_path kalau file di storage

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        return Response($file, 200)->header("Content-Type", $type);
    }

    // public function export_izin_siswa_pdf(Request $request)
    // {
    //     try {
    //         $query = izinSiswa::with(['siswa.kelas.jurusan']);

    //         if ($request->filled('range_tanggal')) {
    //             [$start, $end] = explode(' - ', $request->range_tanggal);
    //             $query->whereBetween('tanggal', [trim($start), trim($end)]);
    //         }

    //         if ($request->filled('search')) {
    //             $searchTerm = strtolower(trim($request->search));
    //             $query->where(function ($q) use ($searchTerm) {
    //             // Search by siswa.nama
    //             $q->whereHas('siswa', function ($q2) use ($searchTerm) {
    //                 $q2->whereRaw('LOWER(REPLACE(nama, " ", "")) LIKE ?', ['%' . $searchTerm . '%'])
    //                     ->orWhereHas('kelas', function ($q3) use ($searchTerm) {
    //                         $q3->whereRaw('LOWER(tingkat) LIKE ?', ["%{$searchTerm}%"])
    //                             ->orWhereRaw('LOWER(no_kelas) LIKE ?', ["%{$searchTerm}%"])
    //                             ->orWhereHas('jurusan', function ($q4) use ($searchTerm) {
    //                                 $q4->whereRaw('LOWER(kode_jurusan) LIKE ?', ["%{$searchTerm}%"]);
    //                             });
    //                     });
    //             });
    //         });
    //         }

    //         $izin = $query->orderBy('tanggal', 'asc')->get();

    //         $data = [
    //             'title' => 'Data Izin Siswa',
    //             'izin_siswa' => $izin,
    //             'exported_at' => now()->format('d-m-Y'),
    //             'exported_at_time' => now()->format('d-m-Y H:i:s'),
    //             'total_izin' => $query->count(),
    //         ];

    //         $pdf = app('domdf.wrapper');
    //         $pdf->loadview('admin.izin.siswa.export.pdf', $data);

    //         $fileName = 'izin_siswa_' . date('Y-m-d_H-i-s') . '.pdf';
    //         return $pdf->download($fileName);
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Gagal export PDF :' . $e->getMessage());
    //     }
    // }

    // public function export_izin_siswa_xlsx(Request $request)
    // {
    //     try {
    //         $filename = 'izin_siswa_' . date('Y-m-d_H-i-s') . '.xlsx';

    //         return Excel::download(new izinSiswaExport($request->only(['kelas', 'range_tanggal', 'search'])), $filename);
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Gagal mengekspor data ke excel: ' . $e->getMessage());
    //     }
    // }

    public function export_izin_siswa_pdf(Request $request)
    {
        $filters = $request->only(['range_tanggal', 'search']);

        $izinSiswa = izinSiswa::select('izin_siswa.*')
            ->join('siswa', 'izin_siswa.siswa_id', '=', 'siswa.id')
            ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id')
            ->join('jurusan', 'kelas.jurusan_id', '=', 'jurusan.id')
            ->with(['siswa.kelas.jurusan'])
            ->when(!empty($filters['range_tanggal']), function ($query) use ($filters) {
                [$start, $end] = explode(' - ', $filters['range_tanggal']);
                $query->whereBetween('izin_siswa.tanggal', [trim($start), trim($end)]);
            })
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $searchTerm = strtolower(trim($filters['search']));
                $query->where(function ($q) use ($searchTerm) {
                    $q->whereRaw('LOWER(REPLACE(siswa.nama, " ", "")) LIKE ?', ["%{$searchTerm}%"])
                        ->orWhereRaw('LOWER(kelas.tingkat) LIKE ?', ["%{$searchTerm}%"])
                        ->orWhereRaw('LOWER(kelas.no_kelas) LIKE ?', ["%{$searchTerm}%"])
                        ->orWhereRaw('LOWER(jurusan.kode_jurusan) LIKE ?', ["%{$searchTerm}%"]);
                });
            })
            ->orderBy('izin_siswa.tanggal', 'asc')
            ->orderByRaw('LOWER(siswa.nama) ASC')
            ->orderBy('kelas.tingkat', 'asc')
            ->orderBy('jurusan.kode_jurusan', 'asc')
            ->orderBy('kelas.no_kelas', 'asc')
            ->get();

        $data = [
            'title' => 'Export Izin Siswa',
            'izin_siswa' => $izinSiswa,
            'total_izin' => $izinSiswa->count(),
            'exported_at' => now()->format('d-m-Y'),
            'exported_at_time' => now()->format('d-m-Y H:i:s'),
        ];

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.izin.siswa.export.pdf', $data);

        return $pdf->download('izin_siswa_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }

    public function export_izin_siswa_xlsx(Request $request)
    {
        $filters = $request->only(['kelas', 'range_tanggal', 'search']);

        return Excel::download(new izinSiswaExport($filters), 'izin_siswa_' . now()->format('Y-m-d_H-i-s') . '.xlsx');
    }

    // izin guru
    public function index_izin_guru(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10);
        $sortBy = $request->input('sort_by');
        $sortDirection = $request->input('sort_direction', 'asc');

        $query = izinGuru::with('guru');

        if ($request->filled('tanggal')) {
            $query->where('tanggal', $request->input('tanggal'));
        }

        if ($request->filled('range_tanggal')) {
            [$start, $end] = explode(' - ', $request->range_tanggal);
            $start = trim($start);
            $end = trim($end);
            $query->whereBetween('tanggal', [$start, $end]);
        }

        if ($search) {
            $searchTerm = strtolower(trim($search));

            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('guru', function ($q2) use ($searchTerm) {
                    $q2->whereRaw('LOWER(REPLACE(nama, " ", "")) LIKE ?', ['%' . $searchTerm . '%']);
                });
            });
        }

        // Sorting manual tanpa join dulu
        $columnMap = [
            'tanggal' => 'tanggal',
            // 'guru' => 'guru_id',
            'jenis_izin' => 'jenis_izin',
            'keterangan' => 'keterangan',
            'lampiran' => 'lampiran',
        ];

        if ($sortBy === 'guru') {
            $query->join('guru', 'izin_guru.guru_id', '=', 'guru.id')
                ->orderBy('guru.nama', $sortDirection)
                ->select('izin_guru.*'); // penting biar pagination gak rusak
        } elseif (isset($columnMap[$sortBy])) {
            $query->orderBy($columnMap[$sortBy], $sortDirection);
        } else {
            $query->orderBy('tanggal', 'asc');
        }


        $izin_guru = $query->paginate($perPage)->withQueryString();

        $tanggalFilter = izinGuru::select('tanggal')->distinct()->orderBy('tanggal', 'desc')->pluck('tanggal');

        $data = [
            'title' => 'Izin Guru',
            'izin_guru' => $izin_guru,
            'tanggalFilter' => $tanggalFilter,
        ];

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.izin.guru.partials.table', $data)->render(),
                'pagination' => view('admin.izin.guru.partials.pagination', ['izin_guru' => $izin_guru])->render(),
            ]);
        }
        return view('admin.izin.guru.index', $data);
    }

    public function download_lampiran_guru($filename)
    {
        $path = storage_path('app/public/lampiran_izin/' . $filename); // Atau storage_path kalau file di storage

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        return Response($file, 200)->header("Content-Type", $type);
    }

    public function export_izin_guru_pdf(Request $request)
    {
        $filters = $request->only(['range_tanggal', 'search']);
        $izinGuru = izinGuru::select('izin_guru.*')
            ->join('guru', 'izin_guru.guru_id', '=', 'guru.id')
            ->when(!empty($filters['range_tanggal']), function ($query) use ($filters) {
                [$start, $end] = explode(' - ', $filters['range_tanggal']);
                $query->whereBetween('izin_guru.tanggal', [trim($start), trim($end)]);
            })
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $searchTerm = strtolower(trim($filters['search']));
                $query->where(function ($q) use ($searchTerm) {
                    $q->whereRaw('LOWER(REPLACE(guru.nama, " ", "")) LIKE ?', ["%{$searchTerm}%"]);
                });
            })
            ->orderBy('izin_guru.tanggal', 'asc')
            ->orderByRaw('LOWER(guru.nama) ASC')
            ->get();

        $data = [
            'title' => 'Export Izin Guru',
            'izin_guru' => $izinGuru,
            'total_izin' => $izinGuru->count(),
            'exported_at' => now()->format('d-m-Y'),
            'exported_at_time' => now()->format('d-m-Y H:i:s'),
        ];

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.izin.guru.export.pdf', $data);

        return $pdf->download('izin_guru_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }

    public function export_izin_guru_xlsx(Request $request)
    {
        $filters = $request->only(['range_tanggal', 'search']);

        return Excel::download(new izinGuruExport($filters), 'izin_guru_' . now()->format('Y-m-d_H-i-s') . '.xlsx');
    }
}
// public function index_semester()
// {
//     $data = array(
//         'title' => 'Daftar Semester',
//         'menu_admin_index_guru' => 'active',
//         'guru' => Guru::get(),
//     );
//     return view('admin.guru.index', $data);
// }

// public function index_tahunAjaran()
// {
//     $data = array(
//         'title' => 'Daftar Guru',
//         'menu_admin_index_guru' => 'active',
//         'guru' => Guru::with('user', 'mapel_kelas.mapel')->get(),
//     );
//     return view('admin.guru.index', $data);
// }
