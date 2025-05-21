<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pemesanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('pemesanan.index') }}" class="text-indigo-600 hover:text-indigo-900">
                            &larr; Kembali ke daftar pemesanan
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pemesanan</h3>
                            
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500">Status</p>
                                <p class="mt-1">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($pemesanan->status == 'pending') bg-yellow-100 text-yellow-800 
                                        @elseif($pemesanan->status == 'disetujui') bg-green-100 text-green-800 
                                        @elseif($pemesanan->status == 'ditolak') bg-red-100 text-red-800
                                        @elseif($pemesanan->status == 'selesai') bg-blue-100 text-blue-800 
                                        @endif">
                                        {{ ucfirst($pemesanan->status) }}
                                    </span>
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500">Tanggal & Waktu Pemesanan</p>
                                <p class="mt-1">{{ $pemesanan->tanggal_pemesanan }} - {{ $pemesanan->jam_pemesanan }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500">Durasi Pemesanan</p>
                                <p class="mt-1">{{ $pemesanan->durasi_pemesanan }} jam</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500">Tujuan</p>
                                <p class="mt-1">{{ $pemesanan->tujuan }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500">Keperluan</p>
                                <p class="mt-1">{{ $pemesanan->keperluan }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500">Jumlah Penumpang</p>
                                <p class="mt-1">{{ $pemesanan->jumlah_penumpang }} orang</p>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Kendaraan & Driver</h3>
                            
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500">Kendaraan</p>
                                <p class="mt-1">{{ $pemesanan->kendaraan->nama }} ({{ $pemesanan->kendaraan->nomor_plat }})</p>
                                <p class="text-sm text-gray-500">Kapasitas: {{ $pemesanan->kendaraan->kapasitas }} orang</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500">Driver</p>
                                @if ($pemesanan->driver)
                                    <p class="mt-1">{{ $pemesanan->driver->nama }}</p>
                                    <p class="text-sm text-gray-500">{{ $pemesanan->driver->telepon }}</p>
                                @else
                                    <p class="mt-1 text-gray-500">Tanpa Driver</p>
                                @endif
                            </div>
                            
                            @if ($pemesanan->catatan)
                                <div class="mb-4">
                                    <p class="text-sm font-medium text-gray-500">Catatan</p>
                                    <p class="mt-1">{{ $pemesanan->catatan }}</p>
                                </div>
                            @endif
                            
                            @if ($pemesanan->document_path)
                                <div class="mb-4">
                                    <p class="text-sm font-medium text-gray-500">Dokumen Pendukung</p>
                                    <div class="mt-2 flex items-center">
                                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        <a href="{{ asset('storage/' . $pemesanan->document_path) }}" 
                                           target="_blank" 
                                           class="ml-2 text-blue-600 hover:text-blue-800 underline">
                                            {{ $pemesanan->document_name }} ({{ round($pemesanan->document_size/1024) }} KB)
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @if ($pemesanan->status === 'disetujui' && !$pemesanan->is_finished)
                        <div class="mt-8 border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Selesaikan Pemesanan</h3>
                            <form action="{{ route('pemesanan.finish', $pemesanan) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Konfirmasi Selesai
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
