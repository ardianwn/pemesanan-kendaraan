<?php

namespace App\Http\Controllers;

use App\Models\Persetujuan;
use App\Models\Pemesanan;
use App\Models\LogAplikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersetujuanController extends Controller
{
    // List pemesanan yang perlu disetujui oleh user (approver)
    public function index()
    {
        $user = Auth::user();

        // Cari persetujuan pending di mana user adalah approver
        $persetujuans = Persetujuan::with('pemesanan')
            ->where('approver_id', $user->id)
            ->where('status', 'pending')
            ->paginate(10);

        return view('persetujuan.index', compact('persetujuans'));
    }

    // Approve pemesanan (level berjenjang)
    public function approve($id)
    {
        $user = Auth::user();

        $persetujuan = Persetujuan::findOrFail($id);

        if ($persetujuan->approver_id !== $user->id) {
            abort(403, 'Tidak berhak melakukan persetujuan ini.');
        }

        if ($persetujuan->status !== 'pending') {
            return redirect()->back()->withErrors('Persetujuan sudah diproses.');
        }

        $persetujuan->status = 'approved';
        $persetujuan->catatan = 'Persetujuan diberikan';
        $persetujuan->save();

        LogAplikasi::create([
            'user_id' => $user->id,
            'aktivitas' => 'Menyetujui pemesanan',
            'keterangan' => "Persetujuan ID: {$persetujuan->id}, Pemesanan ID: {$persetujuan->pemesanans_id}",
        ]);

        // Cek apakah semua level sudah disetujui
        $pemesanansId = $persetujuan->pemesanans_id;
        $allApproved = Persetujuan::where('pemesanans_id', $pemesanansId)
            ->where('status', 'pending')
            ->count() === 0;

        if ($allApproved) {
            $pemesanan = Pemesanan::find($pemesanansId);
            $pemesanan->status = 'approved';
            $pemesanan->save();
        }

        return redirect()->back()->with('success', 'Pemesanan berhasil disetujui.');
    }

    // Reject pemesanan
    public function reject(Request $request, $id)
    {
        $user = Auth::user();

        $persetujuan = Persetujuan::findOrFail($id);

        if ($persetujuan->approver_id !== $user->id) {
            abort(403, 'Tidak berhak melakukan penolakan ini.');
        }

        if ($persetujuan->status !== 'pending') {
            return redirect()->back()->withErrors('Persetujuan sudah diproses.');
        }

        $request->validate([
            'catatan' => 'required|string|max:500',
        ]);

        $persetujuan->status = 'rejected';
        $persetujuan->catatan = $request->catatan;
        $persetujuan->save();

        LogAplikasi::create([
            'user_id' => $user->id,
            'aktivitas' => 'Menolak pemesanan',
            'keterangan' => "Persetujuan ID: {$persetujuan->id}, Pemesanan ID: {$persetujuan->pemesanans_id}, Catatan: {$persetujuan->catatan}",
        ]);

        // Set status pemesanan jadi rejected
        $pemesanan = Pemesanan::find($persetujuan->pemesanans_id);
        $pemesanan->status = 'rejected';
        $pemesanan->save();

        return redirect()->back()->with('success', 'Pemesanan berhasil ditolak.');
    }
}
