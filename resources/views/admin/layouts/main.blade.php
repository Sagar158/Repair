<!DOCTYPE html>
<html lang="en" class="loading">
<!-- BEGIN : Head-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Apex admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Apex admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>{{config('app.name')}}</title>
    <link rel="apple-touch-icon" sizes="60x60" href="{{asset('app-assets/img/ico/apple-icon-60.png')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('app-assets/img/ico/apple-icon-76.png')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{asset('app-assets/img/ico/apple-icon-120.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{asset('app-assets/img/ico/apple-icon-152.png')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('app-assets/img/ico/favicon.ico')}}">
    <link rel="shortcut icon" type="image/png" href="{{asset('app-assets/img/ico/favicon-32.png')}}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,700,900|Montserrat:300,400,500,600,700,800,900" rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <!-- font icons-->
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/fonts/feather/style.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/fonts/simple-line-icons/style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/fonts/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/perfect-scrollbar.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/prism.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/chartist.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/tables/datatable/datatables.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/toastr.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <!-- END VENDOR CSS-->
    <!-- BEGIN APEX CSS-->
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/app.css')}}">
    <!-- END APEX CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/wizard.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog==" crossorigin="anonymous" />
  <style>
      .tab-pane{
          overflow-x:hidden ;
      }
  </style>
    <!-- BEGIN Page Level CSS-->
    <!-- END Page Level CSS-->
    @yield('styles')
</head>
<!-- END : Head-->

<!-- BEGIN : Body-->
<body data-col="2-columns" class=" 2-columns ">
<!-- ////////////////////////////////////////////////////////////////////////////-->
<div class="wrapper  nav-collapsed menu-collapsed">

    @include('admin.layouts.sidebar')


   @include('admin.layouts.header')

    <div class="main-panel">
        <!-- BEGIN : Main Content-->
        <div class="main-content">
            <div class="content-wrapper">
                @yield('content')
            </div>
        </div>
        <!-- END : End Main Content-->

        <!-- BEGIN : Footer-->
        <footer class="footer footer-static footer-light">
            <p class="clearfix text-muted text-sm-center px-2"><span>Copyright  &copy; {{date('Y')}} All rights reserved. </span></p>
        </footer>
        <!-- End : Footer-->

    </div>
</div>
<!-- ////////////////////////////////////////////////////////////////////////////-->


<!-- Theme customizer Ends-->
<!-- BEGIN VENDOR JS-->
<script src="{{asset('app-assets/vendors/js/core/jquery-3.2.1.min.js')}}" type="text/javascript"></script>
<script src="{{asset('app-assets/vendors/js/core/popper.min.js')}}" type="text/javascript"></script>
<script src="{{asset('app-assets/vendors/js/core/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{asset('app-assets/vendors/js/perfect-scrollbar.jquery.min.js')}}" type="text/javascript"></script>
<!-- BEGIN VENDOR JS-->
<!-- BEGIN PAGE VENDOR JS-->
<script src="{{asset('app-assets/vendors/js/chartist.min.js')}}" type="text/javascript"></script>
<script src="{{asset('app-assets/vendors/js/jquery.steps.min.js')}}" type="text/javascript"></script>
<script src="{{asset('app-assets/vendors/js/jquery.validate.min.js')}}" type="text/javascript"></script>
<!-- END PAGE VENDOR JS-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js" integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew==" crossorigin="anonymous"></script>
<!-- BEGIN APEX JS-->
<script src="{{asset('app-assets/js/app-sidebar.js')}}" type="text/javascript"></script>
<script src="{{asset('app-assets/js/notification-sidebar.js')}}" type="text/javascript"></script>
<script src="{{asset('app-assets/js/customizer.js')}}" type="text/javascript"></script>
<!-- END APEX JS-->
<!-- BEGIN PAGE LEVEL JS-->
<script src="{{asset('app-assets/js/wizard-steps.js')}}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js" integrity="sha512-foIijUdV0fR0Zew7vmw98E6mOWd9gkGWQBWaoA1EOFAx+pY+N8FmmtIYAVj64R98KeD2wzZh1aHK0JSpKmRH8w==" crossorigin="anonymous"></script>
<script src="{{asset('app-assets/vendors/js/datatable/datatables.min.js')}}" type="text/javascript"></script>
<script src="{{asset('app-assets/vendors/js/toastr.min.js')}}" type="text/javascript"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $('.dropify').dropify();
    @if (session()->has('message'))
    toastr.success(' {{session("message")}}', 'Success');
    @endif
    @if (session()->has('hasError'))
    toastr.error(' {{session("hasError")}}', 'Error');
    @endif
    @if ($errors->any())
    toastr.error('@foreach ($errors->all() as $error)\n' +
        '                                    {{ $error }}\n' +
        '                                @endforeach', 'Error');
    @endif
    $('.select2').select2({ width: 'resolve'});
</script>
<!-- END PAGE LEVEL JS-->
@yield('modals')
@yield('scripts')




</body>
<!-- END : Body-->
</html>
