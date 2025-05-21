<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DebugController extends Controller
{
    public function showSessionInfo()
    {
        return view('debug.session');
    }
    
    public function testCsrf(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'CSRF token is valid!',
            'token' => $request->session()->token(),
        ]);
    }
    
    public function clearSessions()
    {
        if (config('session.driver') === 'database') {
            DB::table('sessions')->truncate();
            return 'All sessions cleared!';
        }
        
        return 'Cannot clear sessions. Driver is not database.';
    }
}
