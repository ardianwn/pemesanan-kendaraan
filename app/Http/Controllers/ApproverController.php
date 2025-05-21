<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persetujuan;
use App\Models\Pemesanan;
use App\Models\LogAplikasi;
use App\Models\User;
use App\Services\LogService;
use Illuminate\Support\Facades\Auth;

class ApproverController extends Controller
{
    public function __construct()
    {
        // Laravel 12 handles middleware via route instead of constructor
    }
    
    /**
     * Select2 AJAX endpoint untuk memilih approver
     */
    public function selectApprover(Request $request)
    {
        $search = $request->get('search');
        
        $users = User::where('role', 'approver')
            ->where(function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('username', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            })
            ->limit(10)
            ->get();
            
        return response()->json([
            'results' => $users->map(function($user) {
                return [
                    'id' => $user->id,
                    'text' => $user->name . ' (' . $user->email . ')'
                ];
            })
        ]);
    }

    /**
     * Menampilkan dashboard approver
     */
    public function index()
    {
        $persetujuanPending = Persetujuan::where('status', 'pending')->count();
        $persetujuanDisetujui = Persetujuan::where('status', 'disetujui')->count();
        $persetujuanDitolak = Persetujuan::where('status', 'ditolak')->count();
        $persetujuanTerbaru = Persetujuan::with('pemesanan', 'pemesanan.user', 'pemesanan.kendaraan')
            ->latest()
            ->take(5)
            ->get();
        
        return view('approver.dashboard', compact(
            'persetujuanPending', 
            'persetujuanDisetujui', 
            'persetujuanDitolak', 
            'persetujuanTerbaru'
        ));
    }
    
    /**
     * Menampilkan daftar persetujuan
     */
    public function persetujuanIndex()
    {
        $userId = Auth::id();
        
        // Ambil persetujuan yang perlu diputuskan oleh approver ini (level saat ini)
        $persetujuans = Persetujuan::with('pemesanan', 'pemesanan.user', 'pemesanan.kendaraan')
            ->where('approver_id', $userId)
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);
        
        return view('approver.persetujuan.index', compact('persetujuans'));
    }
    
    /**
     * Menampilkan detail persetujuan
     */
    public function persetujuanShow(Persetujuan $persetujuan)
    {
        $persetujuan->load('pemesanan', 'pemesanan.user', 'pemesanan.kendaraan', 'pemesanan.driver');
        
        return view('approver.persetujuan.show', compact('persetujuan'));
    }
    
    /**
     * Menyetujui permohonan pemesanan
     */
    public function persetujuanApprove(Request $request, Persetujuan $persetujuan)
    {
        $request->validate([
            'catatan' => 'nullable|string|max:500',
        ]);
        
        $persetujuan->update([
            'status' => 'approved',
            'catatan' => $request->catatan ?? 'Persetujuan diberikan',
        ]);
        
        $pemesanan = $persetujuan->pemesanan;
        
        // Cek apakah ini adalah persetujuan terakhir
        if ($persetujuan->is_final_approval) {
            // Update status pemesanan menjadi disetujui
            $pemesanan->update(['status' => 'approved']);
            
            // Update status kendaraan
            $pemesanan->kendaraan->update(['status' => 'digunakan']);
            
            // Catat log menggunakan LogService
            LogService::approve('persetujuan', $persetujuan->id, 'Final approval untuk pemesanan ID #' . $pemesanan->id);
        } else {
            // Jika bukan persetujuan terakhir, aktifkan persetujuan berikutnya
            $nextPersetujuan = Persetujuan::where('pemesanans_id', $pemesanan->id)
                ->where('level', $persetujuan->level + 1)
                ->first();
                
            if ($nextPersetujuan) {
                $nextPersetujuan->update(['status' => 'pending']);
                
                // Log untuk persetujuan tingkat berikutnya
                LogService::create('persetujuan', $nextPersetujuan->id, 'Menunggu persetujuan level ' . $nextPersetujuan->level);
            }
            
            // Catat log untuk persetujuan saat ini
            LogService::approve('persetujuan', $persetujuan->id, 'Menyetujui pemesanan ID #' . $pemesanan->id . ' (Level ' . $persetujuan->level . ' dari multi-level approval)');
        }
        
        return redirect()->route('approver.persetujuan.index')
            ->with('success', 'Pemesanan berhasil disetujui!');
    }
    
    /**
     * Menolak permohonan pemesanan
     */
    public function persetujuanReject(Request $request, Persetujuan $persetujuan)
    {
        $request->validate([
            'catatan' => 'required|string|max:500',
        ]);
        
        $persetujuan->update([
            'status' => 'rejected', // Sesuai dengan nilai enum di migrasi
            'catatan' => $request->catatan,
        ]);
        
        // Update status pemesanan
        $pemesanan = $persetujuan->pemesanan;
        $pemesanan->update(['status' => 'rejected']); // Menggunakan nilai yang sesuai dengan enum
        
        // Tandai semua persetujuan selanjutnya sebagai dibatalkan
        Persetujuan::where('pemesanans_id', $pemesanan->id)
            ->where('level', '>', $persetujuan->level)
            ->update(['status' => 'cancelled']);
        
        // Catat log menggunakan LogService
        LogService::reject('persetujuan', $persetujuan->id, 'Menolak pemesanan ID #' . $pemesanan->id . ' dengan alasan: ' . $request->catatan);
        
        return redirect()->route('approver.persetujuan.index')
            ->with('success', 'Pemesanan berhasil ditolak!');
    }
}
