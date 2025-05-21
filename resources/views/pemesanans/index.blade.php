<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pemesanan Kendaraan') }}
            </h2>
            <a href="{{ route('pemesanan.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Buat Pemesanan Baru') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Filter Pemesanan</h3>
                    <form action="{{ route('pemesanan.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <x-input-label for="search" :value="__('Kata Kunci')" />
                            <x-text-input id="search" name="search" type="text" class="mt-1 block w-full" value="{{ request('search') }}" placeholder="Tujuan, catatan, etc." />
                        </div>
                        
                        <div>
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        
                        <div>
                            <x-input-label for="tanggal_mulai" :value="__('Dari Tanggal')" />
                            <x-text-input id="tanggal_mulai" name="tanggal_mulai" type="date" class="mt-1 block w-full" value="{{ request('tanggal_mulai') }}" />
                        </div>
                        
                        <div>
                            <x-input-label for="tanggal_selesai" :value="__('Sampai Tanggal')" />
                            <x-text-input id="tanggal_selesai" name="tanggal_selesai" type="date" class="mt-1 block w-full" value="{{ request('tanggal_selesai') }}" />
                        </div>
                        
                        <div class="md:col-span-4 flex items-center justify-end">
                            <a href="{{ route('pemesanan.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                                Reset
                            </a>
                            <x-primary-button>
                                {{ __('Filter') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($pemesanans->isEmpty())
                        <p class="text-center py-4">Tidak ada pemesanan yang ditemukan.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kendaraan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($pemesanans as $pemesanan)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $pemesanan->tanggal_pemesanan }}</div>
                                                <div class="text-sm text-gray-500">{{ $pemesanan->jam_pemesanan }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $pemesanan->kendaraan->nama }}</div>
                                                <div class="text-sm text-gray-500">{{ $pemesanan->kendaraan->nomor_plat }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    @if ($pemesanan->driver)
                                                        {{ $pemesanan->driver->nama }}
                                                    @else
                                                        <span class="text-gray-500">Tanpa Driver</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($pemesanan->status == 'pending') bg-yellow-100 text-yellow-800 
                                                    @elseif($pemesanan->status == 'disetujui') bg-green-100 text-green-800 
                                                    @elseif($pemesanan->status == 'ditolak') bg-red-100 text-red-800
                                                    @elseif($pemesanan->status == 'selesai') bg-blue-100 text-blue-800 
                                                    @endif">
                                                    {{ ucfirst($pemesanan->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('pemesanan.show', $pemesanan) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $pemesanans->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
