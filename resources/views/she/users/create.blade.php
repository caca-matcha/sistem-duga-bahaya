<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3 py-1">
            <a href="{{ route('she.users.index') }}"
                class="group inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-400 hover:text-red-600 hover:border-red-100 hover:bg-red-50 transition-all duration-200 shadow-sm"
                title="Kembali">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div class="flex items-center gap-2 text-sm font-bold uppercase tracking-[0.2em] text-gray-400">
                <span>Personnel</span>
                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <h2 class="text-gray-900 font-black tracking-tight tracking-normal capitalize text-xl">
                    Create New User
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-xl border border-gray-200">
                <div class="p-12">
                    <!-- Header Form -->
                    <div
                        class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-gray-100 pb-8">
                        <div>
                            <div
                                class="flex items-center gap-3 mb-2 bg-gray-50 w-fit px-4 py-1.5 rounded-xl border border-gray-100">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m10-1a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="text-xs font-black text-gray-500 uppercase tracking-[0.2em]">Tambah User Baru
                                </h3>
                            </div>
                            <p class="text-sm text-gray-400 font-medium">Lengkapi detail identitas untuk personel baru
                                di
                                dalam sistem.
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('she.users.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Name</label>
                                <input type="text" name="name" id="name"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150"
                                    value="{{ old('name') }}" required>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="npk" class="block text-sm font-semibold text-gray-700 mb-1">NPK</label>
                                <input type="text" name="npk" id="npk"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150"
                                    value="{{ old('npk') }}" required>
                                @error('npk')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="position"
                                    class="block text-sm font-semibold text-gray-700 mb-1">Position</label>
                                <input type="text" name="position" id="position"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150"
                                    value="{{ old('position') }}">
                                @error('position')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-100">
                            <div>
                                <label for="division"
                                    class="block text-sm font-semibold text-gray-700 mb-1">Division</label>
                                <input type="text" name="division" id="division"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150"
                                    value="{{ old('division') }}">
                            </div>
                            <div>
                                <label for="department"
                                    class="block text-sm font-semibold text-gray-700 mb-1">Department</label>
                                <input type="text" name="department" id="department"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150"
                                    value="{{ old('department') }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="organization_unit"
                                    class="block text-sm font-semibold text-gray-700 mb-1">Organization Unit</label>
                                <input type="text" name="organization_unit" id="organization_unit"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150"
                                    value="{{ old('organization_unit') }}">
                            </div>
                            <div>
                                <label for="job_family" class="block text-sm font-semibold text-gray-700 mb-1">Job
                                    Family</label>
                                <input type="text" name="job_family" id="job_family"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150"
                                    value="{{ old('job_family') }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-100">
                            <div>
                                <label for="password"
                                    class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                                <input type="password" name="password" id="password"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150"
                                    required>
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="password_confirmation"
                                    class="block text-sm font-semibold text-gray-700 mb-1">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150"
                                    required>
                            </div>
                        </div>

                        <div>
                            <label for="role" class="block text-sm font-semibold text-gray-700 mb-1">Role</label>
                            <select name="role" id="role"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150"
                                required>
                                <option value="karyawan" @selected(old('role') == 'karyawan')>Karyawan</option>
                                <option value="she" @selected(old('role', 'karyawan') == 'she')>SHE (Admin)</option>
                                <option value="magang" @selected(old('role') == 'magang')>Magang</option>
                            </select>
                            @error('role')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-6 border-t border-gray-100 flex items-center justify-end space-x-3">
                            <a href="{{ route('she.users.index') }}"
                                class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition-all">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-5 py-2.5 bg-red-600 border border-transparent rounded-lg font-bold text-sm text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all transform active:scale-95">
                                Buat User Baru
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>