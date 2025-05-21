<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kendaraan;
use App\Models\Driver;
use App\Models\Pemesanan;
use App\Models\LogAplikasi;
use App\Services\LogService;

class AdminController extends Controller
{
    /**
     * Constructor untuk middleware
     * 
     * Catatan: Middleware in Laravel 12 ditangani melalui route daripada constructor
     */
    public function __construct()
    {
        // Laravel 12 handles middleware via route instead of constructor
    }
    
    /**
     * Menampilkan dashboard admin
     */
    public function index()
    {
        $totalKendaraan = Kendaraan::count();
        $totalDriver = Driver::count();
        $totalPemesanan = Pemesanan::count();
        $pemesananTerbaru = Pemesanan::with('user', 'kendaraan', 'driver')->latest()->take(5)->get();
        
        return view('admin.dashboard', compact('totalKendaraan', 'totalDriver', 'totalPemesanan', 'pemesananTerbaru'));
    }
    
    /**
     * Menampilkan daftar kendaraan
     */
    public function kendaraanIndex()
    {
        $kendaraans = Kendaraan::latest()->paginate(10);
        return view('admin.kendaraan.index', compact('kendaraans'));
    }
    
    /**
     * Menampilkan form tambah kendaraan
     */
    public function kendaraanCreate()
    {
        return view('admin.kendaraan.create');
    }
    
    /**
     * Menyimpan kendaraan baru
     */
    public function kendaraanStore(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_plat' => 'required|string|max:20|unique:kendaraan,nomor_plat',
            'jenis' => 'required|string|max:100',
            'kapasitas' => 'required|integer|min:1',
            'status' => 'required|in:tersedia,digunakan,perbaikan'
        ]);
        
        $kendaraan = Kendaraan::create($request->all());
        
        // Catat log menggunakan LogService
        LogService::create('kendaraan', $kendaraan->id, 'Menambahkan kendaraan baru: ' . $kendaraan->nomor_plat);
        
        return redirect()->route('admin.kendaraan.index')
            ->with('success', 'Kendaraan berhasil ditambahkan!');
    }
    
    /**
     * Menampilkan form edit kendaraan
     */
    public function kendaraanEdit(Kendaraan $kendaraan)
    {
        return view('admin.kendaraan.edit', compact('kendaraan'));
    }
    
    /**
     * Update data kendaraan
     */
    public function kendaraanUpdate(Request $request, Kendaraan $kendaraan)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_plat' => 'required|string|max:20|unique:kendaraan,nomor_plat,' . $kendaraan->id,
            'jenis' => 'required|string|max:100',
            'kapasitas' => 'required|integer|min:1',
            'status' => 'required|in:tersedia,digunakan,perbaikan'
        ]);
        
        $kendaraan->update($request->all());
        
        // Catat log menggunakan LogService
        LogService::update('kendaraan', $kendaraan->id, 'Mengubah data kendaraan: ' . $kendaraan->nomor_plat);
        
        return redirect()->route('admin.kendaraan.index')
            ->with('success', 'Kendaraan berhasil diperbarui!');
    }
    
    /**
     * Menghapus kendaraan
     */
    public function kendaraanDestroy(Kendaraan $kendaraan)
    {
        // Simpan informasi untuk log
        $kendaraanInfo = $kendaraan->nomor_plat;
        $kendaraanId = $kendaraan->id;
        
        $kendaraan->delete();
        
        // Catat log menggunakan LogService
        LogService::delete('kendaraan', $kendaraanId, 'Menghapus kendaraan: ' . $kendaraanInfo);
        
        return redirect()->route('admin.kendaraan.index')
            ->with('success', 'Kendaraan berhasil dihapus!');
    }
    
    /**
     * Menampilkan daftar driver
     */
    public function driverIndex()
    {
        $drivers = Driver::latest()->paginate(10);
        return view('admin.driver.index', compact('drivers'));
    }
    
    /**
     * Menampilkan form tambah driver
     */
    public function driverCreate()
    {
        return view('admin.driver.create');
    }
    
    /**
     * Menyimpan driver baru
     */
    public function driverStore(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
            'status' => 'required|in:aktif,cuti,tidak_aktif'
        ]);
        
        $driver = Driver::create($request->all());
        
        // Catat log menggunakan LogService
        LogService::create('driver', $driver->id, 'Menambahkan driver baru: ' . $driver->nama);
        
        return redirect()->route('admin.driver.index')
            ->with('success', 'Driver berhasil ditambahkan!');
    }
    
    /**
     * Menampilkan form edit driver
     */
    public function driverEdit(Driver $driver)
    {
        return view('admin.driver.edit', compact('driver'));
    }
    
    /**
     * Update data driver
     */
    public function driverUpdate(Request $request, Driver $driver)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
            'status' => 'required|in:aktif,cuti,tidak_aktif'
        ]);
        
        $driver->update($request->all());
        
        // Catat log menggunakan LogService
        LogService::update('driver', $driver->id, 'Mengubah data driver: ' . $driver->nama);
        
        return redirect()->route('admin.driver.index')
            ->with('success', 'Driver berhasil diperbarui!');
    }
    
    /**
     * Menghapus driver
     */
    public function driverDestroy(Driver $driver)
    {
        // Simpan informasi untuk log
        $driverInfo = $driver->nama;
        $driverId = $driver->id;
        
        $driver->delete();
        
        // Catat log menggunakan LogService
        LogService::delete('driver', $driverId, 'Menghapus driver: ' . $driverInfo);
        
        return redirect()->route('admin.driver.index')
            ->with('success', 'Driver berhasil dihapus!');
    }
    
    /**
     * Get kendaraan data for select2 dropdown
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function kendaraanSelect(Request $request)
    {
        $search = $request->input('search');
        $page = $request->input('page', 1);
        $limit = 10;
        
        $query = Kendaraan::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nomor_plat', 'like', "%{$search}%")
                  ->orWhere('merk', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
        }
        
        // Exclude already booked vehicles on the selected dates if provided
        if ($request->has('tanggal_mulai') && $request->has('tanggal_selesai')) {
            $start = $request->input('tanggal_mulai');
            $end = $request->input('tanggal_selesai');
            
            $query->whereDoesntHave('pemesanans', function($q) use ($start, $end) {
                $q->where(function($q) use ($start, $end) {
                    // Check if the booking dates overlap with existing bookings
                    $q->where(function($q) use ($start, $end) {
                        $q->whereDate('tanggal_mulai', '<=', $end)
                          ->whereDate('tanggal_selesai', '>=', $start);
                    });
                })->where('status', '!=', 'ditolak');
            });
        }
        
        $total = $query->count();
        
        $kendaraan = $query->skip(($page - 1) * $limit)
                          ->take($limit)
                          ->get()
                          ->map(function($item) {
                              return [
                                  'id' => $item->id,
                                  'text' => $item->nomor_plat . ' - ' . $item->merk . ' ' . $item->model
                              ];
                          });
        
        return response()->json([
            'results' => $kendaraan,
            'pagination' => [
                'more' => ($page * $limit) < $total
            ]
        ]);
    }
    
    /**
     * Get driver data for select2 dropdown
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function driverSelect(Request $request)
    {
        $search = $request->input('search');
        $page = $request->input('page', 1);
        $limit = 10;
        
        $query = Driver::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }
        
        // Exclude already assigned drivers on the selected dates if provided
        if ($request->has('tanggal_mulai') && $request->has('tanggal_selesai')) {
            $start = $request->input('tanggal_mulai');
            $end = $request->input('tanggal_selesai');
            
            $query->whereDoesntHave('pemesanans', function($q) use ($start, $end) {
                $q->where(function($q) use ($start, $end) {
                    $q->whereDate('tanggal_mulai', '<=', $end)
                      ->whereDate('tanggal_selesai', '>=', $start);
                })->where('status', '!=', 'ditolak');
            });
        }
        
        $total = $query->count();
        
        $drivers = $query->skip(($page - 1) * $limit)
                        ->take($limit)
                        ->get()
                        ->map(function($item) {
                            return [
                                'id' => $item->id,
                                'text' => $item->nama . ' (' . $item->no_hp . ')'
                            ];
                        });
        
        return response()->json([
            'results' => $drivers,
            'pagination' => [
                'more' => ($page * $limit) < $total
            ]
        ]);
    }
    
    /**
     * Get approver data for select2 dropdown
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function approverSelect(Request $request)
    {
        $search = $request->input('search');
        $page = $request->input('page', 1);
        $limit = 10;
        
        $query = \App\Models\User::where('role', 'approver');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $total = $query->count();
        
        $approvers = $query->skip(($page - 1) * $limit)
                          ->take($limit)
                          ->get()
                          ->map(function($item) {
                              return [
                                  'id' => $item->id,
                                  'text' => $item->name . ' (' . $item->email . ')'
                              ];
                          });
        
        return response()->json([
            'results' => $approvers,
            'pagination' => [
                'more' => ($page * $limit) < $total
            ]
        ]);
    }
}
