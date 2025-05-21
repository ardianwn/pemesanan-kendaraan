<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\LogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        // Hanya admin yang boleh melihat daftar user
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        $users = User::orderBy('name')->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        // Hanya admin yang boleh membuat user
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        $roles = \App\Models\Role::where('is_active', true)->get();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        // Hanya admin yang boleh membuat user
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => ['required', 'exists:roles,id']
        ]);
        
        $role = \App\Models\Role::findOrFail($request->role_id);
        
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'role' => $role->slug // Keep legacy role field for compatibility
        ]);
        
        LogService::create('users', $user->id, "Menambahkan pengguna baru: {$user->name} dengan role {$user->role}");
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        // Hanya admin yang boleh melihat detail user
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        // Muat log aktivitas user
        $logs = $user->logs()->latest()->take(20)->get();
        
        return view('admin.users.show', compact('user', 'logs'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Hanya admin yang boleh edit user
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        // Hanya admin yang boleh update user
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username,'.$user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'in:user,admin,approver']
        ];
        
        // Hanya validasi password jika ada input password
        if ($request->filled('password')) {
            $rules['password'] = ['confirmed', Rules\Password::defaults()];
        }
        
        $request->validate($rules);
        
        // Update user data
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->role = $request->role;
        
        // Update password jika ada input baru
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();
        
        // Log aktivitas
        LogService::update('users', $user->id, "Mengubah data pengguna: {$user->name}");
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Data user berhasil diubah');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Hanya admin yang boleh hapus user
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        // Mencegah admin menghapus dirinya sendiri
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun anda sendiri');
        }
        
        $userName = $user->name;
        $userId = $user->id;
        
        // Hapus user
        $user->delete();
        
        // Log aktivitas
        LogService::delete('users', $userId, "Menghapus pengguna: {$userName}");
        
        return redirect()->route('admin.users.index')
            ->with('success', "User {$userName} berhasil dihapus");
    }
}
