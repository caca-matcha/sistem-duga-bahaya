<x-app-layout>
    <x-slot name="header">
        <div class="relative py-2">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center shadow-sm border border-red-100/50">
                        <svg class="w-6 h-6 text-red-600" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M9 11c2.2 0 4-1.8 4-4s-1.8-4-4-4-4 1.8-4 4 1.8 4 4 4zm7 0c1.7 0 3-1.3 3-3s-1.3-3-3-3-3 1.3-3 3 1.3 3 3 3zm-7 2c-2.7 0-8 1.3-8 4v2h16v-2c0-2.7-5.3-4-8-4zm7 0c-.3 0-.6 0-.9.1 1.2 1.2 1.9 2.8 1.9 4.6v1.3H22v-2c0-2.7-5.3-4-8-4z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 tracking-tight capitalize leading-none">
                            Manajemen Pengguna</h2>
                        <p class="text-gray-400 font-medium mt-1.5 tracking-tight uppercase text-[12px]">
                            Kelola hak akses dan profil pengguna sistem.</p>
                    </div>
                </div>
            </div>
            <div
                class="absolute -bottom-4 left-0 w-32 h-1 bg-gradient-to-r from-red-600 to-red-400 rounded-full opacity-50">
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-[98%] mx-auto sm:px-6 lg:px-8">
            <!-- Session Status -->
            @if (session('success'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-400 text-green-700 p-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div id="daftar-pengguna" class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">Daftar Pengguna</h3>
                            <p class="text-sm text-gray-500">Kelola dan monitor semua hak akses serta profil pengguna sistem.
                            </p>
                        </div>
                        <div x-data="{ open: false, loading: false }" class="flex flex-nowrap items-center justify-end gap-2 overflow-x-auto pb-1 sm:pb-0">
                            <a href="{{ route('she.users.export', request()->all()) }}"
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-xs font-bold text-gray-700 uppercase tracking-wider hover:bg-gray-50 transition-all whitespace-nowrap">
                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Export
                            </a>
                            <button @click="open = true"
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-xs font-bold text-gray-700 uppercase tracking-wider hover:bg-gray-50 transition-all whitespace-nowrap">
                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Import
                            </button>
                            <a href="{{ route('she.users.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg text-xs font-bold text-white uppercase tracking-wider hover:bg-red-700 transition-all whitespace-nowrap">
                                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Tambah Pengguna
                            </a>

                            <!-- Import Modal (Simplified implementation) -->
                            <div x-show="open" class="fixed z-[60] inset-0 overflow-y-auto" style="display: none;"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                <div
                                    class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                        @click="open = false"></div>
                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                                    <div
                                        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                        <form action="{{ route('she.users.import') }}" method="POST"
                                            enctype="multipart/form-data" @submit="loading = true">
                                            @csrf
                                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                <div class="sm:flex sm:items-start">
                                                    <div
                                                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                                                        <svg class="h-6 w-6 text-emerald-600" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4-4m4 4v12" />
                                                        </svg>
                                                    </div>
                                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                        <h3 class="text-lg leading-6 font-medium text-gray-900">Import
                                                            Pengguna</h3>
                                                        <div class="mt-2">
                                                            <p class="text-sm text-gray-500">Unggah file JSON yang berisi data pengguna.</p>
                                                            <input type="file" name="file" accept=".json" required
                                                                class="mt-4 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                <button type="submit"
                                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm">Import</button>
                                                <button type="button" @click="open = false"
                                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search and Filter Bar -->
                    <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 mb-8">
                        <form action="{{ route('she.users.index') }}" method="GET"
                            class="flex flex-col md:flex-row items-end gap-4">
                            <!-- Search -->
                            <div class="flex-1 w-full">
                                <label
                                    class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2 block ml-1">Search</label>
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="Search name, NPK, unit, or dept..."
                                        class="block w-full pl-10 pr-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:border-red-500 focus:ring-red-500/10">
                                </div>
                            </div>

                            <!-- Role Filter -->
                            <div class="w-full md:w-56">
                                <label
                                    class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2 block ml-1">Role</label>
                                <select name="role"
                                    class="block w-full py-2 pl-3 pr-10 border border-gray-300 bg-white rounded-lg text-sm focus:ring-red-500/10 focus:border-red-500">
                                    <option value="">Semua Role</option>
                                    @foreach(['karyawan', 'she', 'magang'] as $role)
                                        <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                                            {{ strtoupper($role) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Button -->
                            <div class="flex items-center gap-2">
                                <button type="submit"
                                    class="px-6 py-2 bg-gray-800 text-white rounded-lg font-bold text-xs uppercase tracking-wider hover:bg-gray-700 transition-all border-none">
                                    Filter
                                </button>
                                @if (request()->hasAny(['search', 'role']))
                                    <a href="{{ route('she.users.index') }}"
                                        class="p-2 text-gray-400 hover:text-gray-600 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div
                        class="overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm transition-all duration-300">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider w-12">
                                        No</th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                                        Identitas</th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                                        Jabatan</th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                                        Unit & Dept</th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                                        Organization Unit</th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                                        Role</th>
                                    <th scope="col"
                                        class="px-4 py-3 text-right text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @php
                                    $search = request('search');
                                    $highlight = function ($text, $search) {
                                        if (!$search) {
                                            return $text;
                                        }
                                        return preg_replace('/(' . preg_quote($search, '/') . ')/i', '<mark class="bg-yellow-100 text-yellow-900 rounded px-0.5 font-bold">$1</mark>', $text);
                                    };
                                @endphp
                                @forelse ($users as $index => $user)
                                    <tr onclick="if(!event.target.closest('form') && !event.target.closest('a')) window.location='{{ route('she.users.edit', $user) }}'" class="hover:bg-gray-50 transition-colors duration-200 cursor-pointer">
                                        <td class="px-4 py-4 whitespace-nowrap text-xs text-gray-500">
                                            {{ $users->firstItem() + $index }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-xs shrink-0 border border-gray-200">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-bold text-gray-900 leading-none mb-1">
                                                        {!! $highlight($user->name, $search) !!}
                                                    </div>
                                                    <div class="text-[11px] text-gray-500">
                                                        NPK: {!! $highlight($user->npk ?? '-', $search) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-xs font-semibold text-gray-700 leading-none mb-1">
                                                {{ $user->position ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-[11px] font-semibold text-gray-800 leading-none mb-1">
                                                {!! $highlight($user->division ?? '-', $search) !!}
                                            </div>
                                            <div class="text-[11px] font-medium text-gray-600 mb-0.5">
                                                {!! $highlight($user->department ?? '-', $search) !!}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-[11px] font-semibold text-gray-800 leading-none mb-1">
                                                {!! $highlight($user->organization_unit ?? '-', $search) !!}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            @php
                                                $roleClasses = [
                                                    'she' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                                    'magang' => 'bg-gray-100 text-gray-600 border-gray-200',
                                                    'karyawan' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                ][$user->role] ?? 'bg-gray-100 text-gray-600 border-gray-200';
                                            @endphp
                                            <span
                                                class="px-2 py-0.5 rounded text-[10px] font-bold uppercase border {{ $roleClasses }}">
                                                {{ $user->role }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('she.users.edit', $user) }}"
                                                    class="text-gray-400 hover:text-blue-600 transition-colors p-1 rounded hover:bg-white border border-transparent hover:border-gray-200 shadow-none hover:shadow-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </a>
                                                @if(auth()->id() !== $user->id)
                                                    <form action="{{ route('she.users.destroy', $user) }}" method="POST"
                                                        class="inline-block" onsubmit="return confirm('Hapus user ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-gray-400 hover:text-red-600 transition-colors p-1 rounded hover:bg-white border border-transparent hover:border-gray-200 shadow-none hover:shadow-sm">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center text-gray-400">
                                                <p class="text-sm font-bold uppercase tracking-widest">Pengguna tidak ditemukan
                                                </p>
                                                <p class="text-xs mt-1">Sesuaikan kriteria pencarian atau filter Anda.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-10">
                        {{ $users->fragment('daftar-pengguna')->links('vendor.pagination.premium') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>