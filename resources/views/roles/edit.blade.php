<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Role: ') . $role->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.roles.update', $role->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <!-- Nama Role -->
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Nama Role')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $role->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        
                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Deskripsi')" />
                            <textarea id="description" name="description" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" rows="3">{{ old('description', $role->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                        
                        <!-- Status -->
                        <div class="mb-4">
                            <label for="is_active" class="inline-flex items-center">
                                <input id="is_active" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" name="is_active" value="1" {{ old('is_active', $role->is_active) ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">{{ __('Aktif') }}</span>
                            </label>
                        </div>
                        
                        <!-- Permissions -->
                        <div class="mb-6">
                            <h3 class="font-medium text-lg mb-2">Izin Akses:</h3>
                            
                            @foreach($availablePermissions as $group => $permissions)
                                <div class="mb-4">
                                    <h4 class="font-medium text-gray-700 mb-2 capitalize">{{ $group }}</h4>
                                    <div class="pl-4 grid grid-cols-1 md:grid-cols-2 gap-2">
                                        @foreach($permissions as $permission => $label)
                                            <div class="flex items-center">
                                                <input type="checkbox" id="{{ $permission }}" name="permissions[]" value="{{ $permission }}" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                                    {{ in_array($permission, old('permissions', $role->permissions ?? [])) ? 'checked' : '' }}>
                                                <label for="{{ $permission }}" class="ml-2">{{ $label }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
