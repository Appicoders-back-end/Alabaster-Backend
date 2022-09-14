<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{asset('admin_assets')}}/images/favicon.ico" type="image/x-icon">
    <title>Alabaster Admin Panel</title>

    <!-- Bootstrap Core and vandor -->
    <link rel="stylesheet" href="{{asset('admin_assets')}}/plugins/bootstrap/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="{{asset('admin_assets')}}/plugins/dropify/css/dropify.min.css">
    <link rel="stylesheet" href="{{asset('admin_assets')}}/plugins/summernote/dist/summernote.css"/>
    <link rel="stylesheet" href="{{asset('admin_assets')}}/plugins/sweetalert/sweetalert.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css"
          integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">

    <!-- Core css -->
    <link rel="stylesheet" href="{{asset('admin_assets')}}/css/style.min.css"/>
    @yield('style')
</head>

<body class="font-muli">
<div id="main_content">
    <!-- Start Main top header -->
    <div id="header_top" class="header_top">
        <div class="container">
            <div>
                <a href="javascript:void(0)" class="nav-link icon menu_toggle"><i class="fe fe-align-center"></i> </a>
            </div>
        </div>
    </div>
    <!-- Start Main leftbar navigation -->
    <div id="left-sidebar" class="sidebar">
        <h5 class="brand-name"><img src="{{asset('admin_assets')}}\images\logo.png"></h5>
        @include('admin.layouts.menu')
    </div>

    <!-- Start project content area -->
    <div class="page">
        <!-- Start Page header -->
        <div class="" id="page_top">
            <div class="container-fluid" style="background-color: #f6921e;">
                <div class="page-header">
                    <div class="left">
                        <form>
                            <div class="input-group">
                                <input type="text" class="form-control" name="search"
                                       value="{{isset(request()->search) ? request()->search : null}}">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-outline-secondary" type="button">Search
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="right">
                        <!-- <a href="notifications.html"><i class="fas fa-bell" style="font-size: 20px; color: #fff; margin-right: 20px;"></i></a>  -->
                        <div class="input-group">
                            <a href="{{route('admin.logout')}}" class="btn btn-dark">Logout</a>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        @include('admin.layouts.message')
        @yield('content')
    </div>
</div>

<!-- Start Main project js, jQuery, Bootstrap -->
<script src="{{asset('admin_assets')}}/bundles/lib.vendor.bundle.js"></script>

<!-- Start Plugin Js -->
<script src="{{asset('admin_assets')}}/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="{{asset('admin_assets')}}/plugins/dropify/js/dropify.min.js"></script>
<script src="{{asset('admin_assets')}}/bundles/summernote.bundle.js"></script>
<script src="{{asset('admin_assets')}}/plugins/sweetalert/sweetalert.min.js"></script>

<!-- Start project main js  and page js -->
<script src="{{asset('admin_assets')}}/js/core.js"></script>
<script>

    $(".sidebar-dropdown > a").click(function () {
        $(".sidebar-submenu").slideUp(200);
        if ($(this).parent().hasClass("active")) {
            $(".sidebar-dropdown").removeClass("active");
            $(this).parent().removeClass("active");
        } else {
            $(".sidebar-dropdown").removeClass("active");
            $(this).next(".sidebar-submenu").slideDown(200);
            $(this).parent().addClass("active");
        }
    });

    $("#close-sidebar").click(function () {
        $(".page-wrapper").removeClass("toggled");
    });
    $("#show-sidebar").click(function () {
        $(".page-wrapper").addClass("toggled");
    });
</script>
@yield('script')
</body>
</html>
