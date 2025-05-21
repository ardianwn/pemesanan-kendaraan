<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Status Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-4xl font-bold text-blue-600">{{ $stats['total_kendaraan'] }}</div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Kendaraan</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-4xl font-bold text-green-600">{{ $stats['total_driver'] }}</div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Driver</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-4xl font-bold text-purple-600">{{ $stats['total_pemesanan'] }}</div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Pemesanan</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-4xl font-bold text-orange-600">{{ $stats['total_users'] }}</div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Pengguna</div>
                    </div>
                </div>
            </div>
            
            <!-- Pemesanan Status Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-3xl font-bold text-yellow-500">{{ $stats['pemesanan_pending'] }}</div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Menunggu Persetujuan</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-3xl font-bold text-green-500">{{ $stats['pemesanan_disetujui'] }}</div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Disetujui</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-3xl font-bold text-red-500">{{ $stats['pemesanan_ditolak'] }}</div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Ditolak</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-3xl font-bold text-blue-500">{{ $stats['pemesanan_selesai'] }}</div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Selesai</div>
                    </div>
                </div>
            </div>
            
            <!-- Action buttons -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <a href="{{ route('admin.kendaraan.index') }}" class="block bg-white p-6 overflow-hidden shadow-sm sm:rounded-lg hover:bg-gray-50 transition">
                    <div class="text-lg font-medium text-blue-600">Kelola Kendaraan</div>
                    <div class="text-sm text-gray-500">Tambah, edit, dan hapus data kendaraan</div>
                </a>
                
                <a href="{{ route('admin.driver.index') }}" class="block bg-white p-6 overflow-hidden shadow-sm sm:rounded-lg hover:bg-gray-50 transition">
                    <div class="text-lg font-medium text-green-600">Kelola Driver</div>
                    <div class="text-sm text-gray-500">Tambah, edit, dan hapus data driver</div>
                </a>
                
                <a href="{{ route('admin.pemesanan.export-page') }}" class="block bg-white p-6 overflow-hidden shadow-sm sm:rounded-lg hover:bg-gray-50 transition">
                    <div class="text-lg font-medium text-purple-600">Export Pemesanan</div>
                    <div class="text-sm text-gray-500">Export data pemesanan ke Excel</div>
                </a>
                
                <a href="{{ route('admin.logs.index') }}" class="block bg-white p-6 overflow-hidden shadow-sm sm:rounded-lg hover:bg-gray-50 transition">
                    <div class="text-lg font-medium text-orange-600">Log Aplikasi</div>
                    <div class="text-sm text-gray-500">Lihat aktivitas sistem dan pengguna</div>
                </a>
            </div>
            
            <!-- Recent orders -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Pemesanan Terbaru</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemesan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kendaraan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tujuan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($pemesananTerbaru as $index => $pemesanan)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $pemesanan->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $pemesanan->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $pemesanan->kendaraan->nama }}</div>
                                        <div class="text-sm text-gray-500">{{ $pemesanan->kendaraan->nomor_plat }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $pemesanan->tujuan }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($pemesanan->status == 'pending')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @elseif($pemesanan->status == 'disetujui')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Disetujui
                                            </span>
                                        @elseif($pemesanan->status == 'ditolak')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Ditolak
                                            </span>
                                        @elseif($pemesanan->status == 'selesai')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Selesai
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Belum ada data pemesanan
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
