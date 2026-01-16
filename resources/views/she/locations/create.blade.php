<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Tambah Lokasi Baru') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6 bg-white">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6">Form Tambah Lokasi</h3>

                    <form method="POST" action="{{ route('she.locations.store') }}">
                        @csrf

                        <!-- Nama Lokasi -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Lokasi</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- ID Lokasi (String) -->
                        <div class="mb-4">
                            <label for="location_id_string" class="block text-sm font-medium text-gray-700">ID Lokasi (Unik)</label>
                            <input type="text" name="location_id_string" id="location_id_string" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old('location_id_string') }}" required>
                            @error('location_id_string')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tipe Lokasi -->
                        <div class="mb-4">
                            <label for="type_display" class="block text-sm font-medium text-gray-700">Tipe Lokasi</label>
                            <input type="hidden" name="type" id="type" value="Area">
                            <p id="type_display" class="mt-1 block w-full text-gray-800">Area</p>
                        </div>

                        <!-- Peta/Gedung (Dropdown) -->
                        <div class="mb-4">
                            <label for="map_id" class="block text-sm font-medium text-gray-700">Induk Peta/Gedung</label>
                            <select name="map_id" id="map_id" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">-- Pilih Peta/Gedung --</option>
                                @foreach ($maps as $map)
                                    <option value="{{ $map->id }}" @selected(old('map_id') == $map->id)>
                                        {{ $map->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('map_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tombol Submit -->
                        <div class="flex items-center justify-end">
                            <a href="{{ route('she.locations.index') }}" class="mr-4 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">Batal</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Simpan Lokasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
