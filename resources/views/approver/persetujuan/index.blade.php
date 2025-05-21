<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Persetujuan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Filter -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('approver.persetujuan.index') }}" method="GET" class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                        <div class="flex-1">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        
                        <div class="flex-1">
                            <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ request('tanggal_mulai') }}">
                        </div>
                        
                        <div class="flex-1">
                            <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                            <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ request('tanggal_akhir') }}">
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemesan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kendaraan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($persetujuans as $persetujuan)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $persetujuan->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $persetujuan->pemesanan->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $persetujuan->pemesanan->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $persetujuan->pemesanan->kendaraan->nama }}</div>
                                        <div class="text-sm text-gray-500">{{ $persetujuan->pemesanan->kendaraan->nomor_plat }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($persetujuan->pemesanan->tanggal_mulai)->format('d M Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($persetujuan->pemesanan->tanggal_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($persetujuan->pemesanan->tanggal_selesai)->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($persetujuan->status == 'pending')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @elseif($persetujuan->status == 'disetujui')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Disetujui
                                            </span>
                                        @elseif($persetujuan->status == 'ditolak')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('approver.persetujuan.show', $persetujuan) }}" class="text-indigo-600 hover:text-indigo-900">Detail</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Belum ada data persetujuan
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $persetujuans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
