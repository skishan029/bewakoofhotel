<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME') }} | @yield('title') </title>

    <!-- favicon ============================================ -->		
    <link rel="shortcut icon" type="image/x-icon" href="{{ Helper::faviconLogo() }}">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('assests/admin/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('assests/admin/plugins/ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assests/admin/dist/css/adminlte.min.css') }}">

    <!-- Parsley CSS -->
    <link rel="stylesheet" href="{{ asset('assests/parsley/parsley.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('assests/admin/plugins/toastr/toastr.min.css') }}">
    
    <!-- sweetalert -->
    <link rel="stylesheet" href="{{ asset('assests/bootstrap-sweetalert-master/dist/sweetalert.css') }}">
   
    @stack('includestyle')
    <!-- jQuery -->
    

    <style>
        #loading {
            display: inline-block;
            width: 50px;
            height: 50px;
            border: 3px solid rgb(31 32 34 / 42%);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
            -webkit-animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { -webkit-transform: rotate(360deg); }
        }
        @-webkit-keyframes spin {
            to { -webkit-transform: rotate(360deg); }
        }
        .text-danger {
            color: #a90010!important;
        }
        .text-success {
            color: #35ff63!important;
        }
    </style>

    @stack('style')
</head>
<body class="hold-transition sidebar-mini layout-fixed accent-primary layout-footer-fixed layout-navbar-fixed text-sm">
    <div class="wrapper">
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ empty($websetting->company_logo) ? '' : Storage::url($websetting->company_logo) }}" alt="{{ env('APP_NAME') }}" style="border-radius:50%" width="200">
        </div>
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">Home</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ Helper::adminProfile() }}" class="user-image img-circle elevation-2" alt="User Image">
                        <span class="d-none d-md-inline">{{ Auth::guard('admin')->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <!-- User image -->
                        <li class="user-header bg-dark">
                            <img src="{{ Helper::adminProfile() }}" class="img-circle elevation-2" alt="User Image">
                            <p>
                                {{ Auth::guard('admin')->user()->name }}
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <a href="#" class="btn btn-default btn-flat">Profile</a>
                            <a href="{{ route('admin.logout') }}" class="btn btn-default btn-flat float-right">Sign out</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar elevation-4 sidebar-no-expand sidebar-dark-primary">
            <a href="{{ route('admin.dashboard') }}" class="brand-link">
                <img src="{{ empty($websetting->company_logo) ? '' : Storage::url($websetting->company_logo) }}" alt="Logo" class="brand-image elevation-3" style="opacity: .8">
                {{-- {{ env('APP_NAME') }} --}}
                <span class="brand-text font-weight-light"></span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                @include('admin.common.sidebar')
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('module_title')</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">@yield('title')</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            
            @yield('content')

        </div>

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                Developed By<a href="https://spectrotechweb.com/" target="_blank"> SpectroTech Web Solutions Pvt. Ltd.</a>
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; {{date('Y')}} <a href="javascript:void(0)">{{ env('APP_NAME') }}</a>.</strong> All rights reserved.
        </footer>
    </div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<script src="{{asset('assests/admin/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assests/admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('assests/admin/dist/js/adminlte.min.js')}}"></script>

<!-- bs-custom-file-input -->
<script src="{{ asset('assests/admin/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
<!-- Toastr -->
<script src="{{ asset('assests/admin/plugins/toastr/toastr.min.js')}}"></script>
<!-- Parsley JS -->
<script src="{{ asset('assests/parsley/parsley.min.js')}}"></script>

<script src="{{ asset('assests/bootstrap-sweetalert-master/dist/sweetalert.js') }}"></script>

<!-- Form script -->
<script src="{{ asset('assests/from/formscript.js')}}"></script>

@stack('includescript')

<script>
    $(function(){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        //bs-custom-file-input
        bsCustomFileInput.init();
    });
</script>   

@stack('script')

</body>
</html>