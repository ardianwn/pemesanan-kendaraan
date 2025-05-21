<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Pengguna: ' . $user->name) }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                    Edit
                </a>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Informasi Pengguna</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">ID:</span>
                                    <p class="mt-1">{{ $user->id }}</p>
                                </div>
                                
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Nama:</span>
                                    <p class="mt-1">{{ $user->name }}</p>
                                </div>
                                
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Username:</span>
                                    <p class="mt-1">{{ $user->username }}</p>
                                </div>
                                
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Email:</span>
                                    <p class="mt-1">{{ $user->email }}</p>
                                </div>
                                
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Role:</span>
                                    <p class="mt-1">
                                        @if($user->role == 'admin')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Admin
                                            </span>
                                        @elseif($user->role == 'approver')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Approver
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                User
                                            </span>
                                        @endif
                                    </p>
                                </div>
                                
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Terdaftar:</span>
                                    <p class="mt-1">{{ $user->created_at->format('d/m/Y H:i:s') }}</p>
                                </div>
                                
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Terakhir diperbarui:</span>
                                    <p class="mt-1">{{ $user->updated_at->format('d/m/Y H:i:s') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Statistik Aktivitas</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Total Pemesanan:</span>
                                    <p class="mt-1 text-2xl font-bold">{{ $user->pemesanans->count() }}</p>
                                </div>
                                
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Total Log Aktivitas:</span>
                                    <p class="mt-1 text-2xl font-bold">{{ $user->logs->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Aktivitas Terbaru -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Aktivitas Terbaru</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktivitas</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tabel</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($logs as $log)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                            $color = match($log->aktivitas) {
                                                'create' => 'green',
                                                'update' => 'blue',
                                                'delete' => 'red',
                                                'login' => 'purple',
                                                'logout' => 'gray',
                                                'approve' => 'green',
                                                'reject' => 'red',
                                                'export' => 'orange',
                                                default => 'gray'
                                            };
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $color }}-100 text-{{ $color }}-800">
                                                {{ ucfirst($log->aktivitas) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->tabel ? ucfirst($log->tabel) : '-' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ Str::limit($log->deskripsi, 100) }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Belum ada aktivitas tercatat
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('admin.logs.index', ['user_id' => $user->id]) }}" class="text-indigo-600 hover:text-indigo-900">
                                Lihat semua aktivitas pengguna &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
