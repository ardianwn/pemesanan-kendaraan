<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Import Data Pemesanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-6">
                        <a href="{{ route('admin.pemesanan.index') }}" class="text-indigo-600 hover:text-indigo-900">
                            &larr; Kembali
                        </a>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Panduan Import Data</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="mb-2">Format file yang didukung: <strong>.xlsx, .xls</strong></p>
                            <p class="mb-2">Ukuran file maksimal: <strong>2 MB</strong></p>
                            <p class="mb-4">Kolom yang dibutuhkan dalam file Excel:</p>
                            <ul class="list-disc pl-6 mb-4">
                                <li><strong>email_pengguna</strong> - Email pengguna yang akan melakukan pemesanan</li>
                                <li><strong>kendaraan</strong> - Nomor plat kendaraan yang dipesan</li>
                                <li><strong>tujuan</strong> - Tujuan pemesanan</li>
                                <li><strong>tanggal_mulai</strong> - Format tanggal: YYYY-MM-DD</li>
                                <li><strong>tanggal_selesai</strong> - Format tanggal: YYYY-MM-DD (opsional)</li>
                                <li><strong>nama_driver</strong> - Nama driver (opsional)</li>
                                <li><strong>catatan</strong> - Catatan tambahan (opsional)</li>
                                <li><strong>approver_email</strong> - Email approver (opsional)</li>
                            </ul>
                            <p class="mb-4">Anda dapat <a href="{{ route('admin.pemesanan.export') }}?template=1" class="text-blue-600 hover:underline">mengunduh template</a> untuk memulai.</p>
                        </div>
                    </div>

                    <div class="border-t pt-6">
                        <form action="{{ route('admin.pemesanan.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label for="file" class="block text-sm font-medium text-gray-700 mb-1">File Excel</label>
                                <input type="file" name="file" id="file" accept=".xlsx, .xls" class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-blue-50 file:text-blue-700
                                    hover:file:bg-blue-100" required>
                                @error('file')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mt-6">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Import Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
