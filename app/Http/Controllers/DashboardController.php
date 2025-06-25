<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSiswa = Siswa::count();
        $totalPerempuan = Siswa::where('jenis_kelamin', 'Perempuan')->count();
        $totalLaki = Siswa::where('jenis_kelamin', 'Laki-laki')->count();

        $persenPerempuan = $totalSiswa > 0 ? ($totalPerempuan / $totalSiswa) * 100 : 0;
        $persenLaki = $totalSiswa > 0 ? ($totalLaki / $totalSiswa) * 100 : 0;

        // Ambil role user buat filter pengumuman
        $role = Auth::user()->role;
        $today = now();

        if (Auth::user()->role_id === 1) {
            $pengumumen = \App\Models\Pengumuman::where('kadaluarsa_sampai', '>=', $today)->orderByDesc('tanggal')->limit(3)->get();
        } else {
            $pengumumen = \App\Models\Pengumuman::where(function ($q) use ($role) {
                $q->where('ditujukan_untuk', $role)->orWhere('ditujukan_untuk', 'semua');
            })
                ->where('kadaluarsa_sampai', '>=', $today)
                ->orderByDesc('tanggal')
                ->limit(3)
                ->get();
        }

        $data = [
            'title' => 'Beranda',
            'menuDashboard' => 'active',
            'totalSiswa' => $totalSiswa,
            'totalPerempuan' => $totalPerempuan,
            'totalLaki' => $totalLaki,
            'persenPerempuan' => $persenPerempuan,
            'persenLaki' => $persenLaki,
            'pengumumen' => $pengumumen,
        ];

        return view('admin.beranda', $data);
    }

    public function index_data_admin(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10);
        $sortBy = $request->input('sort_by');
        $sortDirection = $request->input('sort_direction', 'asc');

        $query = User::where('role_id', 1);

        if ($search) {
            $searchTerm = strtolower(trim($search));
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(REPLACE(name_admin, " ", "")) LIKE ?', ['%' . $searchTerm . '%'])
                    ->orWhere('username', 'like', "%{$searchTerm}%");
            });
        }

        if ($sortBy) {
            $columnMap = [
                'Username' => 'username',
                'Nama Admin' => 'name_admin',
            ];

            if (isset($columnMap[$sortBy])) {
                $query->orderBy($columnMap[$sortBy], $sortDirection);
            } else {
                $query->orderBy('name_admin', 'asc');
            }
        }

        $admin = $query->paginate($perPage)->withQueryString();

        $data = [
            'title' => 'Data Admin',
            'admin' => $admin,
        ];

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.admin.partials.table', $data)->render(),
                'pagination' => view('admin.admin.partials.pagination', ['admin' => $admin])->render(),
            ]);
        }

        return view('admin.admin.index', $data);
    }

    public function store_data_admin(Request $request)
    {
        $request->validate([
            'create_name_admin' => 'required|string|max:100',
            'create_username' => 'required|string|unique:users,username',
            'create_password' => 'required|string|min:5',
        ], [
            'create_name_admin.required' => 'Nama admin wajib diisi.',
            'create_name_admin.string' => 'Nama admin harus berupa teks.',
            'create_name_admin.max' => 'Nama admin maksimal 100 karakter.',
            'create_username.required' => 'Username wajib diisi.',
            'create_username.string' => 'Username harus berupa teks.',
            'create_username.unique' => 'Username sudah digunakan.',
            'create_password.required' => 'Password wajib diisi.',
            'create_password.string' => 'Password harus berupa teks.',
            'create_password.min' => 'Password minimal 5 karakter.',
        ]);

        if ($request->filled('new_password')) {
            $userData['password'] = Hash::make($request->new_password);
        }

        User::create([
            'name_admin' => $request->create_name_admin,
            'username' => $request->create_username,
            'password' => Hash::make($request->create_password),
            'role_id' => 1,
        ]);

        return redirect()->back()->with('success', 'Admin berhasil ditambahkan!');
    }

    public function destroy_admin($id)
    {
        if (auth()->id() == $id) {
            return redirect()->route('admin_data_admin.index')->with('error', 'Gagal! Kamu tidak bisa menghapus akunmu sendiri.');
        }

        $admin = User::findOrFail($id);
        $admin->delete();

        return redirect()->route('admin_data_admin.index')->with('success', 'Admin berhasil dihapus.');
    }


    public function bulkAction_admin(Request $request)
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
            $skipped = 0;

            foreach ($ids as $id) {
                if (auth()->id() == $id) {
                    $skipped++;
                    continue;
                }

                $admin = User::find($id);
                if ($admin) {
                    $admin->delete();
                    $deletedCount++;
                }
            }

            $message = "Berhasil menghapus {$deletedCount} data admin.";
            if ($skipped > 0) {
                $message .= " ({$skipped} akun dilewati karena tidak bisa menghapus akun sendiri)";
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
            ]);
        }
    }

    public function update_data_admin(Request $request, $id)
    {
        $request->validate([
            'edit_username' => 'required|string|max:255|unique:users,username,' . $id,
            'edit_name_admin' => 'required|string|max:255',
            'edit_password' => 'nullable|string|min:6|same:edit_password_confirmation',
            'edit_password_confirmation' => 'nullable|required_with:edit_password|same:edit_password',
        ], [
            'edit_username.required' => 'Username wajib diisi.',
            'edit_username.string' => 'Username harus berupa teks.',
            'edit_username.max' => 'Username maksimal 255 karakter.',
            'edit_username.unique' => 'Username sudah digunakan.',
            'edit_name_admin.required' => 'Nama admin wajib diisi.',
            'edit_name_admin.string' => 'Nama admin harus berupa teks.',
            'edit_name_admin.max' => 'Nama admin maksimal 255 karakter.',
            'edit_password.string' => 'Password harus berupa teks.',
            'edit_password.min' => 'Password minimal 6 karakter.',
            'edit_password.same' => 'Konfirmasi password tidak cocok.',
            'edit_password_confirmation.required_with' => 'Konfirmasi password wajib diisi jika mengubah password.',
            'edit_password_confirmation.same' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::findOrFail($id);

        $user->username = $request->edit_username;
        $user->name_admin = $request->edit_name_admin;

        if ($request->filled('edit_password')) {
            $user->password = Hash::make($request->edit_password);
        }

        $user->save();

        return redirect()->route('admin_data_admin.index')->with('success', 'Data admin berhasil diperbarui.');
    }

    // pengumuman
    public function index_pengumuman()
    {
        $pengumumen = Pengumuman::where('user_id', Auth::id())->orderByDesc('created_at')->get();

        return view('admin.pengumuman', compact('pengumumen'))->with('title', 'Buat Pengumuman');
    }

    public function store_pengumuman(Request $request)
    {
        $request->validate([
            'judul' => 'required|max:255',
            'isi' => 'required',
            'durasi' => 'required|in:1,3,7',
            'ditujukan_untuk' => 'required|in:siswa,guru,semua',
        ]);

        $tanggal = now();
        $kadaluarsa = $tanggal->copy()->addDays((int) $request->durasi);

        Pengumuman::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'ditujukan_untuk' => $request->ditujukan_untuk,
            'tanggal' => $tanggal,
            'kadaluarsa_sampai' => $kadaluarsa,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin_pengumuman.index')->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function edit_pengumuman($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        $durasi = Carbon::parse($pengumuman->tanggal)->diffInDays(Carbon::parse($pengumuman->kadaluarsa_sampai));

        return response()->json([
            'judul' => $pengumuman->judul,
            'isi' => $pengumuman->isi,
            'durasi' => $durasi,
        ]);
    }

    public function update_pengumuman(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|max:255',
            'isi' => 'required',
        ]);

        $pengumuman = Pengumuman::findOrFail($id);

        if ($request->filled('durasi')) {
            $durasi = (int) $request->durasi;
        } else {
            $durasi = Carbon::parse($pengumuman->tanggal)->diffInDays(Carbon::parse($pengumuman->kadaluarsa_sampai));
        }

        $tanggal = now();
        $kadaluarsa = $tanggal->copy()->addDays($durasi);

        $pengumuman->update([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'tanggal' => $tanggal,
            'kadaluarsa_sampai' => $kadaluarsa,
        ]);

        return redirect()->route('admin_pengumuman.index')->with('success', 'Pengumuman berhasil diubah.');
    }

    public function destroy_pengumuman($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->delete();

        return response()->json(['success' => true]);
    }
}
