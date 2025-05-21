<?php

namespace App\Http\Controllers;

use App\Models\LogAplikasi;

class LogAplikasiController extends Controller
{
    public function index()
    {
        $logs = LogAplikasi::with('user')->latest()->paginate(20);
        return view('log.index', compact('logs'));
    }
}
