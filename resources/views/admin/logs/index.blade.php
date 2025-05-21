<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Log Aktivitas Aplikasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.logs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="aktivitas" class="block text-sm font-medium text-gray-700 mb-1">Aktivitas</label>
                            <select name="aktivitas" id="aktivitas" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Aktivitas</option>
                                @foreach($aktivitasList as $aktivitas)
                                    <option value="{{ $aktivitas }}" {{ request('aktivitas') == $aktivitas ? 'selected' : '' }}>{{ ucfirst($aktivitas) }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="tabel" class="block text-sm font-medium text-gray-700 mb-1">Tabel</label>
                            <select name="tabel" id="tabel" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Tabel</option>
                                @foreach($tabelList as $tabel)
                                    <option value="{{ $tabel }}" {{ request('tabel') == $tabel ? 'selected' : '' }}>{{ ucfirst($tabel) }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ request('tanggal_mulai') }}">
                        </div>
                        
                        <div>
                            <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                            <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ request('tanggal_akhir') }}">
                        </div>
                        
                        <div class="mt-6">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Filter
                            </button>
                            
                            <a href="{{ route('admin.logs.index') }}" class="ml-2 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Log List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktivitas</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tabel</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($logs as $log)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($log->user)
                                            <div class="text-sm font-medium text-gray-900">{{ $log->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $log->user->email }}</div>
                                        @else
                                            <div class="text-sm text-gray-500">System</div>
                                        @endif
                                    </td>
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
                                        {{ Str::limit($log->deskripsi, 50) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.logs.show', $log) }}" class="text-indigo-600 hover:text-indigo-900">Detail</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Belum ada data log
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="{{ route('admin.dashboard') }}" class="text-indigo-600 hover:text-indigo-900">
                    &larr; Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
