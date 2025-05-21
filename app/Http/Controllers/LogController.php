<?php

namespace App\Http\Controllers;

use App\Models\LogAplikasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Menampilkan daftar log aplikasi
     */
    public function index(Request $request)
    {
        // Hanya admin yang boleh melihat log
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        $query = LogAplikasi::with('user')->latest();
        
        // Filter berdasarkan aktivitas
        if ($request->filled('aktivitas')) {
            $query->where('aktivitas', $request->aktivitas);
        }
        
        // Filter berdasarkan tabel
        if ($request->filled('tabel')) {
            $query->where('tabel', $request->tabel);
        }
        
        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }
        
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('created_at', '<=', $request->tanggal_akhir);
        }
        
        $logs = $query->paginate(20);
        
        $aktivitasList = LogAplikasi::select('aktivitas')->distinct()->pluck('aktivitas');
        $tabelList = LogAplikasi::select('tabel')->distinct()->pluck('tabel');
        
        return view('admin.logs.index', compact('logs', 'aktivitasList', 'tabelList'));
    }
    
    /**
     * Menampilkan detail log
     */
    public function show(LogAplikasi $log)
    {
        // Hanya admin yang boleh melihat log
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        $log->load('user');
        
        return view('admin.logs.show', compact('log'));
    }
}
