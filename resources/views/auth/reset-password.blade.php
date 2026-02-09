<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center p-6 lg:p-12">
        <div
            class="w-full max-w-md bg-white rounded-[40px] shadow-2xl shadow-gray-200/50 p-10 border border-gray-50 relative z-10">

            <!-- Siduba Branding -->
            <div class="flex flex-col items-center mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <div
                        class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center shadow-lg shadow-red-200">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tighter uppercase leading-none">SIDUBA</h1>
                </div>
            </div>

            <div class="mb-6 text-center">
                <h2 class="text-xl font-black text-gray-900 tracking-tight mb-2">Buat Password Baru</h2>
                <p class="text-sm text-gray-500 font-medium">
                    Silakan atur ulang kata sandi Anda untuk melanjutkan.
                </p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="space-y-2">
                    <label for="email"
                        class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email</label>
                    <div class="relative group">
                        <div
                            class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-red-500 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <x-text-input id="email"
                            class="w-full pl-14 pr-5 py-4 bg-gray-50/50 border border-transparent rounded-[20px] focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-100 transition-all font-bold text-gray-800 placeholder-gray-400 text-sm"
                            type="email" name="email" :value="old('email', $request->email)" required autofocus
                            autocomplete="username" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 ml-1" />
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <label for="password"
                        class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Password
                        Baru</label>
                    <div class="relative group">
                        <div
                            class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-red-500 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12l1.378-5.511a2 2 0 00-1.94-2.489H6.562a2 2 0 00-1.94 2.489L6 21z" />
                            </svg>
                        </div>
                        <x-text-input id="password"
                            class="w-full pl-14 pr-5 py-4 bg-gray-50/50 border border-transparent rounded-[20px] focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-100 transition-all font-bold text-gray-800 placeholder-gray-400 text-sm"
                            type="password" name="password" required autocomplete="new-password"
                            placeholder="••••••••" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1 ml-1" />
                </div>

                <!-- Confirm Password -->
                <div class="space-y-2">
                    <label for="password_confirmation"
                        class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Konfirmasi
                        Password</label>
                    <div class="relative group">
                        <div
                            class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-red-500 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <x-text-input id="password_confirmation"
                            class="w-full pl-14 pr-5 py-4 bg-gray-50/50 border border-transparent rounded-[20px] focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-100 transition-all font-bold text-gray-800 placeholder-gray-400 text-sm"
                            type="password" name="password_confirmation" required autocomplete="new-password"
                            placeholder="••••••••" />
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 ml-1" />
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="group w-full bg-gradient-to-r from-red-500 to-rose-600 text-white font-black py-4 rounded-[24px] shadow-xl shadow-red-200 hover:shadow-red-300 hover:scale-[1.02] active:scale-95 transition-all duration-300 flex items-center justify-center gap-3">
                        {{ __('Simpan Password Baru') }}
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <div
            class="mt-8 flex items-center justify-center gap-3 opacity-40 grayscale hover:grayscale-0 hover:opacity-100 transition-all">
            <p class="text-[8px] font-black text-gray-400 uppercase tracking-[0.4em]">Powered by</p>
            <img src="{{ asset('images/logo-DharmaPolimetal.png') }}" alt="Dharma" class="h-5 w-auto">
        </div>
    </div>
</x-guest-layout>