<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel')</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pagination.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="hamburger" id="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="header-title">
            @yield('page-title', 'Admin Panel')

        </div>


    
        <!-- Add Notification Bell -->
        <div class="notification-bell">
            <button class="notification-bell-btn" id="notificationBellBtn">
                <i class="fa-solid fa-bell notification-bell-icon"></i>
                @php
                    $unreadCount = \App\Services\NotificationService::getUnreadCount();
                @endphp
                @if($unreadCount > 0)
                    <span class="notification-badge">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                @endif
            </button>

            <!-- Notification Dropdown -->
            <div class="notification-dropdown" id="notificationDropdown">
                <div class="notification-dropdown-header">
                    <span class="notification-dropdown-title">Notifications</span>
                    <a href="{{ route('vendor.notifications.index') }}" class="btn-view-all-notifications">
                        View All
                    </a>
                </div>
                <div class="notification-dropdown-body">
                    @php
                        $recentNotifications = \App\Services\NotificationService::getRecentNotifications(5);
                    @endphp
                    
                    @forelse($recentNotifications as $notification)
                        <a href="{{ route('vendor.notifications.markRead', $notification->id) }}" 
                        class="notification-dropdown-item {{ $notification->is_read ? 'read' : 'unread' }}">
                            <div class="notification-dropdown-item-header">
                                <h4 class="notification-dropdown-item-title">{{ $notification->title }}</h4>
                                <span class="notification-dropdown-item-time">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="notification-dropdown-item-message">{{ Str::limit($notification->message, 80) }}</p>
                        </a>
                    @empty
                        <div class="notification-dropdown-empty">
                            <i class="fa-solid fa-bell-slash"></i>
                            <p>No notifications</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    
    
  
    

    <!-- Sidebar Navigation -->
    <nav class="sidebar" id="sidebar">
        <ul class="nav-menu">

             <!-- newly added -->
            <div class="logo-section">
                <div class="logo-circle">
                    <i class="fa-solid fa-basket-shopping"></i>
                </div>
                <div class="logo-text">HarisStore</div>
            </div>
            
            <li class="nav-item">
                <a href="{{ route('vendor.dashboard') }}" class="nav-link {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('vendor.product.index') }}" class="nav-link {{ request()->routeIs('vendor.product*') ? 'active' : '' }}">
                    <i class="fa-solid fa-boxes-stacked"></i> Inventory
                </a>
            </li>


            

            <li class="nav-item">
                <a href="{{ route('vendor.order.index') }}" class="nav-link {{ request()->routeIs('vendor.order*') ? 'active' : '' }}">
                    <i class="fa-solid fa-basket-shopping"></i> Order
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('vendor.sales.index') }}" class="nav-link {{ request()->routeIs('vendor.sales*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i> Sales
                </a>
            </li>

         
            <!-- style="padding-top:400px;" -->
            <li class="nav-item"> 
                <button type="button" onclick="openLogoutModal()" class="nav-link logout-btn">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                </button>
                <form method="POST" action="{{ route('logout') }}" id="logoutForm" style="display: none;">
                    @csrf
                </form>
            </li>
            
             
    
          
        </ul>
    </nav>

    <!-- Overlay for mobile -->
    <div class="overlay" id="overlay"></div>

    <!-- Main Content -->

    <div class="main-content">
                <div class="content-wrapper">
                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fa-solid fa-check-circle"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Page Content -->
                    @yield('content')
                </div>
    </div>

    <script>
        const hamburger = document.getElementById('hamburger');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        function toggleSidebar() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        hamburger.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);

        // Close sidebar on window resize if in desktop mode
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            }
        });

        
                // Logout Modal Functions
        function openLogoutModal() {
            const modal = document.getElementById('logoutModal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        function closeLogoutModal() {
            const modal = document.getElementById('logoutModal');
            modal.classList.remove('active');
            document.body.style.overflow = ''; // Restore scrolling
        }

        function confirmLogoutAction() {
            document.getElementById('logoutForm').submit();
        }

        // Close modal when clicking outside
        document.getElementById('logoutModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeLogoutModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('logoutModal');
                if (modal && modal.classList.contains('active')) {
                    closeLogoutModal();
                }
            }
        });

        // Legacy support for existing confirmLogout function
        function confirmLogout() {
            openLogoutModal();
        }
        
        //Notification
         const notificationBellBtn = document.getElementById('notificationBellBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');

    if (notificationBellBtn && notificationDropdown) {
        notificationBellBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            notificationDropdown.classList.toggle('active');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!notificationDropdown.contains(e.target) && !notificationBellBtn.contains(e.target)) {
                notificationDropdown.classList.remove('active');
            }
        });

        // Prevent dropdown from closing when clicking inside
        notificationDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    // Auto-refresh notification count every 30 seconds
    setInterval(function() {
        fetch('{{ route("admin.notifications.unreadCount") }}')
            .then(response => response.json())
            .then(data => {
                const badge = document.querySelector('.notification-badge');
                if (data.count > 0) {
                    if (badge) {
                        badge.textContent = data.count > 99 ? '99+' : data.count;
                    } else {
                        const newBadge = document.createElement('span');
                        newBadge.className = 'notification-badge';
                        newBadge.textContent = data.count > 99 ? '99+' : data.count;
                        notificationBellBtn.appendChild(newBadge);
                    }
                } else if (badge) {
                    badge.remove();
                }
            })
            .catch(error => console.error('Error fetching notification count:', error));
    }, 30000);
    </script>

    <!-- Logout Confirmation Modal -->
<div class="logout-modal-overlay" id="logoutModal">
    <div class="logout-modal-content">
        <div class="logout-modal-icon">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
        </div>
        <h3 class="logout-modal-title">Confirm Logout</h3>
        <p class="logout-modal-message">Are you sure you want to logout? You'll need to sign in again to access your account.</p>
        <div class="logout-modal-actions">
            <button type="button" class="btn-logout-cancel" onclick="closeLogoutModal()">
                <i class="fa-solid fa-xmark"></i>
                Cancel
            </button>
            <button type="button" class="btn-logout-confirm" onclick="confirmLogoutAction()">
                <i class="fa-solid fa-check"></i>
                Yes, Logout
            </button>
        </div>
    </div>
</div>

</body>
</html>


