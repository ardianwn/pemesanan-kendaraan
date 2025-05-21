<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Available permissions in the system
     */
    protected function getAvailablePermissions()
    {
        return [
            'users' => [
                'users.view' => 'Melihat data pengguna',
                'users.create' => 'Membuat pengguna baru',
                'users.edit' => 'Mengubah data pengguna',
                'users.delete' => 'Menghapus pengguna',
            ],
            'roles' => [
                'roles.view' => 'Melihat data role',
                'roles.create' => 'Membuat role baru',
                'roles.edit' => 'Mengubah data role',
                'roles.delete' => 'Menghapus role',
            ],
            'kendaraan' => [
                'kendaraan.view' => 'Melihat data kendaraan',
                'kendaraan.create' => 'Menambah kendaraan baru',
                'kendaraan.edit' => 'Mengubah data kendaraan',
                'kendaraan.delete' => 'Menghapus kendaraan',
            ],
            'drivers' => [
                'drivers.view' => 'Melihat data driver',
                'drivers.create' => 'Menambah driver baru',
                'drivers.edit' => 'Mengubah data driver',
                'drivers.delete' => 'Menghapus driver',
            ],
            'pemesanan' => [
                'pemesanan.view' => 'Melihat semua pemesanan',
                'pemesanan.create' => 'Membuat pemesanan baru',
                'pemesanan.edit' => 'Mengubah pemesanan',
                'pemesanan.delete' => 'Menghapus pemesanan',
                'pemesanan.approve' => 'Menyetujui pemesanan',
            ],
            'laporan' => [
                'laporan.view' => 'Melihat laporan',
                'laporan.export' => 'Mengekspor laporan',
            ],
        ];
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::latest()->paginate(10);
        
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $availablePermissions = $this->getAvailablePermissions();
        return view('roles.create', compact('availablePermissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
        ]);
        
        // Create slug from name
        $slug = Str::slug($request->name);
        
        // Create role
        $role = Role::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'permissions' => $request->permissions ?? [],
            'is_active' => true,
        ]);
        
        // Log the activity
        LogService::create('role', $role->id, 'Role baru dibuat: ' . $role->name);
        
        return redirect()->route('roles.index')
            ->with('success', 'Role berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::findOrFail($id);
        $users = $role->users()->paginate(10);
        
        return view('roles.show', compact('role', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        $availablePermissions = $this->getAvailablePermissions();
        
        return view('roles.edit', compact('role', 'availablePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);
        
        $request->validate([
            'name' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('roles')->ignore($role->id)
            ],
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'is_active' => 'boolean',
        ]);
        
        // Update role
        $role->update([
            'name' => $request->name,
            'description' => $request->description,
            'permissions' => $request->permissions ?? [],
            'is_active' => $request->is_active ?? false,
        ]);
        
        // Log the activity
        LogService::update('role', $role->id, 'Role diperbarui: ' . $role->name);
        
        return redirect()->route('roles.index')
            ->with('success', 'Role berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        
        // Check if role has users
        $userCount = $role->users()->count();
        
        if ($userCount > 0) {
            return redirect()->route('roles.index')
                ->with('error', "Role {$role->name} tidak dapat dihapus karena masih memiliki {$userCount} pengguna.");
        }
        
        // Log before delete
        LogService::delete('role', $role->id, 'Role dihapus: ' . $role->name);
        
        // Delete role
        $role->delete();
        
        return redirect()->route('roles.index')
            ->with('success', 'Role berhasil dihapus');
    }

    /**
     * Get roles for Select2 dropdown
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getForSelect(Request $request)
    {
        $search = $request->input('search');
        $page = $request->input('page', 1);
        $limit = 10;
        
        $query = Role::where('is_active', true);
        
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }
        
        $total = $query->count();
        $roles = $query->skip(($page - 1) * $limit)
                      ->take($limit)
                      ->get(['id', 'name as text']);
        
        return response()->json([
            'results' => $roles,
            'pagination' => [
                'more' => ($page * $limit) < $total
            ]
        ]);
    }
}
