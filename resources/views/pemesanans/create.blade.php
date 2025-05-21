<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Pemesanan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('pemesanan.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-6">
                            <a href="{{ route('pemesanan.index') }}" class="text-indigo-600 hover:text-indigo-900">
                                &larr; Kembali ke daftar pemesanan
                            </a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pemesanan</h3>
                                
                                <div class="mb-4">
                                    <x-input-label for="tanggal_pemesanan" :value="__('Tanggal Pemesanan')" />
                                    <x-text-input id="tanggal_pemesanan" name="tanggal_pemesanan" type="date" class="mt-1 block w-full" required autofocus />
                                    <x-input-error :messages="$errors->get('tanggal_pemesanan')" class="mt-2" />
                                </div>
                                
                                <div class="mb-4">
                                    <x-input-label for="jam_pemesanan" :value="__('Jam Pemesanan')" />
                                    <x-text-input id="jam_pemesanan" name="jam_pemesanan" type="time" class="mt-1 block w-full" required />
                                    <x-input-error :messages="$errors->get('jam_pemesanan')" class="mt-2" />
                                </div>
                                
                                <div class="mb-4">
                                    <x-input-label for="durasi_pemesanan" :value="__('Durasi Pemesanan (jam)')" />
                                    <x-text-input id="durasi_pemesanan" name="durasi_pemesanan" type="number" min="1" class="mt-1 block w-full" required />
                                    <x-input-error :messages="$errors->get('durasi_pemesanan')" class="mt-2" />
                                </div>
                                
                                <div class="mb-4">
                                    <x-input-label for="tujuan" :value="__('Tujuan')" />
                                    <x-text-input id="tujuan" name="tujuan" type="text" class="mt-1 block w-full" required />
                                    <x-input-error :messages="$errors->get('tujuan')" class="mt-2" />
                                </div>
                                
                                <div class="mb-4">
                                    <x-input-label for="keperluan" :value="__('Keperluan')" />
                                    <textarea id="keperluan" name="keperluan" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required></textarea>
                                    <x-input-error :messages="$errors->get('keperluan')" class="mt-2" />
                                </div>
                                
                                <div class="mb-4">
                                    <x-input-label for="jumlah_penumpang" :value="__('Jumlah Penumpang')" />
                                    <x-text-input id="jumlah_penumpang" name="jumlah_penumpang" type="number" min="1" class="mt-1 block w-full" required />
                                    <x-input-error :messages="$errors->get('jumlah_penumpang')" class="mt-2" />
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Kendaraan & Driver</h3>
                                
                                <div class="mb-4">
                                    <x-input-label for="kendaraan_id" :value="__('Kendaraan')" />
                                    <select id="kendaraan_id" name="kendaraan_id" 
                                        class="select2-ajax mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        data-url="{{ route('kendaraan.select') }}"
                                        data-placeholder="Pilih Kendaraan">
                                        @if(old('kendaraan_id') && isset($selectedKendaraan))
                                            <option value="{{ old('kendaraan_id') }}" selected>{{ $selectedKendaraan->nama }} ({{ $selectedKendaraan->nomor_plat }}) - Kapasitas: {{ $selectedKendaraan->kapasitas }} orang</option>
                                        @endif
                                    </select>
                                    <x-input-error :messages="$errors->get('kendaraan_id')" class="mt-2" />
                                    <p class="mt-1 text-sm text-gray-500">Kendaraan yang tersedia pada tanggal yang dipilih</p>
                                </div>
                                
                                <div class="mb-4">
                                    <x-input-label for="driver_id" :value="__('Driver (Opsional)')" />
                                    <select id="driver_id" name="driver_id" 
                                        class="select2-ajax mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        data-url="{{ route('driver.select') }}"
                                        data-placeholder="Pilih Driver">
                                        @if(old('driver_id') && isset($selectedDriver))
                                            <option value="{{ old('driver_id') }}" selected>{{ $selectedDriver->nama }} - {{ $selectedDriver->telepon }}</option>
                                        @endif
                                    </select>
                                    <x-input-error :messages="$errors->get('driver_id')" class="mt-2" />
                                    <p class="mt-1 text-sm text-gray-500">Driver yang tersedia pada tanggal yang dipilih</p>
                                </div>
                                
                                <div class="mb-4">
                                    <x-input-label for="catatan" :value="__('Catatan (Opsional)')" />
                                    <textarea id="catatan" name="catatan" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                                    <x-input-error :messages="$errors->get('catatan')" class="mt-2" />
                                </div>
                                
                                <div class="mb-4">
                                    <x-input-label for="document" :value="__('Dokumen Pendukung (PDF, max 500KB)')" />
                                    <input id="document" name="document" type="file" accept="application/pdf" 
                                        class="mt-1 block w-full text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                                    <p class="mt-1 text-sm text-gray-500">Format: PDF dengan ukuran antara 100KB - 500KB</p>
                                    <x-input-error :messages="$errors->get('document')" class="mt-2" />
                                </div>

                                @if(Auth::user()->role === 'admin')
                                <div class="mt-8">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Persetujuan Berjenjang</h3>
                                    <p class="text-sm text-gray-600 mb-2">Pilih minimal 2 approver secara berurutan:</p>
                                    
                                    <div class="mb-4">
                                        <div id="approvers-container">
                                            <div class="flex items-center mb-2">
                                                <div class="w-8 h-8 rounded-full bg-blue-500 text-white flex items-center justify-center mr-2">1</div>
                                                <select name="approvers[]" 
                                                    class="select2-ajax mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                                    data-url="{{ route('approver.select') }}"
                                                    data-placeholder="Pilih Approver Level 1" required>
                                                    @if(old('approvers.0') && isset($selectedApprovers[0]))
                                                        <option value="{{ old('approvers.0') }}" selected>{{ $selectedApprovers[0]->name }}</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="flex items-center mb-2">
                                                <div class="w-8 h-8 rounded-full bg-blue-500 text-white flex items-center justify-center mr-2">2</div>
                                                <select name="approvers[]" 
                                                    class="select2-ajax mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                                    data-url="{{ route('approver.select') }}"
                                                    data-placeholder="Pilih Approver Level 2" required>
                                                    @if(old('approvers.1') && isset($selectedApprovers[1]))
                                                        <option value="{{ old('approvers.1') }}" selected>{{ $selectedApprovers[1]->name }}</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <button type="button" id="add-approver" class="mt-2 text-sm text-blue-600 hover:text-blue-800">
                                            + Tambah Approver
                                        </button>
                                    </div>
                                </div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        let approverCount = 2;
                                        
                                        document.getElementById('add-approver').addEventListener('click', function() {
                                            approverCount++;
                                            const container = document.getElementById('approvers-container');
                                            
                                            const newApproverDiv = document.createElement('div');
                                            newApproverDiv.className = 'flex items-center mb-2';
                                            
                                            const levelDiv = document.createElement('div');
                                            levelDiv.className = 'w-8 h-8 rounded-full bg-blue-500 text-white flex items-center justify-center mr-2';
                                            levelDiv.textContent = approverCount;
                                            
                                            const select = document.createElement('select');
                                            select.name = 'approvers[]';
                                            select.className = 'select2-ajax mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm';
                                            select.required = true;
                                            select.dataset.url = "{{ route('approver.select') }}";
                                            select.dataset.placeholder = `Pilih Approver Level ${approverCount}`;
                                            
                                            // Initialize Select2 after appending to DOM
                                            
                                            const removeButton = document.createElement('button');
                                            removeButton.type = 'button';
                                            removeButton.className = 'ml-2 text-red-600 hover:text-red-800';
                                            removeButton.textContent = '✕';
                                            removeButton.addEventListener('click', function() {
                                                container.removeChild(newApproverDiv);
                                            });
                                            
                                            newApproverDiv.appendChild(levelDiv);
                                            newApproverDiv.appendChild(select);
                                            newApproverDiv.appendChild(removeButton);
                                            
                                            container.appendChild(newApproverDiv);
                                            
                                            // Initialize Select2 on the newly added select element
                                            $(select).select2({
                                                placeholder: select.dataset.placeholder,
                                                allowClear: true,
                                                width: '100%',
                                                ajax: {
                                                    url: select.dataset.url,
                                                    dataType: 'json',
                                                    delay: 250,
                                                    data: function (params) {
                                                        return {
                                                            search: params.term,
                                                            page: params.page || 1
                                                        };
                                                    },
                                                    processResults: function (data, params) {
                                                        return data;
                                                    },
                                                    cache: true
                                                }
                                            });
                                        });
                                    });
                                </script>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end">
                            <x-primary-button>
                                {{ __('Ajukan Pemesanan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
