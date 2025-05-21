<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Export Pemesanan Kendaraan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Filter Data Export</h3>
                    
                    <form action="{{ route('admin.pemesanan.export') }}" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                                <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <p class="mt-1 text-sm text-gray-500">Biarkan kosong untuk semua data</p>
                            </div>
                            
                            <div>
                                <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                                <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <p class="mt-1 text-sm text-gray-500">Biarkan kosong untuk semua data</p>
                            </div>
                            
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status Pemesanan</label>
                                <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Semua Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="disetujui">Disetujui</option>
                                    <option value="ditolak">Ditolak</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Export ke Excel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Import & Export Data</h3>
                    
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.pemesanan.export') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Export Semua Data
                        </a>
                        
                        <a href="{{ route('admin.pemesanan.export') }}?template=1" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Download Template Import
                        </a>
                        
                        <a href="{{ route('admin.pemesanan.import-page') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l4 4m0 0l4-4m-4 4V4" />
                            </svg>
                            Import Data
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Informasi Export</h3>
                    
                    <div class="prose max-w-none">
                        <p>File Excel yang dihasilkan akan berisi informasi berikut:</p>
                        <ul>
                            <li>ID Pemesanan</li>
                            <li>Nama Pemesan</li>
                            <li>Email Pemesan</li>
                            <li>Kendaraan yang Dipesan</li>
                            <li>Nomor Plat Kendaraan</li>
                            <li>Driver yang Ditugaskan</li>
                            <li>Tanggal dan Waktu Mulai</li>
                            <li>Tanggal dan Waktu Selesai</li>
                            <li>Tujuan</li>
                            <li>Keperluan</li>
                            <li>Status Pemesanan</li>
                            <li>Tanggal Pemesanan</li>
                            <li>Status Persetujuan</li>
                            <li>Tanggal Persetujuan/Penolakan</li>
                            <li>Catatan Persetujuan/Penolakan</li>
                        </ul>
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
