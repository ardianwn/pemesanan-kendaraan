<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Approver') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Status Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-4xl font-bold text-yellow-600">{{ $persetujuanPending }}</div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Menunggu Persetujuan</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-4xl font-bold text-green-600">{{ $persetujuanDisetujui }}</div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Disetujui</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-4xl font-bold text-red-600">{{ $persetujuanDitolak }}</div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Ditolak</div>
                    </div>
                </div>
            </div>
            
            <!-- Action buttons -->
            <div class="mb-6">
                <a href="{{ route('approver.persetujuan.index') }}" class="block bg-white p-6 overflow-hidden shadow-sm sm:rounded-lg hover:bg-gray-50 transition">
                    <div class="text-lg font-medium text-blue-600">Daftar Persetujuan</div>
                    <div class="text-sm text-gray-500">Lihat dan proses semua permohonan pemesanan kendaraan</div>
                </a>
            </div>
            
            <!-- Recent orders -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Persetujuan Terbaru</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemesan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kendaraan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tujuan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($persetujuanTerbaru as $index => $persetujuan)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $persetujuan->pemesanan->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $persetujuan->pemesanan->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $persetujuan->pemesanan->kendaraan->nama }}</div>
                                        <div class="text-sm text-gray-500">{{ $persetujuan->pemesanan->kendaraan->nomor_plat }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $persetujuan->pemesanan->tujuan }}
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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
