<!-- Top Bar / Header Container -->
<header x-data="{ showSheChatModal: false }" class="sticky top-0 z-20 bg-white shadow-md border-b border-gray-100 px-6 py-4 flex items-center justify-between transition-all duration-300">
    
    <!-- Left Section: Page Title / Breadcrumbs -->
    <div class="flex flex-col">
        <!-- Judul Halaman -->
        <h1 class="text-2xl font-bold text-gray-800">
            @yield('page-title', 'Dashboard') 
        </h1>
        <!-- Tanggal dan Salam -->
        <p class="text-sm text-gray-500 mt-1 font-semibold">
            {{-- Menampilkan Hari, Tanggal, dan Bulan Tahun saat ini (Contoh: Monday, 02 March 2020) --}}
            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
        </p>
    </div>

    <!-- Right Section: User Profile & Actions -->
    <div class="flex items-center space-x-2">
        
        <!-- Chat Icon: Kotak Masuk (Trigger untuk Modal Chat) -->
        <div class="relative">
            <button id="chat-button" 
                    class="p-2.5 rounded-full text-gray-400 hover:bg-gray-100 hover:text-purple-600 transition duration-150 relative" 
                    title="Pesan Grup SHE">
                {{-- Mengganti ikon email menjadi ikon chat bubble --}}
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 4v-4z" />
                </svg>
                
                {{-- Badge Chat (Contoh: 1 pesan belum dibaca) --}}
                @php
                    // Ganti dengan logic Anda: misalnya Auth::user()->unreadChats->count()
                    $unreadChatCount = 1;
                @endphp
                @if ($unreadChatCount > 0)
                    <span id="chat-count-badge" class="absolute top-1 right-1 block h-3 w-3 rounded-full ring-2 ring-white bg-green-500 text-xs text-white flex items-center justify-center pointer-events-none transform translate-x-1/2 -translate-y-1/2">
                        <span class="sr-only">{{ $unreadChatCount }} pesan baru</span>
                    </span>
                @endif
            </button>

            <!-- Chat Pop-up / Modal Content -->
            <div id="chat-modal" 
                 class="absolute right-0 mt-3 w-80 sm:w-96 bg-white rounded-lg shadow-xl overflow-hidden border border-gray-100 transform translate-x-1/4 opacity-0 scale-95 pointer-events-none transition duration-200 origin-top-right">
                
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800">Pesan SHE Group</h3>
                    @if ($unreadChatCount > 0)
                        <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">{{ $unreadChatCount }} Baru</span>
                    @endif
                </div>
                
                <div class="max-h-96 overflow-y-auto">
                    {{-- Item Percakapan (Dummy Data) --}}
                    @php
                        $chats = [
                            ['user' => 'Bambang (Supervisor)', 'message' => 'Laporan ID 001 sudah diverifikasi dan ditindaklanjuti. Cek statusnya.', 'time' => '5 menit yang lalu', 'unread' => true],
                            ['user' => 'Rina (SHE Staf)', 'message' => 'Apakah jadwal audit bulan depan sudah final?', 'time' => '2 jam yang lalu', 'unread' => false],
                            ['user' => 'Admin Gudang', 'message' => 'Peralatan APD baru sudah tiba di gudang utama.', 'time' => 'Kemarin', 'unread' => false],
                        ];
                    @endphp
                    
                    @forelse ($chats as $chat)
                        @php
                            $bgClass = $chat['unread'] ? 'bg-green-50 hover:bg-green-100' : 'hover:bg-gray-100';
                            $titleClass = $chat['unread'] ? 'text-gray-900 font-semibold' : 'text-gray-700';
                            $timeClass = $chat['unread'] ? 'text-green-600 font-medium' : 'text-gray-400';
                        @endphp
                        <a href="{{-- Tautan ke ruang chat --}}#" class="flex p-4 border-b border-gray-50 transition duration-150 {{ $bgClass }}">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-200 flex items-center justify-center text-green-700 font-bold text-sm mr-3">
                                {{ substr($chat['user'], 0, 1) }}
                            </div>
                            <div class="flex-grow">
                                <div class="flex justify-between items-center">
                                    <p class="text-sm truncate {{ $titleClass }}">{{ $chat['user'] }}</p>
                                    <p class="text-xs {{ $timeClass }} ml-2 flex-shrink-0">{{ $chat['time'] }}</p>
                                </div>
                                <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $chat['message'] }}</p>
                            </div>
                        </a>
                    @empty
                        <div class="p-4 text-center text-gray-500 text-sm">Tidak ada percakapan terbaru.</div>
                    @endforelse
                </div>
                
                <div class="p-3 bg-gray-50 text-center border-t border-gray-100">
                    <a href="{{-- Tautan ke halaman chat penuh --}}#" class="text-sm font-semibold text-green-600 hover:text-green-700 transition duration-150">Buka Semua Pesan</a>
                </div>
            </div>
        </div>

        <!-- Notifikasi Icon: Bell (Trigger untuk Modal Notifikasi) -->
        <div class="relative">
            {{-- Ambil jumlah notifikasi yang belum dibaca. Ini adalah placeholder/contoh asumsi menggunakan fitur Notifikasi Laravel. --}}
            @php
                // Ganti dengan logic Anda: misalnya Auth::user()->unreadNotifications->count()
                $unreadCount = 3; 
                // Ganti dengan daftar notifikasi nyata dari database
                $notifications = [
                    ['title' => 'Laporan Bahaya Baru [ID: 001]', 'desc' => 'Bahaya potensi risiko tinggi di Area Produksi. Perlu diverifikasi Supervisor.', 'time' => '10 menit yang lalu', 'is_read' => false],
                    ['title' => 'Verifikasi Risiko Selesai', 'desc' => 'Risiko dari Laporan #98 telah diverifikasi oleh Pak Budi.', 'time' => '1 jam yang lalu', 'is_read' => false],
                    ['title' => 'Reminder Cek Safety Harian', 'desc' => 'Pastikan semua APD telah digunakan dengan benar di shift pagi.', 'time' => 'Kemarin', 'is_read' => true],
                    ['title' => 'User Baru Ditambahkan', 'desc' => 'Karyawan: Sinta Dewi, Role: Karyawan, telah terdaftar ke sistem.', 'time' => '2 hari yang lalu', 'is_read' => true],
                ];
            @endphp

            <button id="notification-bell" 
                    class="p-2.5 rounded-full text-gray-400 hover:bg-gray-100 hover:text-purple-600 transition duration-150 relative" 
                    title="Notifikasi">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                
                {{-- Badge Notifikasi (Jumlah notifikasi belum terbaca) --}}
                @if ($unreadCount > 0)
                    <span id="notification-count-badge" class="absolute top-1 right-1 block h-3 w-3 rounded-full ring-2 ring-white bg-red-500 text-xs text-white flex items-center justify-center pointer-events-none transform translate-x-1/2 -translate-y-1/2">
                        <span class="sr-only">{{ $unreadCount }} notifikasi baru</span>
                    </span>
                @endif
            </button>
            
            <!-- Notification Pop-up / Modal Content -->
            <div id="notification-modal" 
                 class="absolute right-0 mt-3 w-80 bg-white rounded-lg shadow-xl overflow-hidden border border-gray-100 transform translate-x-1/4 opacity-0 scale-95 pointer-events-none transition duration-200 origin-top-right">
                
                <div class="p-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">Notifikasi Terbaru</h3>
                    @if ($unreadCount > 0)
                        <span class="text-xs font-semibold text-purple-600 bg-purple-50 px-2 py-0.5 rounded-full">{{ $unreadCount }} Baru</span>
                    @endif
                </div>
                
                <div class="max-h-80 overflow-y-auto">
                    {{-- Item Notifikasi (Menggunakan data array PHP di atas) --}}
                    @forelse ($notifications as $notification)
                        @php
                            $bgClass = $notification['is_read'] ? 'hover:bg-gray-100' : 'bg-purple-50 hover:bg-purple-100';
                            $titleClass = $notification['is_read'] ? 'text-gray-700' : 'text-gray-900 font-semibold';
                            $timeClass = $notification['is_read'] ? 'text-gray-400' : 'text-purple-600 font-medium';
                        @endphp
                        <a href="{{-- Tautan ke detail laporan --}}#" class="block p-4 border-b border-gray-50 transition duration-150 {{ $bgClass }}">
                            <p class="text-sm truncate {{ $titleClass }}">{{ $notification['title'] }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $notification['desc'] }}</p>
                            <p class="text-xs mt-1 {{ $timeClass }}">{{ $notification['time'] }}</p>
                        </a>
                    @empty
                        <div class="p-4 text-center text-gray-500 text-sm">Tidak ada notifikasi yang ditemukan.</div>
                    @endforelse
                </div>
                
                <div class="p-3 bg-gray-50 text-center border-t border-gray-100">
                    <a href="{{-- Tautan ke halaman semua notifikasi --}}#" class="text-sm font-semibold text-purple-600 hover:text-purple-700 transition duration-150">Lihat Semua Notifikasi</a>
                </div>
            </div>
        </div>

</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bellButton = document.getElementById('notification-bell');
        const notificationModal = document.getElementById('notification-modal');
        const chatButton = document.getElementById('chat-button'); // Ambil tombol chat
        const chatModal = document.getElementById('chat-modal'); // Ambil modal chat

        // Fungsi untuk mengontrol visibilitas modal
        function toggleModal(button, modal) {
            // Tutup modal lain jika terbuka
            if (modal.id === 'notification-modal' && !chatModal.classList.contains('opacity-0')) {
                hideModal(chatModal);
            } else if (modal.id === 'chat-modal' && !notificationModal.classList.contains('opacity-0')) {
                hideModal(notificationModal);
            }

            const isHidden = modal.classList.contains('opacity-0');
            
            if (isHidden) {
                // Show modal
                modal.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
                modal.classList.add('opacity-100', 'scale-100');
                
                // TODO: Di sini, tambahkan permintaan AJAX untuk memberitahu server (misalnya: tandai pesan chat sebagai dilihat)
            } else {
                // Hide modal
                hideModal(modal);
            }
        }
        
        function hideModal(modal) {
            modal.classList.remove('opacity-100', 'scale-100');
            modal.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
        }

        // Toggle modal Notifikasi
        bellButton.addEventListener('click', function (event) {
            event.stopPropagation();
            toggleModal(bellButton, notificationModal);
        });

        // Toggle modal Chat
        chatButton.addEventListener('click', function (event) {
            event.stopPropagation();
            toggleModal(chatButton, chatModal);
        });

        // Hide modals when clicking anywhere outside
        document.addEventListener('click', function (event) {
            // Check Notification Modal
            if (!bellButton.contains(event.target) && !notificationModal.contains(event.target)) {
                hideModal(notificationModal);
            }
            // Check Chat Modal
            if (!chatButton.contains(event.target) && !chatModal.contains(event.target)) {
                hideModal(chatModal);
            }
        });
    });
</script>