<!-- main menu-->
<!--.main-menu(class="#{menuColor} #{menuOpenType}", class=(menuShadow == true ? 'menu-shadow' : ''))-->
<div data-active-color="white" data-background-color="man-of-steel" data-image="{{asset('app-assets')}}/img/sidebar-bg/01.jpg" class="app-sidebar">
    <!-- main menu header-->
    <!-- Sidebar Header starts-->
    <div class="sidebar-header">
        <div class="logo clearfix"><a href="#" class="logo-text float-left">
                <div class="logo-img">
                   </div>
                    <span class="text align-middle">{{config('app.name')}}</span></a><a id="sidebarToggle" href="javascript:;" class="nav-toggle d-none d-sm-none d-md-none d-lg-block"><i data-toggle="collapsed" class="toggle-icon ft-toggle-left"></i></a><a id="sidebarClose" href="javascript:;" class="nav-close d-block d-md-block d-lg-none d-xl-none"><i class="ft-x"></i></a></div>
    </div>
    <!-- Sidebar Header Ends-->
    <!-- / main menu header-->
    <!-- main menu content-->
    <div class="sidebar-content">
        <div class="nav-container">
            <ul id="main-menu-navigation" data-menu="menu-navigation" data-scroll-to-active="true" class="navigation navigation-main">

                @if(Auth::user()->role==0)
                <li class=" nav-item @if(Request::is('admin/dashboard')){{'active'}}@endif"><a href="{{url('admin/dashboard')}}"><i class="ft-life-buoy"></i><span data-i18n="" class="menu-title">Dashboard</span></a>
                </li>
                <li class=" nav-item @if(Request::is('admin/staff') || Request::is('admin/staff/*')){{'active'}}@endif"><a href="{{url('admin/staff')}}"><i class="ft-user-check"></i><span data-i18n="" class="menu-title">Staffs</span></a>
                </li>
                <li class=" nav-item @if(Request::is('admin/customer') || Request::is('admin/customer/*')){{'active'}}@endif"><a href="{{url('admin/customer')}}"><i class="ft-users"></i><span data-i18n="" class="menu-title">Customers</span></a>
                </li>
                @endif

                <li class="has-sub nav-item @if(Request::is('admin/job') || Request::is('admin/job/*')){{'nav-collapsed-open'}}@endif">
                    <a href="#"><i class="fa fa-list"></i><span data-i18n="" class="menu-title">Jobs</span></a>
                    <ul class="menu-content">
                        <li class="@if(Request::is('admin/job/create')){{'active'}}@endif"><a href="{{route('admin.job.create')}}" class="menu-item">Add Job</a>
                        </li>
                        <li class="@if(Request::is('admin/job')){{'active'}}@endif"><a href="{{route('admin.job.index')}}" class="menu-item">All Jobs</a>
                        </li>
                        <li class="@if(Request::is('admin/job/my_jobs')){{'active'}}@endif"><a href="{{route('admin.job.my_jobs')}}" class="menu-item">My Jobs</a>
                        </li>

                    </ul>
                </li>

            </ul>
        </div>
    </div>
    <!-- main menu content-->
    <div class="sidebar-background"></div>
    <!-- main menu footer-->
    <!-- include includes/menu-footer-->
    <!-- main menu footer-->
</div>
<!-- / main menu-->
