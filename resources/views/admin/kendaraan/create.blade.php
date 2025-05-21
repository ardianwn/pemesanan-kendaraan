<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Kendaraan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.kendaraan.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Kendaraan</label>
                            <input type="text" name="nama" id="nama" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('nama') }}" required>
                            @error('nama')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="nomor_plat" class="block text-sm font-medium text-gray-700">Nomor Plat</label>
                            <input type="text" name="nomor_plat" id="nomor_plat" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('nomor_plat') }}" required>
                            @error('nomor_plat')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="jenis" class="block text-sm font-medium text-gray-700">Jenis Kendaraan</label>
                            <input type="text" name="jenis" id="jenis" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('jenis') }}" required>
                            @error('jenis')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="kapasitas" class="block text-sm font-medium text-gray-700">Kapasitas</label>
                            <input type="number" name="kapasitas" id="kapasitas" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('kapasitas') }}" min="1" required>
                            @error('kapasitas')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="digunakan" {{ old('status') == 'digunakan' ? 'selected' : '' }}>Digunakan</option>
                                <option value="perbaikan" {{ old('status') == 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.kendaraan.index') }}" class="mr-4 inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Batal
                            </a>
                            
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
