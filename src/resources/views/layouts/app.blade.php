<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Focus Timer</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        * {
            color: #000000 !important;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%);
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            display: flex;
            flex-direction: column;
        }
        
        /* Container utama dengan bingkai */
        .app-wrapper {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 2rem 1rem;
        }
        
        .app-container {
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            background: white;
            border-radius: 2rem;
            box-shadow: 0 20px 35px -10px rgba(0,0,0,0.1), 0 0 0 1px rgba(236,72,153,0.1);
            overflow: hidden;
        }
        
        /* Navbar dalam bingkai */
        .navbar {
            background: white;
            border-bottom: 2px solid #fbcfe8;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        
        .navbar-container {
            padding: 0 1.5rem;
        }
        
        .navbar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
        }
        
        .logo {
            font-size: 1.25rem;
            font-weight: bold;
            color: #db2777 !important;
            text-decoration: none;
            transition: color 0.2s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .logo:hover {
            color: #be185d !important;
        }
        
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .notif-btn {
            position: relative;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: background-color 0.2s;
        }
        
        .notif-btn:hover {
            background-color: #fce7f3;
        }
        
        .notif-icon {
            width: 1.5rem;
            height: 1.5rem;
            color: #000000 !important;
        }
        
        .notif-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background-color: #ef4444;
            color: white !important;
            font-size: 0.65rem;
            font-weight: bold;
            border-radius: 9999px;
            padding: 0.125rem 0.375rem;
            min-width: 1.125rem;
            text-align: center;
            display: none;
        }
        
        .divider {
            color: #d1d5db !important;
        }
        
        .logout-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            transition: background-color 0.2s;
            color: #dc2626 !important;
        }
        
        .logout-btn:hover {
            background-color: #fee2e2;
        }
        
        .logout-icon {
            width: 1rem;
            height: 1rem;
            color: #dc2626 !important;
        }
        
        /* Main Content dalam bingkai */
        .main-content {
            padding: 2rem;
            background: #ffffff;
        }
        
        /* Toast Notification */
        .toast-notification {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.02);
            padding: 1rem 1.25rem;
            min-width: 280px;
            max-width: 380px;
            z-index: 10000;
            animation: slideInRight 0.3s ease;
            border-left: 4px solid #ec489a;
        }
        
        .toast-notification * {
            color: #000000 !important;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .toast-notification.hide {
            animation: slideOutRight 0.3s ease forwards;
        }
        
        @keyframes slideOutRight {
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #fce7f3;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #f472b6;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #ec489a;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .app-wrapper {
                padding: 1rem;
            }
            
            .navbar-container {
                padding: 0 1rem;
            }
            
            .main-content {
                padding: 1.25rem;
            }
            
            .logo {
                font-size: 1rem;
            }
            
            .nav-actions {
                gap: 0.75rem;
            }
            
            .logout-btn span {
                display: none;
            }
            
            .logout-btn {
                padding: 0.5rem;
            }
            
            .toast-notification {
                left: 16px;
                right: 16px;
                min-width: auto;
                max-width: none;
                bottom: 16px;
            }
        }
        
        @media (max-width: 480px) {
            .app-wrapper {
                padding: 0.5rem;
            }
            
            .main-content {
                padding: 1rem;
            }
            
            .navbar-content {
                height: 60px;
            }
        }
    </style>
</head>
<body>
    <div id="toast-container"></div>
    
    <div class="app-wrapper">
        <div class="app-container">
            @auth
            <nav class="navbar">
                <div class="navbar-container">
                    <div class="navbar-content">
                        <div class="flex items-center">
                            <a href="{{ url('/dashboard') }}" class="logo">
                                🍅 Focus Timer
                            </a>
                        </div>
                        <div class="nav-actions">
                            <button id="notif-bell" class="notif-btn">
                                <svg class="notif-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <span id="notif-badge" class="notif-badge">0</span>
                            </button>
                            <span class="divider">|</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="logout-btn">
                                    <svg class="logout-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
            @endauth
            
            <main class="main-content">
                {{ $slot }}
            </main>
        </div>
    </div>
    
    @livewireScripts
    
    <script>
        // Toast Notification System
        class ToastManager {
            constructor() {
                this.container = document.getElementById('toast-container');
                this.counter = 0;
            }
            
            show(title, body, type = 'info') {
                const id = this.counter++;
                const toast = document.createElement('div');
                toast.className = 'toast-notification';
                toast.id = `toast-${id}`;
                
                const borderColors = {
                    info: '#ec489a',
                    success: '#10b981',
                    error: '#ef4444',
                    warning: '#f59e0b'
                };
                
                toast.style.borderLeftColor = borderColors[type] || borderColors.info;
                
                toast.innerHTML = `
                    <div style="display: flex; gap: 0.75rem;">
                        <div style="flex: 1;">
                            <strong style="color: #000000 !important;">${title}</strong>
                            <p style="color: #000000 !important; font-size: 0.875rem; margin-top: 0.25rem;">${body}</p>
                        </div>
                        <button onclick="window.toastManager.close(${id})" style="color: #000000 !important; background: none; border: none; cursor: pointer; font-size: 1.25rem;">&times;</button>
                    </div>
                `;
                this.container.appendChild(toast);
                
                setTimeout(() => this.close(id), 6000);
                return id;
            }
            
            close(id) {
                const toast = document.getElementById(`toast-${id}`);
                if (toast) {
                    toast.classList.add('hide');
                    setTimeout(() => toast.remove(), 300);
                }
            }
        }
        
        window.toastManager = new ToastManager();
        
        window.addEventListener('toast-notification', (e) => {
            window.toastManager.show(e.detail.title, e.detail.body, e.detail.type);
        });
        
        // Browser Notification
        async function initNotifications() {
            if ('Notification' in window && Notification.permission !== 'denied') {
                const permission = await Notification.requestPermission();
                if (permission === 'granted' && 'serviceWorker' in navigator) {
                    try {
                        const registration = await navigator.serviceWorker.register('/sw.js');
                        console.log('Service Worker registered:', registration);
                    } catch (error) {
                        console.error('Service Worker registration failed:', error);
                    }
                }
            }
        }
        
        let notificationRequested = false;
        document.body.addEventListener('click', () => {
            if (!notificationRequested) {
                notificationRequested = true;
                initNotifications();
            }
        }, { once: true });
        
                Livewire.on('notify', (data) => {
            console.log('📢 NOTIFY DATA:', data);
            
            let title = 'Notifikasi';
            let body = '';
            
            if (data && typeof data === 'object') {
                if (Array.isArray(data) && data.length > 0) {
                    title = data[0]?.title || 'Notifikasi';
                    body = data[0]?.body || '';
                } else {
                    title = data.title || 'Notifikasi';
                    body = data.body || '';
                }
            }
            
            console.log('📢 Displaying:', title, body);
            
            if (Notification.permission === 'granted' && document.hidden) {
                new Notification(title, { body: body, icon: '/favicon.ico' });
            } else {
                window.toastManager.show(title, body);
            }
        });
        
        @auth
        let pollInterval = null;

        async function checkNotifications() {
            try {
                // CEK JENIS AUTH: apakah ada token (mobile) atau session (web)
                let url = '/notifications/unread';
                let headers = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                };
                
                // Cek apakah user login dengan token (mobile/extension)
                const token = localStorage.getItem('api_token');
                if (token) {
                    // Mobile/extension: pakai API endpoint
                    url = '/api/notifications/unread';
                    headers['Authorization'] = `Bearer ${token}`;
                }
                // Web app: pakai route web (tanpa token)
                
                const response = await fetch(url, { headers });
                
                if (response.status === 401) {
                    // Token invalid, coba pakai route web
                    const webResponse = await fetch('/notifications/unread', { headers });
                    if (webResponse.ok) {
                        const notifs = await webResponse.json();
                        updateNotificationUI(notifs);
                    }
                    return;
                }
                
                if (response.ok) {
                    const notifs = await response.json();
                    updateNotificationUI(notifs);
                }
            } catch (error) {
                console.error('Polling error:', error);
            }
        }

            function updateNotificationUI(notifs) {
            const badge = document.getElementById('notif-badge');
            if (badge) {
                const unreadCount = notifs.length;
                badge.style.display = unreadCount > 0 ? 'block' : 'none';
                if (unreadCount > 0) {
                    badge.textContent = unreadCount > 9 ? '9+' : unreadCount;
                }
            }
            
            for (const notif of notifs) {
                Livewire.dispatch('notify', {
                    title: notif.title,
                    body: notif.body || 'Waktunya mengerjakan tugas!',
                    type: 'info'
                });
                
                // ⭐ HAPUS ATAU COMMENT BARIS INI ⭐
                // Tandai sudah dibaca - JANGAN LANGSUNG!
                /*
                const token = localStorage.getItem('api_token');
                const url = `/notifications/${notif.id}/read`;
                const headers = token ? { 
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                } : {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                };
                
                fetch(url, { method: 'POST', headers });
                */
            }
        }

        function startPolling() {
            if (pollInterval) clearInterval(pollInterval);
            checkNotifications();
            pollInterval = setInterval(checkNotifications, 30000);
        }

        startPolling();

        // Tampilkan daftar notifikasi saat lonceng diklik
document.getElementById('notif-bell')?.addEventListener('click', async () => {
    try {
        const token = localStorage.getItem('api_token');
        const url = '/api/notifications/unread';
        const headers = token ? { 'Authorization': `Bearer ${token}` } : {};
        
        const response = await fetch(url, { headers });
        if (response.ok) {
            const notifs = await response.json();
            
            console.log('Notifikasi dari API:', notifs); // DEBUG
            
            let notifHtml = '<div class="notif-popup" style="position:absolute; top:70px; right:20px; width:350px; background:white; border-radius:12px; box-shadow:0 10px 25px rgba(0,0,0,0.2); z-index:10000;">';
            notifHtml += '<div style="padding:12px; background:#fce7f3; border-bottom:1px solid #fbcfe8;"><strong>📢 Notifikasi</strong></div>';
            
            if (notifs.length === 0) {
                notifHtml += '<div style="padding:20px; text-align:center; color:gray;">Tidak ada notifikasi</div>';
            } else {
                notifHtml += '<div style="max-height:350px; overflow-y:auto;">';
                notifs.forEach(notif => {
                    console.log('Notif item:', notif); // DEBUG
                    notifHtml += `
                        <div style="padding:12px; border-bottom:1px solid #eee;">
                            <div style="font-weight:bold; color:#db2777;">${notif.title || 'Notifikasi'}</div>
                            <div style="font-size:12px; color:gray; margin-top:4px;">${notif.body || 'Tidak ada pesan'}</div>
                            <div style="font-size:10px; color:#aaa; margin-top:6px;">${new Date(notif.created_at).toLocaleString()}</div>
                        </div>
                    `;
                });
                notifHtml += '</div>';
                notifHtml += '<div style="padding:8px; text-align:center; border-top:1px solid #eee;">';
                notifHtml += '<button onclick="markAllNotificationsRead()" style="background:#ec489a; color:white; border:none; border-radius:8px; padding:6px 12px; cursor:pointer;">✅ Tandai semua dibaca</button>';
                notifHtml += '</div>';
            }
            notifHtml += '</div>';
            
            document.querySelector('.notif-popup')?.remove();
            document.body.insertAdjacentHTML('beforeend', notifHtml);
            
            setTimeout(() => {
                document.addEventListener('click', function closePopup(e) {
                    if (!e.target.closest('.notif-popup') && !e.target.closest('#notif-bell')) {
                        document.querySelector('.notif-popup')?.remove();
                        document.removeEventListener('click', closePopup);
                    }
                });
            }, 100);
        }
    } catch (error) {
        console.error('Error fetching notifications:', error);
    }
});

    // Fungsi untuk menandai semua notifikasi sudah dibaca
        window.markAllNotificationsRead = async function() {
        try {
            const token = localStorage.getItem('api_token');
            // PAKSA PAKAI API ENDPOINT (BUKAN WEB ROUTE)
            const url = '/api/notifications/read-all';
            const headers = token ? { 
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            } : {
                'Content-Type': 'application/json'
            };
            
            const response = await fetch(url, { method: 'POST', headers });
            
            if (response.ok) {
                const popup = document.querySelector('.notif-popup');
                if (popup) popup.remove();
                
                const badge = document.getElementById('notif-badge');
                if (badge) badge.style.display = 'none';
                
                window.toastManager.show('Notifikasi', 'Semua notifikasi telah dibaca', 'success');
            }
        } catch (error) {
            console.error('Error marking all as read:', error);
        }
    };

    @endauth
    </script>
    
    @stack('scripts')
</body>
</html>