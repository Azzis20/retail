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
        <div class="header-title">@yield('page-title', 'Admin Panel')</div>
    </div>

    <!-- Sidebar Navigation -->
    <nav class="sidebar" id="sidebar">
        <ul class="nav-menu">
            
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.product.index') }}" class="nav-link {{ request()->routeIs('admin.product*') ? 'active' : '' }}">
                    <i class="fa-solid fa-boxes-stacked"></i> Inventory
                </a>
            </li>


            

            <li class="nav-item">
                <a href="{{ route('admin.order.index') }}" class="nav-link {{ request()->routeIs('admin.order*') ? 'active' : '' }}">
                    <i class="fa-solid fa-basket-shopping"></i> Order
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.sales.index') }}" class="nav-link {{ request()->routeIs('admin.sales*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i> Sales
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.manage.index') }}" class="nav-link {{ request()->routeIs('admin.manage*') ? 'active' : '' }}">
                    <i class="fa-solid fa-people-roof"></i> Manage
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.customer.index') }}" class="nav-link {{ request()->routeIs('admin.customer*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user"></i> Client
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