<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} - Customer Portal</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon"
        href="{{ empty($websetting->favicon_logo) ? '' : Storage::url($websetting->favicon_logo) }}" sizes="20x20"
        type="image/gif">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assests/frontend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assests/parsley/parsley.css') }}">
    <link rel="stylesheet" href="{{ asset('assests/admin/plugins/toastr/toastr.min.css') }}">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}">

    @stack('css')
</head>

<body>
    <div class="app-container">
        <!-- Mobile Overlay -->
        <div class="mobile-overlay" id="mobileOverlay"></div>

        <!-- Sidebar Navigation -->
        <aside class="sidebar" id="sidebar">
            <div class="brand">
                <a href="{{ route('customer.dashboard') }}">
                    <img alt="{{ env('APP_NAME') }}" class="img-fluid"
                        src="{{ empty($websetting->company_logo) ? '' : Storage::url($websetting->company_logo) }}" />
                </a>
            </div>
            <nav class="nav-links">
                <a href="{{ route('customer.dashboard') }}"
                    class="{{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie"></i> <span>Dashboard</span>
                </a>
                {{-- <a href="{{ route('customer.product.index') }}"
                    class="{{ request()->routeIs('customer.product.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-burger"></i> <span>Menu</span>
                </a> --}}
                <a href="{{ route('customer.category') }}"
                    class="{{ request()->routeIs('customer.category') ? 'active' : '' }}">
                    <i class="fa-solid fa-list"></i> <span>Categories</span>
                </a>
                <a href="{{ route('customer.cart.index') }}"
                    class="{{ request()->routeIs('customer.cart.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-cart-shopping"></i> <span>Cart</span>
                    @if(Auth::guard('customer')->check())
                        <span class="badge" id="cart-badge">
                            {{ Cart::instance(Auth::guard('customer')->user()->id)->count() }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('customer.order.index') }}"
                    class="{{ request()->routeIs('customer.order.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-receipt"></i> <span>My Orders</span>
                </a>
                <a href="{{ route('customer.profile.update') }}"
                    class="{{ request()->routeIs('customer.profile.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user"></i> <span>Profile</span>
                </a>
            </nav>
            <div class="logout-section">
                <a href="{{ route('customer.logout') }}" class="logout-btn" style="text-decoration: none;">
                    <i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="top-header">
                <div class="header-title">
                    <button class="menu-toggle" id="menuToggle">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <h1>@yield('title', 'Welcome')</h1>
                </div>
                <div class="user-info">
                    @php
                        $status = \App\Helper\Helper::getRestaurantStatus();
                        $statusColor = $status == 'Open' ? '#28a745' : '#dc3545';
                    @endphp
                    <span style="margin-right: 15px; font-weight: bold; color: {{ $statusColor }};">
                        <i class="fa-solid fa-store"></i> {{ $status }}
                    </span>
                    {{-- <span>Hello, {{ Auth::guard('customer')->user()->name ?? 'Guest' }}</span> --}}
                    <div class="avatar">
                        {{ substr(Auth::guard('customer')->check() ? Auth::guard('customer')->user()->name : 'Guest', 0, 1) }}
                    </div>
                </div>
            </header>

            <div class="content-area">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Mobile Sidebar Script -->
    <script src="{{ asset('assests/frontend/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assests/frontend/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assests/parsley/parsley.min.js') }}"></script>
    <script src="{{ asset('assests/admin/plugins/toastr/toastr.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('#region_id').niceSelect();
            $('#subregion_id').niceSelect();
        });
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.getElementById('sidebar');
            const mobileOverlay = document.getElementById('mobileOverlay');

            function toggleMenu() {
                sidebar.classList.toggle('active');
                mobileOverlay.classList.toggle('active');
            }

            if (menuToggle) {
                menuToggle.addEventListener('click', toggleMenu);
            }

            if (mobileOverlay) {
                mobileOverlay.addEventListener('click', toggleMenu);
            }
        });
    </script>

    @stack('script')
</body>

</html>