<!-- Top Bar / Header Container -->
<header x-data="{ showSheChatModal: false }" class="sticky top-0 z-20 bg-white shadow-md border-b border-gray-100 px-4 md:px-6 py-3 md:py-4 flex items-center justify-between transition-all duration-300">
    
    <!-- Left Section: SIDUBA Title -->
    <div class="flex items-center gap-1.5 md:gap-2">
        <h1 class="text-xl md:text-3xl font-bold text-red-600 tracking-wide" style="font-family: 'Inter', 'Helvetica Neue', 'Arial', sans-serif; letter-spacing: 0.05em;">SIDUBA</h1>
        <span class="hidden sm:inline text-xs md:text-sm text-gray-500 italic font-light">(Sistem Duga Bahaya)</span>
    </div>

    <!-- Center/Right Section: Date & Actions -->
    <div class="flex items-center gap-3 md:gap-6">
        <!-- Tanggal - Hidden on mobile, visible on tablet+ -->
        <p class="hidden md:block text-sm text-gray-600 font-medium">
            {{-- Menampilkan Hari, Tanggal, dan Bulan Tahun saat ini (Contoh: Monday, 02 March 2020) --}}
            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
        </p>
        
        <!-- User Profile & Actions -->
        <div class="flex items-center space-x-1 md:space-x-2">
        


        <!-- Notifikasi Icon: Bell (Trigger untuk Modal Notifikasi) -->
        <div class="relative">
            <button id="notification-bell" 
                    class="p-2 md:p-2.5 rounded-full text-gray-400 hover:bg-gray-100 hover:text-purple-600 transition duration-150 relative" 
                    title="Notifikasi">
                <svg class="h-5 w-5 md:h-6 md:w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                
                {{-- Badge Notifikasi (Jumlah notifikasi belum terbaca) --}}
                <span id="notification-count-badge" class="absolute -top-1 -right-1 block h-3 w-3 rounded-full ring-2 ring-white bg-red-500 text-xs text-white flex items-center justify-center pointer-events-none transform translate-x-1/2 -translate-y-1/2" style="display: none;">
                    <span class="sr-only">0 notifikasi baru</span>
                </span>
            </button>
            
            <!-- Notification Pop-up / Modal Content - Responsive -->
            <div id="notification-modal" 
                 class="absolute right-0 mt-3 w-80 max-w-[calc(100vw-2rem)] bg-white rounded-lg shadow-xl overflow-hidden border border-gray-100 opacity-0 scale-95 pointer-events-none transition duration-200 origin-top-right">
                
                <div class="p-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">Notifikasi Terbaru</h3>
                    <span id="notification-modal-count" class="text-xs font-semibold text-purple-600 bg-purple-50 px-2 py-0.5 rounded-full" style="display: none;">0 Baru</span>
                </div>
                
                <div id="notification-list" class="max-h-80 overflow-y-auto">
                    {{-- Notifications will be loaded here dynamically --}}
                    <div class="p-4 text-center text-gray-500 text-sm">Memuat notifikasi...</div>
                </div>
                
                @if (Auth::check() && Auth::user()->hasRole('karyawan'))
                <div class="p-2 bg-gray-50 text-center border-t border-gray-100">
                    <button id="mark-all-read-btn" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition duration-150 w-full">
                        Tandai semua sudah dibaca
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bellButton = document.getElementById('notification-bell');
        const notificationModal = document.getElementById('notification-modal');
        const notificationList = document.getElementById('notification-list');
        const notificationCountBadge = document.getElementById('notification-count-badge');
        const notificationModalCount = document.getElementById('notification-modal-count');
        const markAllReadButton = document.getElementById('mark-all-read-btn');

        // --- Generic Modal Logic ---
        function toggleModal(button, modal, type) {
            const isHidden = modal.classList.contains('opacity-0');
            
            if (isHidden) {
                modal.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
                modal.classList.add('opacity-100', 'scale-100');
                // Call the correct fetch function based on type
                if (type === 'she_notification') fetchSheNotifications();
                if (type === 'karyawan_notification') fetchKaryawanNotifications();
            } else {
                hideModal(modal);
            }
        }
        
        function hideModal(modal) {
            modal.classList.remove('opacity-100', 'scale-100');
            modal.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
        }

        // --- SHE Notification Logic ---
        function fetchSheNotifications() {
            notificationList.innerHTML = '<div class="p-4 text-center text-gray-500 text-sm">Memuat notifikasi...</div>';
            axios.get('{{ route('she.api.hazards.notifications') }}')
                .then(response => {
                    const { notifications, unread_count } = response.data;
                    notificationList.innerHTML = '';

                    const notificationsToDisplay = notifications.slice(0, 3); // Take top 3

                    if (notificationsToDisplay.length === 0) {
                        notificationList.innerHTML = '<div class="p-4 text-center text-gray-500 text-sm">Tidak ada notifikasi mendesak saat ini.</div>';
                    } else {
                        notificationsToDisplay.forEach(notification => {
                            const bgClass = notification.type === 'overdue' ? 'bg-red-50 hover:bg-red-100' : 'bg-yellow-50 hover:bg-yellow-100';
                            const timeClass = notification.type === 'overdue' ? 'text-red-600 font-medium' : 'text-yellow-600 font-medium';
                            const notificationItem = `
                                <li>
                                    <a href="${notification.link}" class="block p-4 border-b border-gray-50 transition duration-150 ${bgClass}">
                                        <p class="text-sm truncate font-semibold text-gray-900">${notification.title}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">${notification.description}</p>
                                        <p class="text-xs mt-1 ${timeClass}">${notification.time_ago}</p>
                                    </a>
                                </li>
                            `;
                            notificationList.innerHTML += notificationItem;
                        });
                    }
                    updateUnreadCount(unread_count);
                })
                .catch(error => {
                    console.error('Error fetching SHE notifications:', error);
                    notificationList.innerHTML = '<div class="p-4 text-center text-red-500 text-sm">Gagal memuat notifikasi.</div>';
                    updateUnreadCount(0);
                });
        }

        // --- Karyawan Notification Logic ---
        function fetchKaryawanNotifications() {
            notificationList.innerHTML = '<div class="p-4 text-center text-gray-500 text-sm">Memuat notifikasi...</div>';
            
            axios.get("{{ route('karyawan.notifications.index') }}")
                .then(response => {
                    const { unread, read, unread_count } = response.data;
                    notificationList.innerHTML = ''; 

                    const allNotifications = [...unread, ...read]; // Combine unread and read
                    const notificationsToDisplay = allNotifications.slice(0, 3); // Take top 3

                    if (notificationsToDisplay.length === 0) {
                        notificationList.innerHTML = '<div class="p-6 text-center text-gray-500 text-sm">Tidak ada notifikasi.</div>';
                    } else {
                        notificationsToDisplay.forEach(notification => {
                            const isNotificationUnread = unread.some(item => item.id === notification.id);
                            notificationList.innerHTML += createKaryawanNotificationItem(notification, isNotificationUnread);
                        });
                    }
                    updateUnreadCount(unread_count);
                })
                .catch(error => {
                    console.error('Error fetching employee notifications:', error);
                    notificationList.innerHTML = '<div class="p-4 text-center text-red-500 text-sm">Gagal memuat notifikasi.</div>';
                    updateUnreadCount(0);
                });
        }

        function createKaryawanNotificationItem(notification, isUnread = true) {
            const readClass = isUnread ? '' : 'opacity-60';
            const iconBg = { 
                'success': 'bg-green-100 text-green-600', 
                'info': 'bg-blue-100 text-blue-600', 
                'warning': 'bg-yellow-100 text-yellow-600',
                'overdue': 'bg-red-100 text-red-600'
            }[notification.type] || 'bg-gray-100 text-gray-600';
            
            const url = "{{ route('karyawan.notifications.read', ['notification' => ':id']) }}".replace(':id', notification.id);

            return `
                <a href="${url}" class="block p-3 hover:bg-gray-50 transition duration-150 ${readClass}">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center ${iconBg}">
                           <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-800">${notification.title}</p>
                            <p class="text-xs text-gray-600">${notification.message}</p>
                            <p class="text-xs text-gray-400 mt-1">${new Date(notification.created_at).toLocaleString('id-ID')}</p>
                        </div>
                    </div>
                </a>
            `;
        }
        
        function updateUnreadCount(count) {
            if (count > 0) {
                notificationCountBadge.textContent = count;
                notificationCountBadge.style.display = 'flex';
                notificationModalCount.textContent = count + ' Baru';
                notificationModalCount.style.display = 'inline-flex';
            } else {
                notificationCountBadge.style.display = 'none';
                notificationModalCount.style.display = 'none';
            }
        }

        // --- Event Listeners ---
        bellButton.addEventListener('click', function (event) {
            event.stopPropagation();
            @if (Auth::check() && Auth::user()->hasRole('karyawan'))
                toggleModal(bellButton, notificationModal, 'karyawan_notification');
            @elseif (Auth::check() && Auth::user()->hasRole('she'))
                toggleModal(bellButton, notificationModal, 'she_notification');
            @endif
        });

        @if(Auth::check() && Auth::user()->hasRole('karyawan'))
        if (markAllReadButton) {
            markAllReadButton.addEventListener('click', function() {
                axios.post("{{ route('karyawan.notifications.markAllRead') }}", { _token: "{{ csrf_token() }}" })
                .then(response => {
                    fetchKaryawanNotifications();
                })
                .catch(error => {
                    console.error('Error marking all as read:', error);
                });
            });
        }
        @endif

        document.addEventListener('click', function (event) {
            if (!bellButton.contains(event.target) && !notificationModal.contains(event.target)) {
                hideModal(notificationModal);
            }
        });

        // Initial fetch on page load based on role
        @if (Auth::check() && Auth::user()->hasRole('karyawan'))
            fetchKaryawanNotifications();
        @elseif (Auth::check() && Auth::user()->hasRole('she'))
            fetchSheNotifications();
        @endif
    });
</script>