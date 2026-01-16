<x-app-layout>
<x-slot name="header">
<div class="flex items-center justify-between">
    <div>
        <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
            {{ __('Maps Management') }}
        </h2>
        <p class="text-sm text-gray-500 mt-1">Kelola layout peta risiko untuk pabrik dan gedung.</p>
    </div>
</div>
</x-slot>

<div class="py-6 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <!-- Alerts Section -->
        @if (session('success'))
            <div x-data="{ show: true }" 
                 x-init="setTimeout(() => show = false, 5000)" 
                 x-show="show"
                 x-transition:leave="transition ease-in duration-300" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0"
                 class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50 border border-green-200 shadow-sm" role="alert">
                <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <div class="ml-3 text-sm font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if (session('error') || $errors->any())
            <div class="flex p-4 mb-4 text-red-800 rounded-lg bg-red-50 border border-red-200 shadow-sm" role="alert">
                <svg class="flex-shrink-0 w-5 h-5 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                <div class="ml-3">
                    <span class="font-bold text-sm">Terjadi Kesalahan!</span>
                    <ul class="mt-1 ml-4 list-disc list-inside text-xs">
                        @if(session('error')) <li>{{ session('error') }}</li> @endif
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Section 1: Pabrik Map (Primary Map) -->
        <section>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-700 uppercase tracking-wider">Layout Utama Pabrik</h3>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                @if (!$existingPabrikMap)
                    <div class="p-10 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 mb-4 text-yellow-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Peta Pabrik</h4>
                        <p class="text-gray-500 max-w-sm mx-auto mb-6">Buat layout utama untuk keseluruhan pabrik. Anda hanya diperbolehkan memiliki satu peta tipe Pabrik.</p>
                        <a href="{{ route('she.maps.create', ['type' => 'Pabrik']) }}" class="inline-flex items-center px-6 py-3 bg-gray-900 text-white font-bold rounded-lg hover:bg-gray-800 transition shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            Inisialisasi Peta Pabrik
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border-b">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Map Detail</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Tipe</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase text-center">Ukuran Grid</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase">Aksi Pintas</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <tr class="hover:bg-blue-50/30 transition">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-900">{{ $pabrikMap->name }}</div>
                                        <div class="text-xs text-gray-400">ID: #MAP-0{{ $pabrikMap->id }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Pabrik</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2 py-1 bg-gray-100 rounded text-sm font-mono font-bold">{{ $pabrikMap->rows }} × {{ $pabrikMap->cols }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('she.maps.show', $pabrikMap->id) }}" class="inline-flex items-center p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition title="Grid Editor">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                                            </a>
                                            <a href="{{ route('she.maps.edit', $pabrikMap->id) }}" class="inline-flex items-center p-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition" title="Edit Properties">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                            </a>
                                            <div class="relative inline-block group">
                                                <button class="p-2 bg-gray-100 text-gray-600 rounded-lg group-hover:bg-gray-200">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                                </button>
                                                <div class="absolute right-0 bottom-10 hidden group-hover:block bg-white border rounded-lg shadow-xl z-10 w-40 text-left">
                                                    <a href="{{ route('she.maps.export', $pabrikMap->id) }}" class="block px-4 py-2 text-xs hover:bg-gray-50 text-gray-700 font-medium border-b italic">Export JSON</a>
                                                    <a href="{{ route('she.maps.export-risk-excel', $pabrikMap->id) }}" class="block px-4 py-2 text-xs hover:bg-gray-50 text-gray-700 font-medium">Export Excel</a>
                                                </div>
                                            </div>
                                            <form action="{{ route('she.maps.destroy', $pabrikMap->id) }}" method="POST" onsubmit="return confirm('Hapus peta ini beserta seluruh data cell-nya?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 bg-red-50 text-red-400 rounded-lg hover:bg-red-100 hover:text-red-700 transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </section>

        <!-- Section 2: Gedung Maps -->
        <section>
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 uppercase tracking-wider">Daftar Peta Per Gedung</h3>
                    <p class="text-xs text-gray-500 mt-1 italic">Detail layout spesifik untuk setiap gedung operasional.</p>
                </div>
                <a href="{{ route('she.maps.create', ['type' => 'Gedung']) }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-xs font-bold rounded-lg shadow hover:bg-red-700 focus:ring-4 focus:ring-red-200 transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                    TAMBAH GEDUNG
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Nama Peta</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Parent / Lokasi</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Grid</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse ($gedungMaps as $map)
                                <tr class="hover:bg-gray-50 transition group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-2 h-2 rounded-full bg-red-400 mr-3"></div>
                                            <span class="text-sm font-semibold text-gray-800">{{ $map->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($map->parent)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                                {{ $map->parent->name }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400 italic">No Parent</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-xs font-mono text-gray-600 bg-gray-50 px-2 py-1 rounded">{{ $map->rows }}x{{ $map->cols }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end items-center space-x-1 transition-opacity">
                                            <a href="{{ route('she.maps.show', $map->id) }}" class="p-1.5 text-red-600 hover:bg-red-50 rounded" title="Editor">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                                            </a>
                                            <a href="{{ route('she.maps.edit', $map->id) }}" class="p-1.5 text-gray-500 hover:bg-gray-100 rounded" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                            </a>
                                            <form action="{{ route('she.maps.destroy', $map->id) }}" method="POST" onsubmit="return confirm('Hapus peta gedung ini?');" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 rounded">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <p class="text-gray-400 text-sm">Tidak ditemukan peta gedung.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>


</x-app-layout>