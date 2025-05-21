<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; 
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    public function index()
    {
        $kendaraans = Kendaraan::paginate(10);
        return view('kendaraan.index', compact('kendaraans'));
    }

    public function create()
    {
        return view('kendaraan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|in:angkutan_orang,angkutan_barang',
            'status' => 'required|in:milik,sewa',
            'lokasi' => 'required|string|max:255',
        ]);

        Kendaraan::create($request->all());

        return redirect()->route('kendaraan.index')->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function edit(Kendaraan $kendaraan)
    {
        return view('kendaraan.edit', compact('kendaraan'));
    }

    public function update(Request $request, Kendaraan $kendaraan)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|in:angkutan_orang,angkutan_barang',
            'status' => 'required|in:milik,sewa',
            'lokasi' => 'required|string|max:255',
        ]);

        $kendaraan->update($request->all());

        return redirect()->route('kendaraan.index')->with('success', 'Kendaraan berhasil diperbarui.');
    }

    public function destroy(Kendaraan $kendaraan)
    {
        $kendaraan->delete();
        return redirect()->route('kendaraan.index')->with('success', 'Kendaraan berhasil dihapus.');
    }

    public function select(Request $request)
    {
        $search = $request->get('search');
        $tanggal = $request->get('tanggal');
        $jam = $request->get('jam');
        $durasi = $request->get('durasi');

        $query = Kendaraan::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nomor_plat', 'LIKE', "%{$search}%");
            });
        }

        // If tanggal, jam, and durasi are provided, filter available vehicles
        if ($tanggal && $jam && $durasi) {
            $query->whereDoesntHave('pemesanans', function($q) use ($tanggal, $jam, $durasi) {
                $jam_mulai = \Carbon\Carbon::parse($tanggal . ' ' . $jam);
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

        $kendaraans = $query->limit(10)->get();

        return response()->json([
            'results' => $kendaraans->map(function($kendaraan) {
                return [
                    'id' => $kendaraan->id,
                    'text' => "{$kendaraan->nama} ({$kendaraan->nomor_plat}) - Kapasitas: {$kendaraan->kapasitas} orang"
                ];
            })
        ]);
    }
}
