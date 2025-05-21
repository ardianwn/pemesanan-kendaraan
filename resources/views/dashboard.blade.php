<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Status Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-4xl font-bold text-purple-600">{{ $stats['total_pemesanan'] }}</div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Pemesanan</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-3xl font-bold text-yellow-500">{{ $stats['pemesanan_pending'] }}</div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Menunggu</div>
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
            </div>
            
            <!-- Action buttons -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <a href="{{ route('pemesanan.create') }}" class="block bg-white p-6 overflow-hidden shadow-sm sm:rounded-lg hover:bg-gray-50 transition">
                    <div class="text-lg font-medium text-blue-600">Buat Pemesanan Baru</div>
                    <div class="text-sm text-gray-500">Ajukan permintaan pemesanan kendaraan baru</div>
                </a>
                
                <a href="{{ route('pemesanan.index') }}" class="block bg-white p-6 overflow-hidden shadow-sm sm:rounded-lg hover:bg-gray-50 transition">
                    <div class="text-lg font-medium text-green-600">Riwayat Pemesanan</div>
                    <div class="text-sm text-gray-500">Lihat semua riwayat pemesanan Anda</div>
                </a>
            </div>

            <!-- Recent Pemesanan -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Pemesanan Terbaru Anda</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kendaraan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tujuan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($pemesananTerbaru as $pemesanan)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pemesanan->tanggal_pemesanan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($pemesanan->kendaraan)
                                            <div class="text-sm font-medium text-gray-900">{{ $pemesanan->kendaraan->nama }}</div>
                                            <div class="text-xs text-gray-500">{{ $pemesanan->kendaraan->nomor_plat }}</div>
                                        @else
                                            <div class="text-sm text-gray-500">Kendaraan tidak tersedia</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pemesanan->tujuan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($pemesanan->driver)
                                            {{ $pemesanan->driver->nama }}
                                        @else
                                            <span class="text-gray-400">Driver belum ditentukan</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($pemesanan->status === 'pending')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Menunggu
                                            </span>
                                        @elseif($pemesanan->status === 'disetujui')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Disetujui
                                            </span>
                                        @elseif($pemesanan->status === 'ditolak')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Ditolak
                                            </span>
                                        @elseif($pemesanan->status === 'selesai')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Selesai
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('pemesanan.show', $pemesanan) }}" class="text-indigo-600 hover:text-indigo-900">Detail</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Belum ada pemesanan
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if(count($pemesananTerbaru) > 0)
                    <div class="mt-4 text-right">
                        <a href="{{ route('pemesanan.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                            Lihat semua pemesanan &rarr;
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
