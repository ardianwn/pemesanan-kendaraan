<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Kendaraan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; 
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::with('kendaraan')->paginate(10);
        return view('drivers.index', compact('drivers'));
    }

    public function create()
    {
        $kendaraans = Kendaraan::all();
        return view('drivers.create', compact('kendaraans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kendaraan_id' => 'required|exists:kendaraan,id',
        ]);

        Driver::create($request->all());

        return redirect()->route('drivers.index')->with('success', 'Driver berhasil ditambahkan.');
    }

    public function edit(Driver $driver)
    {
        $kendaraans = Kendaraan::all();
        return view('drivers.edit', compact('driver', 'kendaraans'));
    }

    public function update(Request $request, Driver $driver)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kendaraan_id' => 'required|exists:kendaraan,id',
        ]);

        $driver->update($request->all());

        return redirect()->route('drivers.index')->with('success', 'Driver berhasil diperbarui.');
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();
        return redirect()->route('drivers.index')->with('success', 'Driver berhasil dihapus.');
    }

    public function select(Request $request)
    {
        $search = $request->get('search');
        $tanggal = $request->get('tanggal');
        $jam = $request->get('jam');
        $durasi = $request->get('durasi');
        $kendaraan_id = $request->get('kendaraan_id');

        $query = Driver::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('telepon', 'LIKE', "%{$search}%");
            });
        }

        // Filter by kendaraan if provided
        if ($kendaraan_id) {
            $query->where('kendaraan_id', $kendaraan_id);
        }

        // If tanggal, jam, and durasi are provided, filter available drivers
        if ($tanggal && $jam && $durasi) {
            $query->whereDoesntHave('pemesanans', function($q) use ($tanggal, $jam, $durasi) {
                $jam_mulai = Carbon::parse($tanggal . ' ' . $jam);
                $jam_selesai = $jam_mulai->copy()->addHours($durasi);

                $q->where(function($q) use ($jam_mulai, $jam_selesai) {
                    $q->where(function($q) use ($jam_mulai, $jam_selesai) {
                        $q->where('tanggal_pemesanan', '=', $jam_mulai->toDateString())
                          ->where('jam_pemesanan', '<', $jam_selesai->toTimeString())
                          ->where(DB::raw('DATE_ADD(jam_pemesanan, INTERVAL durasi_pemesanan HOUR)'), '>', $jam_mulai->toTimeString());
                    });
                })
                ->whereNotIn('status', ['batal', 'selesai']);
            });
        }

        $drivers = $query->limit(10)->get();

        return response()->json([
            'results' => $drivers->map(function($driver) {
                return [
                    'id' => $driver->id,
                    'text' => "{$driver->nama} - {$driver->telepon}"
                ];
            })
        ]);
    }
}
