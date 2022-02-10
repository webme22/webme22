<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Admin Dashboard" name="description" />
    <meta content="Ibrahim E.Gad" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title')</title>
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link rel="stylesheet" href="{{asset('plugins/morris/morris.css')}}">
    <link href="{{asset('css/admin/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/admin/icons.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/admin/style.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('plugins/bootstrap-sweetalert/sweet-alert.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/admin/bootstrap4-toggle.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/admin/custom.css')}}" rel="stylesheet" type="text/css">
    <link rel="icon" href="<?=asset('images/favicon.ico')?>" type="image/x-icon">

    @yield('head')
</head>
<body class="fixed-left">

<!-- Begin page -->
<div id="wrapper">
    <!-- Top Bar Start -->
    <div class="topbar">
        <!-- LOGO -->
        <div class="topbar-left">
            <div class="text-center p-2 ">
                <a href="{{route('admin.get_dashboard')}}" class="logo">
                    <picture>
                        <source srcset="{{asset('images/al-logo2.webp')}}" type="image/webp">
                        <source srcset="{{asset('images/al-logo2.png')}}" type="image/png">
                        <img src="{{asset('images/al-logo2.png')}}" alt="logo-img">
                    </picture>
                </a>
                <a href="{{route('admin.get_dashboard')}}" class="logo-sm">
                    <picture>
                        <source srcset="{{asset('images/al-logo2.webp')}}" type="image/webp">
                        <source srcset="{{asset('images/al-logo2.png')}}" type="image/png">
                        <img src="{{asset('images/al-logo2.png')}}" alt="logo-img">
                    </picture>
                </a>
            </div>
        </div>
        <!-- Button mobile view to collapse sidebar menu -->
        <nav class="navbar navbar-default" role="navigation">
            <div class="container-fluid">
                <ul class="list-inline menu-left mb-0">
                    <li class="float-left">
                        <button class="button-menu-mobile open-left waves-light waves-effect">
                            <i class="mdi mdi-menu"></i>
                        </button>
                    </li>
                    {{--                    <li class="hide-phone app-search float-left">--}}
                    {{--                        <form role="search" class="navbar-form">--}}
                    {{--                            <input type="text" placeholder="Search..." class="form-control search-bar">--}}
                    {{--                            <a href="#" class="btn-search"><i class="fa fa-search"></i></a>--}}
                    {{--                        </form>--}}
                    {{--                    </li>--}}
                </ul>

                <ul class="nav navbar-right float-right list-inline">
                    <li class="d-none d-sm-block">
                        <a href="#" id="btn-fullscreen" class="waves-effect waves-light notification-icon-box"><i class="mdi mdi-fullscreen"></i></a>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown" aria-expanded="true">
                            <picture>
                                <source srcset="{{asset('images/default-user.webp')}}" type="image/webp">
                                <source srcset="{{asset('images/default-user.png')}}" type="image/png">
                                <img src="{{asset('images/default-user.png')}}" alt="user-img" class="rounded-circle">
                            </picture>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">

                            <li>
                                <form method="POST" action="{{route('admin.post_logout')}}" class="d-inline-block w-100">
                                    @csrf
                                <input type="submit" class="dropdown-item btn w-100" value="Logout">
                                </form>

                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
    <!-- Top Bar End -->
    <!-- ========== Left Sidebar Start ========== -->
    <div class="left side-menu">
        <div class="sidebar-inner slimscrollleft">

            <div class="user-details">
                <div class="text-center">
                    <picture>
                        <source srcset="{{asset('images/default-user.webp')}}" type="image/webp">
                        <source srcset="{{asset('images/default-user.png')}}" type="image/png">
                        <img src="{{asset('images/default-user.png')}}" alt="" class="thumb-md img-circle rounded-circle">
                    </picture>
                </div>
                <div class="user-info m-0 text-center">
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">{{ Auth::user()->name  }} <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li>
                                <form method="POST" action="{{route('admin.post_logout')}}" class="d-inline-block w-100">
                                    @csrf
                                    <input type="submit" class="dropdown-item btn w-100" value="Logout">
                                </form>
                            </li>
                        </ul>
                    </div>
                    <p class="text-muted m-0">Admin</p>
                </div>
            </div>
            <div id="sidebar-menu">
                <ul>
                    <li class="menu-title">Menu</li>
                    <li>
                        <a href="{{route('admin.get_dashboard')}}" class="waves-effect @if(menu_active(['admin.get_dashboard'])) active @endif"><i class="mdi mdi-home"></i>
                            <span>{{__('global.dashboard')}}</span></a>
                    </li>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div> <!-- end sidebarinner -->
    </div>
    <!-- Left Sidebar End -->

    <!-- Start right Content here -->
    <div class="content-page">
        <div class="content">
            <div class="">
                <div class="page-header-title">
                    <h4 class="page-title">@yield('header_title')</h4>
                </div>
            </div>
            <div class="page-content-wrapper ">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            <footer class="footer">
                © {{date('Y')}} {{__('global.short_title')}} - {{__('global.rights_reserved')}}.
            </footer>
        </div>
    </div>
</div>

<script src="{{asset('js/admin/jquery.min.js')}}"></script>
<script src="{{asset('js/admin/popper.min.js')}}"></script>
<script src="{{asset('js/admin/bootstrap.min.js')}}"></script>
<script src="{{asset('js/admin/modernizr.min.js')}}"></script>
<script src="{{asset('js/admin/detect.js')}}"></script>
<script src="{{asset('js/admin/fastclick.js')}}"></script>
<script src="{{asset('js/admin/jquery.slimscroll.js')}}"></script>
<script src="{{asset('js/admin/jquery.blockUI.js')}}"></script>
<script src="{{asset('js/admin/waves.js')}}"></script>
<script src="{{asset('js/admin/wow.min.js')}}"></script>
<script src="{{asset('js/admin/jquery.nicescroll.js')}}"></script>
<script src="{{asset('js/admin/jquery.scrollTo.min.js')}}"></script>
<script src="{{asset('plugins/morris/morris.min.js')}}"></script>
<script src="{{asset('plugins/raphael/raphael-min.js')}}"></script>
<script src="{{asset('plugins/bootstrap-sweetalert/sweet-alert.min.js')}}"></script>
<script src="{{asset('js/admin/bootstrap4-toggle.min.js')}}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/axios/0.21.0/axios.min.js"></script>
<script src="{{asset('js/admin/app.js')}}"></script>
@include('admin.inc.axiosinit')
@yield('scripts')
</body>
