<x-guest-layout>
    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Left Section: Interactive Graphic (Hidden on Mobile) -->
        <div class="hidden lg:flex lg:w-1/2 items-center justify-center p-12 relative overflow-hidden">
            <div class="relative w-full max-w-xl">
                <!-- Large Soft Gray Circle -->
                <div
                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-gray-50 rounded-full">
                </div>

                <!-- Floating Icons -->
                <div class="relative z-10 flex flex-col items-center">
                    <!-- Main Industrial Silhouette -->
                    <div class="mb-12 opacity-20">
                        <svg class="w-64 h-64 text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z" />
                        </svg>
                    </div>

                    <!-- Floating Action Icons -->
                    <div class="absolute -top-10 -right-10 animate-float">
                        <div class="p-4 bg-white rounded-2xl shadow-xl border border-gray-100/50">
                            <svg class="w-10 h-10 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>

                    <div class="absolute bottom-10 -left-10 animate-float-delayed">
                        <div class="p-4 bg-white rounded-2xl shadow-xl border border-gray-100/50">
                            <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                    </div>

                    <div class="absolute top-1/4 -right-20 animate-float-delayed">
                        <div class="p-3 bg-white rounded-xl shadow-lg border border-gray-100/50">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>

                    <!-- Catchy Text -->
                    <div class="mt-8 text-center">
                        <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight leading-tight">
                            Deteksi Dini, <br>
                            <span class="text-red-500">Tindakan Pasti.</span>
                        </h2>
                        <p class="mt-4 text-gray-500 font-medium max-w-xs mx-auto">
                            Pantau potensi bahaya di area kerja Anda dengan sistem deteksi cerdas SIDUBA.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Section: Minimalist Login Form -->
        <div class="flex-grow flex flex-col items-center justify-center p-6 lg:p-12 relative">
            <div
                class="w-full max-w-md bg-white rounded-[40px] shadow-2xl shadow-gray-200/50 p-10 lg:p-12 border border-gray-50 relative z-10 transition-all hover:shadow-gray-300">
                <!-- Siduba Branding -->
                <div class="flex flex-col items-center mb-10">
                    <div class="flex items-center gap-3 mb-2">
                        <div
                            class="w-12 h-12 bg-red-500 rounded-2xl flex items-center justify-center shadow-lg shadow-red-200">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase leading-none">SIDUBA
                        </h1>
                    </div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.4em] ml-1">Sistem Duga Bahaya
                    </p>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight mb-1">Selamat Datang Kembali</h2>
                    <p class="text-gray-400 text-sm font-medium">Silakan masuk untuk mengakses dashboard</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-6" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- NPK -->
                    <div class="space-y-2">
                        <label for="npk"
                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Username /
                            NPK</label>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-red-500 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input id="npk" type="text" name="npk" value="{{ old('npk') }}" required autofocus
                                class="w-full pl-14 pr-5 py-4 bg-gray-50/50 border border-transparent rounded-[20px] focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-100 transition-all font-bold text-gray-800 placeholder-gray-400 text-sm"
                                placeholder="Masukkan username">
                        </div>
                        <x-input-error :messages="$errors->get('npk')" class="mt-1 ml-1" />
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between ml-1">
                            <label for="password"
                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Password</label>
                            @if (Route::has('password.request'))
                                <a class="text-[10px] font-bold text-red-500 hover:text-red-700 uppercase tracking-widest"
                                    href="{{ route('password.request') }}">
                                    Lupa Password?
                                </a>
                            @endif
                        </div>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-red-500 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12l1.378-5.511a2 2 0 00-1.94-2.489H6.562a2 2 0 00-1.94 2.489L6 21z" />
                                </svg>
                            </div>
                            <input id="password" type="password" name="password" required
                                class="w-full pl-14 pr-12 py-4 bg-gray-50/50 border border-transparent rounded-[20px] focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-100 transition-all font-bold text-gray-800 placeholder-gray-400 text-sm"
                                placeholder="••••••••">
                            <div class="absolute inset-y-0 right-0 pr-5 flex items-center">
                                <svg class="w-5 h-5 text-gray-300 cursor-pointer hover:text-gray-500 transition-colors"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1 ml-1" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center ml-1">
                        <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                            <div
                                class="relative flex items-center justify-center h-5 w-5 bg-gray-100 rounded-full border-2 border-gray-200 group-hover:border-red-500 transition-all">
                                <input id="remember_me" type="checkbox" name="remember"
                                    class="opacity-0 absolute h-full w-full cursor-pointer peer">
                                <div
                                    class="h-2.5 w-2.5 bg-red-500 rounded-full scale-0 peer-checked:scale-100 transition-transform duration-200">
                                </div>
                            </div>
                            <span
                                class="ms-3 text-xs text-gray-500 font-bold group-hover:text-gray-900 transition-colors tracking-tight">Tetap
                                masuk di perangkat ini</span>
                        </label>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="group w-full bg-gradient-to-r from-red-500 to-rose-600 text-white font-black py-5 rounded-[24px] shadow-xl shadow-red-200 hover:shadow-red-300 hover:scale-[1.02] active:scale-95 transition-all duration-300 flex items-center justify-center gap-3">
                            Masuk ke Dashboard
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>
                    </div>
                </form>

                <div
                    class="mt-12 flex items-center justify-center gap-4 border-t border-gray-50 pt-8 opacity-40 grayscale group hover:grayscale-0 hover:opacity-100 transition-all">
                    <p class="text-[8px] font-black text-gray-400 uppercase tracking-[0.4em]">Integrated by</p>
                    <img src="{{ asset('images/logo-DharmaPolimetal.png') }}" alt="Dharma Polimetal" class="h-6 w-auto">
                </div>
            </div>

            <!-- Modern Footer Links -->
            <div
                class="absolute bottom-10 left-0 right-0 flex flex-col items-center gap-4 text-gray-400 text-[10px] font-bold uppercase tracking-[0.2em] transition-all hover:text-gray-600">
                <p>&copy; 2025 SIDUBA - Sistem Duga Bahaya</p>
            </div>
        </div>
    </div>
</x-guest-layout>