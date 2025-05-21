<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Role: ') . $role->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Role Details Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Role</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Nama Role</p>
                            <p class="mt-1">{{ $role->name }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Slug</p>
                            <p class="mt-1">{{ $role->slug }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Status</p>
                            <p class="mt-1">
                                @if($role->is_active)
                                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Aktif</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Tidak Aktif</span>
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tanggal Dibuat</p>
                            <p class="mt-1">{{ $role->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <p class="text-sm font-medium text-gray-500">Deskripsi</p>
                        <p class="mt-1">{{ $role->description ?: 'Tidak ada deskripsi' }}</p>
                    </div>
                    
                    <div class="mt-6">
                        <h4 class="font-medium text-gray-700 mb-2">Izin Akses:</h4>
                        
                        @if(empty($role->permissions))
                            <p class="text-gray-500 italic">Tidak ada izin akses yang ditetapkan</p>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                                @foreach($role->permissions as $permission)
                                    <div class="px-2 py-1 bg-blue-50 text-blue-700 rounded text-sm">
                                        {{ $permission }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex mt-6">
                        <a href="{{ route('admin.roles.edit', $role->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                            Edit Role
                        </a>
                        <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Users with this Role -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Pengguna dengan Role Ini</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">Nama</th>
                                    <th class="py-2 px-4 border-b text-left">Email</th>
                                    <th class="py-2 px-4 border-b text-left">Username</th>
                                    <th class="py-2 px-4 border-b text-left">Tanggal Dibuat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td class="py-2 px-4 border-b">{{ $user->name }}</td>
                                        <td class="py-2 px-4 border-b">{{ $user->email }}</td>
                                        <td class="py-2 px-4 border-b">{{ $user->username }}</td>
                                        <td class="py-2 px-4 border-b">{{ $user->created_at->format('d M Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4 px-4 border-b text-center">Tidak ada pengguna dengan role ini</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
