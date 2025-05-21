<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Log #' . $log->id) }}
            </h2>
            
            <a href="{{ route('admin.logs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-3 border-b pb-2">Informasi Log</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">ID Log:</span>
                                    <p class="mt-1">{{ $log->id }}</p>
                                </div>
                                
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Waktu Aktivitas:</span>
                                    <p class="mt-1">{{ $log->created_at->format('d/m/Y H:i:s') }}</p>
                                </div>
                                
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Jenis Aktivitas:</span>
                                    <p class="mt-1">
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
                                    </p>
                                </div>
                                
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Tabel:</span>
                                    <p class="mt-1">{{ $log->tabel ? ucfirst($log->tabel) : '-' }}</p>
                                </div>
                                
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">ID Data:</span>
                                    <p class="mt-1">{{ $log->id_data ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold mb-3 border-b pb-2">User & Deskripsi</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Dilakukan Oleh:</span>
                                    @if($log->user)
                                        <p class="mt-1">{{ $log->user->name }} ({{ $log->user->email }})</p>
                                        <p class="text-sm text-gray-500">Role: {{ ucfirst($log->user->role) }}</p>
                                    @else
                                        <p class="mt-1 text-gray-500">System</p>
                                    @endif
                                </div>
                                
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Deskripsi:</span>
                                    <div class="mt-1 p-4 bg-gray-50 rounded-md">
                                        <p>{{ $log->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Navigasi Log -->
                    <div class="mt-8 flex justify-between items-center">
                        @if($log->id > 1)
                        <a href="{{ route('admin.logs.show', $log->id - 1) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition ease-in-out duration-150">
                            &larr; Log Sebelumnya
                        </a>
                        @else
                        <div></div>
                        @endif
                        
                        <a href="{{ route('admin.logs.show', $log->id + 1) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition ease-in-out duration-150">
                            Log Berikutnya &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
