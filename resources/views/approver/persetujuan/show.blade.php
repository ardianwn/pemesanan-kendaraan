<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Persetujuan #' . $persetujuan->id) }}
            </h2>
            
            <a href="{{ route('approver.persetujuan.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Kembali
            </a>
        </div>
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <!-- Status Badge -->
                    <div class="mb-6">
                        @if($persetujuan->status == 'pending')
                            <span class="px-4 py-1 inline-flex text-md leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Status: Menunggu Persetujuan
                            </span>
                        @elseif($persetujuan->status == 'disetujui')
                            <span class="px-4 py-1 inline-flex text-md leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Status: Disetujui
                            </span>
                        @elseif($persetujuan->status == 'ditolak')
                            <span class="px-4 py-1 inline-flex text-md leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Status: Ditolak
                            </span>
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Detail Pemesanan -->
                        <div>
                            <h3 class="text-lg font-semibold mb-3 border-b pb-2">Informasi Pemesanan</h3>
                            
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Pemesan:</span>
                                    <p class="text-sm font-medium">{{ $persetujuan->pemesanan->user->name }}</p>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Tanggal Pemesanan:</span>
                                    <p class="text-sm font-medium">{{ \Carbon\Carbon::parse($persetujuan->pemesanan->created_at)->format('d M Y H:i') }}</p>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Waktu Penggunaan:</span>
                                    <p class="text-sm font-medium">
                                        {{ \Carbon\Carbon::parse($persetujuan->pemesanan->tanggal_mulai)->format('d M Y H:i') }} - 
                                        {{ \Carbon\Carbon::parse($persetujuan->pemesanan->tanggal_selesai)->format('d M Y H:i') }}
                                    </p>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Tujuan:</span>
                                    <p class="text-sm font-medium">{{ $persetujuan->pemesanan->tujuan }}</p>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Keperluan:</span>
                                    <p class="text-sm font-medium">{{ $persetujuan->pemesanan->keperluan }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Detail Kendaraan dan Driver -->
                        <div>
                            <h3 class="text-lg font-semibold mb-3 border-b pb-2">Kendaraan dan Driver</h3>
                            
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Nama Kendaraan:</span>
                                    <p class="text-sm font-medium">{{ $persetujuan->pemesanan->kendaraan->nama }}</p>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Nomor Plat:</span>
                                    <p class="text-sm font-medium">{{ $persetujuan->pemesanan->kendaraan->nomor_plat }}</p>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Jenis:</span>
                                    <p class="text-sm font-medium">{{ $persetujuan->pemesanan->kendaraan->jenis }}</p>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Kapasitas:</span>
                                    <p class="text-sm font-medium">{{ $persetujuan->pemesanan->kendaraan->kapasitas }} orang</p>
                                </div>
                                
                                @if($persetujuan->pemesanan->driver)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Driver:</span>
                                    <p class="text-sm font-medium">{{ $persetujuan->pemesanan->driver->nama }}</p>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Nomor HP Driver:</span>
                                    <p class="text-sm font-medium">{{ $persetujuan->pemesanan->driver->nomor_hp }}</p>
                                </div>
                                @else
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Driver:</span>
                                    <p class="text-sm font-medium">Tidak ada driver</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Catatan Persetujuan -->
                    @if($persetujuan->status != 'pending')
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold mb-3 border-b pb-2">Catatan {{ $persetujuan->status == 'disetujui' ? 'Persetujuan' : 'Penolakan' }}</h3>
                        
                        <div class="bg-gray-50 p-4 rounded">
                            <p class="text-sm">{{ $persetujuan->catatan ?: 'Tidak ada catatan' }}</p>
                        </div>
                        
                        <div class="mt-2 text-sm text-gray-500">
                            {{ $persetujuan->status == 'disetujui' ? 'Disetujui' : 'Ditolak' }} pada: {{ \Carbon\Carbon::parse($persetujuan->disetujui_pada)->format('d M Y H:i') }}
                        </div>
                    </div>
                    @endif
                    
                    <!-- Action Buttons -->
                    @if($persetujuan->status == 'pending')
                    <div class="mt-8 flex flex-col sm:flex-row sm:space-x-4 space-y-4 sm:space-y-0">
                        <form action="{{ route('approver.persetujuan.approve', $persetujuan) }}" method="POST" class="sm:w-1/2">
                            @csrf
                            <div class="mb-4">
                                <label for="catatan_approve" class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                                <textarea id="catatan_approve" name="catatan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            </div>
                            
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Setujui Pemesanan
                            </button>
                        </form>
                        
                        <form action="{{ route('approver.persetujuan.reject', $persetujuan) }}" method="POST" class="sm:w-1/2">
                            @csrf
                            <div class="mb-4">
                                <label for="catatan_reject" class="block text-sm font-medium text-gray-700">Alasan Penolakan <span class="text-red-500">*</span></label>
                                <textarea id="catatan_reject" name="catatan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
                                @error('catatan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Tolak Pemesanan
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
