<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center p-6 lg:p-12">
        <div
            class="w-full max-w-md bg-white rounded-[40px] shadow-2xl shadow-gray-200/50 p-10 border border-gray-50 relative z-10">

            <!-- Siduba Branding -->
            <div class="flex flex-col items-center mb-6">
                <div class="flex items-center gap-3 mb-2">
                    <div
                        class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center shadow-lg shadow-red-200">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tighter uppercase leading-none">SIDUBA</h1>
                </div>
            </div>

            <div class="mb-6 text-center">
                <h2 class="text-xl font-black text-gray-900 tracking-tight mb-2">Verifikasi Email</h2>
                <p class="text-sm text-gray-500 font-medium leading-relaxed">
                    {{ __('Terima kasih telah mendaftar! Sebelum memulai, mohon verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan.') }}
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 p-4 bg-green-50 border border-green-100 rounded-2xl text-center">
                    <div class="mb-2 flex justify-center text-green-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-green-700">
                        {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                    </p>
                </div>
            @endif

            <div class="flex flex-col gap-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit"
                        class="group w-full bg-gradient-to-r from-red-500 to-rose-600 text-white font-black py-4 rounded-[24px] shadow-xl shadow-red-200 hover:shadow-red-300 hover:scale-[1.02] active:scale-95 transition-all duration-300 flex items-center justify-center gap-3">
                        {{ __('Kirim Ulang Email Verifikasi') }}
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="flex justify-center">
                    @csrf
                    <button type="submit"
                        class="text-xs font-bold text-gray-400 hover:text-red-500 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        {{ __('Keluar (Log Out)') }}
                    </button>
                </form>
            </div>
        </div>

        <div
            class="mt-8 flex items-center justify-center gap-3 opacity-40 grayscale hover:grayscale-0 hover:opacity-100 transition-all">
            <p class="text-[8px] font-black text-gray-400 uppercase tracking-[0.4em]">Powered by</p>
            <img src="{{ asset('images/logo-DharmaPolimetal.png') }}" alt="Dharma" class="h-5 w-auto">
        </div>
    </div>
</x-guest-layout>