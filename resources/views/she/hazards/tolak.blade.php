<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
             <a href="{{ route('she.hazards.show', $hazard) }}" class="inline-flex items-center justify-center p-2 rounded-full text-gray-500 hover:bg-gray-200 hover:text-gray-700 transition" title="Kembali ke Detail">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div class="flex items-center gap-4">
                <div class="p-3 bg-red-50 rounded-xl border border-red-100 text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h2 class="font-bold text-xl text-gray-900 leading-tight">
                        Tolak Laporan
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Konfirmasi penolakan untuk Laporan Bahaya <span class="font-mono font-bold text-red-600">#{{ $hazard->id }}</span>
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 overflow-hidden border border-gray-100">
                
                {{-- WARNING BANNER --}}
                <div class="bg-orange-50 px-4 py-3 border-b border-orange-100 flex items-start gap-3">
                    <svg class="w-5 h-5 text-orange-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <div>
                        <h4 class="text-sm font-bold text-orange-800">Perhatian</h4>
                        <p class="text-sm text-orange-700 mt-0.5">Anda akan mengubah status laporan ini menjadi <span class="font-bold">Ditolak</span>. Tindakan ini akan menghentikan proses tindak lanjut.</p>
                    </div>
                </div>

                {{-- INFORMASI LAPORAN (Compact) --}}
                <div class="p-4">
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 mb-8">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-200 pb-2">Ringkasan Laporan Awal</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Pelapor</label>
                                <div class="flex items-center gap-2 mt-1">
                                    <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-[10px] font-bold text-gray-600">
                                        {{ substr($hazard->pelapor->name ?? 'U', 0, 1) }}
                                    </div>
                                    <span class="text-base font-medium text-gray-900">{{ $hazard->pelapor->name ?? 'N/A' }}</span>
                                    <span class="text-sm text-gray-500">({{ $hazard->dept }})</span>
                                </div>
                            </div>
                            
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Waktu & Lokasi</label>
                                <p class="text-base font-medium text-gray-900 mt-1">
                                    {{ \Carbon\Carbon::parse($hazard->tgl_observasi)->format('d M Y') }}
                                    <span class="text-gray-400 mx-1">•</span>
                                    {{ $hazard->area_name }}
                                </p>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Deskripsi Bahaya</label>
                                <p class="text-base text-gray-700 mt-1 bg-white p-3 rounded-lg border border-gray-200 italic">
                                    "{{ $hazard->deskripsi_bahaya }}"
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- ERROR MESSAGE --}}
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 text-red-700 border-l-4 border-red-500 rounded-r-md">
                            <div class="flex">
                                <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <ul class="list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    {{-- FORM PENOLAKAN --}}
                    <form method="POST" action="{{ route('she.hazards.updateStatus', $hazard) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="ditolak">

                        <div class="space-y-6">
                            <div>
                                <label for="alasan_penolakan" class="block text-sm font-bold text-gray-800 mb-2">
                                    Alasan Penolakan <span class="text-red-500">*</span>
                                </label>
                                <p class="text-xs text-gray-500 mb-3">Mohon jelaskan secara rinci mengapa laporan ini tidak dapat diproses lebih lanjut agar pelapor memahami keputusannya.</p>
                                
                                <div class="relative">
                                    <textarea id="alasan_penolakan"
                                              name="alasan_penolakan"
                                              rows="5"
                                              class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition-all p-4 bg-white"
                                              placeholder="Contoh: Laporan duplikat, kondisi bukan merupakan bahaya K3, atau informasi lokasi tidak valid..."
                                              required>{{ old('alasan_penolakan') }}</textarea>
                                    
                                    {{-- Optional: Icon penjelas di pojok kanan bawah textarea --}}
                                    <div class="absolute bottom-3 right-3 text-gray-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </div>
                                </div>
                                @error('alasan_penolakan')
                                    <p class="text-sm text-red-600 mt-2 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="border-t border-gray-100 pt-6 flex items-center justify-end gap-3">
                                <a href="{{ route('she.hazards.show', $hazard) }}"
                                   class="px-5 py-2.5 bg-white border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-gray-900 focus:ring-4 focus:ring-gray-100 transition-all">
                                    Batal
                                </a>

                                <button type="submit"
                                        onclick="return confirm('Apakah Anda yakin ingin menolak laporan ini?')"
                                        class="inline-flex items-center px-6 py-2.5 bg-red-600 border border-transparent rounded-xl text-sm font-bold text-white shadow-lg shadow-red-200 hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-100 transition-all">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                    Konfirmasi Tolak Laporan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Help Text / Context --}}
            <p class="text-center text-xs text-gray-400 mt-6">
                Laporan yang ditolak akan diarsipkan dan pelapor akan menerima notifikasi beserta alasannya.
            </p>
        </div>
    </div>
</x-app-layout>