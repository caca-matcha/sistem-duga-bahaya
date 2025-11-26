<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Laporan Bahaya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Filter and Search Form -->
                    <form method="GET" action="{{ route('she.hazards.index') }}" class="mb-6 bg-gray-50 p-4 rounded-lg shadow-inner">
                        <div class="flex flex-wrap items-end gap-4">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua</option>
                                    <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu Validasi</option>
                                    <!-- Diubah dari 'divalidasi' menjadi 'disetujui' -->
                                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>
                            <div>
                                <label for="area" class="block text-sm font-medium text-gray-700">Area</label>
                                <input type="text" name="area" id="area" value="{{ request('area') }}" placeholder="Cari Area Gedung" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700">Cari</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="ID, Nama, atau Deskripsi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                            </div>
                            <div class="self-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    Filter & Cari
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Hazard List Table -->
                    <div class="overflow-x-auto border rounded-lg shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Pelapor</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Area</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Deskripsi</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Skor Risiko</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($hazards as $hazard)
                                    <tr class="hover:bg-gray-50 transition duration-100">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $hazard->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $hazard->pelapor->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $hazard->area_gedung }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ Str::limit($hazard->deskripsi_bahaya, 50) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <!-- Updated Status Badge Logic for consistency -->
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if ($hazard->status == 'disetujui') bg-green-100 text-green-800
                                                @elseif ($hazard->status == 'diproses') bg-blue-100 text-blue-800
                                                @elseif ($hazard->status == 'ditolak') bg-red-100 text-red-800
                                                @elseif ($hazard->status == 'selesai') bg-purple-100 text-purple-800
                                                @else bg-yellow-100 text-yellow-800 @endif
                                            ">
                                                {{ ucfirst($hazard->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-700">{{ $hazard->skor_resiko }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <a href="{{ route('she.hazards.show', $hazard) }}" class="text-indigo-600 hover:text-indigo-900 transition duration-150 p-2 rounded-md hover:bg-indigo-50">
                                                Lihat Detail
                                            </a>
                                            {{-- Add Edit/Validate actions later --}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-10 whitespace-nowrap text-lg text-gray-500 text-center bg-gray-50 italic">Tidak ada laporan bahaya ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $hazards->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

#tambahan kategori dan resiko yg tampil dari karyawan

  <div>
    <label class="block font-medium mb-1">Kategori Risiko</label>
     <input type="text" id="kategori_resiko" 
    class="w-full rounded-lg font-bold text-black bg-gray-100 cursor-not-allowed" readonly>
</div>