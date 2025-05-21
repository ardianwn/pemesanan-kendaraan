<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Kendaraan;
use App\Models\Driver;
use App\Models\LogAplikasi;
use App\Exports\PemesananExport;
use App\Imports\PemesananImport;
use App\Http\Requests\PemesananRequest;
use App\Services\LogService;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PemesananController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Pemesanan::with(['kendaraan', 'driver', 'user']);
        
        // Jika bukan admin, filter hanya tampilkan pemesanan milik user ini
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }
        
        // Filter berdasarkan kata kunci pencarian
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tujuan', 'like', "%{$search}%")
                  ->orWhere('keperluan', 'like', "%{$search}%")
                  ->orWhere('catatan', 'like', "%{$search}%")
                  ->orWhereHas('kendaraan', function($q) use ($search) {
                      $q->where('nomor_plat', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%");
                  })
                  ->orWhereHas('driver', function($q) use ($search) {
                      $q->where('nama', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter berdasarkan status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan tanggal mulai
        if ($request->has('tanggal_mulai') && $request->tanggal_mulai !== '') {
            $query->whereDate('tanggal_mulai', '>=', $request->tanggal_mulai);
        }
        
        // Filter berdasarkan tanggal selesai
        if ($request->has('tanggal_selesai') && $request->tanggal_selesai !== '') {
            $query->whereDate('tanggal_selesai', '<=', $request->tanggal_selesai);
        }
        
        $pemesanans = $query->latest()->paginate(10)->withQueryString();
        
        return view('pemesanans.index', compact('pemesanans'));
    }

    public function create()
    {
        // Cek apakah user boleh membuat pemesanan
        if (Gate::denies('create-pemesanan')) {
            abort(403, 'Unauthorized action.');
        }

        // For the Select2 implementation, we'll only load selected items if needed
        $selectedKendaraan = null;
        $selectedDriver = null;
        $selectedApprovers = [];
        
        // If there's old input, load the selected items for display in select2
        if (old('kendaraan_id')) {
            $selectedKendaraan = Kendaraan::find(old('kendaraan_id'));
        }
        
        if (old('driver_id')) {
            $selectedDriver = Driver::find(old('driver_id'));
        }
        
        if (old('approvers')) {
            $approverIds = old('approvers');
            foreach ($approverIds as $key => $id) {
                if ($id) {
                    $selectedApprovers[$key] = \App\Models\User::find($id);
                }
            }
        }
        
        // For non-admin users, we still need to show approver options
        // since Select2 AJAX will only load on demand
        $approvers = \App\Models\User::where('role', 'approver')->get();

        return view('pemesanans.create', compact(
            'selectedKendaraan', 
            'selectedDriver', 
            'selectedApprovers',
            'approvers'
        ));
    }

    public function store(PemesananRequest $request)
    {
        // Cek apakah user boleh membuat pemesanan
        if (Gate::denies('create-pemesanan')) {
            abort(403, 'Unauthorized action.');
        }

        // Validasi sudah dihandle oleh PemesananRequest
        $validated = $request->validated();
        
        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';
        
        // Handle dokumen pendukung jika ada
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            
            // Validasi file
            $request->validate([
                'document' => 'file|mimes:pdf|max:500|min:1', // max 500 KB, min 1 KB
            ]);
            
            // Generate nama file unik
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Simpan file di storage/app/public/documents
            $file->storeAs('public/documents', $fileName);
            
            // Simpan informasi file
            $validated['document_path'] = 'documents/' . $fileName;
            $validated['document_name'] = $file->getClientOriginalName();
            $validated['document_size'] = $file->getSize();
        }

        // Buat pemesanan baru
        $pemesanan = Pemesanan::create($validated);

        // Buat persetujuan berjenjang
        if ($request->has('approvers') && is_array($request->approvers) && count($request->approvers) >= 2) {
            $approvers = $request->approvers;
            $totalApprovers = count($approvers);
            
            for ($i = 0; $i < $totalApprovers; $i++) {
                $isFinalApproval = ($i == $totalApprovers - 1);
                $nextApproverId = ($i < $totalApprovers - 1) ? $approvers[$i + 1] : null;
                
                \App\Models\Persetujuan::create([
                    'pemesanans_id' => $pemesanan->id,
                    'approver_id' => $approvers[$i],
                    'level' => $i + 1,
                    'next_approver_id' => $nextApproverId,
                    'is_final_approval' => $isFinalApproval,
                    'status' => ($i == 0) ? 'pending' : 'waiting',
                ]);
            }
        } else {
            // Fallback jika tidak ada approver yang dipilih (minimal 2)
            return redirect()->back()->with('error', 'Minimal 2 approver harus dipilih untuk persetujuan berjenjang.')->withInput();
        }

        // Log aktivitas menggunakan LogService
        LogService::create('pemesanan', $pemesanan->id, "Pemesanan baru dengan kendaraan ID: {$pemesanan->kendaraan_id}");

        return redirect()->route('pemesanan.index')->with('success', 'Pemesanan berhasil dibuat dan menunggu persetujuan.');
    }

    public function show(Pemesanan $pemesanan)
    {
        // Cek apakah user boleh melihat pemesanan
        if (Gate::denies('view-pemesanan', $pemesanan)) {
            abort(403, 'Unauthorized action.');
        }

        // Load relasi
        $pemesanan->load(['kendaraan', 'driver', 'user', 'persetujuans.approver']);

        return view('pemesanans.show', compact('pemesanan'));
    }

    public function edit(Pemesanan $pemesanan)
    {
        // Cek apakah user boleh mengedit pemesanan
        if (Gate::denies('update-pemesanan', $pemesanan)) {
            abort(403, 'Unauthorized action.');
        }

        // Cek jika status sudah diproses, tidak bisa diedit
        if ($pemesanan->status !== 'pending') {
            return redirect()->route('pemesanans.index')->withErrors('Pemesanan sudah diproses, tidak bisa diedit.');
        }

        // Ambil data kendaraan dan driver
        $kendaraans = Kendaraan::all();
        $drivers = Driver::all();

        return view('pemesanans.edit', compact('pemesanan', 'kendaraans', 'drivers'));
    }

    public function update(PemesananRequest $request, Pemesanan $pemesanan)
    {
        // Cek apakah user boleh mengupdate pemesanan
        if (Gate::denies('update-pemesanan', $pemesanan)) {
            abort(403, 'Unauthorized action.');
        }

        // Cek jika status sudah diproses, tidak bisa diupdate
        if ($pemesanan->status !== 'pending') {
            return redirect()->route('pemesanans.index')->withErrors('Pemesanan sudah diproses, tidak bisa diupdate.');
        }

        // Validasi sudah dihandle oleh PemesananRequest
        $validated = $request->validated();

        // Update pemesanan
        $pemesanan->update($validated);

        // Log aktivitas menggunakan LogService
        LogService::update('pemesanan', $pemesanan->id, "Mengupdate pemesanan untuk kendaraan ID: {$pemesanan->kendaraan_id}");

        return redirect()->route('pemesanans.index')->with('success', 'Pemesanan berhasil diperbarui.');
    }

    public function destroy(Pemesanan $pemesanan)
    {
        // Cek apakah user boleh menghapus pemesanan
        if (Gate::denies('delete-pemesanan', $pemesanan)) {
            abort(403, 'Unauthorized action.');
        }

        // Cek jika status sudah diproses, tidak bisa dihapus
        if ($pemesanan->status !== 'pending') {
            return redirect()->route('pemesanans.index')->withErrors('Pemesanan sudah diproses, tidak bisa dihapus.');
        }

        // Hapus pemesanan
        $pemesanan->delete();

        // Log aktivitas menggunakan LogService
        LogService::delete('pemesanan', $pemesanan->id, "Menghapus pemesanan dengan kendaraan ID: {$pemesanan->kendaraan_id}");

        return redirect()->route('pemesanans.index')->with('success', 'Pemesanan berhasil dihapus.');
    }

    // Contoh fungsi dashboard untuk grafik pemakaian kendaraan
    public function dashboard()
    {
        // Cek apakah user bisa melihat dashboard
        if (Gate::denies('viewAny-pemesanan')) {
            abort(403, 'Unauthorized action.');
        }

        // Pengambilan data jumlah pemesanan per bulan
        $data = Pemesanan::selectRaw('MONTH(tanggal_pemesanan) as bulan, count(*) as total')
            ->whereYear('tanggal_pemesanan', now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $bulan = [];
        $jumlah_pemakaian = [];
        foreach ($data as $d) {
            $bulan[] = date('F', mktime(0, 0, 0, $d->bulan, 10));
            $jumlah_pemakaian[] = $d->total;
        }

        return view('dashboard', compact('bulan', 'jumlah_pemakaian'));
    }
    
    /**
     * Export pemesanan ke Excel
     */
    public function export(Request $request) 
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        // Jika request adalah untuk template import
        if ($request->has('template')) {
            $fileName = 'template_import_pemesanan.xlsx';
            
            // Catat log menggunakan LogService
            LogService::record('export_template', 'pemesanan', null, 'Mengunduh template import pemesanan');
            
            // Buat template kosong
            return Excel::download(new PemesananExport(null, null, null, true), $fileName);
        }
        
        $fileName = 'laporan_pemesanan_kendaraan_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        // Catat log menggunakan LogService
        LogService::record('export', 'pemesanan', null, 'Mengekspor data pemesanan kendaraan ke Excel');
        
        return Excel::download(
            new PemesananExport(
                $request->input('tanggal_mulai'),
                $request->input('tanggal_akhir'),
                $request->input('status'),
                false
            ), 
            $fileName
        );
    }
    
    /**
     * Import pemesanan from Excel file
     */
    public function import(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:2048',
        ]);
        
        $import = new PemesananImport();
        
        try {
            // Use queue if available, otherwise process immediately
            if (config('queue.default') !== 'sync') {
                Excel::queueImport($import, $request->file('file'));
                $message = 'File sedang diproses. Hasil import akan segera tersedia.';
            } else {
                Excel::import($import, $request->file('file'));
                $message = 'Berhasil mengimpor data pemesanan.';
            }
            
            // Catat log menggunakan LogService
            LogService::record('import', 'pemesanan', null, 'Mengimpor data pemesanan dari Excel');
            
            return redirect()->route('admin.pemesanan.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }
    
    /**
     * Mark a pemesanan as finished
     */
    public function finish(Pemesanan $pemesanan)
    {
        // Pastikan pemesanan milik user ini atau user adalah admin
        if ($pemesanan->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        
        // Pastikan status pemesanan adalah disetujui
        if ($pemesanan->status !== 'disetujui') {
            return redirect()->route('pemesanan.show', $pemesanan)
                ->with('error', 'Hanya pemesanan yang sudah disetujui yang dapat diselesaikan.');
        }
        
        // Update status menjadi selesai
        $pemesanan->status = 'selesai';
        $pemesanan->save();
        
        // Catat log
        LogService::update('pemesanan', $pemesanan->id, 'Menyelesaikan pemesanan kendaraan: ' . $pemesanan->kendaraan->nama);
        
        return redirect()->route('pemesanan.show', $pemesanan)
            ->with('success', 'Pemesanan berhasil diselesaikan.');
    }
}
