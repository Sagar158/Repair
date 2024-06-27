
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
    <title>Login</title>
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
    <!-- END VENDOR CSS-->
    <!-- BEGIN APEX CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/toastr.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/app.css')}}">
    <!-- END APEX CSS-->
    <!-- BEGIN Page Level CSS-->
    <!-- END Page Level CSS-->
</head>
<!-- END : Head-->

<!-- BEGIN : Body-->
<body data-col="1-column" class=" 1-column  blank-page">
<!-- ////////////////////////////////////////////////////////////////////////////-->
<div class="wrapper">
    <div class="main-panel">
        <!-- BEGIN : Main Content-->
        <div class="main-content">
            <div class="content-wrapper"><!--Login Page Starts-->
                <section id="login">
                    <div class="container-fluid">
                        <div class="row full-height-vh m-0">
                            <div class="col-12 d-flex align-items-center justify-content-center">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="card-body login-img">
                                            <div class="row m-0">
                                                <div class="col-lg-6 d-lg-block d-none py-2 text-center align-middle">
                                                    <img src="{{asset('app-assets/img/gallery/login.png')}}" alt="" class="img-fluid mt-5" width="400" height="230">
                                                </div>
                                                <div class="col-lg-6 col-md-12 bg-white px-4 pt-3">
                                                    <form action="{{route('login')}}" method="POST">
                                                        @csrf
                                                    <h4 class="mb-2 card-title">Login</h4>
                                                    <p class="card-text mb-3">
                                                        Welcome back, please login to your account.
                                                    </p>
                                                        <input type="text" placeholder="UserName or Email"
                                                               class="form-control mb-3 {{ $errors->has('username') || $errors->has('email') ? ' is-invalid' : '' }}"
                                                               name="login" value="{{ old('username') ?: old('email') }}" required autofocus>

                                                        <input type="password" class="form-control mb-1 @error('password') is-invalid @enderror" name="password" placeholder="Password" />

                                                     <select class="form-control" required name="location">
                                                         <option value="">--select location--</option>
                                                         <option value="Commerce St.">Commerce St.</option>
                                                         <option value="Argyle St">Argyle St.</option>
                                                     </select>

                                                    <div class="d-flex justify-content-between mt-2">
                                                        <div class="remember-me">
                                                            <div class="custom-control custom-checkbox custom-control-inline mb-3">
                                                                <input id="customCheckboxInline1" class="custom-control-input" name="remember" {{ old('remember') ? 'checked' : '' }} type="checkbox">
                                                                <label class="custom-control-label" for="customCheckboxInline1">
                                                                    Remember Me
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="forgot-password-option">

                                                        </div>
                                                    </div>
                                                    <div class="fg-actions d-flex justify-content-between">
                                                        <div class="login-btn">

                                                        </div>
                                                        <div class="recover-pass">
                                                            <button class="btn btn-primary" type="submit">
                                                             Login
                                                            </button>
                                                        </div>
                                                    </div>

                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!--Login Page Ends-->

            </div>
        </div>
        <!-- END : End Main Content-->
    </div>
</div>
<!-- ////////////////////////////////////////////////////////////////////////////-->

<!-- BEGIN VENDOR JS-->
<script src="{{asset('app-assets/vendors/js/core/jquery-3.2.1.min.js')}}" type="text/javascript"></script>
<script src="{{asset('app-assets/vendors/js/core/popper.min.js')}}" type="text/javascript"></script>
<script src="{{asset('app-assets/vendors/js/core/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{asset('app-assets/vendors/js/perfect-scrollbar.jquery.min.js')}}" type="text/javascript"></script>
<script src="{{asset('app-assets/vendors/js/prism.min.js')}}" type="text/javascript"></script>
<script src="{{asset('app-assets/vendors/js/jquery.matchHeight-min.js')}}" type="text/javascript"></script>
<script src="{{asset('app-assets/vendors/js/screenfull.min.js')}}" type="text/javascript"></script>
<script src="{{asset('app-assets/vendors/js/pace/pace.min.js')}}" type="text/javascript"></script>
<!-- BEGIN VENDOR JS-->
<!-- BEGIN PAGE VENDOR JS-->
<!-- END PAGE VENDOR JS-->
<!-- BEGIN APEX JS-->
<script src="{{asset('app-assets/js/app-sidebar.js')}}" type="text/javascript"></script>
<script src="{{asset('app-assets/js/notification-sidebar.js')}}" type="text/javascript"></script>
<script src="{{asset('app-assets/js/customizer.js')}}" type="text/javascript"></script>
<script src="{{asset('app-assets/vendors/js/toastr.min.js')}}" type="text/javascript"></script>

<script>

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

</script>
</body>
<!-- END : Body-->
</html>
