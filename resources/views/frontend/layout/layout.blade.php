<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} | @yield('title') </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" type="image/x-icon"
        href="{{ empty($websetting->favicon_logo) ? '' : Storage::url($websetting->favicon_logo) }}" sizes="20x20"
        type="image/gif">

    <link rel="stylesheet" href="{{ asset('assests/frontend/css/all.css') }}">
    <link rel="stylesheet" href="{{ asset('assests/frontend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assests/frontend/css/boxicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assests/frontend/css/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assests/frontend/css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('assests/frontend/css/swiper-bundle.css') }}">
    <link rel="stylesheet" href="{{ asset('assests/frontend/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('assests/frontend/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('assests/frontend/css/jquery.fancybox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assests/frontend/css/odometer.css') }}">
    <link rel="stylesheet" href="{{ asset('assests/frontend/css/style.css') }}">

    <link rel="stylesheet" href="{{ asset('assests/parsley/parsley.css') }}">
    <link rel="stylesheet" href="{{ asset('assests/admin/plugins/toastr/toastr.min.css') }}">
    <style>
        .preloader::before {
            content: url({{ empty($websetting->company_logo) ? '' : Storage::url($websetting->company_logo) }});
        }
        .video-wrapper {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%; /* 16:9 ratio */
            height: 0;
            overflow: hidden;
            border-radius: 10px;
        }
        
        .video-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }
        .login-link-area {
            font-size: 16px;
            color: #fff;
        }
        
        .login-link {
            color: #c19d60;
            font-weight: 600;
            text-decoration: none;
            transition: 0.3s;
        }
        
        .login-link:hover {
            color: #000;
            text-decoration: underline;
        }
        .primary-btn:hover #cart-badge {
            color: #bf9444;
        }
        .action-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #bf9444;
            border-radius: 8px;
        }
        
    </style>
    @stack('css')
</head>

<body class="tt-magic-cursor">
    <div class="preloader">
    </div>
    <div class="top-bar three">
        <div class="container-lg container-fluid bg-white">
            <div class="row p-12 align-items-center position-relative">
                <div class="col-lg-5 d-flex align-items-center justify-content-md-start justify-content-center">
                    <div class="vector-left"><img src="{{ asset('assests/frontend') }}/images/bg/topbar-vec-left.png"
                            alt /></div>
                    <div class="welcome-note">
                        <p>Welcome Our {{ env('APP_NAME') }}</p>
                    </div>
                </div>
                <div class="col-lg-7 d-flex justify-content-lg-end justify-content-center align-items-center">
                    <div class="vector-right"><img src="{{ asset('assests/frontend') }}/images/bg/topbar-vec-right.png"
                            alt /></div>
                    <div class="contact-info">
                        <ul>
                            <li>
                                <a href="mailto:{{ $websetting->email }}"><i class="bi bi-envelope"></i>
                                    {{ $websetting->email }}</a>
                            </li>
                            <li>
                                <a><i class="bi bi-geo-alt"></i>{{ $websetting->address }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <header class="header-area style-3">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="header-logo">
                <a href="{{ route('home') }}">
                    <img alt="{{ env('APP_NAME') }}" class="img-fluid"
                        src="{{ empty($websetting->company_logo) ? '' : Storage::url($websetting->company_logo) }}" />
                </a>
            </div>
            <div class="main-menu">
                <div class="mobile-logo-area d-lg-none d-flex justify-content-between align-items-center">
                    <div class="mobile-logo-wrap">
                        <a href="{{ route('home') }}"><img alt="image"
                                src="{{ empty($websetting->company_logo) ? '' : Storage::url($websetting->company_logo) }}" /></a>
                    </div>
                    <div class="menu-close-btn">
                        <i class="bi bi-x-lg"></i>
                    </div>
                </div>
                <ul class="menu-list">
                    <li><a class="{{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                    <li><a class="{{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a></li>
                    <li><a class="{{ request()->routeIs('customer.category') ? 'active' : '' }}" href="{{ route('customer.category') }}">Explore Menu</a></li>
                    @if (Auth::guard('customer')->check())
                        <li><a class="{{ request()->routeIs('customer.order.*') ? 'active' : '' }}" href="{{ route('customer.order.index') }}">My Order</a></li>
                    @else
                        <li><a class="{{ request()->routeIs('testimonialenquiry') ? 'active' : '' }}" href="{{ route('testimonialenquiry') }}">Feedback</a></li>
                        <li><a class="{{ request()->routeIs('faq') ? 'active' : '' }}" href="{{ route('faq') }}">FAQ</a></li>
                    @endif
                    {{--<li><a class="{{ request()->routeIs('menu') ? 'active' : '' }}" href="{{ route('menu') }}">Menu</a></li>--}}
                    <li><a class="{{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a></li>
                </ul>
                <div class="hotline d-lg-none d-flex mb-30">
                    <div class="hotline-info">
                        <span>Call Now</span>
                        <h6><a href="tel:+{{ $websetting->contact_one }}">{{ $websetting->contact_one }}</a></h6>
                    </div>
                </div>
                <div class="reservation-btns d-lg-none d-flex">
                    {{--<a href="{{ route('contact') }}" class="primary-btn6 btn-md">Connect Now</a>--}}
                    @if (Auth::guard('customer')->check())
                        <a href="{{ route('customer.logout') }}" class="primary-btn btn-md" title="Logout"><i class="bi-box-arrow-right"></i></a>
                    @else
                        <a href="{{ route('customer.login') }}" class="primary-btn btn-md"  title="Login"><i class="bi-box-arrow-left"></i></a>
                    @endif
                </div>
            </div>
            <div class="nav-right d-flex jsutify-content-end align-items-center">
                <div class="hotline d-flex">
                    <div class="hotline-info">
                        <span>Call Now</span>
                        <h6><a href="tel:+{{ $websetting->contact_one }}">{{ $websetting->contact_one }}</a></h6>
                    </div>
                </div>

                @if (Auth::guard('customer')->check())
                    <a class="primary-btn btn-md" href="{{ route('customer.cart.index') }}" title="Card">
                        <i class="bi bi-cart-plus-fill"></i>
                        @if(Auth::guard('customer')->check())
                            <span class="badge" id="cart-badge">
                                {{ Cart::instance(Auth::guard('customer')->user()->id)->count() }}
                            </span>
                        @endif
                    </a>
                    <a class="primary-btn btn-md" href="{{ route('customer.profile.update') }}" title="Profile"><i class="bi-person-fill"></i></a>
                    <a href="{{ route('customer.logout') }}" class="primary-btn btn-md" title="Logout"><i class="bi-box-arrow-right"></i></a>
                @else
                    <a href="{{ route('customer.login') }}" class="primary-btn btn-md" title="Login"><i class="bi-box-arrow-left"></i></a>
                @endif

                {{-- <a href="{{ route('contact') }}" class="primary-btn6 btn-md">Connect Now</a> --}}
                <div class="sidebar-button mobile-menu-btn">
                    <i class="bi bi-list"></i>
                </div>
            </div>
        </div>
    </header>
    @yield('content')

    <footer
        style="background-image: linear-gradient(rgba(9, 22, 29, 0.9), rgba(9, 22, 29, 0.9)), url(' {{ Storage::url($websetting->footer_back_image) }}')">
        <div class="footer-top ">
            <div class="container">
                <div class="row justify-content-center align-items-center gy-5">
                    <div class="col-lg-4 col-md-6  order-md-1 order-2">
                        <div class="footer-widget one">
                            <div class="widget-title">
                                <h3>Useful Link</h3>
                            </div>
                            <div class="menu-container">
                                <ul>
                                    <li><a href="{{ route('home') }}">Home</a></li>
                                    <li><a href="{{ route('about') }}">About</a></li>
                                    <!-- <li><a href="{{ route('menu') }}">Menu</a></li> -->
                                    <li><a href="{{ route('customer.category') }}">Explore Menu</a></li>
                                    @if (Auth::guard('customer')->check())
                                        <li><a href="{{ route('customer.order.index') }}">My Order</a></li>
                                    @else
                                        <li><a href="{{ route('testimonialenquiry') }}">Feedback</a></li>
                                        <li><a href="{{ route('faq') }}">FAQ</a></li>
                                    @endif
                                    <li><a href="{{ route('contact') }}">Contact</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 order-md-2 order-1">
                        <div class="footer-widgetfooter-widget social-area">
                            <div class="footer-logo text-center">
                                <a href="{{ route('home') }}"><img
                                        src="{{ empty($websetting->company_logo) ? '' : Storage::url($websetting->company_logo) }}"
                                        alt></a>
                                <p>Established . {{ $websetting->established_year }}</p> <span><img
                                        src="{{ asset('assests/frontend') }}/images/icon/footer-shape.svg" alt></span>
                            </div>
                            <div class="footer-social">
                                <ul class="social-link d-flex align-items-center justify-content-center">
                                    <li><a href="{{ $websetting->facebook }}"><i class="bx bxl-facebook"></i></a>
                                    </li>
                                    <li><a href="{{ $websetting->instagram }}"><i
                                                class="bx bxl-instagram-alt"></i></a></li>
                                    <li><a href="{{ $websetting->youtube }}"><i class="bx bxl-youtube"></i></a></li>
                                    <li><a href="{{ $websetting->twitter }}"><i class="bx bxl-twitter"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 order-3">
                        <div class="footer-widget one">
                            <div class="widget-title">
                                <h3>Address Info</h3>
                            </div>
                            <div class="contact-info">
                                <div class="single-contact">
                                    <span class="title">Phone :</span> <span class="content"><a
                                            href="tel:+91{{ $websetting->contact_one }}">{{ $websetting->contact_one }}</a></span>
                                </div>
                                <div class="single-contact">
                                    <span class="title">Phone :</span> <span class="content"><a
                                            href="tel:+91{{ $websetting->contact_two }}">{{ $websetting->contact_two }}</a></span>
                                </div>
                                <div class="single-contact">
                                    <span class="title">Email :</span> <span class="content"><a
                                            href="mailto:{{ $websetting->email }}">{{ $websetting->email }}</a></span>
                                </div>
                                <div class="single-contact">
                                    <span class="title">Location :</span> <span class="content"><a
                                            href="">{{ $websetting->address }}</a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-btm">
            <div class="container">
                <div class="row border-ttop g-2">
                    <div class="col-md-8 justify-content-md-start justify-content-center">
                        <div class="copyright-area">
                            <p>@Copyright by {{ env('APP_NAME') }}-{{ date('Y') }}, All Right Reserved.</p>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex justify-content-md-end justify-content-center">
                        <div class="privacy-policy">
                            <p><a href="#">Privacy & Policy</a> | <a href="#">Terms and Conditions</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('assests/frontend/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assests/frontend/js/jquery-ui.js') }}"></script>
    <script src="{{ asset('assests/frontend/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assests/frontend/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assests/frontend/js/jquery.nice-select.js') }}"></script>
    <script src="{{ asset('assests/frontend/js/jquery.fancybox.min.js') }}"></script>
    <script src="{{ asset('assests/frontend/js/odometer.min.js') }}"></script>
    <script src="{{ asset('assests/frontend/js/viewport.jquery.js') }}"></script>
    <script src="{{ asset('assests/frontend/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assests/frontend/js/SmoothScroll.js') }}"></script>
    <script src="{{ asset('assests/frontend/js/jquery.nice-number.min.js') }}"></script>
    <script src="{{ asset('assests/frontend/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('assests/frontend/js/imagesloaded.pkgd.js') }}"></script>
    <script src="{{ asset('assests/frontend/js/masonry.pkgd.min.js') }}"></script>
    <script src="{{ asset('assests/frontend/js/main.js') }}"></script>

    <!-- Toastr -->
    <script src="{{ asset('assests/admin/plugins/toastr/toastr.min.js') }}"></script>
    <!-- Parsley JS -->
    <script src="{{ asset('assests/parsley/parsley.min.js') }}"></script>

    <!-- Form script -->
    <script src="{{ asset('assests/from/formscript.js') }}"></script>

    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>

    @stack('script')

</body>

</html>
