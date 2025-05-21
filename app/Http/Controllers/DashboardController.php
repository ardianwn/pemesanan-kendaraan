<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Kendaraan;
use App\Models\Pemesanan;
use App\Models\Persetujuan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Admin dashboard dengan statistik lengkap
     */
    public function adminDashboard()
    {
        // Verifikasi role admin melalui middleware, tidak perlu cek lagi di sini
        
        // Statistik pemesanan
        $stats = [
            'total_users' => User::count(),
            'total_pemesanan' => Pemesanan::count(),
            'total_kendaraan' => Kendaraan::count(),
            'total_driver' => Driver::count(),
            'pemesanan_pending' => Pemesanan::where('status', 'pending')->count(),
            'pemesanan_disetujui' => Pemesanan::where('status', 'disetujui')->count(),
            'pemesanan_ditolak' => Pemesanan::where('status', 'ditolak')->count(),
            'pemesanan_selesai' => Pemesanan::where('status', 'selesai')->count(),
        ];
        
        // Data pemesanan per bulan untuk grafik
        $pemesananPerBulan = Pemesanan::selectRaw('MONTH(tanggal_pemesanan) as bulan, COUNT(*) as total')
            ->whereYear('tanggal_pemesanan', Carbon::now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->pluck('total', 'bulan')
            ->toArray();
        
        // Format data untuk grafik - pastikan semua bulan ada
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[$i] = $pemesananPerBulan[$i] ?? 0;
        }
        
        // Top 5 kendaraan yang paling sering dipesan
        $topKendaraan = Pemesanan::select('kendaraan_id', DB::raw('COUNT(*) as total'))
            ->with('kendaraan')
            ->groupBy('kendaraan_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        
        // Pemesanan terbaru
        $pemesananTerbaru = Pemesanan::with(['user', 'kendaraan', 'driver'])
            ->latest()
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'stats',
            'chartData',
            'topKendaraan',
            'pemesananTerbaru'
        ));
    }
    
    /**
     * Approver dashboard dengan fokus pada persetujuan
     */
    public function approverDashboard()
    {
        // Hanya approver yang boleh mengakses
          if (!Auth::user()->isApprover()) {
            abort(403);
        }
        
        // Statistik persetujuan
        $stats = [
            'pending_approval' => Pemesanan::where('status', 'pending')->count(),
            'total_disetujui' => Pemesanan::where('status', 'disetujui')->count(),
            'total_ditolak' => Pemesanan::where('status', 'ditolak')->count(),
            'disetujui_minggu_ini' => Pemesanan::where('status', 'disetujui')
                ->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->count(),
        ];
        
        // Pemesanan yang perlu disetujui
        $pemesananPending = Pemesanan::with(['user', 'kendaraan', 'driver'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();
            
        // Persetujuan terbaru untuk ditampilkan di tabel
        $persetujuanTerbaru = Persetujuan::with(['pemesanan.user', 'pemesanan.kendaraan', 'approver'])
            ->latest()
            ->take(5)
            ->get();
        
        // Data persetujuan per hari selama 7 hari terakhir
        $persetujuanHarian = Pemesanan::selectRaw('DATE(updated_at) as tanggal, status, COUNT(*) as total')
            ->whereIn('status', ['disetujui', 'ditolak'])
            ->where('updated_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('tanggal', 'status')
            ->orderBy('tanggal')
            ->get();
        
        // Format data untuk grafik
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $disetujui = $persetujuanHarian->where('tanggal', $date)->where('status', 'disetujui')->first();
            $ditolak = $persetujuanHarian->where('tanggal', $date)->where('status', 'ditolak')->first();
            
            $chartData[$date] = [
                'disetujui' => $disetujui ? $disetujui->total : 0,
                'ditolak' => $ditolak ? $ditolak->total : 0,
            ];
        }
        
        // Define variables that match what the view expects
        $persetujuanPending = $stats['pending_approval'];
        $persetujuanDisetujui = $stats['total_disetujui'];
        $persetujuanDitolak = $stats['total_ditolak'];
        
        return view('approver.dashboard', compact(
            'stats',
            'pemesananPending',
            'persetujuanPending',
            'persetujuanDisetujui',
            'persetujuanDitolak',
            'persetujuanTerbaru',
            'chartData'
        ));
    }
    
    /**
     * User dashboard untuk melihat pemesanan mereka
     */
    public function userDashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Redirect admin ke dashboard admin
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        
        // Redirect approver ke dashboard approver
        if ($user->isApprover()) {
            return redirect()->route('approver.dashboard');
        }
        
        // Statistik dasar yang berlaku untuk semua pengguna
        $stats = [
            'total_pemesanan' => $user->pemesanans()->count(),
            'pemesanan_pending' => $user->pemesanans()->where('status', 'pending')->count(),
            'pemesanan_disetujui' => $user->pemesanans()->where('status', 'disetujui')->count(),
            'pemesanan_ditolak' => $user->pemesanans()->where('status', 'ditolak')->count(),
            'pemesanan_selesai' => $user->pemesanans()->where('status', 'selesai')->count(),
        ];
        
        // Pemesanan terbaru pengguna
        $pemesananTerbaru = $user->pemesanans()
            ->with(['kendaraan', 'driver'])
            ->latest()
            ->take(5)
            ->get();
            
        // Pastikan tidak ada pemesanan tanpa kendaraan atau driver yang menyebabkan error
        $pemesananTerbaru->each(function ($pemesanan) {
            if (!$pemesanan->kendaraan) {
                \Log::warning("Pemesanan ID: {$pemesanan->id} tidak memiliki kendaraan");
            }
            if (!$pemesanan->driver) {
                \Log::warning("Pemesanan ID: {$pemesanan->id} tidak memiliki driver");
            }
        });
            
        // Data khusus untuk admin
        if ($user->isAdmin()) {
            $stats['total_users'] = User::count();
            $stats['total_kendaraan'] = Kendaraan::count();
            $stats['total_drivers'] = Driver::count();
            $stats['kendaraan_tersedia'] = Kendaraan::where('status', 'tersedia')->count();
            
            // Data untuk chart penggunaan kendaraan
            $pemesananPerHari = $this->getPemesananChartData();
        }
        
        // Data khusus untuk approver
        if ($user->isApprover()) {
            $stats['persetujuan_pending'] = Persetujuan::where('approver_id', $user->id)
                                                     ->where('status', 'pending')
                                                     ->count();
            
            $stats['persetujuan_disetujui'] = Persetujuan::where('approver_id', $user->id)
                                                       ->where('status', 'disetujui')
                                                       ->count();
                                                       
            $stats['persetujuan_ditolak'] = Persetujuan::where('approver_id', $user->id)
                                                      ->where('status', 'ditolak')
                                                      ->count();
                                                      
            // Persetujuan yang menunggu tindakan
            $persetujuanPending = Persetujuan::with(['pemesanan', 'pemesanan.kendaraan', 'pemesanan.user'])
                                            ->where('approver_id', $user->id)
                                            ->where('status', 'pending')
                                            ->latest()
                                            ->take(5)
                                            ->get();
            
            $role = 'approver'; // Untuk view
            return view('dashboard', compact(
                'stats',
                'pemesananTerbaru',
                'persetujuanPending',
                'role'
            ));
        }
        
        // Tambahkan data chart untuk admin
        if ($user->isAdmin()) {
            $role = 'admin'; // Untuk view
            return view('dashboard', compact(
                'stats',
                'pemesananTerbaru',
                'pemesananPerHari',
                'role'
            ));
        }
        
        // View default untuk user biasa
        $role = 'user'; // Untuk view
        return view('dashboard', compact(
            'stats',
            'pemesananTerbaru',
            'role'
        ));
    }
    
    /**
     * Mendapatkan data untuk chart penggunaan kendaraan
     * 
     * @return array
     */
    private function getPemesananChartData()
    {
        // Ambil data pemesanan per hari untuk 14 hari terakhir
        $startDate = Carbon::now()->subDays(13)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        
        // Query untuk mendapatkan jumlah pemesanan per hari
        $pemesananPerHari = Pemesanan::selectRaw('DATE(tanggal_pemesanan) as tanggal, COUNT(*) as total_pemesanan')
            ->whereBetween('tanggal_pemesanan', [$startDate, $endDate])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->keyBy('tanggal')
            ->map(function ($item) {
                return $item->total_pemesanan;
            })
            ->toArray();
        
        // Query untuk mendapatkan kendaraan yang digunakan per hari
        $kendaraanPerHari = Pemesanan::selectRaw('DATE(tanggal_pemesanan) as tanggal, COUNT(DISTINCT kendaraan_id) as total_kendaraan')
            ->whereBetween('tanggal_pemesanan', [$startDate, $endDate])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->keyBy('tanggal')
            ->map(function ($item) {
                return $item->total_kendaraan;
            })
            ->toArray();
            
        // Membuat array tanggal untuk 14 hari terakhir
        $dateRange = [];
        $labels = [];
        
        for ($i = 13; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dateRange[] = $date;
            $labels[] = Carbon::parse($date)->format('d M');
        }
        
        // Menyusun data untuk chart
        $pemesananData = [];
        $kendaraanData = [];
        
        foreach ($dateRange as $date) {
            $pemesananData[] = $pemesananPerHari[$date] ?? 0;
            $kendaraanData[] = $kendaraanPerHari[$date] ?? 0;
        }
        
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Jumlah Pemesanan',
                    'data' => $pemesananData,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1
                ],
                [
                    'label' => 'Kendaraan Digunakan',
                    'data' => $kendaraanData,
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1
                ]
            ]
        ];
    }
}
